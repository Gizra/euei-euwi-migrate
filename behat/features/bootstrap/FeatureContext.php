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
   * @Given /^I should see the text "([^"]*)" in the date$/
   */
  public function iShouldSeeTheTextInTheDate($text) {
    $this->assertElementContains('.node-submitted', $text);
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

  /**
   * @Given /^I should see the download "([^"]*)"$/
   */
  public function iShouldSeeTheDownload($text) {
    $this->assertElementContains('.document-download', $text);
  }


  /**
   * @Then /^I should see "([^"]*)" under profile$/
   */
  public function iShouldSeeUnderProfile($text) {
    $this->assertElementContains('#profile-main-info', $text);
  }

  /**
   * @Given /^I should see "([^"]*)" under groups$/
   */
  public function iShouldSeeUnderGroups($groups) {
    $groups = explode(';', $groups);
    foreach ($groups as $group) {
      $group = trim($group);
      $this->assertElementContains('#block-views-ec_people_groups-block_3', $group);
    }
  }

  /**
   * @Given /^I should see the text "([^"]*)" in the terms$/
   */
  public function iShouldSeeTheTextInTheTerms($terms) {
    $terms = explode(';', $terms);
    foreach ($terms as $term) {
      $term = trim($term);
      $this->assertElementContains('.node-terms', $term);
    }
  }

  /**
   * @Given /^I should see the content count "([^"]*)"$/
   */
  public function iShouldSeeTheContentCount($count) {
    $page = $this->getSession()->getPage();
    $text = $page->find('css', '.node-totalcount')->getText();

    $text = intval(str_replace('views', '', $text));
    print('****' . $text);
    if ($text < $count) {
      throw new Exception(sprintf('Wrong views count (showing %d instead of %d)', $text, $count));
    }

  }

}
