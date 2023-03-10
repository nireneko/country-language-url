<?php

namespace Drupal\country_language_url\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines country_language_manager annotation object.
 *
 * @Annotation
 */
class CountryLanguageManager extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The human-readable name of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $title;

  /**
   * The description of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $description;

}
