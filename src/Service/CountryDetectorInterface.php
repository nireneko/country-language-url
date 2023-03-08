<?php

namespace Drupal\country_language_url\Service;


/**
 * @todo: document the class.
 */
interface CountryDetectorInterface {

  /**
   * @param string $url
   *
   * @return string
   */
  public function getCountryFromUrl(string $url): string;

  /**
   * @param string $url
   *
   * @return string
   */
  public function getLanguageFromUrl(string $url): string;

  /**
   * @param string $string
   *
   * @return bool
   */
  public function isCountryInString(string $string): bool;

  /**
   * @param string $string
   *
   * @return bool
   */
  public function isOnlyLanguageInString(string $string): bool;

}
