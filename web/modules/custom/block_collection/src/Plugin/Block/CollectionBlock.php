<?php

namespace Drupal\block_collection\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;
use Drupal\Component\Utility\Unicode;

/**
 * Provides a 'CollectionBlock' block.
 *
 * @Block(
 *  id = "collection_block",
 *  admin_label = @Translation("Block collection"),
 * )
 */
class CollectionBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $route_match = \Drupal::routeMatch();

    $current_node = $route_match->getParameter('node');
    $nid = $current_node->id();

    $output = $this->getObjet($nid);

    return $output;
  }

  public function getObjet($nid) {
    $output = [];
    $output['#theme'] = 'block_collection';

    $collection = Node::load($nid);
    $field_term = $collection->get('field_theme_collection')->getValue();
    $term_id = $field_term[0]['target_id'];

    $wanted_terms_id = [];
    $filtres_second_niveau = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('theme_de_collection', $term_id, 1);
    foreach ($filtres_second_niveau as $term) {
      $wanted_terms_id[] = $term->tid;
      $output['#var']['term'][$term->tid] = $term->name;
    }
    $wanted_terms_id[] = $term_id;

    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'objet');
    $query->condition('status', 1);
    $query->condition('field_theme_collection', $wanted_terms_id, 'IN');

    $objects_ids = $query->execute();

    $objects = Node::loadMultiple($objects_ids);

    foreach ($objects as $key => $node) {
      $url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/' . $node->get('nid')->value);
      $field_media = $node->get('field_medias')->getValue();

      $output['#var']['object'][$key]['visuel_id'] = $field_media[0]['target_id'];
      $output['#var']['object'][$key]['url'] = $url;
      $output['#var']['object'][$key]['title'] = $node->getTitle();
    }

    return $output;
  }

}
