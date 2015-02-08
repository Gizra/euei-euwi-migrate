<?php

/**
 * @file
 * Contains \EuDocumentMigration
 */

class EuDocumentMigration extends EuMigrationNode {

  protected $entityType = 'node';

  protected $bundle = 'document';

  public function __construct() {
    parent::__construct();

    $this->description = t('Migration of the Documents');

    // Set source object.
    $query = db_select('_gizra_node_document', 'document')
      ->fields('document', array_merge($this->getNodeFields(), array('file_path', 'file_name')))
      ->orderBy('nid', 'ASC');

    $this->source = new MigrateSourceSQL($query);

    // Set destination object.
    $this->destination = new MigrateDestinationNode($this->bundle);

    // Map file.
    $this
      ->addFieldMapping('field_file_file', 'file_path')
      ->separator('|');

    // Explicitly set the file function.
    $this
      ->addFieldMapping('field_file_file:file_function')
      ->defaultValue(' ');

    // Set path to folder that contain all migrated files.
    $this
      ->addFieldMapping('field_file_file:source_path')
      ->defaultValue('sites/default/files');
  }

}
