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
      ->fields('user', array(
        'unique_id',
        'name',
        'first_name',
        'last_name',
        'picture_path',
        'organization',
        'organization_category',
        'about_me',
        'country'
      ))
      ->orderBy('unique_id', 'ASC');

    $this->source = new MigrateSourceSQL($query);

    // Set destination object.
    $this->destination = new MigrateDestinationNode($this->bundle);

    // Map Node user with uid.
    $this
      ->addFieldMapping('uid', 'unique_id')
      ->sourceMigration('EuUser');

    $this->addFieldMapping('field_firstname', 'first_name');
    $this->addFieldMapping('field_lastname', 'last_name');
    $this->addFieldMapping('field_organisation', 'organization');
    $this->addFieldMapping('field_organisation_type_of', 'organization_category');
    $this->addFieldMapping('field_country', 'country');

    // Map picture of a User.
    $this
      ->addFieldMapping('field_picture', 'picture_path');

    // Explicitly set the file function.
    $this
      ->addFieldMapping('field_picture:file_function')
      ->defaultValue(' ');

    // Set path to folder that contain all migrated files.
    $this
      ->addFieldMapping('field_picture:source_path')
      ->defaultValue('sites/default/files');

    // Map description for a user.
    $this
      ->addFieldMapping('field_description', 'about_me');

  }

  /**
   * Generate title for a profile page.
   * Attach a user to a profile if a user already exist.
   *
   * @param $entity
   * @param $row
   */
  public function prepare($entity, $row) {
    // Skip if profile for this user already exists.
    $profile_nid = db_select('node', 'n')
      ->fields('n', array('nid'))
      ->condition('type', 'profile')
      ->condition('uid', $entity->uid)
      ->execute()
      ->rowCount();

    if (!empty($profile_nid)) {
      return FALSE;
    }

    // Make title for a Profile page.
    // Be sure that auto title generation is disabled
    // in Content-type -> People type.
    $entity->title = $row->first_name . ' ' . $row->last_name;

    // If first_name and last_name is empty then use name as title.
    $entity->title = trim($entity->title);
    if (empty($entity->title)) {
      $entity->title = $row->name;
    }

    // Test that user of the entity didn't migrated
    // because already exist in system.
    if (empty($entity->uid) && $row->unique_id) {
      // Fetch a name of existing user in dump.
      $user_name = db_select('_gizra_user', 'u')
        ->fields('u', array('name'))
        ->condition('unique_id', $row->unique_id)
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

    // Change format of description input to WYSWYG editor.
    if (isset($entity->field_description[0]['format'])) {
      $entity->field_description[0]['format'] = 4;
    }
  }

}
