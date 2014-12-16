<?php

/**
 * @file
 * Export the users
 */

require '/vagrant/wordpress/build/euei/export_data/export_data.php';

$fields = array();

export_data('user');

/**
 *  Prepare data before inserting to the database.
 *
 * @param object $user
 *   The object user.
 * @param array $fields
 *   Array fields for export, keyed by the column name and the  directive type
 *   (e.g. '%s', '%d') as value.
 *
 * @return array $values
 *   The values ready to be inserted.
 */
function export_prepare_data_for_insert__user__user($user, $fields) {
  $values = array();

  foreach($fields as $key => $directive) {
    switch ($key){
      case 'password':
        $values[$key] = $user->pass;
        break;
      default:
        $values[$key] = $user->$key;
        break;
    }
  }
  return $values;
}
