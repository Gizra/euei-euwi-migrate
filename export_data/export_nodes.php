<?php
//testing data
export_nodes('ipaper', array(
  'nid'=>"'%d'",
  'title' => "'%s'",
  'body' => "'%s'",
  'uid' => "'%d'",
  'path' => "'%s'",
  'file_name' => "'%s'"),
  '_gizra_documents');

/**
 * @param $node_type
 *  Type of node for export
 * @param array $fields
 *  Array fields for export to another table consist
 *  Key is name of field,
 *  Value is directive type ('%s' - string, '%d' - integer, etc.)
 * @param $result_table
 *  Name for a new table for move data
 * @return bool
 *
 */
function export_nodes ($node_type, $fields = array(), $result_table) {
  if(empty($node_type) || empty($fields)|| empty($result_table)){
    return;
  }
  $total =  db_result(db_query("SELECT COUNT(nid) FROM {node} n WHERE n.type = '%s' ORDER BY n.nid", $node_type));
  db_query("TRUNCATE TABLE  `_gizra_blog_post`"); //temporary
  $range = 2;//temp
  $count = 0;

  $total=2; //temporary
  $d = array();
  // TODO: string of directives appropriate to the type of data.
  foreach ($fields as $type){
    $d[] = $type;
  }
  $di = implode(", ", $d);
  print_r($di);

  while($count < $total){
    $result = db_query("SELECT nid FROM {node} n WHERE n.type = '%s' ORDER BY n.nid LIMIT %d OFFSET %d", $node_type, $range, $count);
    while ($row = db_fetch_array($result)) {
      $node = node_load($row['nid']);
      //print_r($node);
      // Prepare the query:
      $values = "";
      foreach($fields as $key => $type) {
        if($key = 'file_name' && $node_type = 'ipaper') {
          $file = reset($node->files);
          print_r($file->filename);
          if (!empty($file->filename)) {
            $values[] = $file->filename;
          }
        }

        elseif($key = 'path' && $node_type = 'ipaper') {
          $file = reset($node->files);
          print_r($file->path);
          if (!empty($file->path)) {
            $values[] = $file->filepath;
          }
        }
        else {
          $values[] = $node->$key;
        }
      }

      //print_r($values);
      $query = "INSERT INTO $result_table(". implode(", ", array_keys($fields)) .") VALUES(" . implode(", ", $d) . ")";
      //print_r($query);
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
