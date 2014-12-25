<?php

abstract class EuMigration extends Migration {
  public function __construct() {
    parent::__construct();

    $this->team = array(
      new MigrateTeamMember('Ilya Manyahin', 'ilya@gizra.com', t('Developer'))
    );

  }
}

class UserMigration extends EuMigration {
  public function __construct() {
    parent::__construct();

    $this->description = t('Migrate users');

    $this->map = new MigrateSQLMap($this->machineName,
      array(
        'unique_id' => array('type' => 'varchar',
          'length' => 254,
          'not null' => TRUE,
          'description' => 'Source name and user id',
        )
      ),
      MigrateDestinationTerm::getKeySchema()
    );

    $query = db_select('_gizra_user', 'user')
      ->fields('user', array('unique_id', 'uid', 'name', 'password', 'mail'));

    $this->source = new MigrateSourceSQL($query);

    $this->destination = new MigrateDestinationUser('Migrate User');


    // $Destination = Source
    $this->addFieldMapping('name', 'name');
    $this->addFieldMapping('pass', 'password');
    $this->addFieldMapping('mail', 'mail');

  }
}
