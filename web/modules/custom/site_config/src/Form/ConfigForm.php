<?php

namespace Drupal\site_config\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

/**
 * Class ConfigForm.
 */
class ConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'site_config.config',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('site_config.config');

    $form['rs'] = [
      '#type' => 'details',
      '#title' => 'Réseaux sociaux',
      '#open' => FALSE,
    ];

    $form['rs']['facebook'] = [
      '#type' => 'url',
      '#title' => $this->t('Facebook'),
      '#default_value' => $config->get('facebook'),
    ];

    // Astuces et alertes
    $form['alert'] = [
      '#type' => 'details',
      '#title' => 'Astuces et alertes',
      '#open' => FALSE,
    ];

    $form['alert']['alert_1_txt'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Texte alerte n°1'),
      '#default_value' => $config->get('alert_1_txt'),
    ];

    $form['alert']['alert_1_url'] = [
      '#type' => 'url',
      '#title' => $this->t('Lien alerte n°1'),
      '#default_value' => $config->get('alert_1_url'),
      '#field_suffix' => '<br/><br/><br/><br/>'
    ];

    $form['alert']['alert_2_txt'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Texte alerte n°2'),
      '#default_value' => $config->get('alert_2_txt'),
    ];

    $form['alert']['alert_2_url'] = [
      '#type' => 'url',
      '#title' => $this->t('Lien alerte n°2'),
      '#default_value' => $config->get('alert_2_url'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('site_config.config')->set('facebook', $form_state->getValue('facebook'));

    //alerts
    $this->config('site_config.config')->set('alert_1_txt', $form_state->getValue('alert_1_txt'));
    $this->config('site_config.config')->set('alert_1_url', $form_state->getValue('alert_1_url'));
    $this->config('site_config.config')->set('alert_2_txt', $form_state->getValue('alert_2_txt'));
    $this->config('site_config.config')->set('alert_2_url', $form_state->getValue('alert_2_url'));

    $this->config('site_config.config')->save();
  }

}
