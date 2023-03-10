<?php

namespace Drupal\country_language_url;

use Drupal\Component\Plugin\PluginBase;

/**
 * Base class for country_language_manager plugins.
 */
abstract class CountryLanguageManagerPluginBase extends PluginBase implements CountryLanguageManagerInterface {

  /**
   * {@inheritdoc}
   */
  public function label() {
    // Cast the label to a string since it is a TranslatableMarkup object.
    return (string) $this->pluginDefinition['label'];
  }

}
