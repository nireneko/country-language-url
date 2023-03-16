<?php

namespace Drupal\country_language_url\Service;

use Symfony\Component\HttpFoundation\Request;

/**
 * Interface for the country_language_url.country_redirect service.
 */
interface CountryRedirectInterface {

  /**
   * Add country to the url of the current request.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The current request.
   *
   * @return string
   *   Url with the country added if is needed.
   */
  public function addCountryToUrlFromRequest(Request $request): string;

}
