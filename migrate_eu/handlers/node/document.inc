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
    ));

    // Map file.
    $this
      ->addFieldMapping('field_file_file', 'file_path');

    $this
      ->addFieldMapping('field_file_file:file_replace')
      ->defaultValue(FILE_EXISTS_REPLACE);

    // Explicitly set the file function.
    $this
      ->addFieldMapping('field_file_file:file_function')
      ->defaultValue('file_copy');

    $files_path = variable_get('c4d_migrate_files_path', NULL);

    $this
      ->addFieldMapping('field_file_file:source_path')
      ->defaultValue($files_path);

  }

}