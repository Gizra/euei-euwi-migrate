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

    $file = reset($entity->files);
    $exported_file = $file ? $this->exportFile($file) : '';

    //First value for uniaue ID
    $values = $this->getEntityUniqueId($entity);
    foreach($this->getFields() as $key => $directive) {
      if($key == 'file_path') {
        $values[$key] = $exported_file;
      }
      elseif ($key == 'file_name') {
        $values[$key]= $exported_file ? $file->filename : '';
      }
      else {
        $values[$key] = $entity->$key;
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
    if (!file_check_directory($destination, FILE_CREATE_DIRECTORY)) {
      throw new Exception(strstr('Directory @dest does not exist.', array('@dest' => $destination)));
    }

    $source = $file->filepath;
    $destination .= '/' . $file->filename;
    if (!file_exists($source)) {
      drush_print(dt('File @source could not be found.', array('@source' => $source)));
    }

    if (copy($source, $destination)){
      return $destination;
    }
  }
}
