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
    'file_mime' => '%s',
    'file_size' => '%s',
  );

  /**
   * {@inheritdoc}
   */
  protected function getValues($entity) {

    $file_path = $file_name = $file_mime = $file_size = array();
    if (!empty ($entity->files)) {
      foreach ($entity->files as $file){

        if ($path = $this->exportFile($file)) {
          $file_path[] = $path;
          $file_name[] = $file->filename;
          $file_mime[] = $file->filemime;
          $file_size[] = $file->filesize;
        };
      }
    }

    //First value for uniaue ID
    $values = parent::getValues($entity);
    foreach($values as $key => $directive) {
      if ($key == 'file_path') {
        $values[$key] = implode ('|', $file_path);
      }
      elseif ($key == 'file_name') {
        $values[$key] = implode ('|', $file_name);
      }
      elseif ($key == 'file_mime') {
        $values[$key] = implode ('|', $file_mime);
      }
      elseif ($key == 'file_size') {
        $values[$key] = implode ('|', $file_size);
      }
    }
    return $values;
  }

  /**
   * Export a file object.
   *
   * @param object $file
   *   The object file.
   * @param string $destination
   *   Path to the destination folder.
   *
   * @return string
   *   Path to exported file or empty if unsuccsessfull.
   *
   * @throws Exception
   *   message if destination directory not exist.
   */
  protected function exportFile($file, $destination = 'export_data/files/euei/') {
    if ($this->getSiteName() == 'euwi') {
      $destination = '../euei/export_data/files/euwi';
    }

    if (!file_check_directory($destination, FILE_CREATE_DIRECTORY)) {
      throw new Exception(strstr('Directory @dest does not exist.', array('@dest' => $destination)));
    }

    $source = $this->getSiteName() == 'euwi' ? file_directory_path() . '/' . $file->filepath : $file->filepath;
    $path = 'export_data/files/' . $this->getSiteName() . '/' . $file->filename;
    if (!file_exists($source)) {
      drush_print(dt('File @source could not be found.', array('@source' => $source)));
    }

    $destination .= '/' . $file->filename;
    if (copy($source, $destination)){
      return $path;
    }

    return;
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
