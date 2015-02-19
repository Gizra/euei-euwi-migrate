<?php

/**
 * @file
 * Contains \EuBodyLinkMigration
 */

class EuBodyLinkMigration extends EuMigration {

  protected $entityType = 'node';

  protected $bundle = 'document';

  public function __construct() {
    parent::__construct();

    $this->description = t('Migration of the body links');
    $this->dependencies = array('EuUser', 'EuProfile', 'EuMembership',
      'EuDocument', 'EuEvent', 'EuNews', 'EuComment',
      'EuCounter',
    );

    // Set map object.
    $this->map = new MigrateSQLMap($this->machineName,
      $this->uniqueIdSetting,
      MigrateDestinationNode::getKeySchema()
    );

    // Set source object
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
    $this->destination = new MigrateDestinationNode($this->bundle);

    // Set this migration only for update.
    $this->systemOfRecord = Migration::DESTINATION;

    $this
      ->addFieldMapping('nid', 'unique_id')
      ->sourceMigration(array('EuEvent', 'EuDocument', 'EuNews'));

  }

  public function prepare($node, stdClass $row) {
    // Safe time and memory. Process only if links exists in a body.
    if (!strpos($row->body, '<a'))
      return FALSE;

    $dom = new DOMDocument();

    // Fix for loadHTML charset to safe ascii symbols.
    $meta = '<html><head><meta http-equiv="content-type" content="text/html; charset=utf-8">';
    $row->body = $meta . $row->body;

    // Turns off the automatic adding html/body tags and doctype.
    @$dom->loadHTML($row->body, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOENT);

    // Remove silly white space
    $dom->preserveWhiteSpace = false;

    $links = $dom->getElementsByTagName('a');

    foreach ($links as $link) {
      $href = $link->getAttribute('href');
      if (strpos($href, 'http') === FALSE
        || strpos($href, 'euei.net') || strpos($href, 'euwi.net')
      ) {

        if (strpos($href, '@')) {
          // Filter for emails.
          continue;
        }

        $href_part = explode('/', $href);
        $needle_part = '%' . array_pop($href_part) . '%';

        $source_node = db_select('url_alias', 'a')
          ->fields('a', array('src', 'dst'))
          ->condition('dst', $needle_part , 'LIKE')
          ->execute()
          ->fetchAssoc();

        if ($source_node['dst']) {
          $link->setAttribute('href', '/' . $source_node['dst']);
        }

      }
    }

    $node->body = $dom->saveHTML();

    // We don't need meta tag anymore.
    $node->body = str_replace($meta, '', $node->body);

  }

}
