Feature: Book
  In order to be able to view a book
  As an anonymous user
  We need to be able to have access to a book page

  @api
  Scenario: Visit a book page
    Given I am an anonymous user
    When  I visit "/public-water_and_sanitation/blog/bill-gates-boit-de-leau-produite-à-partir-dexcréments"
    Then  I should get a "200" HTTP response
    And   I should see the text "Bill Gates boit de l'eau produite à partir"
