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

  $fields += call_user_func('export_data_get_base_fields__' . $entity_type);

  // Remove any existing data.
  db_query('TRUNCATE TABLE '. $destination_table);
  $query = export_get_select_query_base_by_entity_type($entity_type, TRUE);
  $total =  db_result(db_query($query, $original_bundle));
  $count = 0;

  $directives = array();
  foreach ($fields as $directive) {
    $directives[] = "'" . $directive . "'";
  }
  //$total = 5
  //$range =5;
  while ($count < $total) {

    $query = export_get_select_query_base_by_entity_type($entity_type);
    $result = db_query($query . ' LIMIT %d OFFSET %d', $original_bundle, $range, $count);

    while ($row = db_fetch_array($result)) {

      $entity = export_load_entity_base_entity_type($entity_type, $row);
      //$entity = call_user_func($entity_type . '_load', $row['id']);
      var_dump($row);

      $function = 'export_prepare_data_for_insert__' . $entity_type . '__' . $destination_bundle;
      if (function_exists($function)) {
        $values = $function($entity, $fields);
      }
      else {
        // No special case, just take the values.
        $values = array();
        foreach($fields as $key => $directive) {
          $values[$key] = $entity->$key;
        }
      }

      $query = "INSERT INTO $destination_table(". implode(", ", array_keys($fields)) .") VALUES(" . implode(", ", $directives) . ")";

      db_query($query, $values);
      ++$count;
      $id = export_get_id_name_base_on_entity_type($entity_type);


      $params = array(
        '@count' => $count,
        '@total' => $total,
        '@id' => $entity->$id,
      );
      drush_print(dt('(@count / @total) Processed node ID @id.', $params));
    }
  }
}

/**
 * Return the base fields that need to be exported for node content type.
 *
 * @return array
 *   Array keyed by the column name, and the SQL directive as value.
 */
function export_data_get_base_fields__node() {
  return array(
    'nid' => '%d',
    'title' => '%s',
    'body' => '%s',
    'uid' => '%d',
    'path' => '%s',
    'promote' => '%d',
    'sticky' => '%d',
  );
}

function export_data_get_base_fields__user() {
  return array(
    'uid' => '%d',
    'name' => '%s',
    'password' => '%s',
    'mail' => '%s',
  );
}

/**
 * Return a select query by entity type.
 *
 * @param $entity_type
 *   The entity type name.
 * @param bool $count_query
 *   Indicate if the query should be a COUNT(). Defaults to FALSE.
 *
 * @return string
 *   The query string that can be used with query_db().
 */
function export_get_select_query_base_by_entity_type($entity_type, $count_query = FALSE) {
  switch ($entity_type) {
    case 'node':
      $select = $count_query ? 'COUNT(nid)' : 'nid';
      break;
    case 'user':
      $select = $count_query ? 'COUNT(uid)' : 'uid';
      break;
    default:
      $select = $count_query?  'COUNT(*)' : '*';
      break;
  }

  switch ($entity_type) {
    case 'node':
      return "SELECT {$select} FROM {node} n WHERE n.type = '%s' ORDER BY n.nid";
    case 'user':
      return "SELECT {$select} FROM {users} u WHERE status = '%d' ORDER BY u.uid";
  }
}

function export_get_id_name_base_on_entity_type($entity_type) {
  switch ($entity_type) {
    case 'node':
      $id = 'nid';
      break;
    case 'user':
      $id = 'uid';
      break;
  }
  return $id;
}

function export_load_entity_base_entity_type($entity_type, $row) {
  switch ($entity_type) {
    case 'node':
      $entity = node_load($row['nid']);
      break;
    case 'user':
      $entity = user_load($row);
      break;
  }
  return $entity;
}
