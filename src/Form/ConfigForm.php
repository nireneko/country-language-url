<?php

namespace Drupal\country_language_url\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a Country language url form.
 */
class ConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'country_language_url_config';
  }

  protected function getEditableConfigNames() {
    return [
      'country_langauge_url.config',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('country_langauge_url.config');

    $form['default_country'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Default country'),
      '#default_value' => $config->get('default_country'),
      '#size' => 60,
      '#maxlength' => 2,
      '#pattern' => '[a-z]+',
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('country_langauge_url.config')
      ->set('default_country', $form_state->getValue('default_country'))
      ->save();
    $this->messenger()->addStatus($this->t('The configuration has been saved.'));
  }



}
