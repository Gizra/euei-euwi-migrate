<?php

/**
 * @file
 * Contains \EuMembershipMigration
 */

class EuMembershipMigration extends EuMigration {

  protected $entityType = 'og';

  public function __construct() {
    parent::__construct();

    $this->description = t('Migration of the OG Membership');
    $this->dependencies = array('EuUser');

    // Set source object.
    $query = db_select('_gizra_og_membership', 'og')
      ->fields('og', array('unique_id', 'nid', 'og_role', 'is_active', 'is_admin', 'uid', 'created', 'changed'))
      ->orderBy('unique_id', 'ASC');

    $this->source = new MigrateSourceSQL($query);

    // Set destination object.
    $this->destination = new MigrateDestinationTable('og_uid');

    // Map Node user with uid.
    $this
      ->addFieldMapping('uid', 'unique_id')
      ->sourceMigration('EuUser');

    $this
      ->addSimpleMappings(array('og_role',
        'is_active',
        'is_admin'));

  }

  /**
   * Prepare row. Check for user and for group.
   *
   * @param $row
   * @return bool
   */
  public function prepareRow($row) {
    // Skip row if a group is not in list of importing groups.
    if (!isset($this->groupConnect[$row->nid])) {
      return FALSE;
    }

    // Skip row if a user is empty.
    if (!$row->uid) {
      return FALSE;
    }

    $user_name = db_select('_gizra_user', 'u')
      ->fields('u', array('name'))
      ->condition('unique_id', $row->uid)
      ->execute()
      ->fetchField();

    // Skip row if a user not exists in the EuUser migration.
    if (!$user_name) {
      return FALSE;
    }

    // Fetch a uid of existing user in c4d.
    $user_id = db_select('users', 'u')
      ->fields('u', array('uid'))
      ->condition('name', $user_name)
      ->execute()
      ->fetchField();

    // Skip row if a user not exists in C4D.
    if (!$user_id) {
      return FALSE;
    }

    // Skip row if a user-group already mapped in og_uid table.
    $og_uid_id = db_select('og_uid', 'og')
      ->fields('og')
      ->condition('nid', $this->groupConnect[$row->nid])
      ->condition('uid', $user_id)
      ->execute()
      ->rowCount();

    // It's mean that user - group already mapped. Then skip it.
    if ($og_uid_id) {
      return FALSE;
    }

    // Save additional col with a user id in c4d.
    $row->c4d_uid = $user_id;
  }

  /**
   * Map a group and a user to entity.
   *
   * @param $entity
   * @param $row
   */
  public function prepare($entity, $row) {
    // Map og membership with a user.
    $entity->uid = $row->c4d_uid;

    // Map original group id with new group id.
    $entity->nid = $this->groupConnect[$row->nid];
  }
}
