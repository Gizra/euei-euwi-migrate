<?php

abstract class EuMigration extends Migration {

  protected $unique_id_setting = array(
    'unique_id' => array('type' => 'varchar',
      'length' => 254,
      'not null' => TRUE,
      'description' => 'Origin source name and user id separated by colon.',
    )
  );

  public function __construct() {
    // Make sure only a single alias is created.
    $this->addFieldMapping('pathauto_perform_alias')
      ->defaultValue(FALSE);

    // Set map object.
    $this->map = new MigrateSQLMap($this->machineName,
      $this->unique_id_setting,
      $this->entityType == 'user' ? MigrateDestinationUser::getKeySchema() : MigrateDestinationNode::getKeySchema(),
    );
  }

}
