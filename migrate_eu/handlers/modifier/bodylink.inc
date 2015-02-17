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

    // Wrapper for mysql union to fix error with wrong count columns.
    $result_query = db_select('_gizra_node_event', 'e')->fields('e', array('unique_id', 'body'));

    $this->source = new MigrateSourceSQL($result_query);

    // Set destination object.
    $this->destination = new MigrateDestinationNode($this->bundle);

    // Set this migration only for update.
    $this->systemOfRecord = Migration::DESTINATION;

    $this
      ->addFieldMapping('nid', 'unique_id')
      ->sourceMigration(array('EuEvent'));

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
      if (strpos($href, 'http') === FALSE
        || strpos($href, 'euei.net') || strpos($href, 'euwi.net')
      ) {

        if (strpos($href, '@')) continue; // Filter for emails.

        dsm($href);
        $href_part = explode('/', $href);
        $needle_part = '%' . array_pop($href_part) . '%';

        $source_node = db_select('url_alias', 'a')
          ->fields('a', array('src', 'dst'))
          ->condition('dst', $needle_part , 'LIKE')
          ->execute()
          ->fetchAssoc();

        dsm($source_node);
        if ($source_node['src']) {
          $link->setAttribute('href', '/' . $source_node['src']);
        }

      }
    }

    $node->body = $dom->saveHTML();
//    dsm($node->body);

  }


}
