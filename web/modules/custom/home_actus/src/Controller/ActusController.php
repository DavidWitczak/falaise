<?php

namespace Drupal\home_actus\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

/**
 * Class ActusController.
 */
class ActusController extends ControllerBase {

  /**
   * Home.
   *
   * @return array
   *   Return Hello string.
   */
  public function home() {
    $output = [];
    $output['results']['#theme'] = 'home_actus';
    $output['results']['#var']['actus'] = $this->getActus();
    $output['pager'] = ['#type' => 'pager'];

    return $output;
  }

  public function getActus(){
    $output = [];

    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'actu');
    $query->condition('status', 1);
    $query->sort('created', 'DESC');
    $query->pager(6);

    $actus_ids = $query->execute();

    $actus = Node::loadMultiple($actus_ids);

    foreach ($actus as $key => $actu){
      $field_media = $actu->get('field_medias')->getValue();
      $url = \Drupal::service('path_alias.manager')->getAliasByPath('/node/' . $actu->get('nid')->value);

      $output[$key]['title'] = $actu->getTitle();
      $output[$key]['visuel_id'] = $field_media[0]['target_id'];
      $output[$key]['url'] = $url;
      $output[$key]['date'] = $actu->get('created');
    }

    return $output;
  }

}
