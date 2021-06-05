<?php

namespace Drupal\home\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\node\Entity\Node;

/**
 * Class HomeController.
 */
class HomeController extends ControllerBase {

  /**
   * Home.
   *
   * @return string
   *   Return Hello string.
   */
  public function home() {
    $output['#theme'] = 'home';
    $output['#var']['actus'] = $this->getActus();
    $output['#var']['en_avant'] = $this->getEnAvant();
    $output['#var']['agenda'] = $this->getAgendas();
    $output['#var']['discipline'] = $this->getDiscipline();

    return $output;
  }

  public function getDiscipline(){
    $output = [];
    $config = \Drupal::config('site_config.config');

    for ($i = 1; $i <= 12; $i++) {
      $node = Node::load($config->get('discipline_' . $i));
      if($node) {
        $field_media = $node->get('field_medias')->getValue();
        $url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/' . $node->get('nid')->value);

        $output[$i]['title'] = $node->getTitle();
        $output[$i]['visuel_id'] = $field_media[0]['target_id'];
        $output[$i]['url'] = $url;
        $output[$i]['tid'] = $node->get('field_discipline');
      }
    }

    return $output;
  }

  public function getEnAvant(){
    $output = [];
    $config = \Drupal::config('site_config.config');

    for ($i = 1; $i <= 2; $i++) {
      $node = Node::load($config->get('event_' . $i));
      if($node) {
        $field_media = $node->get('field_medias')->getValue();
        $url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/' . $node->get('nid')->value);

        $output[$i]['title'] = $node->getTitle();
        $output[$i]['visuel_id'] = $field_media[0]['target_id'];
        $output[$i]['url'] = $url;
        $output[$i]['tid'] = $node->get('field_cat_agenda');
        $output[$i]['date_debut'] = $node->get('field_date_debut');
        $output[$i]['date_fin'] = $node->get('field_date_fin');
        $output[$i]['lieu'] = $node->get('field_lieu');
      }
    }

    return $output;
  }

  public function getAgendas(){
    $output = [];

    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'agenda');
    $query->condition('status', 1);
    $query->condition('field_no_front', 0);

    $date = new DrupalDateTime('today midnight');
    $date_min_format = $date->format(\Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface::DATETIME_STORAGE_FORMAT);
    $query->condition('field_date_fin', $date_min_format, '>=');

    $query->sort('field_date_debut', 'ASC');
    $query->pager(6);
    $agendas_ids = $query->execute();

    $agendas = Node::loadMultiple($agendas_ids);

    foreach ($agendas as $key => $agenda) {
      $field_media = $agenda->get('field_medias')->getValue();
      $url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/' . $agenda->get('nid')->value);

      $output[$key]['title'] = $agenda->getTitle();
      $output[$key]['visuel_id'] = $field_media[0]['target_id'];
      $output[$key]['url'] = $url;
      $output[$key]['tid'] = $agenda->get('field_cat_agenda');
      $output[$key]['date_debut'] = $agenda->get('field_date_debut');
      $output[$key]['date_fin'] = $agenda->get('field_date_fin');
      $output[$key]['jours'] = $agenda->get('field_infos_sup');
      $output[$key]['public'] = $agenda->get('field_public');
    }

    return $output;
  }

  public function getActus(){
    $output = [];

    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'actu');
    $query->condition('status', 1);
    $query->range(0,2);
    $query->sort('created', 'DESC');

    $actus_ids = $query->execute();

    $actus = Node::loadMultiple($actus_ids);

    foreach ($actus as $key => $actu){
      $field_media = $actu->get('field_medias')->getValue();
      $url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/' . $actu->get('nid')->value);

      $output[$key]['title'] = $actu->getTitle();
      $output[$key]['visuel_id'] = $field_media[0]['target_id'];
      $output[$key]['url'] = $url;
      $output[$key]['date'] = $actu->get('created');
    }

    return $output;
  }

}
