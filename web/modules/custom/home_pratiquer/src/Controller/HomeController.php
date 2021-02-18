<?php

namespace Drupal\home_pratiquer\Controller;

use Drupal\Core\Controller\ControllerBase;

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
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: home')
    ];
  }

}
