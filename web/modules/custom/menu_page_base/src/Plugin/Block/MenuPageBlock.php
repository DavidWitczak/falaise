<?php

namespace Drupal\menu_page_base\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Provides a 'MenuPageBlock' block.
 *
 * @Block(
 *  id = "menu_page_block",
 *  admin_label = @Translation("Menu page block"),
 * )
 */
class MenuPageBlock extends BlockBase {

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

    $output = $this->getPage($nid);

    return $output;
  }

  public function getPage($nid) {
    $output = [];
    $output['#theme'] = 'menu_page_base';

    $current_node = Node::load($nid);

    if ($current_node->get('field_page_principale')->value) {
      $main_page = $current_node;
      $main_page_id = $nid;
    }
    else {
      $field_main_page = $current_node->get('field_page_base_liee')->getValue();
      $id_main_page = $field_main_page[0]['target_id'];
      $main_page = Node::load($id_main_page);
      $main_page_id = $id_main_page;
    }

    if ($main_page) {
      //page principale
      $image = $main_page->get('field_medias')->getValue();
      $output['#var']['main_page']['media_id'] = $image[0]['target_id'];
      $output['#var']['main_page']['title'] = $main_page->getTitle();
      $output['#var']['main_page']['url'] = $url = \Drupal::service('path_alias.manager')->getAliasByPath('/node/' . $main_page->get('nid')->value);
      $output['#var']['current_id'] = $nid;
      $output['#var']['main_page_id'] = $main_page_id;

      //page liées
      $query = \Drupal::entityQuery('node');
      $query->condition('type', 'page_base');
      $query->condition('status', 1);
      $query->condition('field_page_base_liee', $main_page_id, '=');
      $query->sort('created', 'ASC');

      $pages_ids = $query->execute();

      $pages = Node::loadMultiple($pages_ids);

      foreach ($pages as $key => $page) {
        $url = \Drupal::service('path_alias.manager')->getAliasByPath('/node/' . $page->get('nid')->value);

        $image = $page->get('field_medias')->getValue();

        $output['#var']['page'][$key]['title'] = $page->getTitle();
        $output['#var']['page'][$key]['url'] = $url;
        $output['#var']['page'][$key]['media_id'] = $image[0]['target_id'];
        $output['#var']['page'][$key]['current_page_liee_id'] = $page->get('nid')->value;
      }
    }
    return $output;
  }

}
