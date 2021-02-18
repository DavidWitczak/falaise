<?php

namespace Drupal\block_discipline_assoc\Plugin\Block;

use Drupal\Component\Utility\Unicode;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\node\Entity\Node;

/**
 * Provides a 'DisciplineBlock' block.
 *
 * @Block(
 *  id = "discipline_block",
 *  admin_label = @Translation("Discipline block"),
 * )
 */
class DisciplineBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $route_match = \Drupal::routeMatch();

    $current_node = $route_match->getParameter('node');
    $nid = $current_node->id();

    return $this->getDiscipline($nid);
  }

  public function getDiscipline($nid) {
    $output = [];
    $output['#theme'] = 'discipline_block';

    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'agenda');
    $query->condition('status', 1);
    $query->condition('field_disciplines_asso', $nid);

    $date = new DrupalDateTime('today midnight');
    $date_min_format = $date->format(\Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface::DATETIME_STORAGE_FORMAT);
    $query->condition('field_date_fin', $date_min_format, '>=');

    $agendas_ids = $query->execute();

    $agendas = Node::loadMultiple($agendas_ids);

    foreach ($agendas as $key => $node) {
      $url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/' . $node->get('nid')->value);
      $field_media = $node->get('field_medias')->getValue();

      $output['#var']['agenda'][$key]['visuel_id'] = $field_media[0]['target_id'];
      $output['#var']['agenda'][$key]['url'] = $url;
      $output['#var']['agenda'][$key]['title'] = $node->getTitle();
      $output['#var']['agenda'][$key]['tid'] = $node->get('field_cat_agenda');
      $output['#var']['agenda'][$key]['date_debut'] = $node->get('field_date_debut');
      $output['#var']['agenda'][$key]['date_fin'] = $node->get('field_date_fin');
      $output['#var']['agenda'][$key]['lieu'] = $node->get('field_lieu');
    }

    return $output;
  }

}
