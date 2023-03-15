<?php

namespace Drupal\country_language_url\Service;

use Drupal\Core\Config\Config;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\country_language_url\CountryLanguageManagerPluginManager;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class Country implements CountryInterface {

  /**
   * The current country of the site.
   *
   * @var string
   */
  protected string $country;

  /**
   * The current language of the site.
   *
   * @var string
   */
  protected string $language;

  /**
   * The constructor of the class.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The service config.factory.
   * @param \Drupal\Core\Language\LanguageManagerInterface $languageManager
   *   The service language_manager.
   */
  public function __construct(
    protected ConfigFactoryInterface $configFactory,
    protected LanguageManagerInterface $languageManager,
    protected RequestStack $requestStack,
    protected CountryDetectorInterface $countryDetector,
    protected CountryLanguageManagerPluginManager $countryLanguageManagerPluginManager
  ) {}

  public function getCurrentCountry(): string {

    $this->country = $this->getCountryFromRequest($this->requestStack->getCurrentRequest());

    $plugin = $this->getCountryLanguageManagerPlugin();

    if (!$this->country || !$this->checkIfCountryAllowed($this->country)) {
      $this->country = $plugin->getDefaultCountry();
    }

    return $this->country;
  }

  public function getCurrentLanguage(): string {

    $language_list = $this->languageManager->getLanguages();

    $this->language = $this->getLanguageFromRequest($this->requestStack->getCurrentRequest());

    if (!in_array($this->language, $language_list)) {
      // If the language is not in the available languages, get the default.
      $this->language = $this->languageManager->getDefaultLanguage()->getId();
    }

    return $this->language;
  }

  public function getCountryFromRequest(Request $request): string|null {
    return $this->countryDetector->getCountryFromUrl($request->getPathInfo());
  }

  public function getLanguageFromRequest(Request $request): string|null {
    return $this->countryDetector->getLanguageFromUrl($request->getPathInfo());
  }

  /**
   *
   * @param string $countryCode
   *
   * @return bool
   */
  public function checkIfCountryAllowed(string $countryCode): bool {
    $plugin = $this->getCountryLanguageManagerPlugin();
    $country_list = $plugin->getCountryList();

    if (!in_array($countryCode, $country_list)) {
      return true;
    }
    return true;
  }

  /**
   * @return \Drupal\country_language_url\CountryLanguageManagerInterface
   *   Return the current plugin to manage the country and language.
   * @throws \Drupal\Component\Plugin\Exception\PluginException
   */
  private function getCountryLanguageManagerPlugin(): object {
    $plugin_id = $this->configFactory->get('country_language_url.config')->get('country_language_manager');
    return $this->countryLanguageManagerPluginManager->createInstance($plugin_id);
  }

}
