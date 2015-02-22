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
    'created' => '%d',
    'changed' => '%d',
  );

  /**
   * Get amount membership records for valid, active users.
   *
   * @return integer
   */
  protected function getTotal() {
    // Check that user has name, password, mail, active status
    // and belongs to certain group.
    return db_result(db_query("SELECT COUNT(*) FROM {og_uid} og LEFT JOIN {users} u
      ON og.uid = u.uid
      WHERE og.nid IN (%s)
      AND u.name != ''
      AND u.pass  != ''
      AND u.mail  != ''
      AND u.status != 0", implode(', ', $this->groupForExport[$this->getSiteName()])));
  }

  /**
   * Get the results by a certain offset for valid, active users.
   *
   * @param int $offset
   *
   * @return array
   */
  protected function getResults($offset = 0) {
    // Check that user has name, password, mail, active status
    // and belongs to certain group.
    return db_query("SELECT og.nid, og.uid FROM {og_uid} og LEFT JOIN {users} u
      ON og.uid = u.uid
      WHERE og.nid IN (%s)
      AND u.name != ''
      AND u.pass  != ''
      AND u.mail  != ''
      AND u.status != 0
      ORDER BY nid, uid LIMIT %d OFFSET %d",
      implode(', ', $this->groupForExport[$this->getSiteName()]),
      $this->getRange(),
      $offset
    );
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
      if (in_array($key, array('nid', 'uid'))) {
        $values[$key] = $this->getSiteName() . ':' . $entity->$key;
      }
    }

    if (empty($value['changed'])) {
      $value['changed'] = $value['created'];
    }
    return $values;
  }
}

