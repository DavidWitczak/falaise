<?php

namespace Drupal\home_enfants\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\node\Entity\Node;

/**
 * Class EnfantController.
 */
class EnfantController extends ControllerBase {

  /**
   * Home.
   *
   * @return array
   *   Return Hello string.
   */
  public function home() {
    $output = [];

    $output['#theme'] = 'home_enfants';
    $output['#var']['expos'] = $this->getExpos();

    return $output;
  }

  public function getExpos() {
    $output = [];
    $today = new DrupalDateTime('today midnight');

    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'exposition');
    $query->condition('status', 1);
    $query->condition('field_date_fin.value', $today, '>');
    $query->sort('field_date_fin.value', 'DESC');

    $expos_ids = $query->execute();

    $expos = Node::loadMultiple($expos_ids);

    foreach ($expos as $key => $expo) {
      $field_media = $expo->get('field_affiche')->getValue();
      $url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/' . $expo->get('nid')->value);

      $output[$key]['title'] = $expo->getTitle();
      $output[$key]['date_debut'] = $expo->field_date_debut;
      $output[$key]['date_fin'] = $expo->field_date_fin;
      $output[$key]['visuel_id'] = $field_media[0]['target_id'];
      $output[$key]['url'] = $url;
    }

    return $output;
  }

}
