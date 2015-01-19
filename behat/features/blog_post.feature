Feature: Blog post
  In order to be able to view a blog post
  As an anonymous user
  We need to be able to have access to a blog post page

  @api
  Scenario Outline: Visit a blog post page
    Given I am an anonymous user
    When  I visit "<url>"
    Then  I should get a "200" HTTP response
    And   I should see the text "<title>"
    And   I should see the text "<body>"
    And I should see the text "<categories>"
    And I should see the text "<author>"

  Examples:
    | url                  | title                                  | body | categories | author |
    | public-water_and_sanitation/blog/bill-gates-boit-de-leau-produite-à-partir-dexcréments | Bill Gates boit de l'eau produite à partir | http://www.franceinfo.fr/actu/monde/article/bill-gates-boit-de-l-eau-produite-partir-d-excrements-628153 | Water supply | Antoine Saintraint |
