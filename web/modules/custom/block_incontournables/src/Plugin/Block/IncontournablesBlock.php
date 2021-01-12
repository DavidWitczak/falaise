<?php

namespace Drupal\block_incontournables\Plugin\Block;

use Drupal\Component\Utility\Unicode;
use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;

/**
 * Provides a 'IncontournablesBlock' block.
 *
 * @Block(
 *  id = "incontournables_block",
 *  admin_label = @Translation("Incontournables block"),
 * )
 */
class IncontournablesBlock extends BlockBase {

  public $config = 'site_config.config';

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();
    $type = $config['type_object'];
    $build = [];
    $build['#theme'] = 'incontournables_block';
    $build['#var']['objets'] = $this->getObjects($type);

    return $build;
  }

  public function getObjects($type){
    $config = \Drupal::config($this->config);

    $output = [];

    for ($i = 1; $i <= 8; $i++) {
      $node = Node::load($config->get($type . '_' . $i));
      if($node) {
        $field_media = $node->get('field_medias')->getValue();
        $url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/' . $node->get('nid')->value);

        $tid = $node->get('field_theme_collection')->getValue();
        $term = Term::load($tid[0]['target_id']);
        $name = $term->getName();
        $output[$i]['theme'] = $name;

        $output[$i]['title'] = $node->getTitle();
        $output[$i]['visuel_id'] = $field_media[0]['target_id'];
        $output[$i]['url'] = $url;
      }
    }

    return $output;
  }

}
