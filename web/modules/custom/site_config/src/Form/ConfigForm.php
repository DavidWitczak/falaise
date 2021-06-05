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

    $form['presentation']['ban_1'] = [
      '#type' => 'item',
      '#title' => 'Bannière se former',
      '#markup' => 'Modifier la bannière se former : <a href="/media/750/edit">Modifier</a><br/><br/><hr/><br/>',
    ];

    $form['presentation']['ban_2'] = [
      '#type' => 'item',
      '#title' => 'Bannière se former',
      '#markup' => 'Modifier la bannière pratiquer : <a href="/media/749/edit">Modifier</a><br/><br/><hr/><br/>',
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

    $form['presentation']['pratiquer'] = array(
      '#type' => 'text_format',
      '#title' => 'Chapô - Pratiquer',
      '#default_value' => $config->get('pratiquer')['value'],
      '#format' => 'ckeditor',
    );

    $form['presentation']['pratiquer_suite'] = array(
      '#type' => 'text_format',
      '#title' => 'Présentation - Pratiquer',
      '#default_value' => $config->get('pratiquer_suite')['value'],
      '#format' => 'ckeditor',
    );

    //Texte home agenda
    $form['presentation_agenda'] = [
      '#type' => 'details',
      '#title' => 'Presentations - Agenda',
      '#open' => FALSE,
    ];

    $form['presentation_agenda']['agenda'] = array(
      '#type' => 'text_format',
      '#title' => 'Chapô - Agenda',
      '#default_value' => $config->get('agenda')['value'],
      '#format' => 'ckeditor',
    );

    $form['presentation_agenda']['agenda_suite'] = array(
      '#type' => 'text_format',
      '#title' => 'Présentation - Agenda',
      '#default_value' => $config->get('agenda_suite')['value'],
      '#format' => 'ckeditor',
    );

    //Texte home atelier
    $form['presentation_atelier'] = [
      '#type' => 'details',
      '#title' => 'Presentations - Ateliers',
      '#open' => FALSE,
    ];

    $form['presentation_atelier']['atelier'] = array(
      '#type' => 'text_format',
      '#title' => 'Présentation - ateliers',
      '#default_value' => $config->get('atelier')['value'],
      '#format' => 'ckeditor',
    );

    // Discipline - Accueil
    $form['discipline'] = [
      '#type' => 'details',
      '#title' => 'Accueil',
      '#open' => FALSE,
    ];

    $form['discipline']['help'] = [
      '#type' => 'item',
      '#title' => 'Bannière accueil',
      '#markup' => 'Modifier la bannière de la page d\'accueil : <a href="/media/754/edit">Modifier</a><br/><br/><hr/><br/>',
    ];

    for ($i = 1; $i <= 12; $i++) {

      $form['discipline']['discipline_' . $i] = [
        '#type' => 'entity_autocomplete',
        '#title' => 'Discipline n°' . $i,
        '#target_type' => 'node',
        '#selection_handler' => 'default',
        '#selection_settings' => [
          'target_bundles' => array('pratiquer', 'se_former'),
        ],
        '#default_value' => Node::load($config->get('discipline_' . $i)),
      ];
    }

    //Infos pratiques
    $form['infos'] = [
      '#type' => 'details',
      '#title' => 'Infos pratiques',
      '#open' => FALSE,
    ];

    $form['infos']['horaires'] = array(
      '#type' => 'text_format',
      '#title' => 'Horaires (Pied de page)',
      '#default_value' => $config->get('horaires')['value'],
      '#format' => 'ckeditor',
    );

    //Texte inscription & tarifs
    $form['inscription'] = [
      '#type' => 'details',
      '#title' => 'Inscription & tarifs',
      '#open' => FALSE,
    ];

    $form['inscription']['inscription_tarifs'] = array(
      '#type' => 'text_format',
      '#title' => 'Inscription & tarifs',
      '#default_value' => $config->get('inscription_tarifs')['value'],
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

    // texte agenda
    $this->configFactory->getEditable('site_config.config')->set('agenda', $form_state->getValue('agenda'));
    $this->configFactory->getEditable('site_config.config')->set('agenda_suite', $form_state->getValue('agenda_suite'));

    //alerts
    $this->configFactory->getEditable('site_config.config')->set('alert_1_txt', $form_state->getValue('alert_1_txt'));
    $this->configFactory->getEditable('site_config.config')->set('alert_1_url', $form_state->getValue('alert_1_url'));
    $this->configFactory->getEditable('site_config.config')->set('alert_2_txt', $form_state->getValue('alert_2_txt'));
    $this->configFactory->getEditable('site_config.config')->set('alert_2_url', $form_state->getValue('alert_2_url'));

    // Se former
    $this->configFactory->getEditable('site_config.config')->set('se_former', $form_state->getValue('se_former'));
    $this->configFactory->getEditable('site_config.config')->set('se_former_suite', $form_state->getValue('se_former_suite'));

    // pratiquer
    $this->configFactory->getEditable('site_config.config')->set('pratiquer', $form_state->getValue('pratiquer'));
    $this->configFactory->getEditable('site_config.config')->set('pratiquer_suite', $form_state->getValue('pratiquer_suite'));

    //Ateliers
    $this->configFactory->getEditable('site_config.config')->set('atelier', $form_state->getValue('atelier'));

    //Horaires
    $this->configFactory->getEditable('site_config.config')->set('horaires', $form_state->getValue('horaires'));

    // Discipline accueil
    for ($i = 1; $i <= 12; $i++) {
      $this->configFactory->getEditable('site_config.config')->set('discipline_' . $i, $form_state->getValue('discipline_' . $i));
    }

    //inscription & tarifs
    $this->configFactory->getEditable('site_config.config')->set('inscription_tarifs', $form_state->getValue('inscription_tarifs'));

    $this->configFactory->getEditable('site_config.config')->save();
  }

}
