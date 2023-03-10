<?php

namespace Drupal\country_language_url;

/**
 * Interface for country_language_manager plugins.
 */
interface CountryLanguageManagerInterface {

  /**
   * Returns the translated plugin label.
   *
   * @return string
   *   The translated title.
   */
  public function label();

}
