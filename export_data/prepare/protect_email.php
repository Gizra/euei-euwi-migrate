<?php

/**
 * @file
 * Protect email.
 *
 * For unprotect emails use: drush scr protect.php --unprotect=1
 */

$unprotect = drush_get_option('unprotect', 0);
$total = db_result(db_query("SELECT COUNT(uid) FROM {users} u WHERE uid != 0 ORDER BY u.uid ", $id));
$range = 50;
$count = 0;

while ($count < $total){
  $result = db_query("SELECT uid FROM {users} u WHERE uid != 0 ORDER BY u.uid LIMIT %d OFFSET %d ", $range, $count);
  while ($user = user_load(db_fetch_array($result))) {

    $unprotect == 1 ? unprotect($user) : protect($user);

    $count++;
    $params = array(
      '@count' => $count,
      '@total' => $total,
      '@id' => $user->uid,
    );

    drush_print(dt('(@count / @total) Processed user ID @id.', $params));
  }
}

function protect($user) {
  $mail = explode('.', $user->mail);
  if (end($mail) == 'test') {
    return;
  }

  $user->mail .= '.test';
  db_query("UPDATE users SET mail = '%s' WHERE uid = '%d'", $user->mail, $user->uid);
}

function unprotect($user) {
  $mail = explode('.', $user->mail);
  if (end($mail) != 'test') {
    return;
  }

  array_pop($mail);
  $user->mail = implode('.', $mail);
  db_query("UPDATE users SET mail = '%s' WHERE uid = '%d'", $user->mail, $user->uid);
}
