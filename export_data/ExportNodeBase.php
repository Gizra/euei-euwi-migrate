<?php

/**
 * @file
 * Contains \ExportNodeBase.
 */

require '/vagrant/wordpress/build/euei/export_data/ExportBase.php';

class ExportNodeBase extends ExportBase {

  protected $entityType = 'node';

  protected $bundle = NULL;

  /**
   * Return key value array with name and format.
   *
   * @return array
   */
  protected function getBaseFields() {
    return array(
      'nid' => '%d',
      'title' => '%s',
      'body' => '%s',
      'uid' => '%s',
      'path' => '%s',
      'promote' => '%d',
      'sticky' => '%d',
      'gid' => '%s',
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
    return parent::getDestinationTable() . '_' . $this->getBundle();
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

  /**
   * {@inheritdoc}
   */
  protected function getValues($entity) {
    $values = parent::getValues($entity);
    foreach($values as $key => $directive) {
      if ($key == 'gid') {
        $values[$key] = $this->getGroupIdFromEntity($entity);
      }
      elseif ($key == 'uid') {
        $values[$key] = $this->getSiteName() . ':' . $entity->$key;
      }
    }
    return $values;
  }

  /**
   * Check if the node belongs certain groups and needs to export.
   *
   * @param $entity
   *   The entity object.
   *
   * @return bool
   */
  protected function isExportable($entity) {
    if (empty($entity->og_groups)) {
      // Node is not associated with any group.
      return;
    }
    foreach ($entity->og_groups as $og_group) {
      if (in_array($og_group, $this->groupForExport[$this->getSiteName()])) {
        return TRUE;
      }
    }
  }

  /**
   * Return list of necessary groups of entity for export separated by pipe.
   *
   * @param $entity
   *   The entity object.
   *
   * @return string
   *
   */
  protected function getGroupIdFromEntity($entity) {
    foreach ($entity->og_groups as $og_group) {
      if (in_array($og_group, $this->groupForExport[$this->getSiteName()])) {
        return $this->getSiteName() . ':' . $og_group;
      }
    }
  }
}

