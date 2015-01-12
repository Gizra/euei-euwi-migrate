<?php

/**
 * @file
 * Contains \ExportOgMembership.
 */

class ExportOgMembership extends ExportBase {

  // The entity type name, destination table name.
  protected $entityType = 'og_membership';

  // There are fields to export. Key is name, value is sql directive.
  protected $fields = array(
    'nid' => '%s',
    'og_role' => '%d',
    'is_active' => '%d',
    'is_admin' => '%d',
    'uid' => '%s',
  );

  /**
   * Get amount membership records.
   *
   * @return integer
   */
  protected function getTotal() {
    return db_result(db_query("SELECT COUNT(*) FROM og_uid"));
  }

  /**
   * Get the results by a certain offset.
   *
   * @param int $offset
   *
   * @return array
   */
  protected function getResults($offset = 0) {
    return db_query("SELECT nid, uid FROM og_uid ORDER BY nid, uid LIMIT %d OFFSET %d", $this->getRange(), $offset);
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
    return db_fetch_object(db_query("SELECT * FROM og_uid WHERE nid = '%d' AND uid = '%d'", $row['nid'], $row['uid']));
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

  /**
   * Get the unique ID of the entity.
   *
   * @param $entity
   * @return array
   *   Array keyed by "unique_id" and the unique ID (site name, and entity ID, and node ID for og membership )
   *   as value.
   */
  protected function getEntityUniqueId($entity) {
    return array('unique_id' => $this->getSiteName() . ':' . $this->getEntityId($entity) . ':' . $entity->nid);
  }

  /**
   * {@inheritdoc}
   */
  protected function getValues($entity) {
    $values = parent::getValues($entity);
    foreach ($values as $key => $directive) {
      if (in_array($key, array('nid','uid'))) {
        $values[$key] = $this->getSiteName() . ':' . $entity->$key;
      }
    }
    return $values;
  }
}

