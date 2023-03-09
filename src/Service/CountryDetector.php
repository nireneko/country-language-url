<?php

namespace Drupal\country_language_url\Service;


/**
 * Class to get the country and language from Url.
 */
class CountryDetector implements CountryDetectorInterface {

  /**
   * {@inheritdoc}
   */
  public function getCountryFromUrl(string $url): string|null {
    $prefix = $this->getUrlPrefix($url);

    $country = null;

    if ($this->isCountryInString($prefix)) {
      // if enters here the prefix has this format 'es-es'
      $country = substr($prefix, 0, 2);
    }

    return $country;
  }

  /**
   * {@inheritdoc}
   */
  public function getLanguageFromUrl(string $url): string|null {
    $prefix = $this->getUrlPrefix($url);

    $language = null;

    if ($this->isCountryInString($prefix)) {
      // if enters here the prefix has this format 'es-es'
      $language = substr($prefix, 3, 2);
    }

    if ($this->isOnlyLanguageInString($prefix)) {
      // if enters here the prefix has this format 'es'
      $language = $prefix;
    }

    return $language;
  }

  /**
   * {@inheritdoc}
   */
  public function isCountryInString(string $string): bool {
   return preg_match( self::COUNTRY_LANGUAGE_PATTERN, $string);
  }

  /**
   * {@inheritdoc}
   */
  public function isOnlyLanguageInString(string $string): bool {
    return preg_match( self::LANGUAGE_PATTERN, $string);
  }

  /**
   * Get the prefix from the url.
   *
   * @param string $url
   *   The url from get the prefix.
   *
   * @return string
   *   The prefix of the url.
   */
  private function getUrlPrefix(string $url): string {
    $request_path = urldecode(trim($url, '/'));
    $path_args = explode('/', $request_path);
    return array_shift($path_args);
  }

}
