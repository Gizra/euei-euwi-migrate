<?php
/**
 * @file
 * Contains \ExportNodeBase.
 */

class ExportNodeBase extends  ExportBase {

  protected $entityType = 'node';

  protected function getBaseFields() {
    return array(
      'nid' => '%d',
      'title' => '%s',
      'body' => '%s',
      'uid' => '%d',
      'path' => '%s',
      'published' => '%d',
      'sticky' => '%d',
    );
  }

}