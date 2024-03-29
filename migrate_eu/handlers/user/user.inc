<?php

/**
 * @file
 * Contains \EuUserMigration
 */

class EuUserMigration extends EuMigration {

  protected $entityType = 'user';

  public function __construct() {
    parent::__construct();

    $this->description = t('Migration of the Users');

    // Set source object.
    $query = db_select('_gizra_user', 'user')
      ->fields('user', array('unique_id', 'uid', 'name', 'password', 'mail', 'created'))
      ->orderBy('uid', 'ASC');

    $this->source = new MigrateSourceSQL($query);

    // Set destination object.
    $this->destination = new MigrateDestinationUser();

    $this->addSimpleMappings(array(
      'name',
      'mail',
      'created',
    ));

    $this->addFieldMapping('pass', 'password');

    // Map user init.
    $this->addFieldMapping('init', 'mail');

  }

  /**
   * Check that current imported user are not exists in origin database.
   * And modify user email to prevent sending emails by mistake.
   *
   * @param $row
   *  Current row in exported table.
   */
  public function prepareRow($row) {
    // Check that current import user are not exists in origin database.
    $result = db_select('users', 'u')
      ->fields('u', array('name'))
      ->condition('name', $row->name)
      ->countQuery()
      ->execute()
      ->fetchField();

    if ($result) {
      // User already exists, so skip it.
      return FALSE;
    }

    return TRUE;
  }

}
