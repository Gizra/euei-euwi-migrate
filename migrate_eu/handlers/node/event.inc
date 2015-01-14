<?php

/**
 * @file
 * Contains \EuEventMigration
 */

class EuEventMigration extends EuMigrationNode {

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
    ));

    // Set "Group event" as default value for all migrated groups.
    $this->addFieldMapping('field_event_type')
      ->defaultValue(array('group_event'));

  }

  /**
   * Prepare function fix time format for new imported events.
   *
   * @param $entity
   * @param $row
   */
  public function prepare($entity, $row) {
    parent::prepare($entity, $row);

    // Convert timestamps to allowed date format.
    $entity->field_date = array();
    $entity->field_date[0] = array(
      'value' => date('c', $row->event_start),
      'value2' => date('c', $row->event_end),
    );
  }

}
