<?php
/**
 * @file
 * Contains \ExportNodeDocumentImage.
 */

class ExportNodeDocumentImage extends ExportNodeDocument {

  protected function getTotal() {
    return db_result(db_query("SELECT i.nid FROM {content_field_image} i
      LEFT JOIN {og_ancestry} og ON i.nid = og.nid
      LEFT JOIN {node} AS n ON i.nid = n.nid
      WHERE n.type LIKE 'news'
      AND og.group_nid IN ('%s')", implode(',' ,$this->groupForExport[$this->getSiteName()])));
  }

  protected function getResults($offset = 0) {
    return db_query("SELECT i.nid FROM {content_field_image} i
      LEFT JOIN {og_ancestry} og ON i.nid = og.nid
      LEFT JOIN {node} AS n ON i.nid = n.nid
      WHERE n.type LIKE 'news'
      ORDER BY n.nid LIMIT %d OFFSET %d",
      implode(',' ,$this->groupForExport[$this->getSiteName()]),
      $this->getRange(),
      $offset);
  }

}
