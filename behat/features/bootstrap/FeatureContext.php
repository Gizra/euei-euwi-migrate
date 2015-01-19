<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Drupal\DrupalExtension\Context\DrupalContext;
use Behat\Behat\Context\Step;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends DrupalContext {

  protected $drupalUsers = array();
    /**
     * Initializes context.
     * Every scenario gets its own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters) {
      if (isset($parameters['drupal_users'])) {
        $this->drupalUsers = $parameters['drupal_users'];
      }
      else {
        throw new Exception('behat.yml should include "drupal_users" property.');
      }
    }

  /**
   * Authenticates a user with password from configuration.
   *
   * @Given /^I logging in as "([^"]*)"$/
   */
  public function iAmLoggingInAs($username) {
    try {
      $password = $this->drupalUsers[$username];
    }
    catch (Exception $e) {
      throw new Exception("Password not found for '$username'.");
    }
    if ($this->getDriver() instanceof Drupal\Driver\DrushDriver) {
      // We are using a cli, log in with meta step.
      return array(
        new Step\When('I am not logged in'),
        new Step\When('I visit "/user"'),
        new Step\When('I fill in "Username" with "' . $username . '"'),
        new Step\When('I fill in "Password" with "' . $password . '"'),
        new Step\When('I press "edit-submit"'),
      );
    }
    else {
      // Log in.
      // Go to the user page.
      $element = $this->getSession()->getPage();
      $this->getSession()->visit($this->locatePath('/user'));
      $element->fillField('Username', $username);
      $element->fillField('Password', $password);
      $submit = $element->findButton('Log in');
      $submit->click();
    }
  }

}
