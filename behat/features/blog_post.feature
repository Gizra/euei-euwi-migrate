Feature: Blog post
  In order to be able to view a book
  As an anonymous user
  We need to be able to have access to a book page

  @api
  Scenario Outline: Visit a blog post page
    Given I am an anonymous user
    When  I visit "<url>"
    Then  I should get a "200" HTTP response
    And   I should see the text "<text>"

  Examples:
    | url                  | text                                  |
    | public-water_and_sanitation/blog/bill-gates-boit-de-leau-produite-à-partir-dexcréments | Bill Gates boit de l'eau produite à partir |
