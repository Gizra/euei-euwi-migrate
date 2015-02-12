<?php
/**
 * @file
 * Contains \ExportNodeDocument.
 */

class ExportNodeDocument extends ExportNodeBase {

  protected $bundle = 'document';

  protected $originalBundle = 'ipaper';

  protected $fields = array(
    'file_path' => '%s',
    'file_name' => '%s',
  );

  /**
   * {@inheritdoc}
   */
  protected function getValues($entity) {

    $file = array();
    if (!empty ($entity->files)) {
      $file = array();
      if(count($entity->files)>1){
        $file = $this->createZip($entity);
      }
      else {
        $path = $this->exportFile(reset($entity->files));
        $file = array (
          'file_name'=> end(explode('/', $path)),
          'file_path'=> $path,
        );
      }
    }

    $values = parent::getValues($entity);
    if(count($file)){
      foreach($values as $key => $directive) {
        if ($key == 'file_path') {
          $values[$key] = $file['file_path'];
        }
        elseif ($key == 'file_name') {
          $values[$key] = $file['file_name'];
        }
      }
    }

    return $values;
  }

  /**
   * {@inheritdoc}
   */
  protected function isExportable($entity) {
    if (empty($entity->og_groups)) {
      // The Document is not associated with any group we will still export
      // it to the mother group for both sites (EUEI,EUWI). Only for documents.
      return TRUE;
    }

    foreach ($entity->og_groups as $og_group) {
      if (in_array($og_group, $this->groupForExport[$this->getSiteName()])) {
        return TRUE;
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function getGroupIdFromEntity($entity) {
    if (empty($entity->og_groups)) {
      //The document is not part of the valid groups still exports
      // it to the mother group for both sites. Only for documents.
      return $this->getSiteName() == 'euwi' ? 'euwi:21098' : 'euei:euei';
    }

    foreach ($entity->og_groups as $og_group) {
      if (in_array($og_group, $this->groupForExport[$this->getSiteName()])) {
        return $this->getSiteName() . ':' . $og_group;
      }
    }
  }
}
