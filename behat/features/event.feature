Feature: Event
  In order to be able to view an event
  As an anonymous user
  We need to be able to have access to an event page

  @api
  Scenario Outline: Visit event dashboard
    Given I logging in as "admin"
    When  I visit "<url>"
    Then  I should see "<upcoming>" under events
    And   I should see "<past>" under events

  Examples:
    | url                                                | upcoming                                                       | past                                                       |
    | public-water_and_sanitation/calendar               | Regulation of public services: national and local perspectives | Water Thematic Regional Seminar - Astana, Kazakhstan       |
    | afretep/calendar                                   | No upcoming events found.                                      | 1st Announcement for ARPEDAC Clean Development Mechanism   |
    | euwi-mediterranean/calendar                        | No upcoming events found.                                      | EU Water Framework Directive and Med-EUWI                  |
    | euwi-eastern-europe-caucasus-central-asia/calendar | No upcoming events found.                                      | EU Water Initiative - EECCA Component Working Group        |


  @api @foo
  Scenario Outline: Visit an event page
    Given I logging in as "admin"
    When  I visit "<url>"
    Then  I should get a "200" HTTP response
    And   I should see the text "<title>"
    And   I should see the text "<body>" in the body
    And   I should see the text "<terms>" in terms
    And   I should see the text "<author>"

  Examples:
    | url                                                                | title                                                       | body                                                                                     | terms                                                                                                                          | author           |
    | afretep/event/botswana-renewable-energy-expo-2013                  | Botswana Renewable Energy Expo 2013                         | Botswana Renewable Energy Expo 2013</a>, the country’s Leading renewable energy event ho | africa;African area;Asian area;Botswana Renewable Energy Expo;English;International conference;Meeting;SADC;Workshop - Seminar | Douglas Duncan   |
    | euwi-eastern-europe-caucasus-central-asia/event/joint-meeting-euwi-eecca-working-group-and-eap-task-force-group-senior-officials | Joint meeting of EUWI EECCA | Joint meeting of EUWI EECCA Working Group                  | English;EECCA area;Meeting                                                                                                     | Celine Dondeynaz |
    | euwi-mediterranean/event/eu-water-framework-directive-and-med-euwi | EU Water Framework Directive and Med-EUWI                   | This meeting will focus on finalising                                                    | monitoring;pollution;water                                                                                                     | Celine Dondeynaz |
    | euwi-community-space/event/smart-cities                            | SMART CITIES                                                | We kindly invite you to become a part of Smart Cities                                    | smart cities;English;International conference;Meeting;other;                                                                   | Via Expo         |
    | euwi-coordination/event/euwi-coordination-group-june-2010          | EUWI Coordination group - June 2010                         | You are also welcome to join the EUWI Finance Working Group                              | English;Meeting                                                                                                                | maria Vink       |
