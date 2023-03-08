<?php

namespace Drupal\Tests\country_language_url\Functional;

use Drupal\Core\Url;
use Drupal\Tests\BrowserTestBase;

/**
 * Test description.
 *
 * @group country_language_url
 */
class ExampleTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['country_language_url'];

  /**
   * Test callback.
   */
  protected function setUp(): void {
    parent::setUp();
    $user = $this->drupalCreateUser(['administer site configuration']);
    $this->drupalLogin($user);
  }

  /**
   * Tests that the home page loads with a 200 response.
   */
  public function testLoad() {
    $this->drupalGet(Url::fromRoute('<front>'));
    $this->assertSession()->statusCodeEquals(200);
  }

}
