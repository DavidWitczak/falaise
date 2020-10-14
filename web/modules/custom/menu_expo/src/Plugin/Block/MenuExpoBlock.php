<?php

namespace Drupal\menu_expo\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Provides a 'MenuExpoBlock' block.
 *
 * @Block(
 *  id = "menu_expo_block",
 *  admin_label = @Translation("Menu expo block"),
 * )
 */
class MenuExpoBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $route_match = \Drupal::routeMatch();

    if ($route_match->getRouteName() == 'entity.node.canonical') {
      $current_node = $route_match->getParameter('node');
      $nid = $current_node->id();
    }
    else {
      $nid = NULL;
    }

    $output = $this->getExpo($nid);

    return $output;
  }

  public function getExpo($nid){
    $output = [];
    $output['#theme'] = 'menu_expo_block';

    $current_node = Node::load($nid);

    if ($current_node->getType() == 'expo_detail') {
      $field_expo_liee = $current_node->get('field_expo_liee')->getValue();
      $id_expo_liee = $field_expo_liee[0]['target_id'];
      $expo_liee = Node::load($id_expo_liee);
    }
    else {
      $id_expo_liee = $nid;
      $expo_liee = $current_node;
    }

    //l'expo elle-même
    $image = $expo_liee->get('field_medias')->getValue();
    $output['#var']['expo_liee']['url'] = $url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/' . $expo_liee->get('nid')->value);
    $output['#var']['expo_liee']['title'] = $expo_liee->title->value;
    $output['#var']['expo_liee']['media_id'] = $image[0]['target_id'];
    $output['#var']['expo_liee']['id'] = $id_expo_liee;
    $output['#var']['current_id'] = $nid;

    //Expos détail
    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'expo_detail');
    $query->condition('status', 1);
    $query->condition('field_expo_liee', $id_expo_liee, '=');
    $query->sort('created', 'ASC');

    $expos_ids = $query->execute();

    $expos = Node::loadMultiple($expos_ids);

    foreach ($expos as $key => $expo) {
      $url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/' . $expo->get('nid')->value);

      $image = $expo->get('field_medias')->getValue();

      $output['#var']['expo'][$key]['title'] = $expo->title->value;
      $output['#var']['expo'][$key]['url'] = $url;
      $output['#var']['expo'][$key]['media_id'] = $image[0]['target_id'];
      $output['#var']['expo'][$key]['current_expo_liee_id'] = $expo->get('nid')->value;
    }

    return $output;
  }

}
