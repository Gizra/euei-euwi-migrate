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
  'file_path' => '%s',
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
function export_prepare_data_for_insert__node__document($node, $fields) {

  $values = array();
  $file = reset($node->files);
  $exported_file = export_file($file);

  foreach($fields as $key => $directive) {
    if($key == 'file_path') {
      $values[$key] = $exported_file ? $exported_file : '';
    }
    elseif ($key == 'file_name') {
      $values[$key]= $exported_file ? $file->filename : '';
    }
    else {
      $values[$key] = $node->$key;
    }
  }

  return $values;
}

function export_file($file){
  //Set export folder.
  $dest = 'export_data/files/euei/';
  if(!file_check_directory($dest, FILE_CREATE_DIRECTORY)){
    return;
  }
  $source = file_directory_path() . "/" . $file->filepath;
  $dest .= '/'.$file->filename;
  if (copy($source, $dest)){
    return $dest;
  }
  return;
}
