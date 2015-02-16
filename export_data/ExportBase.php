<?php

require '/vagrant/wordpress/build/euei/export_data/ExportInterface.php';

class ExportBase implements ExportInterface {

  protected $entityType = NULL;

  protected $range = 50;

  protected $siteName;

  /**
   * Array for set working groups will be exported for each site.
   *
   * @var array
   */
  protected $groupForExport = array(
    'euei' => array(
      'AFRETEP' => 54,
      'CEREEECA' => 10772,
    ),
    'euwi' => array(
      'EUWI Community Space' => 21098,
      'Africa' => 21244,
      'Eastern Europe, Caucasus and Central Asia' => 20336,
      'Finance Working Group' => 20532,
      'Latin America Water Supply and Sanitation' => 21019,
      'Mediterranean' => 20691,
      'Monitoring' => 20488,
      'Multi-stakeholder Forum' => 20733,
      'Research' => 20451,
      'Secretariat' => 21010,
      'Coordination Group' => 20868,
    ),
  );

  /**
   * Construct method.
   */
  public function __construct() {
    $this->siteName = drush_get_option('site-name', 'euei');
  }

  /**
   * Export data.
   */
  public function export() {
    // Remove any existing data if the EUEI site.
    // Cause of export images runs before documents for the same table.
    // IT need not be trancated before export documents.
    if ($this->getSiteName() == 'euei' && $this->getBundle() != 'document') {
      $this->truncateTable();
    }

    // Get total count.
    if (!$total = $this->getTotal()) {
      throw new Exception('No total count for entity type.');
    }

    $count = 0;

    while ($count < $total) {
      $result = $this->getResults($count);

      while ($row = db_fetch_array($result)) {

        $entity = $this->getEntityFromRow($row);
        if ($this->isExportable($entity)) {
          $this->insertQuery($entity);
        }

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
    // First value for unique ID.
    $values = $this->getEntityUniqueId($entity);
    foreach($this->getFields() as $key => $directive) {
      $values[$key] = $entity->$key;
    }
    return $values;
  }

  /**
   * Get the unique ID of the entity.
   *
   * @param $entity
   * @return array
   *   Array keyed by "unique_id" and the unique ID (site name, and entity ID)
   *   as value.
   */
  protected function getEntityUniqueId($entity) {
    return array('unique_id' => $this->getSiteName() . ':' . $this->getEntityId($entity));
  }

  /**
   * Get the site name.
   *
   * @return string
   */
  protected function getSiteName() {
    return $this->siteName;
  }

  /**
   * Get the bundle name.
   *
   * @return string
   */
  protected function getBundle() {
    return $this->bundle;
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
    //First field  with directive for unique_id
    $fields = array('unique_id' => '%s');
    $fields = array_merge($fields, $this->getFields()) ;

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
      return 'euei._gizra_' . $this->getEntityType();
  }

  /**
   * Check necessity of exporting data. Default value is TRUE
   *
   * @param $entity
   *   Verifiable entity
   *
   * @return bool
   */
  protected function isExportable($entity) {
    return TRUE;
  }

  /**
   * Export a file object.
   *
   * @param object $file
   *   The object file with 'filepath' property .
   *
   * @return string
   *   Path to exported file or empty if unsuccsessfull.
   *
   * @throws Exception
   *   message if destination directory not exist.
   */
  protected function exportFile($file, $folder='files') {
    $file = is_array($file) ? (object)$file : $file;
    $destination = "../euei/export_data/" . $folder . "/" . $this->getSiteName();

    if (!file_check_directory($destination, FILE_CREATE_DIRECTORY)) {
      throw new Exception(strstr('Directory @dest does not exist.', array('@dest' => $destination)));
    }

    $source = $this->getSiteName() == 'euwi' ? file_directory_path() . '/' . $file->filepath : $file->filepath;

    if (!file_exists($source)) {
      drush_print(dt('File @source could not be found.', array('@source' => $source)));
    }

    $filename = array_pop(explode('/', $file->filepath));
    $path = $destination . '/' . $filename;
    if (copy($source, $path)){
      $path = explode('/', $path);
      //remove the "../euei/" prefix.
      $path = array_slice($path, 2);
      return implode('/', $path);
    }
  }
}
