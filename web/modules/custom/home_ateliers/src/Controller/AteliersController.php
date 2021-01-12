<?php

namespace Drupal\home_ateliers\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;

/**
 * Class AteliersController.
 */
class AteliersController extends ControllerBase {

  /**
   * Home.
   *
   * @return array
   *   Return Hello string.
   */
  public function home() {
    $output = [];
    $output['#theme'] = 'home_ateliers';
    $output['#var']['ateliers'] = $this->getAteliers();
    $output['#var']['term'] = $this->getTerm();

    return $output;
  }

  public function getTerm(){
    $filtres_second_niveau = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('scolaires');
    $output = [];

    foreach ($filtres_second_niveau as $term) {
      $output[$term->tid] = $term->name;
    }

    return $output;
  }

  public function getAteliers(){
    $output = [];
    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'atelier');
    $query->condition('status', 1);

    $medias_ids = $query->execute();

    $medias = Node::loadMultiple($medias_ids);

    foreach ($medias as $key => $node) {
      $url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/' . $node->get('nid')->value);
      $field_media = $node->get('field_medias')->getValue();
      $field_tid = $node->get('field_type_groupe')->getValue();
      $term = Term::load($field_tid[0]['target_id']);
      $name = $term->getName();

      $output[$key]['visuel_id'] = $field_media[0]['target_id'];
      $output[$key]['url'] = $url;
      $output[$key]['title'] = $node->getTitle();
      $output[$key]['sub_title'] = $node->get('field_sous_titre')->value;
      $output[$key]['tid'] = $field_tid;//$field_tid[0]['target_id'];
      $output[$key]['type'] = $name;
    }

    return $output;
  }

}
