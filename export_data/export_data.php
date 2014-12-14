<?php


/**
 * Entry point for exporting data.
 *
 * @param string $entity_type
 *  The entity type to export.
 * @param string $bundle
 *  The bundle to export.
 * @param array $fields
 *  Array fields for export, keyed by the column name and the  directive type
 * (e.g. '%s', '%d') as value.
 * @param $table_bundle_name
 *  Name for a bundle of the table, in case we are renaming an existing bundle
 * (e.g. "ipaper" is renamed to "document").
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
