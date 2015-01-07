<?php

/**
 * @file
 * Contains \ExportNodeEvent.
 */

class ExportNodeEvent extends  ExportNodeBase {

  // Bundle name for new table
  protected $bundle = 'event';

  // Bundle name for searching in database.
  protected $originalBundle = 'event';

  // Additional fields for the bundle.
  protected $fields = array(
    'event_start' => '%d',
    'event_end' => '%d',
  );

}
