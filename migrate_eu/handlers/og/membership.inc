<?php

/**
 * @file
 * Contains \EuMembershipMigration
 */

class EuMembershipMigration extends EuMigration {

  protected $entityType = 'og';

  public function __construct() {
    parent::__construct();

    $this->description = t('Migration of the Membership');
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
   * Map a user if it's not did automatically.
   * Map a group.
   *
   * @param $entity
   * @param $row
   */
  public function prepare($entity, $row) {
    // Test that user of the entity didn't migrated
    // because already exist in system.
    if (empty($entity->uid) && $row->uid) {
      // Fetch a name of existing user in dump.
      $user_name = db_select('_gizra_user', 'u')
        ->fields('u', array('name'))
        ->condition('unique_id', $row->uid)
        ->execute()
        ->fetchField();

      // Fetch a uid of existing user in c4d.
      $user_id = db_select('users', 'u')
        ->fields('u', array('uid'))
        ->condition('name', $user_name)
        ->execute()
        ->fetchField();

      // Set uid for entity.
      $entity->uid = $user_id;
    }

    // Map original group id with new group id.
    $entity->nid = $this->groupConnect[$row->nid];
  }

  /**
   * Update `created` and `updated` cols.
   *
   * @param $entity
   */
  public function complete($entity) {
    $entity->created = $entity->changed = time();
  }
}
