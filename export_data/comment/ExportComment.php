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
}

