<?php
function export_nodes ($type, $fields,  $table_name) {
  $total =  db_result(db_query("SELECT COUNT(nid) FROM {node} n WHERE n.type = '%s' ORDER BY n.nid", $type));
  $range = 50;
  $count = 0;

  while($count < $total){
    $result = db_query("SELECT nid, type FROM {node} n WHERE n.type = '%s' ORDER BY n.nid LIMIT %d OFFSET %d", $type, $range, $count);
    while ($row = db_fetch_array($result)) {
      $node = node_load($row['nid']);
      $list_fields = implode(", ", $fields);
      $directives = "'%d', '%s', '%s', '%d'";
      $values = "";
      foreach($fields as $item) {
        $values += "\$node -> $item, ";
      }

      $insert = db_query("INSERT INTO $table_name($list_fields)) VALUES($directives)",
        $values );
      ++$count;
    }

    $params = array(
      '@count' => $count,
      '@total' => $total,
      '@id' => $node->nid,
    );
    drush_print(dt('(@count / @total) Processed node ID @id.', $params));
  }
}
