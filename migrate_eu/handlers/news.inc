<?php
/**
 * @file
 * Contains EuNewsMigration class for migrate EuNews.
 */
class EuNewsMigration extends EuMigration {
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
    $this->destination = new MigrateDestinationNode('news');

    // Set map object.
    $this->map = new MigrateSQLMap($this->machineName,
      $this->unique_id_setting,
      MigrateDestinationNode::getKeySchema()
    );

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
