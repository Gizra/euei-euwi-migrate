<?php

/**
 * @file
 * Contains \EuDocumentMigration
 */

class EuDocumentMigration extends EuMigration {

  protected $entityType = 'node';

  protected $bundle = 'document';

  public function __construct() {
    parent::__construct();

    $this->description = t('Migrate documents');

    // Set source object.
    $query = db_select('_gizra_node_document', 'document')
      ->fields('document', array_merge($this->getNodeFields(), array('file_path', 'file_name')))
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
      'file_path',
      'file_name',
    ));

    $this
      ->addFieldMapping('uid', 'uid')
      ->sourceMigration('EuUser');

    $this->addFieldMapping('og_groups', 'gid');

  }

  /**
   * Map original group id with new group id.
   */
  public function prepare($entity, $row) {
    $entity->og_groups = array($this->groupConnect[$row->gid]);
  }
}
