<?php

/**
 * @file
 * Contains \EuCommentMigration
 */

class EuCommentMigration extends EuMigration {

  protected $entityType = 'comment';

  protected $bundle = 'comment';

  public function __construct() {
    parent::__construct();

    $this->description = t('Migration of the Comments');
    $this->dependencies = array(
      'EuDocument',
      'EuEvent',
      'EuNews',
      'EuUser',
    );

    // Set source object.
    $query = db_select('_gizra_comment', 'comment')
      ->fields('comment', array('unique_id',
        'pid',
        'nid',
        'uid',
        'subject',
        'comment',
        'timestamp',
        'status',
        'name',
        'mail'))
      ->orderBy('unique_id', 'ASC');

    $this->source = new MigrateSourceSQL($query);

    // Set destination object.
    $this->destination = new MigrateDestinationComment($this->bundle);

    $this->addSimpleMappings(array(
      'subject',
      'comment',
      'timestamp',
      'status',
      'name',
      'mail',
    ));

    // Map the node.
    $this
      ->addFieldMapping('nid', 'nid')
      ->sourceMigration(array('EuNews', 'EuEvent', 'EuDocument'));

    // Map the parent comment.
    $this
      ->addFieldMapping('pid', 'pid')
      ->sourceMigration('EuComment');

  }
}
