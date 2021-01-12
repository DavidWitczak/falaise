<?php

namespace Drupal\block_expo\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Provides a 'ExpoBlock' block.
 *
 * @Block(
 *  id = "expo_block",
 *  admin_label = @Translation("Expo block"),
 * )
 */
class ExpoBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $route_match = \Drupal::routeMatch();

    $current_node = $route_match->getParameter('node');
    $nid = $current_node->id();

    $output = $this->getAgenda($nid);

    return $output;
  }

  public function getAgenda($nid){
    $output['#theme'] = 'block_expo';

    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'agenda');
    $query->condition('status', 1);
    $query->condition('field_expo_liee', $nid);

    $agendas_ids = $query->execute();

    $agendas = Node::loadMultiple($agendas_ids);

    foreach ($agendas as $key => $node) {
      $url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/' . $node->get('nid')->value);
      $field_media = $node->get('field_medias')->getValue();

      $output['#var']['agenda'][$key]['visuel_id'] = $field_media[0]['target_id'];
      $output['#var']['agenda'][$key]['url'] = $url;
      $output['#var']['agenda'][$key]['title'] = $node->getTitle();
      $output['#var']['agenda'][$key]['tid'] = $node->get('field_cat_agenda');
      $output['#var']['agenda'][$key]['jours'] = $node->get('field_infos_sup');
      $output['#var']['agenda'][$key]['date_debut'] = $node->get('field_date_debut');
      $output['#var']['agenda'][$key]['date_fin'] = $node->get('field_date_fin');
    }

    return $output;
  }

}
