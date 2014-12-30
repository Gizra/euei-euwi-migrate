<?php
/**
 * @file
 * Contains EuNewsMigration class for migrate EuNews.
 */
class EuEventMigration extends EuMigration {

  protected $entityType = 'node';

  public function __construct() {
    parent::__construct();

    $this->description = t('Migrate events');
    $this->dependencies = array('EuUser');

    // Set source object.
    $query = db_select('_gizra_node_event', 'event')
      ->fields('event', array('unique_id', 'nid', 'title', 'body', 'uid', 'path', 'promote', 'sticky', 'event_start', 'event_end'))
      ->orderBy('nid', 'ASC');

    $this->source = new MigrateSourceSQL($query);

    // Set destination object.
    $this->destination = new MigrateDestinationNode('event');

    $this->addSimpleMappings(array(
      'title',
      'body',
      'path',
      'promote',
      'sticky',
      'event_start',
      'event_end',
    ));

    $this
      ->addFieldMapping('uid', 'uid')
      ->sourceMigration('EuUser');

  }
}
