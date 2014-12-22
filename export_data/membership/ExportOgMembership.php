<?php
/**
 * @file
 * Contains \ExportOgMembership.
 */
require '/vagrant/wordpress/build/euei/export_data/ExportBase.php';

class ExportOgMembership extends ExportBase {

  // The entity type name, destination table name.
  protected $entityType = 'og_membership';

  /**
   * Get amount membership records.
   *
   * @return integer
   */
  protected function getTotal() {
    return db_result(db_query("SELECT COUNT(uid) FROM og_uid"));
  }

  /**
   * Get the results by a certain offset.
   *
   * @param int $offset
   *
   * @return array
   */
  protected function getResults($offset = 0) {
    return db_query("SELECT uid FROM og_uid ORDER BY nid LIMIT %d OFFSET %d", $this->getRange(), $offset);
  }


}

