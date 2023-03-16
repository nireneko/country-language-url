<?php

namespace Drupal\country_language_url\Context;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Cache\Context\CacheContextInterface;
use Drupal\Core\Cache\Context\RequestStackCacheContextBase;
use Drupal\country_language_url\Service\CountryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * This class defines a cache context for country.
 */
class CountryLanguageCache extends RequestStackCacheContextBase implements CacheContextInterface {

  /**
   * The service country_language_url.country.
   *
   * @var \Drupal\country_language_url\Service\CountryInterface
   */
  protected CountryInterface $country;

  /**
   * Constructor of the class.
   *
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The service request_stack.
   * @param \Drupal\country_language_url\Service\CountryInterface $country
   *   The service country_language_url.country.
   */
  public function __construct(RequestStack $request_stack, CountryInterface $country) {
    parent::__construct($request_stack);
    $this->country = $country;
  }

  /**
   * {@inheritdoc}
   */
  public static function getLabel() {
    return t('Country language url');
  }

  /**
   * {@inheritdoc}
   */
  public function getContext() {
    $request = $this->requestStack->getCurrentRequest();
    return $this->country->getCountryFromRequest($request);
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheableMetadata() {
    return new CacheableMetadata();
  }

}
