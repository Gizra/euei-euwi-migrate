<?php
//Get amount rows
$total =  db_result(db_query("SELECT COUNT(nid) FROM {node} n WHERE n.type = '%s' ORDER BY n.nid", 'news'));
$range = 50;
$count = 0;

while($count < $total){
  $result = db_query("SELECT nid, type FROM {node} n WHERE n.type = '%s' ORDER BY n.nid LIMIT %d OFFSET %d", 'news', $range, $count);

  while ($row = db_fetch_array($result)) {
    $node = node_load($row['nid']);
    $insert = db_query("INSERT INTO _gizra_blog_post(nid, title, body, uid) VALUES('%d', '%s', '%s', '%d')",
      $node->nid, $node->title, $node->body, $node->uid);
    ++$count;
  }

  $params = array(
    '@count' => $count,
    '@total' => $total,
    '@id' => $node->nid,
  );
  drush_print(dt('(@count / @total) Processed node ID @id.', $params));
}
