<?php

namespace Drupal\block_search\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'SearchBlock' block.
 *
 * @Block(
 *  id = "search_block",
 *  admin_label = @Translation("Search block"),
 * )
 */
class SearchBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $output = [];
    $output['#theme'] = 'block_search';
    $output['#attached'] = array(
      'library' => array(
        'block_search/fusejs',
        'block_search/custom_search',
      ),
    );

    return $output;
  }

}
