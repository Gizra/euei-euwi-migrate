<?php

class EuUserMigration extends Migration {
  public function __construct() {
    parent::__construct();

    $this->description = t('Migrate users');

    // Set source object.
    $query = db_select('_gizra_user', 'user')
      ->fields('user', array('unique_id', 'uid', 'name', 'password', 'mail'))
      ->orderBy('uid', 'ASC');


    $this->source = new MigrateSourceSQL($query);

    // Set destination object.
    $this->destination = new MigrateDestinationUser();

    // Set map object.
    $this->map = new MigrateSQLMap($this->machineName,
      array(
        'unique_id' => array('type' => 'varchar',
          'length' => 254,
          'not null' => TRUE,
          'description' => 'Source name and user id',
        )
      ),
      MigrateDestinationUser::getKeySchema()
    );

    $this->addSimpleMappings(array(
      'name',
      'mail',
    ));
    $this->addFieldMapping('pass', 'password');
  }

  public function prepareRow($row) {
    // Always include this fragment at the beginning of every prepareRow()
    // implementation, so parent classes can ignore rows.
    if (parent::prepareRow($row) === FALSE) {
      return FALSE;
    }

    // Check that current import user are not exists in origin database.
    $result = db_select('users', 'u')
      ->fields('u', array('name'))
      ->condition('name', $row->name)
      ->countQuery()
      ->execute()
      ->fetchField();

    if($result) {
      return FALSE;
    }

    return TRUE;
  }

}
