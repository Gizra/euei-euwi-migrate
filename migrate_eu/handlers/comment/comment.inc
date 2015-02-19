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
    $this->dependencies = array('EuUser', 'EuProfile', 'EuMembership',
      'EuDocument', 'EuEvent', 'EuNews',
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

    // Map the user.
    $this
      ->addFieldMapping('uid', 'uid')
      ->sourceMigration('EuUser');

  }

  public function prepare($entity, $row) {
    // If user not found for the comment, try to find it by name.
    if (empty($entity->uid)) {
      $uid = db_select('users', 'u')
        ->fields('u', array('uid'))
        ->condition('name', $row->name)
        ->execute()
        ->fetchField();

      // Attach user to a comment if a user exist.
      if ($uid) {
        $entity->uid = $uid;
      }
    }
  }

}
