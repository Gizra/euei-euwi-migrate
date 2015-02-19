<?php

/**
 * @file
 * Contains \EuNewsMigration
 */

class EuNewsMigration extends EuMigrationNode {

  protected $entityType = 'node';

  protected $bundle = 'blog';

  public function __construct() {
    parent::__construct();

    $this->description = t('Migration of the News');
    $this->dependencies = array('EuUser', 'EuProfile', 'EuMembership',
      'EuDocument', 'EuEvent',
    );

    // Set source object.
    $query = db_select('_gizra_node_blog_post', 'post')
      ->fields('post', $this->getNodeFields())
      ->orderBy('nid', 'ASC');

    $this->source = new MigrateSourceSQL($query);

    // Set destination object.
    $this->destination = new MigrateDestinationNode($this->bundle);

    // Map additional documents to this News.
    $this
      ->addFieldMapping('field_ref_document', 'ref_documents')
      ->separator('|')
      ->sourceMigration('EuDocument');

  }

}
