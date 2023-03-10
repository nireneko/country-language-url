<?php

namespace Drupal\country_language_url;

use Symfony\Component\HttpFoundation\Request;

/**
 * Interface for country_language_manager plugins.
 */
interface CountryLanguageManagerInterface {

  public function getCountryList(): array;

  public function getDefaultCountry(): string;

  public function getCurrentCountry(Request $request): string ;
}
