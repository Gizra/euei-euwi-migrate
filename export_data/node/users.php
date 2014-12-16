<?php

/**
 * @file
 * Export the users
 */

require '/vagrant/wordpress/build/euei/export_data/export_data.php';

$fields = array();

export_data('user', 1, $fields, 'user');
