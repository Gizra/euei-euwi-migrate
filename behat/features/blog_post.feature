Feature: Blog post
  In order to be able to view a blog post
  As an anonymous user
  We need to be able to have access to a blog post page

  @api @foo
  Scenario Outline: Visit a blog post page
#    Given I am logged in as a user with the "administrator" role
    Given I visit "/user"
    When  I fill in "name" with "admin"
    And   I fill in "pass" with "admin"
    And   I press "Log in"
    And   I should see the text "Login successful."
    And   I visit "<url>"
    Then  I should get a "200" HTTP response
    And   I should see the text "<title>"
    And   I should see the text "<body>"
    And I should see the text "<categories>"
    And I should see the text "<author>"

  Examples:
    | url                                                                                    | title                                      | body                                                                                                     | categories   | author              |
    | public-water_and_sanitation/blog/bill-gates-boit-de-leau-produite-à-partir-dexcréments | Bill Gates boit de l'eau produite à partir | http://www.franceinfo.fr/actu/monde/article/bill-gates-boit-de-l-eau-produite-partir-d-excrements-628153 | Water supply | Antoine Saintraint  |
    | news/csp-alive-and-well-america                                                        | CSP is alive and well in America!          | During the 2000s the re -emergence and growth of CSP after                                               | Solar Energy | Gonzalez Bea        |
    | news/project-lom-pangar-30-mw-hydroelectric-dam                                        | Cameroon - Project Lom Pangar:             | Project Lom Pangar: donors reveal their intentions. This article presents the results of the round       |              | Girbau Garcia Zaira |
    | news/caroline-jackson-join-waste-management-forum-speaker                              | Caroline Jackson to join the Waste Management forum as a speaker| John P. Foden will speak about the history of waste-to-energy      |              | Via Expo            |
