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
}
