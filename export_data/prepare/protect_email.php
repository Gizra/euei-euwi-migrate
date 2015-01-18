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
  while ($account = user_load(db_fetch_array($result))) {

    $unprotect == 1 ? unprotect($account) : protect($account);

    $count++;
    $params = array(
      '@count' => $count,
      '@total' => $total,
      '@id' => $account->uid,
    );

    drush_print(dt('(@count / @total) Processed user ID @id.', $params));
  }
}
/**
 * Add to all users email ".test".
 *
 * @param $account
 */
function protect($account) {
  $mail = explode('.', $account->mail);
  if (end($mail) == 'test') {
    return;
  }

  $account->mail .= '.test';
  db_query("UPDATE users SET mail = '%s' WHERE uid = '%d'", $account->mail, $account->uid);
}

/**
 * Remove if exist ".test" from all users mail.
 *
 * @param $account
 */
function unprotect($account) {
  $mail = explode('.', $account->mail);
  if (end($mail) != 'test') {
    return;
  }

  array_pop($mail);
  $account->mail = implode('.', $mail);
  db_query("UPDATE users SET mail = '%s' WHERE uid = '%d'", $account->mail, $account->uid);
}
