<?php

/**
 * @file
 * Contains \ExportComment.
 */

class ExportComment extends ExportBase {

  // The entity name.
  protected $entityType = 'comment';

  // Fields to export for comments.
  protected $fields = array(
    'cid' => '%d',
    'pid' => '%s',
    'nid' => '%s',
    'uid' => '%s',
    'subject' => '%s',
    'comment' => '%s',
    'timestamp' => '%d',
    'name' => '%s',
    'mail' => '%s',
  );

  /**
   * Get amount comment records.
   *
   * @return integer
   */
  protected function getTotal() {
    return db_result(db_query("SELECT COUNT(cid) FROM comments"));
  }

  /**
   * Get the results by a certain offset.
   *
   * @param int $offset
   *
   * @return array
   */
  protected function getResults($offset = 0) {
    return db_query("SELECT cid FROM comments ORDER BY nid LIMIT %d OFFSET %d", $this->getRange(), $offset);
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
    return _comment_load($row['cid']);
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
    return $entity->cid;
  }

  /**
   * {@inheritdoc}
   */
  protected function getValues($entity) {

    $values = parent::getValues($entity);
    foreach ($values as $key => $directive) {
      if (in_array($key, array('uid', 'nid', 'pid'))) {
        $values[$key] = $this->getSiteName() . ':' . $entity->$key;
      }
    }
    return $values;
  }

  /**
  * Check necessity of exporting data.
  *
  * @param $entity
  *   Verifiable entity
  *
  * @return bool
  */
  protected function isExportable($entity) {
    $node = node_load($entity->nid);
    if (empty($node->og_groups)) {
      return $node->type == 'ipaper';
    }
    foreach ($node->og_groups as $og_group) {
      if (in_array($og_group, $this->groupForExport[$this->getSiteName()])) {
        return TRUE;
      }
    }
  }
}

