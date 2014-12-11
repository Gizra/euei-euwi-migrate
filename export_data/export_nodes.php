<?php
//testing data
export_nodes('news', array('nid', 'title', 'body', 'uid'),'_gizra_blog_post');

/**
 * @param $node_type
 *  Type of node for export
 * @param array $fields
 *  Array fields for export to another table
 * @param $result_table
 *  Name for a new table for move data
 * @return bool|mysqli_result|resource
 *
 */
function export_nodes ($node_type, $fields = array(), $result_table) {
  if(empty($node_type) || empty($fields)|| empty($result_table)){
    return;
  }
  $total =  db_result(db_query("SELECT COUNT(nid) FROM {node} n WHERE n.type = '%s' ORDER BY n.nid", $node_type));
  db_query("TRUNCATE TABLE  `_gizra_blog_post`"); //temporary
  $range = 5;//temp
  $count = 0;

  $total=5; //temporary
  print_r($list_fields);
  // TODO: string of directives appropriate to the type of data.
  $directives = "'%d', '%s', '%s', '%d'";
  $values = "";
  foreach($fields as $item) {
    $values .= "\$node->$item, ";
  }
  print_r($values);

  while($count < $total){
    $result = db_query("SELECT nid FROM {node} n WHERE n.type = '%s' ORDER BY n.nid LIMIT %d OFFSET %d", $node_type, $range, $count);
    while ($row = db_fetch_array($result)) {
      $node = node_load($row['nid']);
      // Prepare the query:
      $query = "INSERT INTO $result_table(". implode(", ", $fields) .") VALUES(" . $directives . ")";
      $insert = db_query($query, $values);
      ++$count;
      $params = array(
        '@count' => $count,
        '@total' => $total,
        '@id' => $node->nid,
      );
      drush_print(dt('(@count / @total) Processed node ID @id.', $params));
    }
  }
  return $insert;
}
