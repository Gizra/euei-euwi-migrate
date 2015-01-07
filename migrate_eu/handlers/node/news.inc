<?php

/**
 * @file
 * Contains \EuNewsMigration
 */

class EuNewsMigration extends EuMigration {

  protected $entityType = 'node';

  protected $bundle = 'blog';

  public function __construct() {
    parent::__construct();

    $this->description = t('Migrate news');
    $this->dependencies = array('EuUser');

    // Set source object.
    $query = db_select('_gizra_node_blog_post', 'post')
      ->fields('post', $this->getNodeFields())
      ->orderBy('nid', 'ASC');

    $this->source = new MigrateSourceSQL($query);

    // Set destination object.
    $this->destination = new MigrateDestinationNode($this->bundle);

    $this->addSimpleMappings(array(
      'title',
      'body',
      'path',
      'promote',
      'sticky',
    ));

    $this->addFieldMapping('og_groups', 'gid');

    $this
      ->addFieldMapping('uid', 'uid')
      ->sourceMigration('EuUser');
  }

  public function prepare($entity, $row) {

    // Test that user of the news didn't migrated
    // because already exist in system.
    if (empty($entity->uid)) {
      // Fetch a mail of existing user in dump.
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
    $entity->og_groups = array($this->groupConnect[$row->gid]);
  }
}
