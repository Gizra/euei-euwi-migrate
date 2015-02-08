<?php
/**
 * @file
 * Contains \ExportNodeDocumentImage.
 */

class ExportNodeDocumentImage extends ExportNodeDocument {

  protected $originalBundle = 'news';

  /**
   * {@inheritdoc}
   */
  protected function getTotal() {
    return db_result(db_query("SELECT COUNT(DISTINCT i.nid)
      FROM {content_field_image} i
      LEFT JOIN {og_ancestry} og ON i.nid = og.nid
      LEFT JOIN {node} AS n ON i.nid = n.nid
      WHERE n.type = 'news'
      AND og.group_nid IN (%s)",
      implode(', ' ,$this->groupForExport[$this->getSiteName()])));
  }

  /**
   * {@inheritdoc}
   */
  protected function getResults($offset = 0) {
    return db_query("SELECT DISTINCT i.nid FROM {content_field_image} i
      LEFT JOIN {og_ancestry} og ON i.nid = og.nid
      LEFT JOIN {node} n ON i.nid = n.nid
      WHERE n.type = 'news'
      AND og.group_nid IN (%s)
      ORDER BY i.nid LIMIT %d OFFSET %d",
      implode(',' ,$this->groupForExport[$this->getSiteName()]),
      $this->getRange(),
      $offset);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEntityFromRow($row) {
    return node_load($row['nid']);
  }

  protected function getValues($entity) {

      if ($path = $this->exportFile($entity->field_image[0])) {
        $file_path = $path;
        $file_name = $entity->field_image[0]['filename'];
      };

    //First value for uniaue ID
    $values = parent::getValues($entity);
    foreach($values as $key => $directive) {

      if ($key == 'title') {
        $values[$key] = $entity->field_image[0]['title'];
      }
      //There are fields not exists for image.
      elseif (in_array ($key, array('body', 'path', 'tags', 'taxonomy', 'counter'))) {
        $values[$key] = '';
      }
      elseif ($key == 'file_path') {
        $values[$key] = $file_path;
      }
      elseif ($key == 'file_name') {
        $values[$key] = $file_name;
      }
    }
    return $values;
  }

  protected function isExportable($entity) {
    //The Entity not needs check cause  of SQL query get only exportable records
    return TRUE;
  }

  protected function getEntityUniqueId($entity) {
    return array('unique_id' => $this->getSiteName() . ':images:' . $this->getEntityId($entity));
  }
}
