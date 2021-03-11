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
    $output['results']['#var']['en_avant'] = $this->getEnAvant();
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

    //Musée
    $wanted_cat_ids = array();
    if (\Drupal::request()->query->get('musee')) {
      $query_cat = \Drupal::request()->query->get('musee');
      foreach ($query_cat as $filtre_id) {
        $wanted_cat_ids[] = $filtre_id;
      }
      $query->condition('field_musee', $wanted_cat_ids, 'IN');
    }

    //categories
    $wanted_cat_ids = array();
    if (\Drupal::request()->query->get('categorie')) {
      $query_cat = \Drupal::request()->query->get('categorie');
      foreach ($query_cat as $filtre_id) {
        $wanted_cat_ids[] = $filtre_id;
      }
      $query->condition('field_cat_agenda', $wanted_cat_ids, 'IN');
    }

    //Public
    $wanted_cat_ids = array();
    if (\Drupal::request()->query->get('public')) {
      $query_cat = \Drupal::request()->query->get('public');
      foreach ($query_cat as $filtre_id) {
        $wanted_cat_ids[] = $filtre_id;
      }
      $query->condition('field_public', $wanted_cat_ids, 'IN');
    }

    $query->sort('field_date_debut', 'ASC');
    $query->pager(12);
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

  public function getEnAvant(){
    $output = [];
    $config = \Drupal::config('site_config.config');

    for ($i = 1; $i <= 3; $i++) {
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
}
