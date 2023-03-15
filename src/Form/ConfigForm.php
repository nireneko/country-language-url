<?php

namespace Drupal\country_language_url\Form;

use Drupal\Core\Batch\BatchBuilder;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\country_language_url\CountryLanguageManagerPluginManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a Country language url form.
 */
class ConfigForm extends ConfigFormBase {

  /**
   * @var \Drupal\country_language_url\CountryLanguageManagerPluginManager
   */
  protected CountryLanguageManagerPluginManager $countryLanguageManager;

  /**
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The service config.factory.
   * @param \Drupal\country_language_url\CountryLanguageManagerPluginManager $countryLanguageManager
   *   The service plugin.manager.country_language_manager.
   */
  public function __construct(ConfigFactoryInterface $config_factory, CountryLanguageManagerPluginManager $countryLanguageManager) {
    parent::__construct($config_factory);
    $this->countryLanguageManager = $countryLanguageManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('plugin.manager.country_language_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'country_language_url_config';
  }

  protected function getEditableConfigNames() {
    return [
      'country_langauge_url.config',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $plugins_raw = $this->countryLanguageManager->getDefinitions();

    $plugins = [];
    foreach ($plugins_raw as $key => $item) {
      $plugins[$key] = $item['label'];
    }

    $config = $this->config('country_langauge_url.config');

    $plugin_id = $config->get('country_language_manager');

    $userInput = $form_state->getUserInput();

    if (isset($userInput['country_language_manager'])) {
      $plugin_id = $userInput['country_language_manager'];
    }

    $form['country_language_manager'] = [
      '#type' => 'select',
      '#title' => $this->t('Choose country manager'),
      '#default_value' => $plugin_id,
      '#options' => $plugins,
      '#required' => true,
      '#ajax' => [
        'callback' => '::pluginProcessCallback',
        'event' => 'change',
        'wrapper' => 'plugin-container-configuration',
      ],
    ];

    $form['plugin_configuration'] = [
      '#type' => 'container',
      '#attributes' => [
        'id' => [
          'plugin_configuration',
        ],
      ],
      '#prefix' => '<div id="plugin-container-configuration">',
      '#suffix' => '</div>',
    ];

    if (!is_null($plugin_id)) {
      $plugin = $this->countryLanguageManager->createInstance($plugin_id);
      $form['plugin_configuration']['custom_configuration'] = $plugin->buildForm($form_state);
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    $plugin_id = $form_state->getValue('country_language_manager');
    $plugin = $this->countryLanguageManager->createInstance($plugin_id);
    $plugin->validateForm($form, $form_state);
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $plugin_id = $form_state->getValue('country_language_manager');
    $plugin = $this->countryLanguageManager->createInstance($plugin_id);
    $form = $plugin->submitForm($form, $form_state);

    $this->config('country_langauge_url.config')
      ->set('country_language_manager', $form_state->getValue('country_language_manager'))
      ->save();
    $this->messenger()->addStatus($this->t('The configuration has been saved.'));
  }

  public static function pluginProcessCallback(array $form, FormStateInterface $form_state) {
    return $form['plugin_configuration'];
  }

}
