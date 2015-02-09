<?php
/**
 * @file
 * Contains \ExportNodeDocumentImage.
 */

class ExportNodeDocumentImage extends ExportNodeBase {

  protected $originalBundle = 'news';
  protected $bundle = "document";

  protected $fields = array(
    'file_path' => '%s',
    'file_name' => '%s',
  );

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
        $values[$key] = $this->exportFile($entity->field_image[0]);
      }
      elseif ($key == 'file_name') {
        $values[$key] = $entity->field_image[0]['filename'];
      }
    }
    return $values;
  }

  protected function isExportable($entity) {
    //The Entity not needs check cause  of SQL query get only exportable records
    return TRUE;
  }

  protected function getEntityUniqueId($entity) {
    return array('unique_id' => $this->getSiteName() . ':image:' . $this->getEntityId($entity));
  }


  protected function insertQuery($entity) {
    if(parent::insertQuery($entity)) {
      //Add ID to ref_document
      $uniqueID = $this->getSiteName() . ":" . $entity->nid;
      $this->addReferenceDocument($this->getEntityUniqueId(), $uniqueID, '_gizra_node_blog_post')
    }
  }

    protected function addReferenceDocument($ref_document, $uniqueID, $table) {

      $query = "SELECT 'ref_document' FROM  '%s' WHERE 'unique_id' = '%s'";
      if(!$ref_document = db_result(db_query($query, $table, $uniqueID))){
        $ref_document = $this->getEntityUniqueId();
      }
      else {
        $ref_document = explpode('|', $ref_document);
        array_push($ref_document, $this->getEntityUniqueId());
        $ref_document = implode('|', $ref_document);
      }

      $query = "UPDATE '_gizra_node_blog_post' SET 'ref_document'='%s' WHERE 'unique_id' = '%s' ";
      if (!db_query($query, $ref_document, $uniqueID)) {
        throw new Exception(strstr('Request  @query failed.', array('@query' => $query)));
      }

  }

}
