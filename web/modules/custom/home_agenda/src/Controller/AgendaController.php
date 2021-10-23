<?php

namespace Drupal\home_agenda\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;

/**
 * Class AgendaController.
 */
class AgendaController extends ControllerBase {

  /**
   * Home.
   *
   * @return array
   *   Return Hello string.
   */
  public function home() {
    $output = [];
    $output['results']['#theme'] = 'home_agenda';
    $output['results']['#var']['agenda'] = $this->getAgendas();
    $output['pager'] = ['#type' => 'pager'];

    return $output;
  }

  public function getAgendas(){
    $output = [];

    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'agenda');
    $query->condition('status', 1);

    $condition_and = $query->andConditionGroup();
    //champ date "DU"
    if (\Drupal::request()->query->get('date')) {
      $date_min = \Drupal::request()->query->get('date');
      $date = new DrupalDateTime($date_min . '-1 day');
      $date_min_format = $date->format(\Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface::DATETIME_STORAGE_FORMAT);
    }
    else {
      $date = new DrupalDateTime('today midnight');
      $date_min_format = $date->format(\Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface::DATETIME_STORAGE_FORMAT);
    }
    $condition_and->condition('field_date_fin', $date_min_format, '>=');

    //champ date "AU"
    if (\Drupal::request()->query->get('au')) {
      $date_max = \Drupal::request()->query->get('au');
      $date = new DrupalDateTime($date_max);
      $date_max_format = $date->format(\Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface::DATETIME_STORAGE_FORMAT);
      $condition_and->condition('field_date_debut', $date_max_format, '<=');
    }

    $query->condition($condition_and);

    //categories
    if (\Drupal::request()->query->get('categorie')) {
      $query_cat = \Drupal::request()->query->get('categorie');
      $query->condition('field_cat_agenda', $query_cat);
    }

    $query->sort('field_date_debut', 'ASC');
    $query->pager(12);
    $agendas_ids = $query->execute();

    $agendas = Node::loadMultiple($agendas_ids);

    foreach ($agendas as $key => $agenda) {
      $field_media = $agenda->get('field_medias')->getValue();
      $url = \Drupal::service('path_alias.manager')->getAliasByPath('/node/' . $agenda->get('nid')->value);

      $output[$key]['title'] = $agenda->getTitle();
      $output[$key]['visuel_id'] = $field_media[0]['target_id'];
      $output[$key]['url'] = $url;
      $output[$key]['tid'] = $agenda->get('field_cat_agenda');
      $output[$key]['tid_event'] = $agenda->get('field_evenement');
      $output[$key]['date_debut'] = $agenda->get('field_date_debut');
      $output[$key]['date_fin'] = $agenda->get('field_date_fin');
      $output[$key]['jours'] = $agenda->get('field_infos_sup');
      $output[$key]['public'] = $agenda->get('field_public');
      $output[$key]['lieu'] = $agenda->get('field_lieu');
    }

    return $output;
  }

}
