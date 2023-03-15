<?php

namespace Drupal\Tests\country_language_url\Kernel;

use Drupal\country_language_url\Service\CountryRedirectInterface;
use Drupal\KernelTests\KernelTestBase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test class for the service country_language_url.country_detector.
 */
class CountryDetectorTest extends KernelTestBase {

  /**
   * The service to test.
   *
   * @var \Drupal\country_language_url\Service\CountryRedirectInterface
   */
  private CountryRedirectInterface $countryRedirect;

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['country_language_url'];

  protected function setUp(): void {
    parent::setUp();
    $this->countryRedirect = $this->container->get('country_language_url.country_redirect');

    $this->config('country_language_url.config')
      ->set('default_country', 'es')
      ->save();
  }

  public function testIsCountryInPath() {
    $request1 = Request::create('/');
    $request2 = Request::create('/foo');
    $request3 = Request::create('/foo/bar');
    $request4 = Request::create('/es/foo/bar');
    $request5 = Request::create('/sites/default/files/image/image.jpg');
    $request6 = Request::create('/es-es/foo/bar');
    $request7 = Request::create('/es/foo/bar?foo=bar');
    $request8 = Request::create('/es/foo/bar?foo=bar&bar=foo');

    $this->assertEquals('/es-es', $this->countryRedirect->addCountryToUrlFromRequest($request1));
    $this->assertEquals('/es-es/foo', $this->countryRedirect->addCountryToUrlFromRequest($request2));
    $this->assertEquals('/es-es/foo/bar', $this->countryRedirect->addCountryToUrlFromRequest($request3));
    $this->assertEquals('/es-es/foo/bar', $this->countryRedirect->addCountryToUrlFromRequest($request4));
    $this->assertEquals('/sites/default/files/image/image.jpg', $this->countryRedirect->addCountryToUrlFromRequest($request5));
    $this->assertEquals('/es-es/foo/bar', $this->countryRedirect->addCountryToUrlFromRequest($request6));
    $this->assertEquals('/es-es/foo/bar?foo=bar', $this->countryRedirect->addCountryToUrlFromRequest($request7));
    $this->assertEquals('/es-es/foo/bar?foo=bar&bar=foo', $this->countryRedirect->addCountryToUrlFromRequest($request8));
  }

}
