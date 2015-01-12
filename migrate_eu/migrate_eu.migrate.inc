<?php

/**
 * @file
 * Contains \EuMigration
 */

abstract class EuMigration extends Migration {

  protected $unique_id_setting = array(
    'unique_id' => array('type' => 'varchar',
      'length' => 254,
      'not null' => TRUE,
      'description' => 'Origin source name and user id separated by colon.',
    )
  );

  /**
   * Array for set mapping between old and new group id.
   *
   * @var array
   */
  protected $groupConnect = array(
    // EUEI
    'euei:54' => '52090', // African Renewable Energy Technology Platform - AFRETEP
    'euei:10772' => '52092', // CEREEECA Project
    // EUWI
    'euwi:21098' => '52100', // EUWI Community Space
    'euwi:21244' => '52105', // EUWI Africa
    'euwi:20336' => '52107', // Eastern Europe, Caucasus and Central Asia
    'euwi:20532' => '52109', // EUWI Finance Working Group
    'euwi:21019' => '52111', // EUWI Latin America Water Supply and Sanitation
    'euwi:20691' => '52113', // EUWI Mediterranean
    'euwi:20488' => '52115', // EUWI Monitoring
    'euwi:20733' => '52117', // EUWI Multi-Stakeholder Forum
    'euwi:20451' => '52119', // EUWI Research
    'euwi:21010' => '52121', // EUWI Coordination Group and _Secretariat_ (Merged with next group)
    'euwi:20868' => '52121', // EUWI _Coordination Group_ and Secretariat
  );

  /**
   * Return the base fields that should be migrated on all node bundles.
   */
  protected function getNodeFields() {
    return array('unique_id',
      'nid',
      'title',
      'body',
      'uid',
      'path',
      'promote',
      'sticky',
      'gid',
      'tags',
    );
  }

  public function __construct() {
    parent::__construct();

    // Set destination class.
    if ($this->entityType == 'node') {
      $destination_class = 'MigrateDestinationNode';
    }
    else {
      $destination_class = 'MigrateDestinationUser';
    }

    // Set map object.
    $this->map = new MigrateSQLMap($this->machineName,
      $this->unique_id_setting,
      call_user_func(array($destination_class, 'getKeySchema'))
    );

    // Make sure only a single alias is created.
    $this->addFieldMapping('pathauto_perform_alias')
      ->defaultValue(FALSE);

    // Map for groups.
    $this->addFieldMapping('og_groups', 'gid');

    // Map for users.
    $this
      ->addFieldMapping('uid', 'uid')
      ->sourceMigration('EuUser');
  }

  /**
   * Callback method; Add taxonomy terms.
   */
  public function addTags($tags) {
    $tags_array = array();
    foreach ($tags as $tag_name) {
      // if tag already exists return term obj.
      if ($term_obj = taxonomy_get_term_by_name($tag_name)) {
        $tags_array[] = array_shift($term_obj);
      }
      // else create a new tag.
      else {
        $term = array(
          'vid' => 512,
          'name' => $tag_name,
        );
        taxonomy_save_term($term);
        $tags_array = array_merge(taxonomy_get_term_by_name($tag_name), $tags_array);
      }
    }
    return $tags_array;
  }

  public function prepare($entity, $row) {
    // Test that user of the entity didn't migrated
    // because already exist in system.
    if (empty($entity->uid) && $row->uid) {
      // Fetch a name of existing user in dump.
      $user_name = db_select('_gizra_user', 'u')
        ->fields('u', array('name'))
        ->condition('unique_id', $row->uid)
        ->execute()
        ->fetchField();

      // Fetch a uid of existing user in c4d.
      $user_id = db_select('users', 'u')
        ->fields('u', array('uid'))
        ->condition('name', $user_name)
        ->execute()
        ->fetchField();

      // Set uid for entity.
      $entity->uid = $user_id;
    }

    // Map original group id with new group id.
    if ($this->entityType == 'node') {
      $gid = $this->groupConnect[$row->gid];
      $entity->og_groups = array($gid);

      $vocabs = array_keys(og_vocab_get_vocabularies($gid));
      $entity->vocabs = $vocabs;

      // Parse tags.
      if ($row->tags) {
        $tags = explode('|', $row->tags);
        $entity->taxonomy = array();
        foreach ($tags as $tag_name) {
          // Search tag in Categories.
          $tag_id = $this->searchTagInVocabulary($tag_name, $entity->vocabs[0]);
          if ($tag_id) {
            $entity = $this->addTagToEntity($tag_id, $entity);
          }
          else {
            // Search tag in Additional tags.
            $tag_id = $this->searchTagInVocabulary($tag_name, $entity->vocabs[1]);
            if ($tag_id) {
              $entity = $this->addTagToEntity($tag_id, $entity);
            }
            else {
              // Create a new tag and safe in Additional tags.
              $new_term = array(
                'vid' => $entity->vocabs[1],
                'name' => $tag_name,
              );
              if (taxonomy_save_term($new_term)) {
                $entity = $this->addTagToEntity($new_term['tid'], $entity);
              }
            }
          }
        }
      }
    }
  }

  /**
   * Search tag by name in special vocabulary.
   *
   * @param $tag_name
   *  Tag name for search.
   * @param $vocabulary_id
   *  ID of vocabulary where make search.
   *
   * @return int
   *   Id of founded tag or false.
   */
  public function searchTagInVocabulary($tag_name, $vocabulary_id) {
    $tree = taxonomy_get_tree($vocabulary_id);
    // Find
    if ($tree) {
      foreach ($tree as $tag) {
        if(strtolower($tag->name) == strtolower($tag_name)) {
          return $tag->tid;
        }
      }
    }

    return false;
  }

  /**
   * Add exists tag to entity.
   *
   * @param $tag_id
   *   Id of tag.
   * @param $entity
   *   Entity prepare.
   * @return obect
   *   Return updated entity.
   */
  public function addTagToEntity($tag_id, $entity) {
    if (!is_array($entity->taxonomy)) {
      $entity->taxonomy = array();
    }
    $term = taxonomy_get_term($tag_id);
    $entity->taxonomy[$tag_id] = $term;

    return $entity;
  }
}
