<?php

namespace Drupal\home_enseignants\Controller;

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
    $output['#theme'] = 'home_enseignants';
    $output['#var']['profs'] = $this->getProfs();

    return $output;
  }

  public function getProfs(): array{
    $output = [];

    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'enseignants');
    $query->condition('status', 1);
    $query->sort('title', 'ASC');

    if (\Drupal::request()->query->get('discipline')) {
      $discipline = \Drupal::request()->query->get('discipline');
      $query->condition('field_discipline_profs', $discipline, 'IN');
    }

    if (\Drupal::request()->query->get('pratiquer')) {
      $discipline = \Drupal::request()->query->get('pratiquer');
      $query->condition('field_discipline_profs', $discipline, 'IN');
    }

    $nodes_ids = $query->execute();

    $nodes = Node::loadMultiple($nodes_ids);

    foreach ($nodes as $key => $node){
      $field_media = $node->get('field_medias')->getValue();
      $url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/' . $node->get('nid')->value);

      $output[$key]['title'] = $node->getTitle();
      $output[$key]['prenom'] = $node->get('field_prenom');
      $output[$key]['visuel_id'] = $field_media[0]['target_id'];
      $output[$key]['url'] = $url;
      $output[$key]['tid'] = $node->get('field_discipline_profs');
      $output[$key]['sous_titre'] = $node->get('field_sous_titre');
    }

    return $output;
  }

}
