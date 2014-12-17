<?php
/**
 * @file
 * Contains \ExportNodeBase.
 */

require '/vagrant/wordpress/build/euei/export_data/ExportBase.php';

class ExportNodeBase extends ExportBase {

  protected $entityType = 'node';

  protected $bundle = NULL;

  protected function getBaseFields() {
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

  /**
   * Get the results by a certain offset.
   *
   * @param int $offset
   *
   * @return array
   */
  protected function getResults($offset = 0) {
    return db_query("SELECT nid FROM {node} n WHERE n.type = '%s' ORDER BY n.nid LIMIT %d OFFSET %d", $this->getOriginalBundle(), $this->getRange(), $offset);
  }

  /**
   * Get the bundle name.
   *
   * @return string
   */
  protected function getBundle() {
    return $this->bundle;
  }

  /**
   * Return the destination table.
   *
   * @return string
   */
  protected function getDestinationTable() {
    return '_gizra_' . $this->getEntityType() . '_' . $this->getBundle();
  }

  /**
   * Get the original bundle, if exists.
   *
   * For example the "ipaper" bundle, is the original bundle for "document".
   *
   * @return string
   *   The bundle name.
   */
  protected function getOriginalBundle() {
    return !empty($this->originalBundle) ? $this->originalBundle : $this->getBundle();
  }

  /**
   * Get amount node records.
   *
   * @return integer
   */
  protected function getTotal() {
    return db_result(db_query("SELECT COUNT(nid) FROM {node} n WHERE n.type = '%s' ORDER BY n.nid", $this->getOriginalBundle()));
  }

  /**
   * Get Entity from query result row.
   *
   * @param $row
   *   The row fetched from result query
   *
   * @return object
   */
  protected function getEntityFromRow($row) {
    return node_load($row['nid']);
  }

  /**
   * Get entity ID.
   *
   * @param object $entity
   *    The entity object.
   *
   * @return integer
   */
  protected function getEntityId($entity) {
    return $entity->nid;
  }
}