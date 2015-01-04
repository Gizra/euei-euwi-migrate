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
      'sticky'
    ));

    $this
      ->addFieldMapping('uid', 'uid')
      ->sourceMigration('EuUser');
    
  }

  /**
   * Check that current imported news have group id.
   * Map original group id with new group id.
   *
   * @param $row
   *  Current row in exported table.
   */
  public function prepareRow($row)
  {
    var_dump($row); die;
    // Skip news without gid or if gid not defined.
    if (isset($this->groups_connect[$row->gid]) && !empty($this->groups_connect[$row->gid])) {
      // Map original gid with new gid.
      $row->gid = $this->groups_connect[$row->gid];
    }
    else {
      return FALSE;
    }

    return TRUE;
  }
}
