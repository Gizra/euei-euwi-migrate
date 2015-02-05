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
   * Get the bundle name.
   *
   * @return string
   */
  protected function getBundle() {
    return $this->bundle;
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
}

