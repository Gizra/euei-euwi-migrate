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
 * Entry point for exporting data.
 *
 * @param $entity_type
 *  The entity type to export.
 * @param bundle
 *  Array fields for export to another table consist
 *  Key is name of field,
 *  Value is directive type ('%s' - string, '%d' - integer, etc.)
 * @param $result_table
 *  Name for a new table for move data
 * @return bool
 *
 */
function export_data($entity_type, $bundle, $fields = array(), $table_bundle_name = NULL) {
  $result_table = '_gizra_' . $entity_type . '_';
  $result_table .= $table_bundle_name ? $table_bundle_name : $bundle;

  $total =  db_result(db_query("SELECT COUNT(nid) FROM {node} n WHERE n.type = '%s' ORDER BY n.nid", $bundle));
  db_query("TRUNCATE TABLE  `_gizra_blog_post`"); //temporary
  $range = 50;
  $count = 0;

  $directives = array();
  foreach ($fields as $type){
    $directives[] = $type;
  }

  while($count < $total){
    $result = db_query("SELECT nid FROM {node} n WHERE n.type = '%s' ORDER BY n.nid LIMIT %d OFFSET %d", $node_type, $range, $count);
    while ($row = db_fetch_array($result)) {
      $node = node_load($row['nid']);
      //print_r($node);
      // Prepare the query:
      $values = "";
      foreach($fields as $key => $type) {
        if($key == 'file_name' && $node_type == 'ipaper') {
          $file = reset($node->files);
          $values[] = !empty($file->filename)? $file->filename :'';
        }

        elseif($key == 'path' && $node_type == 'ipaper') {
          $file = reset($node->files);
          print_r($file->path);
          if (!empty($file->filepath)) {
            $values[] = $file->filepath;
          }
        }
        else {
          $values[] = $node->$key;
        }
      }

      //print_r($values);
      $query = "INSERT INTO $result_table(". implode(", ", array_keys($fields)) .") VALUES(" . implode(", ", $directives) . ")";
      print_r($values);
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
