Feature: Document
  In order to be able to view a document
  As an anonymous user
  We need to be able to have access to a document page

  @api @wip
  Scenario Outline: Visit Document dashboard
    Given   I logging in as "admin"
    When    I visit "<url>"
    Then    I should see "<upcoming>" under events
    And     I should see "<past>" under events

  Examples:
    | url                                  | upcoming                                                       | past  |
    | public-water_and_sanitation/calendar | Regulation of public services: national and local perspectives | Water Thematic Regional Seminar - Astana, Kazakhstan       |



  @api @foo
  Scenario Outline: Visit a document page
    Given I logging in as "admin"
    When  I visit "<url>"
    Then  I should get a "200" HTTP response
    And   I should see the text "<title>"
    And   I should see the link "<link>"
    And   I should see the text "<filetype>"
    And   I should see the text "<author>"

  Examples:
    | url                                                                                                     | title                                                             | link                            | filetype                     | author              |
    | euwi-community-space/document/eecca-regional/armenia-npd-steering-group-meetings                        | Armenia NPD Steering Group Meetings                               | Download this document          | Filetype: msword             | Diderich ecaterina  |
    | euwi-africa/document/newsletter/euwi-awg-newsletter-august-2012-bulletin-de-liee-gta-août-2012          | EUWI AWG Newsletter August 2012 // Bulletin de l'IEE GTA août     | EUWI AWG Newsletter August 2012 | Filetype:                    | Sjödin Johanna      |
    | euwi-africa/document/eu-water-initiative-africa-working-group-newsletter-september-2013                 | Report of the meeting of the EUWI AWG in Stockholm                | Download this document          | Filetype: pdf                | Le Deunff Hélène    |
    | euwi-eastern-europe-caucasus-central-asia/document/euwi/ukraine-ru                                      | Ukraine Ru                                                        | Download this document          | Filetype: msword             | Celine Dondeynaz    |
    | euwi-monitoring/document/euwi/minutes-2nd-meeting                                                       | Minutes of the 2nd meeting                                        | Download this document          | Filetype: msword             | Celine Dondeynaz    |
