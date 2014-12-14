<?php

/**
 * @file
 * Export the news content type.
 */

require '../export_data.php';

$fields = array(
  'nid' => '%d',
  'title' => '%s',
);

export_data('node', 'news', $fields, 'blog_post');

/**
 * Prepare data before inserting to the database.
 *
 * @return array
 *   The values ready to be insered.
 */
function export_prepare_data_for_insert__node__blog_post($entity_type, $entity, $fields) {

}
