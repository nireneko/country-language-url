<?php

namespace Drupal\country_language_url\Service;

use Symfony\Component\HttpFoundation\Request;

/**
 * The interface for the service country_language_url.country.
 */
interface CountryInterface {

  /**
   * This method must return the current country, could be the default
   * country or the country from geolocation or any other valid option.
   *
   * @return string
   *   Return the current country.
   */
  public function getCurrentCountry(): string;

  /**
   * This method must return the current language.
   *
   * @return string
   *   Return the current language.
   */
  public function getCurrentLanguage(): string;

  /**
   * Extract the country from the request URL.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request from where get the country.
   *
   * @return string|null
   *   The country of the request or null if there is no country in the request.
   */
  public function getCountryFromRequest(Request $request): string|null;

  /**
   * Extract the language from the request URL.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request from where get the language.
   *
   * @return string|null
   *   The language of the request or null if there is no language.
   */
  public function getLanguageFromRequest(Request $request): string|null;

  /**
   * Checks if the given country code is allowed to be used.
   *
   * @param string $countryCode
   *   The country code to check if is allowed.
   *
   * @return bool
   *   True if the country is in the available list, failse if not.
   */
  public function checkIfCountryAllowed(string $countryCode): bool;

}
