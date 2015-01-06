<?php

/**
 * @file
 * Contains \ExportNodeNews.
 */

class ExportNodeNews extends  ExportNodeBase {

  protected $bundle = 'blog_post';

  protected $originalBundle = 'news';

  protected $fields = array(
    'gid' => '%s'
  );

  /**
   * {@inheritdoc}
   */
  protected function getValues($entity) {
    // First value for unique ID.
    $values = $this->getEntityUniqueId($entity);
    foreach($this->getFields() as $key => $directive) {
      if ($key == 'gid') {
        $values[$key] = !empty($entity->og_groups[0]) ?
          $this->getSiteName() . ':' . $entity->og_groups[0] : 0;
      }
      elseif ($key == 'uid') {
        $values[$key] = $this->getSiteName() . ':' . $entity->$key;
      }
      else {
        $values[$key] = $entity->$key;
      }

    }
    return $values;
  }
}
