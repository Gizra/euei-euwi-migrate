<?php

/**
 * @file
 * Contains \ExportNodeBase.
 */

require '/vagrant/wordpress/build/euei/export_data/ExportBase.php';

class ExportNodeBase extends ExportBase {

  protected $entityType = 'node';

  protected $bundle = NULL;

  /**
   * Return key value array with name and format.
   *
   * @return array
   */
  protected function getBaseFields() {
    return array(
      'nid' => '%d',
      'title' => '%s',
      'body' => '%s',
      'teaser' => '%s',
      'uid' => '%s',
      'path' => '%s',
      'promote' => '%d',
      'sticky' => '%d',
      'gid' => '%s',
      'tags' => '%s',
      'taxonomy' => '%s',
      'created' => '%d',
      'changed' => '%d',
      'counter' => '%d',
      'ref_documents' => '%s'
    );
  }

  /**
   * Get the results by a certain offset.
   *
   * @param int $offset
   *
   * @return array
   */
  protected function getResults($offset = 0) {
    return db_query("SELECT nid FROM {node} n WHERE n.type = '%s' ORDER BY n.nid LIMIT %d OFFSET %d", $this->getOriginalBundle(), $this->getRange(), $offset);
  }

  /**
   * Return the destination table.
   *
   * @return string
   */
  protected function getDestinationTable() {
    return parent::getDestinationTable() . '_' . $this->getBundle();
  }

  /**
   * Get the original bundle, if exists.
   *
   * For example the "ipaper" bundle, is the original bundle for "document".
   *
   * @return string
   *   The bundle name.
   */
  protected function getOriginalBundle() {
    return !empty($this->originalBundle) ? $this->originalBundle : $this->getBundle();
  }

  /**
   * Get amount node records.
   *
   * @return integer
   */
  protected function getTotal() {
    return db_result(db_query("SELECT COUNT(nid) FROM {node} n WHERE n.type = '%s' ORDER BY n.nid", $this->getOriginalBundle()));
  }

  /**
   * Get Entity from query result row.
   *
   * @param $row
   *   The row fetched from result query
   *
   * @return object
   */
  protected function getEntityFromRow($row) {
    return node_load($row['nid']);
  }

  /**
   * Get entity ID.
   *
   * @param object $entity
   *    The entity object.
   *
   * @return integer
   */
  protected function getEntityId($entity) {
    return $entity->nid;
  }

  /**
   * {@inheritdoc}
   */
  protected function getValues($entity) {
    $values = parent::getValues($entity);
    foreach($values as $key => $directive) {
      if ($key == 'gid') {
        $values[$key] = $this->getGroupIdFromEntity($entity);
      }
      elseif ($key == 'uid') {
        $values[$key] = $this->getSiteName() . ':' . $entity->$key;
      }
      elseif ($key == 'tags') {
        $values[$key] = $this->getTagsFromNode($entity);
      }
      elseif ($key == 'path') {
        $values[$key] = $this->getPathFromNode($entity);
      }
      elseif ($key == 'promote') {
        $values[$key] = $this->isHighlighted($entity);
      }
      elseif ($key == 'taxonomy') {
        $values[$key] = $this->getTaxonomyFromNode($entity);
      }
      elseif ($key == 'counter') {
        $values[$key] = $this->getCounterFromNode($entity);
      }
      elseif ($key == 'ref_documents') {
        //Documents hasn't images and files exports as document.
        $values[$key] = $this->getOriginalBundle()=='ipaper' ? "" : $this->getReferenceDocument($entity);
      }
    }
    return $values;
  }

  /**
   * Check if the node belongs certain groups and needs to export.
   *
   * @param $entity
   *   The entity object.
   *
   * @return bool
   */
  protected function isExportable($entity) {
    if (empty($entity->og_groups)) {
      // Node is not associated with any group.
      return;
    }
    foreach ($entity->og_groups as $og_group) {
      if (in_array($og_group, $this->groupForExport[$this->getSiteName()])) {
        return TRUE;
      }
    }
  }

  /**
   * Return list of necessary groups of entity for export separated by pipe.
   *
   * @param $entity
   *   The entity object.
   *
   * @return string
   *
   */
  protected function getGroupIdFromEntity($entity) {
    foreach ($entity->og_groups as $og_group) {
      if (in_array($og_group, $this->groupForExport[$this->getSiteName()])) {
        return $this->getSiteName() . ':' . $og_group;
      }
    }
  }

  /**
   * Return the tags of the node.
   *
   * @param $entity
   *   The entity object.
   *
   * @return string
   */
  protected function getTagsFromNode($entity) {
    if (empty($entity->tags)) {
      return;
    }

    $tags = array();
    foreach(reset($entity->tags) as $tag) {
      $tags[] = $tag->name;
    }
    return implode ('|', $tags);
  }

  /**
   * Return prepared path. Remove first prefix.
   *
   * @param $entity
   *   The entity object.
   *
   * @return string
   */
  protected function getPathFromNode($entity) {
    $path = explode('/', $entity->path);
    unset($path[0]);
    return implode('/', $path);
  }

