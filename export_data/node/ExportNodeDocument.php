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

    $file_path = $file_name = array();
    if (!empty ($entity->files)) {
      foreach ($entity->files as $file){

        if ($path = $this->exportFile($file)) {
          $file_path[] = $path;
          $file_name[] = $file->filename;
        };
      }
    }

    $values = parent::getValues($entity);
    foreach($values as $key => $directive) {
      if ($key == 'file_path') {
        $values[$key] = implode ('|', $file_path);
      }
      elseif ($key == 'file_name') {
        $values[$key] = implode ('|', $file_name);
      }
    }
    return $values;
  }



  /**
   * {@inheritdoc}
   */
  protected function isExportable($entity) {
    if (empty($entity->og_groups)) {
      // Node is not associated with any group.
      return;
    }
    if ($this->getSiteName() == 'euwi') {
      // The document is not part of the valid groups but we will still export
      // it to the mother group.
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
