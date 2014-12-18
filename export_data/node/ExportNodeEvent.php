<?php
/**
 * @file
 * Contains \ExportNodeEvent.
 */

class ExportNodeEvent extends  ExportNodeBase {

  protected $bundle = 'event';

  protected $originalBundle = 'event';

  protected $fields = array(
    'event_start' => '%d',
    'event_end' => '%d',
  );

  
}
