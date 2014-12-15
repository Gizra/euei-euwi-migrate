<?php

/**
 * @file
 * Export the document content type.
 */

require '/vagrant/wordpress/build/euei/export_data/export_data.php';

$fields = array(
  'nid' => '%d',
  'title' => '%s',
  'body' => '%s',
  'uid' => '%d',
  'path' => '%s',
  'file_name' => '%s',
);

export_data('node', 'ipaper', $fields, 'document');

/**
 *  Prepare data before inserting to the database.
 *
 * @param string $entity_type
 *   The entity type to export.
 * @param obj $entity
 *   Node of type document
 * @param array $fields
 *   Array fields for export, keyed by the column name and the  directive type
 *   (e.g. '%s', '%d') as value.
 * @return array
 *   The values ready to be inserted.
 */
function export_prepare_data_for_insert__node__document($entity, $fields) {
  $node = $entity;

  $values = array();
  foreach($fields as $key => $directive) {
    if ($key == 'file_name') {
      $file = reset($node->files);
      if ()
      $values[] = !empty($file->filename) ? $file->filename : '';
      file_copy($file,)
    }
    elseif($key == 'path') {
      $file = reset($node->files);
      $values[] = !empty($file->filepath) ? $file->filepath : '';
    }
    else {
      $values[] = $node->$key;
    }
  }

  return $values;
}

function export_file($original_file){
  //Set export folder.
  $dirname = '/vagrant/wordpress/build/euei/export_data/files/euei';
  file_check_directory($dirname, FILE_CREATE_DIRECTORY);
  file_copy($original_file, $dirname);

}
