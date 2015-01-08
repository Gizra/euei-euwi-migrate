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
  protected $groupConnect = array(
    // EUEI
    'euei:54' => '43874', // AFRETEP
    'euei:10772' => '43876', // CEREEECA
  );

  /**
   * Return the base fields that should be migrated on all node bundles.
   */
  protected function getNodeFields() {
    return array('unique_id',
      'nid',
      'title',
      'body',
      'uid',
      'path',
      'promote',
      'sticky',
      'gid',
    );
  }

  public function __construct() {
    parent::__construct();

    // Set destination class.
    if ($this->entityType == 'node') {
      $destination_class = 'MigrateDestinationNode';
    }
    elseif ($this->entityType == 'user') {
      $destination_class = 'MigrateDestinationUser';
    }
    else {
      $destination_class = 'MigrateDestinationComment';
    }

    // Set map object.
    $this->map = new MigrateSQLMap($this->machineName,
      $this->unique_id_setting,
      call_user_func(array($destination_class, 'getKeySchema'))
    );

    // Make sure only a single alias is created.
    $this->addFieldMapping('pathauto_perform_alias')
      ->defaultValue(FALSE);

    // Map for groups.
    $this->addFieldMapping('og_groups', 'gid');

    // Map for users.
    $this
      ->addFieldMapping('uid', 'uid')
      ->sourceMigration('EuUser');
  }

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
    if ($this->entityType == 'node') {
      $entity->og_groups = array($this->groupConnect[$row->gid]);
    }
  }
}
