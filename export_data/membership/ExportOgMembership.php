<?php
/**
 * @file
 * Contains \ExportOgMembership.
 */

class ExportOgMembership extends ExportBase {

  // The entity type name, destination table name.
  protected $entityType = 'og_membership';

  protected $fields = array(
    'nid' => '%d',
    'og_role' => '%d',
    'is_active' => '%d',
    'is_admin' => '%d',
    'uid' => '%d',
  );

  /**
   * Get amount membership records.
   *
   * @return integer
   */
  protected function getTotal() {
    return db_result(db_query("SELECT COUNT(uid) FROM og_uid"));
  }

  /**
   * Get the results by a certain offset.
   *
   * @param int $offset
   *
   * @return array
   */
  protected function getResults($offset = 0) {
    return db_query("SELECT uid FROM og_uid ORDER BY nid LIMIT %d OFFSET %d", $this->getRange(), $offset);
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
    var_dump($row);
    return db_fetch_object(db_query('SELECT * FROM og_uid WHERE uid = %d', $row['uid']));
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
    return $entity->uid;
  }
}

