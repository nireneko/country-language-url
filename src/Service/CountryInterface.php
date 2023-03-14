<?php

namespace Drupal\country_language_url\Service;

use Symfony\Component\HttpFoundation\Request;

/**
 *
 */
interface CountryInterface {

  public function getCurrentCountry(): string;

  public function getCurrentLanguage(): string;

  public function getCountryFromRequest(Request $request): string|null;

  public function getLanguageFromRequest(Request $request): string|null;

  /**
   * @todo: Implement this method, must use the country manager plugins.
   *
   * @param string $countryCode
   *
   * @return bool
   */
  public function checkIfCountryAllowed(string $countryCode): bool;

}
