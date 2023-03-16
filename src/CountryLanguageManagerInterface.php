<?php

namespace Drupal\country_language_url;

use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface for country_language_manager plugins.
 */
interface CountryLanguageManagerInterface {

  /**
   * Return one array with the arrays of countries, country code
   * like array key and country name like value of that key.
   *
   * This could return the array from hardcoded list, external
   * service, taxonomy vocabulary, node list...
   *
   * @return array
   *   The array with the list of countries.
   */
  public function getCountryList(): array;

  /**
   * Return the default country of the site, could be from configuration
   * or geolocation for example.
   *
   * @return string
   *   The default country.
   */
  public function getDefaultCountry(): string;

  /**
   * Return the current country.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return string
   *   The current country.
   *
   */
  public function getCurrentCountry(Request $request): string;

  /**
   * @todo document this method.
   */
  public function buildForm(FormStateInterface $form_state): array;

  /**
   * @todo document this method.
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void;

  /**
   * @todo document this method.
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void;

}
