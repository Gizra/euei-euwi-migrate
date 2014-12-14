<?php

/**
 * @file
 * Export the document content type.
 */

$fields = array(
  'nid' => '%s',
  'title' => '%s',
);
export_nodes('node', 'ipaper', $fields, 'document');

/**
 * Prepare data before inserting to the database.
 */
function export_document_prepare_data_for_insert() {

}
