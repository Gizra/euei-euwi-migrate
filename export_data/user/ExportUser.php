<?php
/**
 * @file
 * Contains \ExportUser.
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
   * @param stdClass $user
   *   The entity to process and extract the values.
   *
   * @return array
   *   Array keyed by field name, and the value to insert.
   */
  protected function getValues($user) {
    $values = array();

    foreach($this->getFields() as $key => $directive) {
      $values[$key] = $values[$key] == 'password' ? $user->pass : $user->$key;
    }
    return $values;
  }

  /**
  * Get amount user records.
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
   * Get the entity ID.
   *
   * @param $user
   *  The user object.
   *
   * @return integer
   */
  protected function getEntityId($user) {
    return $user->uid;
  }
}
