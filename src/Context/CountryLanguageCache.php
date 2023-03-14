<?php

namespace Drupal\country_language_url\Context;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Cache\Context\CacheContextInterface;
use Drupal\Core\Cache\Context\RequestStackCacheContextBase;
use Drupal\country_language_url\Service\CountryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CountryLanguageCache extends RequestStackCacheContextBase implements CacheContextInterface {

  protected CountryInterface $country;

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
