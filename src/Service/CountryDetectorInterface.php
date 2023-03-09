<?php

namespace Drupal\country_language_url\Service;


/**
 * The interface for the country and language detector.
 */
interface CountryDetectorInterface {

  /**
   * Prefix country-language pattern.
   *
   * @todo move this to a plugin.
   */
  const COUNTRY_LANGUAGE_PATTERN = "/^[a-z]{2}-[a-z]{2}$/";

  /**
   * Prefix language pattern.
   *
   * @todo move this to a plugin.
   */
  const LANGUAGE_PATTERN = "/^[a-z]{2}$/";

  /**
   * Return the country from the url.
   *
   * @param string $url
   *   The url from where get the country.
   *
   * @return string|null
   *   The country from the url or null if is missing.
   */
  public function getCountryFromUrl(string $url): string|null;

  /**
   * Return the language from the url.
   *
   * @param string $url
   *   The url from where get the language.
   *
   * @return string|null
   *   The language from the url or null if is missing.
   */
  public function getLanguageFromUrl(string $url): string|null;

  /**
   * Check if the current string has a country.
   *
   * @param string $string
   *   The string where check if has the country.
   *
   * @return bool
   *   True if the country is in the string.
   */
  public function isCountryInString(string $string): bool;

  /**
   * Check if the current string has a language.
   *
   * @param string $string
   *   The string where check if has the language.
   *
   * @return bool
   *   True if the language is in the string.
   */
  public function isOnlyLanguageInString(string $string): bool;

}
