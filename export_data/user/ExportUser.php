<?php
/**
 * @file
 * Contains \ExportNodeDocument.
 */

class ExportUser extends ExportBase {

  protected $entityType = 'user';

  protected $fields = array(
    'uid' => '%d',
    'name' => '%s',
    'password' => '%s',
    'mail' => '%s',
  );

  /**
   * Get values from entity.
   *
   * @param stdClass $entity
   *   The entity to process and extract the values.
   *
   * @return array
   *   Array keyed by the SQL directive, and the value to insert.
   */
  protected function getValues($user) {
    $values = array();
    foreach($this->getFields() as $key => $directive) {
      switch ($key){
        case 'password':
          $values['password'] = $user->pass;
          break;
        default:
          $values[$key] = $user->$key;
          break;
      }
    }
    return $values;
  }

  /**
  * Get amount node records.
  *
  * @return integer
  */
  protected function getTotal() {
    return db_result(db_query("SELECT COUNT(u.uid) FROM {users} u WHERE u.uid != 0 and status = 1 ORDER BY u.uid"));
  }

  /**
   * Get the results by a certain offset.
   *
   * @param int $offset
   *
   * @return array
   */
  protected function getResults($offset = 0) {
    return db_query("SELECT u.uid FROM {users} u WHERE u.uid != 0 and status = 1 ORDER BY u.uid LIMIT %d OFFSET %d", $this->getRange(), $offset);
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
    return user_load($row);
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
