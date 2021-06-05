<?php

namespace Drupal\block_profs\Plugin\Block;

use Drupal\Component\Utility\Unicode;
use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Provides a 'ProfsBlock' block.
 *
 * @Block(
 *  id = "profs_block",
 *  admin_label = @Translation("Profs block"),
 * )
 */
class ProfsBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $output = [];

    $route_match = \Drupal::routeMatch();

    $current_node = $route_match->getParameter('node');
    $nid = $current_node->id();

    $output = $this->getProfs($nid);

    return $output;

  }

  /**
   * @param array $nid
   *
   * @return array
   */
  public function getProfs($nid){
    $output = [];
    $output['#theme'] = 'profs_block';

    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'enseignants');
    $query->condition('status', 1);

    $condition_or = $query->orConditionGroup();
    $condition_or->condition('field_disciplines_asso', $nid);
    $condition_or->condition('field_pratiques_collectives_asso', $nid);

    $query->condition($condition_or);

    $query->sort('title', 'ASC');

    $profs_ids = $query->execute();

    $profs = Node::loadMultiple($profs_ids);

    foreach ($profs as $key => $node) {
      $url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/' . $node->get('nid')->value);
      $field_media = $node->get('field_medias')->getValue();
      $chapo = Unicode::truncate(strip_tags($node->get('field_chapo')->value), 250, TRUE, TRUE);

      $output['#var']['prof'][$key]['visuel_id'] = $field_media[0]['target_id'];
      $output['#var']['prof'][$key]['url'] = $url;
      $output['#var']['prof'][$key]['title'] = $node->getTitle();
      $output['#var']['prof'][$key]['prenom'] = $node->get('field_prenom');
      $output['#var']['prof'][$key]['chapo'] = $chapo;
    }

    return $output;
  }

}
