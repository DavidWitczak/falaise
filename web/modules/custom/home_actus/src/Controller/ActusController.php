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
    $output['#theme'] = 'home_actus';
    $output['#var']['actus'] = $this->getActus();

    return $output;
  }

  public function getActus(){
    $output = [];

    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'actu');
    $query->condition('status', 1);
    $query->sort('created', 'DESC');

    $actus_ids = $query->execute();

    $actus = Node::loadMultiple($actus_ids);

    foreach ($actus as $key => $actu){
      $field_media = $actu->get('field_medias')->getValue();
      $url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/' . $actu->get('nid')->value);

      $output[$key]['title'] = $actu->getTitle();
      $output[$key]['visuel_id'] = $field_media[0]['target_id'];
      $output[$key]['url'] = $url;
    }

    return $output;
  }

}
