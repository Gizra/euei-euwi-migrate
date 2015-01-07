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
    $exported_files = array();
    if (count($entity->files)>1)
      foreach ($entity->files as $file) {
        if ($path = $this->exportFile($file)) {
          $exported_files[] = array (
            'unique_id' => $this->getEntityUniqueId($entity),
            'file_path' => $path,
            'file_name' => $file->filename,
          );
        }
      }
    else {
      if ($file = reset($entity->files)) {
        if ($path = $this->exportFile($file)) {
          $exported_files[] = array (
            'unique_id' => $this->getEntityUniqueId($entity),
            'file_path' => $this->exportFile($file),
            'file_name' => $file->filename,
          );
        }
      }
    }

    if ($exported_files) {
      //TODO: Insert the file information to euei._gizra_files.
    }

    // Temporary solution until files not completed.
    return $values = parent::getValues($entity);
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

    $source = $file->filepath;
    if($this->getSiteName() == 'euwi') {
      $source = file_directory_path() . '/' . $source;
    }

    $path = 'export_data/files/euei/' . $file->filename;
    if (!file_exists($source)) {
      drush_print(dt('File @source could not be found.', array('@source' => $source)));
    }

    if (copy($source, $destination)){
      return $path;
    }

    return;
  }

  /**
   * Check files.
   */
  protected function checkFiles() {
    if (!$total = $this->getTotal()) {
      throw new Exception('No total count for entity type');
    }

    $count = 0;

    while($count < $total) {
      $result = $this->getResults($count);

      while ($row = db_fetch_array($result)) {
        $entity = $this->getEntityFromRow($row);
        if ($entity->files) {
          if (count($entity->files) > 1) {
            // @todo: Deal with multiple files.
          }
        }

        ++$count;

        $params = array(
          '@entity_type' => $this->getEntityType(),
          '@count' => $count,
          '@total' => $total,
          '@id' => $this->getEntityId($entity),
        );

        drush_print(dt('(@count / @total) Processed @entity_type ID @id.', $params));
      }
    }
  }

}
