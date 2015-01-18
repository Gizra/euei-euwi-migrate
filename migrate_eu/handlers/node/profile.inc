<?php

/**
 * @file
 * Contains \EuProfileMigration
 */

class EuProfileMigration extends EuMigration {

  protected $entityType = 'node';

  protected $bundle = 'profile';

  public function __construct() {
    parent::__construct();

    $this->description = t('Migration of the Profile');
    $this->dependencies = array('EuUser');

    // Set source object.
    $query = db_select('_gizra_user', 'user')
      ->fields('user', array('unique_id', 'full_name', 'organization', 'organization_category', 'country'))
      ->orderBy('unique_id', 'ASC');

    $this->source = new MigrateSourceSQL($query);

    // Set destination object.
    $this->destination = new MigrateDestinationNode($this->bundle);

    $this->addFieldMapping('field_lastname', 'full_name');
    $this->addFieldMapping('field_firstname', 'full_name');
    $this->addFieldMapping('field_organisation', 'organization');
    $this->addFieldMapping('field_organisation_type_of', 'organization_category');
    $this->addFieldMapping('field_country', 'country');

  }

}
