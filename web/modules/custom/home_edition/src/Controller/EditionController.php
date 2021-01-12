<?php

namespace Drupal\home_edition\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

/**
 * Class EditionController.
 */
class EditionController extends ControllerBase {

  /**
   * Home.
   *
   * @return array
   *   Return Hello string.
   */
  public function home() {
    $output = [];
    $output['#theme'] = 'home_edition';
    $output['#var']['editions'] = $this->getEditions();

    return $output;
  }

  public function getEditions(){
    $output = [];

    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'edition');
    $query->condition('status', 1);
    $query->sort('created', 'DESC');

    $editions_ids = $query->execute();

    $editions = Node::loadMultiple($editions_ids);

    foreach ($editions as $key => $edition){
      $field_media = $edition->get('field_medias')->getValue();
      $url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/' . $edition->get('nid')->value);

      $output[$key]['title'] = $edition->getTitle();
      $output[$key]['visuel_id'] = $field_media[0]['target_id'];
      $output[$key]['url'] = $url;
    }

    return $output;
  }

}
