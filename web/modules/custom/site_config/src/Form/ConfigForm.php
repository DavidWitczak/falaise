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

    $form['rs']['instagram'] = [
      '#type' => 'url',
      '#title' => $this->t('Instagram'),
      '#default_value' => $config->get('instagram'),
    ];

    // Agenda - Mise en avant
    $form['home'] = [
      '#type' => 'details',
      '#title' => 'Accueil',
      '#open' => FALSE,
    ];

    $form['home']['image'] = [
      '#type' => 'item',
      '#markup' => '<a href="/media/936/edit?destination=/admin/config/site_config/config">Modifier la bannière</a>',
    ];

    $form['home']['banniere'] = [
      '#type' => 'entity_autocomplete',
      '#title' => 'Bannière accueil',
      '#target_type' => 'node',
      '#selection_handler' => 'default',
      '#selection_settings' => [
        'target_bundles' => ['actu', 'agenda', 'page_base'],
      ],
      '#default_value' => Node::load($config->get('banniere')),
    ];

    // Agenda - Mise en avant
    $form['saison'] = [
      '#type' => 'details',
      '#title' => 'PDF Saison en court',
      '#open' => FALSE,
    ];

    $form['saison']['pdf'] = [
      '#type' => 'item',
      '#markup' => '<a href="/media/841/edit">Modifier le PDF de la saison en court</a>',
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
      '#field_suffix' => '<br/><br/><br/><br/>',
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
          'target_bundles' => ['exposition', 'agenda'],
        ],
        '#default_value' => Node::load($config->get('event_' . $i)),
      ];
    }

    //Texte home agenda
    $form['presentation_agenda'] = [
      '#type' => 'details',
      '#title' => 'Presentations - Agenda',
      '#open' => FALSE,
    ];

    $form['presentation_agenda']['agenda'] = [
      '#type' => 'text_format',
      '#title' => 'Chapô - Agenda',
      '#default_value' => $config->get('agenda')['value'],
      '#format' => 'ckeditor',
    ];

    $form['presentation_agenda']['agenda_suite'] = [
      '#type' => 'text_format',
      '#title' => 'Présentation - Agenda',
      '#default_value' => $config->get('agenda_suite')['value'],
      '#format' => 'ckeditor',
    ];

    //Infos pratiques
    $form['infos'] = [
      '#type' => 'details',
      '#title' => 'Infos pratiques',
      '#open' => FALSE,
    ];

    $form['infos']['horaires'] = [
      '#type' => 'text_format',
      '#title' => 'Horaires (Pied de page)',
      '#default_value' => $config->get('horaires')['value'],
      '#format' => 'ckeditor',
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    //RS
    $this->configFactory->getEditable('site_config.config')
      ->set('facebook', $form_state->getValue('facebook'));
    $this->configFactory->getEditable('site_config.config')
      ->set('instagram', $form_state->getValue('instagram'));

    // Mise en avant agenda
    for ($i = 1; $i <= 3; $i++) {
      $this->configFactory->getEditable('site_config.config')
        ->set('event_' . $i, $form_state->getValue('event_' . $i));
    }

    // texte agenda
    $this->configFactory->getEditable('site_config.config')
      ->set('agenda', $form_state->getValue('agenda'));
    $this->configFactory->getEditable('site_config.config')
      ->set('agenda_suite', $form_state->getValue('agenda_suite'));

    //alerts
    $this->configFactory->getEditable('site_config.config')
      ->set('alert_1_txt', $form_state->getValue('alert_1_txt'));
    $this->configFactory->getEditable('site_config.config')
      ->set('alert_1_url', $form_state->getValue('alert_1_url'));
    $this->configFactory->getEditable('site_config.config')
      ->set('alert_2_txt', $form_state->getValue('alert_2_txt'));
    $this->configFactory->getEditable('site_config.config')
      ->set('alert_2_url', $form_state->getValue('alert_2_url'));

    //Horaires
    $this->configFactory->getEditable('site_config.config')
      ->set('horaires', $form_state->getValue('horaires'));

    //Accueil
    $this->configFactory->getEditable('site_config.config')
      ->set('banniere', $form_state->getValue('banniere'));

    $this->configFactory->getEditable('site_config.config')->save();
  }

}
