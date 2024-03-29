<?php

namespace Drupal\country_language_url\Plugin\CountryLanguageManager;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Locale\CountryManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\country_language_url\CountryLanguageManagerPluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Plugin implementation of the country_language_manager.
 *
 * @CountryLanguageManager(
 *   id = "default_country_manager",
 *   label = @Translation("Default country manager."),
 *   description = @Translation("Default country manager to load countries.")
 * )
 */
class DefaultCountryManager extends CountryLanguageManagerPluginBase implements ContainerFactoryPluginInterface {

  use StringTranslationTrait;

  /**
   * The service country_manager.
   *
   * @var \Drupal\Core\Locale\CountryManagerInterface
   */
  protected CountryManagerInterface $countryManager;

  /**
   * The service config.factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected ConfigFactoryInterface $configFactory;

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = new static($configuration, $plugin_id, $plugin_definition);
    $instance->countryManager = $container->get('country_manager');
    $instance->configFactory = $container->get('config.factory');
    return $instance;
  }

  /**
   * {@inheritDoc}
   */
  public function getCountryList(): array {
    return $this->countryManager->getList();
  }

  /**
   * {@inheritDoc}
   */
  public function getDefaultCountry(): string {
    return $this->configFactory->get('system.date')->get('country.default');
  }

  /**
   * {@inheritDoc}
   */
  public function getCurrentCountry(Request $request): string {
    return $this->configFactory->get('system.date')->get('country.default');
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(FormStateInterface $form_state) {
    // @todo remove this before version 1.0.0.
    $form['expiration'] = [
      '#type' => 'date',
      '#title' => $this->t('Content expiration'),
      '#default_value' => [
        'year' => 2020,
        'month' => 2,
        'day' => 15,
      ],
    ];

    return $form;
  }

}
