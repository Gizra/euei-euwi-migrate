<?php

/**
 * @file
 * Contains \ExportNodeEvent.
 */

class ExportNodeEvent extends ExportNodeBase {

  // Bundle name for new table
  protected $bundle = 'event';

  // Bundle name for searching in database.
  protected $originalBundle = 'event';

  // Additional fields for the bundle.
  protected $fields = array(
    'event_start' => '%d',
    'event_end' => '%d',
  );

  /**
   * {@inheritdoc}
   */
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
