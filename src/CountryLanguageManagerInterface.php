<?php

namespace Drupal\country_language_url;

use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface for country_language_manager plugins.
 */
interface CountryLanguageManagerInterface {

  public function getCountryList(): array;

  public function getDefaultCountry(): string;

  public function getCurrentCountry(Request $request): string;

  /**
   * {@inheritdoc}
   */
  public function buildForm(FormStateInterface $form_state);

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state);

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state);

}
