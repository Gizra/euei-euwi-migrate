Feature: Blog post
  In order to be able to view a blog post
  As an anonymous user
  We need to be able to have access to a blog post page

  @api @foo
  Scenario Outline: Visit a blog post page
    Given I logging in as "admin"
    And   I visit "<url>"
    Then  I should get a "200" HTTP response
    And   I should see the text "<title>"
    And   I should see the text "<body>" in the body
    And   I should see the text "<categories>"
    And   I should see the text "<author>"
    And   I should see the text "<date>" in the date

  Examples:
    | url                                                                                    | title                                      | body                                                                                                     | categories                                                                            | author              | date       |
    | public-water_and_sanitation/blog/bill-gates-boit-de-leau-produite-à-partir-dexcréments | Bill Gates boit de l'eau produite à partir | http://www.franceinfo.fr/actu/monde/article/bill-gates-boit-de-l-eau-produite-partir-d-excrements-628153 | Water supply, WASH                                                                    | Antoine Saintraint  | 08/01/2015 |
    | news/csp-alive-and-well-america                                                        | CSP is alive and well in America!          | <strong>SolarReserve </strong>                                                                           | Concentrated solar power, Solar Energy                                                | Bea Gonzalez        | 12/03/2012 |
    | news/project-lom-pangar-30-mw-hydroelectric-dam                                        | Cameroon - Project Lom Pangar:             | Project Lom Pangar: donors reveal their intentions. This article presents the results of the round       | Hydropower                                                                            | Zaira Girbau Garcia | 26/01/2010 |
    | news/caroline-jackson-join-waste-management-forum-speaker                              | Caroline Jackson to join the Waste Management forum as a speaker| <p><strong>John P. Foden</strong> will speak about the                              | Climate change, Environment, Technologies                                             | Via Expo            | 13/12/2010 |
    | news/cg-coordination-meeting-10june-2010                                               | CG coordination meeting -10June 2010       | The documentation of the CG held in Brussels on the 10th June is gathered                                | documentation, EUWI meetings, meeting minutes                                         | Celine Dondeynaz    | 14/06/2010 |
    | afretep/news/solar-south-africa-2012                                                   | Solar South Africa 2012                    | Of the 19 successful bidders, <strong>9 new PV projects                                                  | PV south Africa, renewable south africa, solar power south africa, solar south africa | Amber Williams      | 24/05/2012 |

