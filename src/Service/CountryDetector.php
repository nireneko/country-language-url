<?php

namespace Drupal\country_language_url\Service;


use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CountryDetector implements CountryDetectorInterface {

  /**
   *
   */
  public function __construct(
    protected ConfigFactoryInterface $configFactory
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getCountryFromUrl(string $url): string {
    $prefix = $this->getUrlPrefix($url);

    // @todo: Change this to the default country of the site.
    $country = $this->configFactory->get('country_language_url.config')->get('default_country');
    if ($this->isCountryInString($prefix)) {
      // Si entra aqui el path contiene 'es-es'
      $country = substr($prefix, 0, 2);
    }

    return $country;
  }

  /**
   * {@inheritdoc}
   */
  public function getLanguageFromUrl(string $url): string {
    $prefix = $this->getUrlPrefix($url);
    // @todo: Change this to the default langauge of the site.
    $language = 'es';
    if ($this->isCountryInString($prefix)) {
      // @todo: translete this.
      // Si entra aqui el path contiene 'es-es'
      $language = substr($prefix, 3, 2);
    }

    if ($this->isOnlyLanguageInString($prefix)) {
      // @todo: translete this.
      // Si entra aqui el path contiene 'es'
      $language = $prefix;
    }

    return $language;
  }

  /**
   * {@inheritdoc}
   */
  public function isCountryInString(string $string): bool {
   return preg_match( '/^[a-z]{2}-[a-z]{2}$/',$string);
  }

  /**
   * {@inheritdoc}
   */
  public function isOnlyLanguageInString(string $string): bool {
    return preg_match( '/^[a-z]{2}$/',$string);
  }

  /**
   * @param string $url
   *
   * @return string
   */
  private function getUrlPrefix(string $url): string {
    $request_path = urldecode(trim($url, '/'));
    $path_args = explode('/', $request_path);
    return array_shift($path_args);
  }

}
