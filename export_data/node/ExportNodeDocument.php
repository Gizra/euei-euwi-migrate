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
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  protected function getGroupIdFromEntity($entity) {
    foreach ($entity->og_groups as $og_group) {
      if (in_array($og_group, $this->groupForExport[$this->getSiteName()])) {
        return $this->getSiteName() . ':' . $og_group;
      }
    }
    if ($this->getSiteName() == 'euwi') {
      // The document is not part of the valid groups but we will still export
      // it to the mother group (EUWI Community Space).
      return 'euwi:21098';
    }
  }
}
