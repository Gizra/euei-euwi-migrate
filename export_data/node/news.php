<?php

/**
 * @file
 * Export the news content type.
 */

require '/vagrant/wordpress/build/euei/export_data/export_data.php';

$fields = array();

export_data('node', 'news', $fields, 'blog_post');
