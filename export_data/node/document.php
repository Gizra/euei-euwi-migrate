<?php

/**
 * @file
 * Export the document content type.
 */

$fields = array(
  'nid' => '%s',
  'title' => '%s',
);

export_data('node', 'ipaper', $fields, 'document');

/**
 * Prepare data before inserting to the database.
 *
 * @return array
 *   The values ready to be insered.
 */
function export_prepare_data_for_insert__node__document($entity_type, $entity, $fields) {
  $values = array();
  foreach($fields as $key => $directive) {
    if($key == 'file_name' && $node_type == 'ipaper') {
      $file = reset($node->files);
      $values[] = !empty($file->filename)? $file->filename :'';
    }

    elseif($key == 'path' && $node_type == 'ipaper') {
      $file = reset($node->files);
      print_r($file->path);
      if (!empty($file->filepath)) {
        $values[] = $file->filepath;
      }
    }
    else {
      $values[] = $node->$key;
    }
  }

  return $values;
}
