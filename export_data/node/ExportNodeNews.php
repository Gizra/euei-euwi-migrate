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
        if ($entity->og_groups[0]) {
          $values[$key] = $this->getSiteName() . ':' . $entity->og_groups[0];
        }
        else {
          $values[$key] = 0;
        }
      }
      else {
        $values[$key] = $entity->$key;
      }

    }
    return $values;
  }
}
