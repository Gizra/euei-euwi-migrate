<?php

/**
 * @file
 * Contains \EuDocumentMigration
 */

class EuBodyLinkMigration extends EuMigration {

  protected $entityType = 'node';

  protected $bundle = 'document';

  public function __construct() {
    parent::__construct();

    $this->description = t('Migration of the body links');
    $this->dependencies = array('EuNews', 'EuDocument', 'EuEvent');

    // Set source object.
    // Select all data from three tables by mysql union.
    $event_table = db_select('_gizra_node_event', 'e')->fields('e', array('unique_id', 'body'));
    $document_table = db_select('_gizra_node_document', 'd')->fields('d', array('unique_id', 'body'));
    $blog_post_table = db_select('_gizra_node_blog_post', 'b')->fields('b', array('unique_id', 'body'));
    $event_table->union($document_table, 'UNION');
    $event_table->union($blog_post_table, 'UNION');

    // Wrapper for mysql union to fix error with wrong count columns.
    $result_query = db_select($event_table, 'q')->fields('q', array('unique_id', 'body'));

    $this->source = new MigrateSourceSQL($result_query);

    // Set destination object.
    $this->destination = new MigrateDestinationTable('node');

  }

}
