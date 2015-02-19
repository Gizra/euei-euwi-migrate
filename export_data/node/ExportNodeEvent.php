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

    // Put information for behat tests to file.
    $list = array ('euei:10786', 'euwi:22154', 'euwi:23099', 'euwi:25094', 'euwi:22762',
      'euwi:22188', );
    if (in_array($values['unique_id'], $list )) {
      print_r("I AM IN!!!!!!!!!!!");
      $filename = '../euei/export_data/' . $this->getBundle() . '_behat.txt';
      //$file = fopen($filename, 'w');
      //fclose($file);

      $string = array();
      $string['url'] = $this->getPathFromNode($entity);
      $string['title'] = substr($entity->title, 0, 30);
      $string['body'] = substr(strip_tags($entity->body), 0, 50);
      $terms = array();
      if (!empty ($entity->taxonomy)) {
        foreach ($entity->taxonomy as $term) {
          $terms[] = $term->name;
        }
      }
      if (!empty ($entity->tags)){
        foreach ($entity->tags as $term) {
          $terms[] = $term->name;
        }
      }
      $string['terms'] = count($terms) ? implode(';', $terms) : '';
      // append.
      $user = user_load($entity->uid);
      $string['author'] = $user->name;
      $string['counter'] = $this->getCounterFromNode($entity);

      $string = '| ' . implode(' | ', $string) . " |\n\n";
      file_put_contents( $filename, $string, FILE_APPEND);
    }

    return $values;
  }
}
