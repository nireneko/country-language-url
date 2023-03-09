<?php

namespace Drupal\Tests\country_language_url\Kernel;

use Drupal\country_language_url\Service\CountryInterface;
use Drupal\KernelTests\KernelTestBase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test class for the service country_language_url.country.
 */
class CountryTest extends KernelTestBase {

  /**
   * The service to test.
   *
   * @var \Drupal\country_language_url\Service\CountryInterface
   */
  private CountryInterface $country;

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['country_language_url'];

  protected function setUp(): void {
    parent::setUp();
    $this->country = $this->container->get('country_language_url.country');

    $this->config('country_language_url.config')
      ->set('default_country', 'it')
      ->save();
  }

  public function testGetCountryFromRequest() {
    $request1 = Request::create('/fr-es/pepito');
    $request2 = Request::create('fr-es/pepito');
    $request3 = Request::create('es/pepito');
    $request4 = Request::create('/pepito');
    $request5 = Request::create('pepito');

    $this->assertEquals('fr', $this->country->getCountryFromRequest($request1));
    $this->assertEquals('fr', $this->country->getCountryFromRequest($request2));
    // With no country in the url should return the default language, and is 'it'.
    $this->assertNull($this->country->getCountryFromRequest($request3));
    $this->assertNull($this->country->getCountryFromRequest($request4));
    $this->assertNull($this->country->getCountryFromRequest($request5));
  }

  public function testGetLanguageFromRequest() {

    $request1 = Request::create('/es-de/pepito');
    $request2 = Request::create('es-de/pepito');
    $request3 = Request::create('de/pepito');
    $request4 = Request::create('/pepito');
    $request5 = Request::create('pepito');

    $this->assertEquals('de', $this->country->getLanguageFromRequest($request1));
    $this->assertEquals('de', $this->country->getLanguageFromRequest($request2));
    $this->assertEquals('de', $this->country->getLanguageFromRequest($request3));
    // Default language is 'en';
    $this->assertNull($this->country->getLanguageFromRequest($request4));
    $this->assertNull($this->country->getLanguageFromRequest($request5));
  }

}
