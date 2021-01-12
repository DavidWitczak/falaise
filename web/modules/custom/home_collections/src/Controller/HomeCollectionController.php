<?php

namespace Drupal\home_collections\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

/**
 * Class HomeCollectionController.
 */
class HomeCollectionController extends ControllerBase {

  /**
   * Home.
   *
   * @return string
   *   Return Hello string.
   */
  public function home() {
    $output = [];
    $output['#theme'] = 'home_collections';
    $output['#var']['collections'] = $this->getCollections();

    return $output;
  }

  public function getCollections(){
    $output = [];

    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'collection');
    $query->condition('status', 1);
    $query->sort('nid', 'ASC');

    $collections_ids = $query->execute();

    $collections = Node::loadMultiple($collections_ids);

    foreach ($collections as $key => $collection){
      $field_media = $collection->get('field_medias')->getValue();
      $url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/' . $collection->get('nid')->value);

      $output[$key]['title'] = $collection->getTitle();
      $output[$key]['visuel_id'] = $field_media[0]['target_id'];
      $output[$key]['url'] = $url;
    }

    return $output;
  }

}
