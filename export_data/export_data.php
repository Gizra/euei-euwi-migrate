<?php

/**
 * @file
 * Export data for all entities.
 */

/**
 * Entry point for exporting data.
 *
 * @param string $entity_type
 *   The entity type to export.
 * @param string $original_bundle
 *   The bundle to export.
 * @param array $fields
 *   Array fields for export, keyed by the column name and the  directive type
 *  (e.g. '%s', '%d') as value.
 * @param $destination_bundle
 *   Name for a bundle of the table, in case we are renaming an existing bundle
 *  (e.g. "ipaper" is renamed to "document").
 * @param int $range
 *   The number of items to process in one batch. Defaults to 50.
 */
function export_data($entity_type, $original_bundle, $fields = array(), $destination_bundle = NULL, $range = 50) {
  $destination_table = '_gizra_' . $entity_type . '_';

  $destination_bundle = $destination_bundle ? $destination_bundle : $original_bundle;
  $destination_table .= $destination_bundle;

  // Remove any existing data.
  db_query('TRUNCATE TABLE '. $destination_table);

  $total =  db_result(db_query("SELECT COUNT(nid) FROM {node} n WHERE n.type = '%s' ORDER BY n.nid", $original_bundle));
  $count = 0;

  $directives = array();
  foreach ($fields as $directive){
    $directives[] = "'" . $directive . "'";
  }

  while($count < $total){
    $result = db_query("SELECT nid FROM {node} n WHERE n.type = '%s' ORDER BY n.nid LIMIT %d OFFSET %d", $original_bundle, $range, $count);

    while ($row = db_fetch_array($result)) {
      $node = node_load($row['nid']);

      $function = 'export_prepare_data_for_insert__' . $entity_type . '__' . $destination_bundle;
      if (function_exists($function)) {
        $values = $function($node, $fields);
      }
      else {
        // No special case, just take the values.
        $values = array();
        foreach($fields as $key => $directive) {
          $values[$key] = $node->$key;
        }
      }

      $query = "INSERT INTO $destination_table(". implode(", ", array_keys($fields)) .") VALUES(" . implode(", ", $directives) . ")";
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
