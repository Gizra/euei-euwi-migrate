<?php

/**
 * @file
 * Contains \EuEventMigration
 */

class EuEventMigration extends EuMigration {

  protected $entityType = 'node';

  protected $bundle = 'event';

  public function __construct() {
    parent::__construct();

    $this->description = t('Migration of the Events');
    $this->dependencies = array('EuUser');

    // Set source object.
    $query = db_select('_gizra_node_event', 'event')
      ->fields('event', array_merge($this->getNodeFields(), array('event_start', 'event_end')))
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
      'event_start',
      'event_end',
    ));

  }
}
