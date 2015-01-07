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

  }

  /**
   * Map original group id with new group id.
   */
  public function prepare($entity, $row) {
    $entity->og_groups = array($this->groupConnect[$row->gid]);
  }
}
