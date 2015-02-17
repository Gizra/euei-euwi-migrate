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
    $this->dependencies = array('EuEvent');

    // Set map object.
    $this->map = new MigrateSQLMap($this->machineName,
      $this->uniqueIdSetting,
      MigrateDestinationNode::getKeySchema()
    );

    // Set source object.
    // Select all data from three tables by mysql union.
//    $event_table = db_select('_gizra_node_event', 'e')->fields('e', array('unique_id', 'body'));
//    $document_table = db_select('_gizra_node_document', 'd')->fields('d', array('unique_id', 'body'));
//    $blog_post_table = db_select('_gizra_node_blog_post', 'b')->fields('b', array('unique_id', 'body'));
//    $event_table->union($document_table, 'UNION');
//    $event_table->union($blog_post_table, 'UNION');

    // Wrapper for mysql union to fix error with wrong count columns.
//    $result_query = db_select($event_table, 'q')->fields('q', array('unique_id', 'body'));
    $result_query = db_select('_gizra_node_event', 'e')->fields('e', array('unique_id', 'body'));

    $this->source = new MigrateSourceSQL($result_query);

    // Set destination object.
    $this->destination = new MigrateDestinationNode($this->bundle);

    // Set this migration only for update.
    $this->systemOfRecord = Migration::DESTINATION;

    $this
      ->addFieldMapping('nid', 'unique_id')
      ->sourceMigration(array('EuEvent'));

    // Find all patches
//    $this->url_aliases = db_select('url_alias', 'a')->fields('a', array('src', 'dst'));

//    $this->patches = ;

  }

  public function prepare($node, stdClass $row) {

    dsm($row);
    dsm($node->nid);

    // Safe time and memory. Search for links in body.
    if (!strpos($row->body, '<a'))
      return FALSE;

    $dom = new DOMDocument();
    @$dom->loadHTML($row->body);

    /*** remove silly white space ***/
    $dom->preserveWhiteSpace = false;

    $links = $dom->getElementsByTagName('a');

    foreach ($links as $link) {
      $href = $link->getAttribute('href');
      dsm($href);
      if (!strpos($href, 'http')) {
        $link->setAttribute('href', 'changeURL!');
      }
      else if (strpos($href, 'euei.net')) {
        $link->setAttribute('href', 'changeURL!');
      }
      else if (strpos($href, 'euwi.net')) {
        $link->setAttribute('href', 'changeURL!');
      }
    }

    $node->body = $dom->saveHTML();

//    var_dump($links);


//    $links = $dom->getElementsByTagName('a');
//    if (count($links) < 1)
//      return FALSE;

//    foreach ($links as $link) {
//      var_dump($link);
//    }

//    $row->body; // original
//    $node->body = 'HELLO';
//    $row->body = 'HELLO';
//    dsm($node);
//    dsm($row);
  }

//  public function complete($entity, stdClass $row) {
//    $row->body = 'TEST';
//    dsm($entity);
//    dsm($row);
//  }

}
