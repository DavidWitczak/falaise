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


    // Agenda - Mise en avant
    $form['agenda'] = [
      '#type' => 'details',
      '#title' => 'Agenda - Mise en avant',
      '#open' => FALSE,
    ];

    for ($i = 1; $i <= 2; $i++) {

      $form['agenda']['event_' . $i] = [
        '#type' => 'entity_autocomplete',
        '#title' => 'Evénement n°' . $i,
        '#target_type' => 'node',
        '#selection_handler' => 'default',
        '#selection_settings' => [
          'target_bundles' => array('exposition', 'agenda'),
        ],
        '#default_value' => Node::load($config->get('event_' . $i)),
      ];
    }

    //Texte home disciplines
    $form['presentation'] = [
      '#type' => 'details',
      '#title' => 'Presentations - Se former / pratiquer',
      '#open' => FALSE,
    ];

    $form['presentation']['se_former'] = array(
      '#type' => 'text_format',
      '#title' => 'Chapô - Se former',
      '#default_value' => $config->get('se_former')['value'],
      '#format' => 'ckeditor',
    );

    $form['presentation']['se_former_suite'] = array(
      '#type' => 'text_format',
      '#title' => 'Présentation - Se former',
      '#default_value' => $config->get('se_former_suite')['value'],
      '#format' => 'ckeditor',
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    //RS
    $this->configFactory->getEditable('site_config.config')->set('facebook', $form_state->getValue('facebook'));

    // Mise en avant agenda
    for ($i = 1; $i <= 3; $i++) {
      $this->configFactory->getEditable('site_config.config')->set('event_' . $i, $form_state->getValue('event_' . $i));
    }

    //alerts
    $this->configFactory->getEditable('site_config.config')->set('alert_1_txt', $form_state->getValue('alert_1_txt'));
    $this->configFactory->getEditable('site_config.config')->set('alert_1_url', $form_state->getValue('alert_1_url'));
    $this->configFactory->getEditable('site_config.config')->set('alert_2_txt', $form_state->getValue('alert_2_txt'));
    $this->configFactory->getEditable('site_config.config')->set('alert_2_url', $form_state->getValue('alert_2_url'));

    // Se former
    $this->configFactory->getEditable('site_config.config')->set('se_former', $form_state->getValue('se_former'));
    $this->configFactory->getEditable('site_config.config')->set('se_former_suite', $form_state->getValue('se_former_suite'));

    $this->configFactory->getEditable('site_config.config')->save();
  }

}
