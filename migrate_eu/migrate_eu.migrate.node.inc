<?php

/**
 * @file
 * Contains \EuMigrationNode
 */

class EuMigrationNode extends EuMigration {

  /**
   * Return the base fields that should be migrated on all node bundles.
   */
  protected function getNodeFields() {
    return array('unique_id',
      'nid',
      'title',
      'body',
      'teaser',
      'uid',
      'path',
      'promote',
      'sticky',
      'gid',
      'tags',
      'created',
      'changed',
      'taxonomy',
      'ref_documents',
    );
  }

  public function __construct() {
    parent::__construct();

    // Map Node group with gid.
    $this
      ->addFieldMapping('og_groups', 'gid');

    // Map Node user with uid.
    $this
      ->addFieldMapping('uid', 'uid')
      ->sourceMigration('EuUser');

    // Make sure only a single alias is created.
    $this
      ->addFieldMapping('pathauto_perform_alias')
      ->defaultValue(FALSE);

    // Make mapping for all simple fields.
    $this
      ->addSimpleMappings(array(
      'title',
      'body',
      'teaser',
      'path',
      'promote',
      'sticky',
      'created',
      'changed',
    ));

  }

  /**
   * Prepare function map Users that already exists in C4D.
   * Also prepare function map Groups, Tags for nodes.
   *
   * @param $entity
   * @param $row
   */
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
    $gid = $this->groupConnect[$row->gid];
    $entity->og_groups = array($gid);

    // Map tags for the node.
    $vocabs = array_keys(og_vocab_get_vocabularies($gid));
    $entity->vocabs = $vocabs;

    // If a node have tags start parse it.
    if ($row->tags) {
      $tags = explode('|', $row->tags);
      $entity->taxonomy = array();
      foreach ($tags as $tag_name) {
        // Search tag in Categories.
        $tag_id = $this->searchTagInVocabulary($tag_name, $entity->vocabs[0]);
        if ($tag_id) {
          $this->addTagToEntity($tag_id, $entity);
        }
        else {
          // Search tag in custom tags.
          $tag_id = $this->searchTagInVocabulary($tag_name, $entity->vocabs[1]);
          if ($tag_id) {
           $this->addTagToEntity($tag_id, $entity);
          }
          else {
            // Create a new tag and safe in custom tags.
            $new_term = array(
              'vid' => $entity->vocabs[1],
              'name' => $tag_name,
            );
            if (taxonomy_save_term($new_term)) {
              $this->addTagToEntity($new_term['tid'], $entity);
            }
          }
        }
      }
    }

    // If a node have taxonomy then parse it.
    if ($row->taxonomy) {
      $tags = explode('|', $row->taxonomy);
      $entity->taxonomy = array();
      foreach ($tags as $tag) {
        list ($tag_name, $tag_description) = explode(':', $tag);

        // Search tag in Categories.
        $tag_id = $this->searchTagInVocabulary($tag_name, $entity->vocabs[0]);
        if ($tag_id) {
          $this->addTagToEntity($tag_id, $entity);
        }
        else {
          // Search tag in custom tags.
          $tag_id = $this->searchTagInVocabulary($tag_name, $entity->vocabs[1]);
          if ($tag_id) {
            $this->addTagToEntity($tag_id, $entity);
          }
          else {
            // Create a new tag and safe in Additional tags.
            $new_term = array(
              'vid' => $entity->vocabs[1],
              'name' => $tag_name,
              'description' => $tag_description,
            );
            if (taxonomy_save_term($new_term)) {
              $this->addTagToEntity($new_term['tid'], $entity);
            }
          }
        }

      }
    }

    // Set entity format property equal to 5 that mean entity body edited in
    // WYSIWYG editor and will support special format tags. This option
    // actually only for News (blog) and Events.
    if ($this->bundle == 'blog' || $this->bundle == 'event' || $this->bundle == 'document') {
      $entity->format = 5;
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
    $terms = db_select('term_data', 't')
      ->fields('t')
      ->condition('vid', $vocabulary_id)
      ->execute()
      ->fetchAllAssoc('tid');

    if (count($terms) == 0)
      return FALSE;

    foreach ($terms as $term) {
      if (strtolower(trim($term->name)) == strtolower(trim($tag_name))) {
        return $term->tid;
      }
    }

    return FALSE;
  }

  /**
   * Add exists tag to entity.
   *
   * @param $tag_id
   *   Id of tag.
   * @param $entity
   *   Entity.
   * @return obect
   *   Return updated entity.
   */
  public function addTagToEntity($tag_id, &$entity) {
    if (!is_array($entity->taxonomy)) {
      $entity->taxonomy = array();
    }
    $term = taxonomy_get_term($tag_id);
    $entity->taxonomy[$tag_id] = $term;
  }

}
