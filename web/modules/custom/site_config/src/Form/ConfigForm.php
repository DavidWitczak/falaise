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

    // Les incontournables
    $form['incontournable'] = [
      '#type' => 'details',
      '#title' => 'Collections Incontournable',
      '#open' => FALSE,
    ];

    for ($i = 1; $i <= 8; $i++) {

      $form['incontournable']['object_' . $i] = [
        '#type' => 'entity_autocomplete',
        '#title' => 'Object n°' . $i,
        '#target_type' => 'node',
        '#selection_handler' => 'default',
        '#selection_settings' => [
          'target_bundles' => array('objet'),
        ],
        '#default_value' => Node::load($config->get('object_' . $i)),
      ];
    }

    // Agenda - Mise en avant
    $form['agenda'] = [
      '#type' => 'details',
      '#title' => 'Agenda - Mise en avant',
      '#open' => FALSE,
    ];

    for ($i = 1; $i <= 3; $i++) {

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

    // Collection enfants
    $form['collection_enfants'] = [
      '#type' => 'details',
      '#title' => 'Collections Enfants',
      '#open' => FALSE,
    ];

    for ($i = 1; $i <= 8; $i++) {

      $form['collection_enfants']['object_enfant_' . $i] = [
        '#type' => 'entity_autocomplete',
        '#title' => 'Object n°' . $i,
        '#target_type' => 'node',
        '#selection_handler' => 'default',
        '#selection_settings' => [
          'target_bundles' => array('objet'),
        ],
        '#default_value' => Node::load($config->get('object_enfant_' . $i)),
      ];
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    //RS
    $this->configFactory->getEditable('site_config.config')->set('facebook', $form_state->getValue('facebook'));

    // Les incontournables
    for ($i = 1; $i <= 8; $i++) {
      $this->configFactory->getEditable('site_config.config')->set('object_' . $i, $form_state->getValue('object_' . $i));
    }

    // Collection enfants
    for ($i = 1; $i <= 8; $i++) {
      $this->configFactory->getEditable('site_config.config')->set('object_enfant_' . $i, $form_state->getValue('object_enfant_' . $i));
    }

    // Mise en avant agenda
    for ($i = 1; $i <= 3; $i++) {
      $this->configFactory->getEditable('site_config.config')->set('event_' . $i, $form_state->getValue('event_' . $i));
    }

    //alerts
    $this->configFactory->getEditable('site_config.config')->set('alert_1_txt', $form_state->getValue('alert_1_txt'));
    $this->configFactory->getEditable('site_config.config')->set('alert_1_url', $form_state->getValue('alert_1_url'));
    $this->configFactory->getEditable('site_config.config')->set('alert_2_txt', $form_state->getValue('alert_2_txt'));
    $this->configFactory->getEditable('site_config.config')->set('alert_2_url', $form_state->getValue('alert_2_url'));

    $this->configFactory->getEditable('site_config.config')->save();
  }

}
