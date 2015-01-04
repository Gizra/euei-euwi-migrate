<?php

/**
 * @file
 * Contains \EuMigration
 */

abstract class EuMigration extends Migration {

  protected $unique_id_setting = array(
    'unique_id' => array('type' => 'varchar',
      'length' => 254,
      'not null' => TRUE,
      'description' => 'Origin source name and user id separated by colon.',
    )
  );

  /**
   * Array for set mapping between old and new group id.
   *
   * @var array
   */
  protected $groups_connect = array(
    // EUEI
    'euei:54' => '43874', // AFRETEP
    'euei:10772' => '43876', // CEREEECA
  );

  /**
   * Return the base fields that should be migrated on all node bundles.
   */
  protected function getNodeFields() {
    return array('unique_id', 'nid', 'title', 'body', 'uid', 'path', 'promote', 'sticky');
  }

  public function __construct() {
    parent::__construct();

    // Set destination class.
    if ($this->entityType == 'node') {
      $destination_class = 'MigrateDestinationNode';
    }
    else {
      $destination_class = 'MigrateDestinationUser';
    }

    // Set map object.
    $this->map = new MigrateSQLMap($this->machineName,
      $this->unique_id_setting,
      call_user_func(array($destination_class, 'getKeySchema'))
    );

    // Make sure only a single alias is created.
    $this->addFieldMapping('pathauto_perform_alias')
      ->defaultValue(FALSE);
  }

}
