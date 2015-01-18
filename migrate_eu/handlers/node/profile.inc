<?php

/**
 * @file
 * Contains \EuProfileMigration
 */

class EuProfileMigration extends EuMigration {

  protected $entityType = 'node';

  protected $bundle = 'profile';

  public function __construct() {
    parent::__construct();

    $this->description = t('Migration of the Profile');
    $this->dependencies = array('EuUser');

    // Set source object.
    $query = db_select('_gizra_user', 'user')
      ->fields('user', array('unique_id', 'first_name', 'last_name', 'organization', 'organization_category', 'country'))
      ->orderBy('unique_id', 'ASC');

    $this->source = new MigrateSourceSQL($query);

    // Set destination object.
    $this->destination = new MigrateDestinationNode($this->bundle);

    // Map Node user with uid.
    $this
      ->addFieldMapping('uid', 'unique_id')
      ->sourceMigration('EuUser');

    $this->addFieldMapping('field_lastname', 'first_name');
    $this->addFieldMapping('field_firstname', 'last_name');
    $this->addFieldMapping('field_organisation', 'organization');
    $this->addFieldMapping('field_organisation_type_of', 'organization_category');
    $this->addFieldMapping('field_country', 'country');

  }

  public function prepare($entity, $row)
  {
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
  }
}
