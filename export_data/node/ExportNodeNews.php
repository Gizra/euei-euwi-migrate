<?php

/**
 * @file
 * Contains \ExportNodeNews.
 */

class ExportNodeNews extends  ExportNodeBase {

  protected $bundle = 'blog_post';

  protected $originalBundle = 'news';

  protected function getValues($entity) {
    $values = parent::getValues($entity);
    foreach ($values as $key => $directive) {
      if ($key == 'ref_documents') {
        $values[$key] = $this->getReferenceDocument($entity);
      }
    }
    return $values;
  }
}
