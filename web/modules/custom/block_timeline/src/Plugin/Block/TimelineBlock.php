<?php

namespace Drupal\block_timeline\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Provides a 'TimelineBlock' block.
 *
 * @Block(
 *  id = "timeline_block",
 *  admin_label = @Translation("Timeline block"),
 * )
 */
class TimelineBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $route_match = \Drupal::routeMatch();

    if ($route_match->getRouteName() == 'entity.node.canonical') {
      $current_node = $route_match->getParameter('node');
      $nid = $current_node->id();
    }
    else {
      $nid = NULL;
    }

    $output = $this->getTimeline($nid);

    return $output;
  }

  public function getTimeline($nid = NULL) {
    $node = Node::load($nid);
    $target_id_theme = $node->get('field_theme_collection')->getValue();
    $theme = $target_id_theme[0]['target_id'];

    $output = [];
    $output['#theme'] = 'block_timeline';
    $output['#attached'] = array(
      'library' => array(
        'block_timeline/timelinejs',
      ),
    );
    $output['#var']['theme_tl'] = $theme;
    $output['#var']['nid'] = $nid;

    return $output;
  }

}
