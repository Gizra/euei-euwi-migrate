<?php
  $total = db_result(db_query("SELECT COUNT(uid) FROM {users} u ORDER BY u.uid"));
  $range = 50;
  $count = 0;
  drush_print ($total);

  while($count < $total){
    $result = db_query("SELECT uid FROM {users} u ORDER BY u.uid LIMIT %d OFFSET %d", $range, $count);

    while ($user = user_load(db_fetch_array($result))) {
      $insert = db_query("INSERT INTO _gizra_users(uid, `name`, password, mail)
        VALUES('%d','%s','%s', '%s')", $user->uid, $user->name, $user->pass, $user->mail);
      $count++;
      $params = array(
        '@count' => $count,
        '@total' => $total,
        '@id' => $user->uid,
      );
      drush_print(dt('(@count / @total) Processed user ID @id.', $params));
    }
  }
?>
