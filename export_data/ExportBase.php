<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12/17/14
 * Time: 8:40 AM
 */

class ExportBase implements ExportInterface {

  protected $entityType = NULL;

  protected $destination_table = NULL;

  protected $range = 50;

  public function export() {
    $destination_table = '_gizra_' . $entity_type . '_';

    $destination_bundle = $destination_bundle ? $destination_bundle : $original_bundle;
    $destination_table .= $destination_bundle;

    $fields $this->getFields();

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
  }

  /**
   * @return array
   */
  protected function getFields() {
    return array_merge($this->fields, $this->getBaseFields());
  }

  /**
   * @return mixed
   */
  protected function getEntityType() {
    return $this->entityType;
  }

  protected function truncateTable() {

  }

  protected function getTotal() {

  }

  protected function insertQuery() {

  }

  protected function prepare() {

  }

}