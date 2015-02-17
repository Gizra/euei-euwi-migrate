<?php

/**
 * @file
 * Contains \EuCounterMigration
 */

class EuCounterMigration extends EuMigration {

  protected $entityType = 'counter';

  public function __construct() {
    parent::__construct();

    $this->description = t('Migration of a Counters for News, Documents and Events');
    $this->dependencies = array('EuNews', 'EuDocument', 'EuEvent');

    // Set source object.
    // Select all data from three tables by mysql union.
    $event_table = db_select('_gizra_node_event', 'e')->fields('e', array('unique_id', 'counter'));
    $document_table = db_select('_gizra_node_document', 'd')->fields('d', array('unique_id', 'counter'));
    $blog_post_table = db_select('_gizra_node_blog_post', 'b')->fields('b', array('unique_id', 'counter'));
    $event_table->union($document_table, 'UNION');
    $event_table->union($blog_post_table, 'UNION');

    // Wrapper for mysql union to fix error with wrong count columns.
    $result_query = db_select($event_table, 'q')->fields('q', array('unique_id', 'counter'));

    $this->source = new MigrateSourceSQL($result_query);

    // Set destination object.
    $this->destination = new MigrateDestinationNode('node_counter');

    // Map Node user with uid.
    $this
      ->addFieldMapping('nid', 'unique_id')
      ->sourceMigration(array('EuNews', 'EuDocument', 'EuEvent'));

    $this
      ->addFieldMapping('totalcount', 'counter');

  }

  public function prepare($entity, $row) {
    // Make sure delete already exists counter.
    $cnt_deleted = db_delete('node_counter')
      ->condition('nid', $entity->nid)
      ->execute();

    $entity->timestamp = time();
  }

}
