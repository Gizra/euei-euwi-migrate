<?php
/**
 * @file
 * Contains \ExportNodeDocumentImage.
 */

class ExportNodeDocumentImage extends ExportNodeDocument {

  protected $originalBundle = 'news';

  protected function getTotal() {
    return db_result(db_query("SELECT COUNT(i.nid) FROM {content_field_image} i
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
      AND og.group_nid IN ('%s')
      ORDER BY n.nid LIMIT %d OFFSET %d",
      implode(',' ,$this->groupForExport[$this->getSiteName()]),
      $this->getRange(),
      $offset);
  }

  protected function getEntityFromRow($row) {
    return node_load($row['nid']);
  }

  protected function getValues($entity) {

    $file_path = $file_name = array();

      if ($path = $this->exportFile($entity->field_image[0])) {
        $file_path[] = $path;
        $file_name[] = $entity->field_image[0]->filename;
      };

    //First value for uniaue ID
    $values = parent::getValues($entity);
    foreach($values as $key => $directive) {

      if ($key == 'title') {
        $values[$key] = $entity->$entity->files[0]->title;
      }
      elseif ($key == 'body') {
        //There is no body for image.
        $values[$key] = "";
      }
      elseif ($key == 'file_path') {
        $values[$key] = implode ('|', $file_path);
      }
      elseif ($key == 'file_name') {
        $values[$key] = implode ('|', $file_name);
      }
    }
    return $values;
  }

  protected function isExportable($entity) {
    //The Entity not need checks cause SQL query get only exportable records
    return TRUE;
  }
}
