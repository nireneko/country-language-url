<?php

namespace Drupal\country_language_url;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Base class for country_language_manager plugins.
 */
abstract class CountryLanguageManagerPluginBase extends PluginBase implements CountryLanguageManagerInterface {

  /**
   * {@inheritdoc}
   */
  public function buildForm(FormStateInterface $form_state): array {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {}

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {}

}
