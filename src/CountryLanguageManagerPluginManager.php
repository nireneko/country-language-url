<?php

namespace Drupal\country_language_url;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * CountryLanguageManager plugin manager.
 */
class CountryLanguageManagerPluginManager extends DefaultPluginManager {

  /**
   * Constructs CountryLanguageManagerPluginManager object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct(
      'Plugin/CountryLanguageManager',
      $namespaces,
      $module_handler,
      'Drupal\country_language_url\CountryLanguageManagerInterface',
      'Drupal\country_language_url\Annotation\CountryLanguageManager'
    );
    $this->alterInfo('country_language_manager_info');
    $this->setCacheBackend($cache_backend, 'country_language_manager_plugins');
  }

}
