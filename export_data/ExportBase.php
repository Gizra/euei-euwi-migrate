<?php

require '/vagrant/wordpress/build/euei/export_data/ExportInterface.php';

class ExportBase implements ExportInterface {

  protected $entityType = NULL;

  protected $range = 50;

  /**
   * Export data.
   */
  public function export() {
    // Remove any existing data.
    $this->truncateTable();

    // Get total count
    if (!$total = $this->getTotal()) {
      throw new Exception('No total count for entity type');
    }

    $count = 0;

    while($count < $total) {
      $result = $this->getResults($count);

      while ($row = db_fetch_array($result)) {

        $entity = $this->getEntityFromRow($row);

        $this->insertQuery($entity);

        ++$count;
        $params = array(
          '@entity_type' => $this->getEntityType(),
          '@count' => $count,
          '@total' => $total,
          '@id' => $this->getEntityId($entity),
        );

        drush_print(dt('(@count / @total) Processed @entity_type ID @id.', $params));
      }
    }
  }

  /**
   * Return array of all fields.
   *
   * @return array
   */
  protected function getFields() {
    return empty($this->fields) ?  $this->getBaseFields() : array_merge($this->getBaseFields(), $this->fields);
  }

  /**
   * Return array of general fields of entity type.
   *
   * @return array
   */
  protected function getBaseFields(){
    return array();
  }

  /**
   * Return entity type name.
   *
   * @return mixed
   */
  protected function getEntityType() {
    return $this->entityType;
  }

  /**
   * Truncate table.
   */
  protected function truncateTable() {
    db_query('TRUNCATE TABLE '. $this->getDestinationTable());
  }

   /**
   * Get values from entity.
   *
   * @param stdClass $entity
   *   The entity to process and extract the values.
   *
   * @return array
   *   Array keyed by the SQL directive, and the value to insert.
   */
  protected function getValues($entity) {
    $values = array();
    foreach($this->getFields() as $key => $directive) {
      $values[$key] = $entity->$key;
    }
    return $values;
  }

  /**
   * Insert Values to DB.
   *
   * @param stdClass $entity
   *   The entity to process and insert to the DB.
   *
   * @return bool
   *   TRUE if insert was successful.
   */
  protected function insertQuery($entity) {
    $destination_table = $this->getDestinationTable();

    $fields = $this->getFields();

    $directives = array();
    foreach ($fields as $directive) {
      $directives[] = "'" . $directive . "'";
    }

    $query = "INSERT INTO $destination_table(". implode(", ", array_keys($fields)) .") VALUES(" . implode(", ", $directives) . ")";
    return db_query($query, $this->getValues($entity));
  }

  /**
   * Get range of records processed per batch.
   *
   * @return int
   */
  public function getRange() {
    return $this->range;
  }

  /**
   * Return the destination table.
   *
   * @return string
   */
  protected function getDestinationTable() {
    return '_gizra_' . $this->getEntityType();
  }
}
