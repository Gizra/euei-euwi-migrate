<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Drupal\DrupalExtension\Context\DrupalContext;
use Behat\Behat\Context\Step;

/**
 * Features context.
 */
class FeatureContext extends DrupalContext {

  /**
   * Authenticates a user with password from configuration.
   *
   * @Given /^I logging in as "([^"]*)"$/
   */
  public function iAmLoggingInAs($username) {
    $password = 'admin';
    // We are using a cli, log in with meta step.
    return array(
      new Step\When('I am not logged in'),
      new Step\When('I visit "/user"'),
      new Step\When('I fill in "Username" with "' . $username . '"'),
      new Step\When('I fill in "Password" with "' . $password . '"'),
      new Step\When('I press "edit-submit"'),
    );
  }

  /**
   * @Given /^I should see the text "([^"]*)" in the body$/
   */
  public function iShouldSeeTheTextInTheBody($text) {
    $this->assertElementContains('.node-content', $text);
  }

  /**
   * @Then /^I should see "([^"]*)" under events$/
   */
  public function iShouldSeeUnderEvents($text) {
    $this->assertElementContains('.view-ec-calendar', $text);
  }

  /**
   * @Then /^I should the link "([^"]*)" under documents$/
   */
  public function iShouldTheLinkUnderDocuments($text) {
    $this->assertElementContains('.view-ec-documents', $text);
  }



}
