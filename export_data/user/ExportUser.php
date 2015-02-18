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
    'first_name' => '%s',
    'last_name' => '%s',
    'picture_path' => '%s',
    'organization' => '%s',
    'organization_category' => '%s',
    'about_me' => '%s',
    'taxonomy' => '%s',
    'country' => '%s',
    'created' => '%d'
  );

  /**
   * Get values from entity.
   *
   * @param object $entity
   *   The entity to process and extract the values.
   *
   * @return array
   *   Array keyed by field name, and the value to insert.
   */
  protected function getValues($entity) {
    $values = parent::getValues($entity);
    foreach ($values as $key => $directive) {
      if ($key == 'password') {
        $values[$key] = $entity->pass;
      }
      elseif ($key == 'first_name') {
        $values[$key] = $entity->profile_name;
      }
      elseif ($key == 'last_name') {
        $values[$key] = $entity->profile_lastname;
      }
      elseif ($key == 'picture_path') {
        $values[$key] = $this->getPicturePath($entity);
      }
      elseif ($key == 'organization') {
        $values[$key] = $entity->profile_organization;
      }
      elseif ($key == 'organization_category') {
        $values[$key] = $entity->profile_organization_category;
      }
      elseif ($key == 'country') {
        $values[$key] = $entity->profile_country;
      }
      elseif ($key == 'about_me') {
        $values[$key] = $entity->profile_selfprojection;
      }
      elseif ($key == 'taxonomy') {
        $values[$key] = $this->getTaxonomyFromAccount($entity);
      }
    }
    return $values;
  }

  /**
  * Get amount user records.
  *
  * @return integer
  */
  protected function getTotal() {
    return db_result(db_query("SELECT COUNT(u.uid) FROM {users} u WHERE u.uid != 0 and status = 1"));
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

  /**
   * Check necessity of exporting data.
   * Check existence name, pass, email and belonging to groups for export.
   *
   * @param $entity
   *   Verifiable entity
   *
   * @return bool
   */
  protected function isExportable($entity) {

    if (empty($entity->name) || empty($entity->pass) || empty($entity->mail)) {
      return FALSE;
    }
    foreach ($entity->og_groups as $og_key => $og_group) {
      if (in_array($og_key, $this->groupForExport[$this->getSiteName()])) {
        return TRUE;
      }
    }
  }

  /**
   * Returns path to copied file.
   *
   * @param $entity
   *   The account.
   *
   * @return string
   *   Path to copied file.
   *
   * @throws Exception
   *   Message if something is wrong.
   */
  protected function getPicturePath($entity) {
    if (!empty($entity->picture)) {
      $file['filepath'] = $entity->picture;
      return $this->exportFile($file, 'pictures');
    }
  }

  /**
   * Return taxonomy term of account.
   *
   * @param $entity
   *   The account.
   *
   * @return string
   *   Taxonomy terms as string separated by pipe.
   */

  protected function getTaxonomyFromAccount($entity) {
    if (empty($entity->taxonomy)) {
      return;
    }
    $taxonomy=array();
    foreach ($entity->taxonomy as $term) {
      $taxonomy[] = $term->name;
    }
    return implode('|', $taxonomy);
  }
}
