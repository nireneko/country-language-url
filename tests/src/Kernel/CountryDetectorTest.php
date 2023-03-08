<?php

namespace Drupal\Tests\country_language_url\Kernel;

use Drupal\country_language_url\Service\CountryDetectorInterface;
use Drupal\KernelTests\KernelTestBase;

class CountryDetectorTest extends KernelTestBase {

  private CountryDetectorInterface $countryDetector;

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['country_language_url'];

  protected function setUp(): void {
    parent::setUp();
    $this->countryDetector = $this->container->get('country_language_url.country_detector');

    $this->config('country_language_url.config')
      ->set('default_country', 'it')
      ->save();
  }

  public function testIsCountryInPath() {
    $this->assertTrue($this->countryDetector->isCountryInString('es-es'));
    $this->assertFalse($this->countryDetector->isCountryInString('es'));
    $this->assertFalse($this->countryDetector->isCountryInString('1s'));
    $this->assertFalse($this->countryDetector->isCountryInString('12'));
    $this->assertFalse($this->countryDetector->isCountryInString(''));
    $this->assertFalse($this->countryDetector->isCountryInString('1-as'));
    $this->assertFalse($this->countryDetector->isCountryInString('asd-asd'));
    $this->assertFalse($this->countryDetector->isCountryInString('as-asd'));
    $this->assertFalse($this->countryDetector->isCountryInString('ass-as'));
  }

  public function testIsLanguageInPath() {
    $this->assertFalse($this->countryDetector->isOnlyLanguageInString('es-es'));
    $this->assertTrue($this->countryDetector->isOnlyLanguageInString('es'));
  }

  public function testGetCountryFromUrl() {
    $this->assertEquals('fr', $this->countryDetector->getCountryFromUrl('/fr-es/pepito'));
    $this->assertEquals('fr', $this->countryDetector->getCountryFromUrl('fr-es/pepito'));
    $this->assertEquals('it', $this->countryDetector->getCountryFromUrl('es/pepito'));
  }

  public function testGetLanguageFromUrl() {
    $this->assertEquals('de', $this->countryDetector->getLanguageFromUrl('/es-de/pepito'));
    $this->assertEquals('de', $this->countryDetector->getLanguageFromUrl('es-de/pepito'));
    $this->assertEquals('de', $this->countryDetector->getLanguageFromUrl('de/pepito'));
  }

}
