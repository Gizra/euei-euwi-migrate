Feature: Blog post
  In order to be able to view a blog post
  As an anonymous user
  We need to be able to have access to a blog post page

  @api
  Scenario Outline: Visit a blog post page
    Given I logging in as "admin"
    When  I visit "<url>"
    And   I should see the text "<title>"
    And   I should see the text "<body>" in the body
    And   I should see the text "<categories>"
    And   I should see the text "<author>"
    And   I should see the text "<date>" in the date
    And   I should see the text "<terms>" in terms
    And   I should see the content count "<count>"
    And   I should see the text "<ref-document>"

  Examples:
    | url                                                                                                                                            | title                                      | body                                                                                                     | categories                                                                            | author              | date       | terms                                                                                                                                                          | count | ref-document   |
    | public-water_and_sanitation/blog/bill-gates-boit-de-leau-produite-à-partir-dexcréments                                                         | Bill Gates boit de l'eau produite à partir | http://www.franceinfo.fr/actu/monde/article/bill-gates-boit-de-l-eau-produite-partir-d-excrements-628153 | Water supply, WASH                                                                    | Antoine Saintraint  | 08/01/2015 | WASH;Français;Water supply                                                                                                                                     | 1     |                |
    | afretep/news/csp-alive-and-well-america                                                                                                        | CSP is alive and well in America!          | <strong>SolarReserve </strong>                                                                           | Concentrated solar power, Solar Energy                                                | Bea Gonzalez        | 12/03/2012 | Concentrated solar power;csp;CSP Today;English;Press Releases;Solar Energy                                                                                     | 427   |                |
    | afretep/news/project-lom-pangar-30-mw-hydroelectric-dam                                                                                        | Cameroon - Project Lom Pangar:             | Project Lom Pangar: donors reveal their intentions. This article presents the results of the round       | Hydropower                                                                            | Zaira Girbau Garcia | 26/01/2010 | Francais;Hydropower;Newsletter                                                                                                                                 | 483   |                |
    | euwi-community-space/news/caroline-jackson-join-waste-management-forum-speaker                                                                 | Caroline Jackson to join the Waste Management forum as a speaker| <p><strong>John P. Foden</strong> will speak about the                              | Climate change, Environment, Technologies                                             | Via Expo            | 13/12/2010 | Climate change;Environment;Technologies;Event;Energy-From-Waste;exhibition;save planet;south-east european conference;waste management;waste-to-energy;English | 2333  |                |
    | euwi-coordination/news/cg-coordination-meeting-10june-2010                                                                                     | CG coordination meeting -10June 2010       | The documentation of the CG held in Brussels on the 10th June is gathered                                | documentation, EUWI meetings, meeting minutes                                         | Celine Dondeynaz    | 14/06/2010 | Brief - news;documentation;EUWI meetings;meeting minutes;English                                                                                               | 7     |                |
    | afretep/news/solar-south-africa-2012                                                                                                           | Solar South Africa 2012                    | Of the 19 successful bidders, <strong>9 new PV projects                                                  | PV south Africa, renewable south africa, solar power south africa, solar south africa | Amber Williams      | 24/05/2012 | Press Releases;PV south Africa;renewable south africa;Solar Energy;solar power south africa;solar south africa                                                 | 1602  |                |
    | euwi-eastern-europe-caucasus-central-asia/blog/unece-national-policy-dialogue-supports-water-sector-reform-tajikistan                          | UNECE National Policy Dialogue             | Following the adoption of the Strategy it will be necessary to revis                                     |                                                                                       | Nataliya Nikiforova | 09/10/2012 | IWRM;Event;English;TAJIKISTAN                                                                                                                                  | 1600  |                |
    | euwi-eastern-europe-caucasus-central-asia/blog/national-policy-dialogues-water-taking-stock-10-years-progress-eastern-europe-caucasus-and-cent | National Policy Dialogues on Water:        | Ten years since the launching of the European  Union Water                                               |                                                                                       | Nataliya Nikiforova | 09/10/2012 | English;Event;OECD;Project Management;UNECE;Working group                                                                                                      |       | ec3573c737.jpg |

  @api
  Scenario: Confirm link to ref document properly
    Given I logging in as "admin"
    When  I visit "euwi-eastern-europe-caucasus-central-asia/blog/national-policy-dialogues-water-taking-stock-10-years-progress-eastern-europe-caucasus-and-cent"
    And   I click "ec3573c737.jpg"
    Then  I should get a "200" HTTP response
    And   I should see the link "Download this document"
    