  /**
   * Return taxonomy as string with format "name:description" separated by pipe.
   *
   * @param $entity
   *  The entity object of type node.
   *
   * @return string
   */
  protected function getTaxonomyFromNode($entity) {
    if (!$entity->taxonomy) {
      return;
    }
    $taxonomy = array();
    foreach ($entity->taxonomy as $term) {
      $taxonomy[] = $term->name. ':' .$term->description;
    }
    return implode('|', $taxonomy);
  }

  /**
   * Check if the node is highlighted.
   *
   * @param $entity
   *  The entity object of type node.
   *
   * @return bool
   */
  protected function isHighlighted($entity) {
    if ($this->getSiteName() == 'euwi') {
      // 1379 taxonomy term  is 'Highlighted'
      return (in_array(1379, array_keys($entity->taxonomy)));

    }
    elseif ($this->getSiteName() == 'euei') {
      // Nodes marked as highlited in the {nodequeue_nodes} with Queue ID 15.
      $higtlighted_nodes = array(9514, 7900, 7899);
      return in_array($entity->nid, $higtlighted_nodes);
    }
  }
  protected function getCounterFromNode($entity) {
    return db_result(db_query("SELECT totalcount FROM node_counter WHERE nid = '%d'", $entity->nid));
  }


  /**
   * Add Id for reference document in the 'ref_documents' field.
   * separated by pipe.
   *
   * A document is already migrated in ExportNodeDocumentImage.
   * And files related to node (News, Event).
   *
   * @param $entity
   *  The entity object of type node.
   *
   * @return string.
   *  The ID documents separated by pipe or false.
   */
  protected function getReferenceDocument($entity) {

    $ref_documents = array();

    if (!empty($entity->field_image[0])) {
      $ref_documents[] = $this->getSiteName() . ':image:' . $this->getEntityId($entity);
    }

    if (!empty($entity->files)) {
      if(count($entity->files) > 1) {
        $file = $this->createZip($entity);
      }
      else {
        $path = $this->exportFile(reset($entity->files));
        $file = array (
          'file_name'=> end(explode('/', $path)),
          'file_path'=> $path,
        );
      }

      $ref_documents[] = $this->addFileAsDocument($file, $entity);

    }

    return count($ref_documents)? implode('|', $ref_documents) : FALSE;
  }

  /**
   * Create zip archive from related node files.
   *
   * @param $entity
   *   The entity object of type node.
   *
   * @return array
   *   The array with  the 'filename' and 'filepath' elements for new zip file.
   *
   * @throws Exception
   *   Message if zip file could not be created.
   */
  protected function createZip($entity) {
    $valid_files = array();
    foreach ($entity->files as $file) {
      $filepath = $this->getSiteName() == 'euwi' ? file_directory_path() . '/' . $file->filepath : $file->filepath;
      if (file_exists($filepath)) {
        $valid_files[] = $filepath;
      } else {
        drush_print(dt('Reference file @source could not be found.', array('@source' => $filepath)));
      }
    }

    if (count($valid_files)) {
      $destination = '../euei/export_data/files/' . $this->getSiteName() . '/' . $this->getSiteName() . '_' . $this->getEntityId($entity) . '.zip';
      $zip = new ZipArchive();
      if($zip->open($destination, ZIPARCHIVE::OVERWRITE) !== true) {
        throw new Exception(strstr('Cannot create zip file @dest ', array('@dest' => $destination)));
      }
      foreach($valid_files as $file) {
        $name = "/" . end(explode('/', $file));
        $zip->addFile($file, $name);
      }
      $zip->close();
      if (file_exists($destination)) {
        $destination = explode('/', $destination);
        $destination = array_slice($destination, 2);
        $zipfile = array(
          'file_name' => end($destination),
          'file_path' => implode('/', $destination),
        );

        return $zipfile;
      }
    }
  }


  /**
   * @param $file
   *  Array contains 'file_name" and "file_path" elements.
   * @param $entity
   *   The entity ID object.
   *
   * @return string
   *   The uniqueID
   */
  protected function addFileAsDocument($file, $entity) {

    $fields = array(
      'unique_id' => '%s',
      'title' => '%s',
      'uid' => '%s',
      'gid' => '%s',
      'created' => '%d',
      'file_name' => '%s',
      'file_path' => '%s',
    );

    $directives = array();
    foreach ($fields as $directive) {
      $directives[] = "'" . $directive . "'";
    }

    $values = array();
    foreach($fields as $key => $directive) {
      if ($key == 'unique_id') {
        $values[$key] = $this->getSiteName() . ':file:' . $this->getEntityId($entity);
      }
      elseif ($key == 'title') {
        $values[$key] = $file['file_name'];
      }
      elseif ($key == 'uid') {
        $values[$key] = $this->getSiteName() . ':' . $entity->uid;
      }
      elseif ($key == 'gid') {
        $values[$key] = $this->getGroupIdFromEntity($entity);
      }
      elseif ($key == 'created') {
        $values[$key] = $entity->created;
      }
      else {
        $values[$key] = $file[$key];
      }
    }

    $query = "INSERT INTO euei._gizra_node_document(". implode(", ", array_keys($fields)) .") VALUES(" . implode(", ", $directives) . ")";
    return db_query($query, $values) ? $values['unique_id'] : FALSE ;
  }

}

