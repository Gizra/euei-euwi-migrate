<?php

/**
 * @file
 * Protect email.
 */

$id = drush_get_option('id', 1);
$total = db_result(db_query("SELECT COUNT(uid) FROM {users} u ORDER BY u.uid", $id));
$range = 50;
$count = 0;

while ($count < $total){
  $result = db_query("SELECT uid FROM {users} u  ORDER BY u.uid LIMIT %d OFFSET %d", $range, $count);
  while ($user = user_load(db_fetch_array($result))) {

    $mail = $user->mail;
    $mail = explode('.', $mail);
    if (end($mail) != 'test') {
      $user->mail .= '.test';
      $insert = db_query("UPDATE users SET mail = '%s' WHERE uid = '%d'", $user->mail, $user->uid);
    }

    $count++;
    $params = array(
      '@count' => $count,
      '@total' => $total,
      '@id' => $user->uid,
    );

    drush_print(dt('(@count / @total) Processed user ID @id.', $params));
  }
}
