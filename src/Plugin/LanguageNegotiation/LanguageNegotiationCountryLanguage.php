<?php

namespace Drupal\country_language_url\Plugin\LanguageNegotiation;

use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\PathProcessor\InboundPathProcessorInterface;
use Drupal\Core\PathProcessor\OutboundPathProcessorInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Render\BubbleableMetadata;
use Drupal\Core\Url;
use Drupal\country_language_url\Service\CountryInterface;
use Drupal\language\LanguageNegotiationMethodBase;
use Drupal\language\LanguageSwitcherInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class for identifying language via URL country-lang prefix.
 *
 * @LanguageNegotiation(
 *   id = \Drupal\country_language_url\Plugin\LanguageNegotiation\LanguageNegotiationCountryLanguage::METHOD_ID,
 *   types = {
 *     \Drupal\Core\Language\LanguageInterface::TYPE_INTERFACE,
 *     \Drupal\Core\Language\LanguageInterface::TYPE_CONTENT,
 *     \Drupal\Core\Language\LanguageInterface::TYPE_URL
 *   },
 *   weight = -8,
 *   name = @Translation("Country-Language URL"),
 *   description = @Translation("Language from the Country-Lang URL (Path prefix)."),
 *   config_route_name = "country_language_url.config"
 * )
 */
class LanguageNegotiationCountryLanguage extends LanguageNegotiationMethodBase implements ContainerFactoryPluginInterface, InboundPathProcessorInterface, OutboundPathProcessorInterface, LanguageSwitcherInterface {

  /**
   * The language negotiation method id.
   */
  const METHOD_ID = 'country-language-url';

  /**
   * Array with prepared prefixes country-lang format.
   *
   * @var array
   */
  protected array $prefixes;

  /**
   * Country Service.
   *
   * @var \Drupal\country_language_url\Service\CountryInterface
   */
  private CountryInterface $countryManager;

  /**
   * Constructor.
   */
  public function __construct(CountryInterface $countryManager) {
    $this->countryManager = $countryManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($container->get('country_language_url.country'));
  }

  /**
   * {@inheritdoc}
   */
  public function getLangcode(Request $request = NULL) {
    $langcode = NULL;
    $prefixes = $this->getPrefixes($request);

    if ($request && $this->languageManager) {
      $languages = $this->languageManager->getLanguages();

      $language_url = $this->countryManager->getLanguageFromRequest($request);

      // Search prefix within added languages.
      $negotiated_language = FALSE;
      foreach ($languages as $language) {
        if (isset($prefixes[$language->getId()]) && $prefixes[$language->getId()] == $language_url) {
          $negotiated_language = $language;
          break;
        }
      }

      if ($negotiated_language) {
        $langcode = $negotiated_language->getId();
      }
    }

    return $langcode;
  }

  /**
   * {@inheritdoc}
   */
  public function processInbound($path, Request $request) {
    $parts = explode('/', trim($path, '/'));
    $prefix = array_shift($parts);
    $prefixes = $this->getPrefixes($request);

    // Search prefix within added languages.
    foreach ($this->languageManager->getLanguages() as $language) {
      if (isset($prefixes[$language->getId()]) && $prefixes[$language->getId()] == $prefix) {
        // Rebuild $path with the language removed.
        $path = '/' . implode('/', $parts);
        break;
      }
    }

    return $path;
  }

  /**
   * {@inheritdoc}
   */
  public function processOutbound($path, &$options = [], Request $request = NULL, BubbleableMetadata $bubbleable_metadata = NULL) {
    $languages = array_flip(array_keys($this->languageManager->getLanguages()));
    // Language can be passed as an option, or we go for current URL language.
    if (!isset($options['language'])) {
      $language_url = $this->languageManager->getCurrentLanguage(LanguageInterface::TYPE_URL);
      $options['language'] = $language_url;
    }
    // We allow only added languages here.
    elseif (!is_object($options['language']) || !isset($languages[$options['language']->getId()])) {
      return $path;
    }
    $prefixes = $this->getPrefixes($request);

    if (is_object($options['language']) && !empty($prefixes[$options['language']->getId()])) {
      $options['prefix'] = $prefixes[$options['language']->getId()] . '/';
      if ($bubbleable_metadata) {
        $bubbleable_metadata->addCacheContexts([
          'languages:' . LanguageInterface::TYPE_URL,
          'country',
        ]);
      }
    }

    return $path;
  }

  /**
   * {@inheritdoc}
   */
  public function getLanguageSwitchLinks(Request $request, $type, Url $url) {
    $links = [];
    $query = $request->query->all();

    foreach ($this->languageManager->getNativeLanguages() as $language) {
      $links[$language->getId()] = [
        // We need to clone the $url object to avoid using the same one for all
        // links. When the links are rendered, options are set on the $url
        // object, so if we use the same one, they would be set for all links.
        'url' => clone $url,
        'title' => $language->getName(),
        'language' => $language,
        'attributes' => ['class' => ['language-link']],
        'query' => $query,
      ];
    }

    return $links;
  }

  /**
   * Prepare prefixes, added country code.
   *
   * @param mixed $request
   *   URL Request.
   *
   * @return array
   *   Array with prepared prefixes.
   */
  private function getPrefixes(mixed $request) {

    $config = $this->config->get('language.negotiation')->get('url');
    if ($request) {
      // If path prefix in country-lang format.
      $countryCode = $this->countryManager->getCountryFromRequest($request);
      if ($countryCode) {
        if ($this->countryManager->checkIfCountryAllowed($countryCode)) {
          foreach ($config["prefixes"] as $lang => $prefix) {
            $config["prefixes"][$lang] = $countryCode . '-' . $prefix;
          }
        }
      }
    }

    return $config["prefixes"];
  }

}
