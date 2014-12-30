<?php
/**
 * @file
 * Contains EuNewsMigration class for migrate EuNews.
 */
class EuNewsMigration extends EuMigration {

  protected $destination_class_name = 'MigrateDestinationNode';

  protected $bundle = 'blog';

  public function __construct() {
    parent::__construct();

    $this->description = t('Migrate news');
    $this->dependencies = array('EuUser');

    // Set source object.
    $query = db_select('_gizra_node_blog_post', 'post')
      ->fields('post', array('unique_id', 'nid', 'title', 'body', 'uid', 'path', 'promote', 'sticky'))
      ->orderBy('nid', 'ASC');

    $this->source = new MigrateSourceSQL($query);

    // Set destination object.
    $this->destination = new $this->destination_class_name($this->bundle);

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

}
