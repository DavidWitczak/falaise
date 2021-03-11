<?php

namespace Drupal\home_pratiquer\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

/**
 * Class HomeController.
 */
class HomeController extends ControllerBase {

  /**
   * Home.
   *
   * @return array
   *   Return Hello string.
   */
  public function home(): array {
    $output = [];
    $output['#theme'] = 'home_pratiquer';
    $output['#var']['pratiquer'] = $this->getPratiquer();

    return $output;
  }

  public function getPratiquer(): array{
    $output = [];

    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'pratiquer');
    $query->condition('status', 1);
    $query->sort('title', 'ASC');

    if (\Drupal::request()->query->get('discipline')) {
      $discipline = \Drupal::request()->query->get('discipline');
      $query->condition('field_discipline', $discipline);
    }

    if (\Drupal::request()->query->get('public')) {
      $public = \Drupal::request()->query->get('public');
      $query->condition('field_public', $public);
    }

    $nodes_ids = $query->execute();

    $nodes = Node::loadMultiple($nodes_ids);

    foreach ($nodes as $key => $node){
      $field_media = $node->get('field_medias')->getValue();
      $url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/' . $node->get('nid')->value);

      $output[$key]['title'] = $node->getTitle();
      $output[$key]['visuel_id'] = $field_media[0]['target_id'];
      $output[$key]['url'] = $url;
      $output[$key]['tid'] = $node->get('field_discipline');
    }

    return $output;
  }

}
