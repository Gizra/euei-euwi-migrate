Feature: Document
  In order to be able to view a document
  As an anonymous user
  We need to be able to have access to a document page

  @api
  Scenario Outline: Visit Document dashboard
    Given   I logging in as "admin"
    When    I visit "<url>"
    Then    I should the link "<link>" under documents
    And     I should see the text "<author>"

  Examples:
    | url                                                 | link                                                                                  | author             |
    | euwi-africa/documents                               | Report of the meeting of the EUWI AWG in Stockholm                                    | Le Deunff Hélène   |
    | euwi-eastern-europe-caucasus-central-asia/documents | National Policy Dialogues in Armenia related to Integrated Water Resources Management | Diderich ecaterina |
    | euwi-fwg/documents                                  | A Primer for Practitioners and Students in Developing Countries                       | Celine Dondeynaz   |
    | euwi-multi-stakeholder-forum/documents              | EU Council Resolution Water management in developing countries: Policy and            | Vink maria         |



  @api
  Scenario Outline: Visit a document page
    Given I logging in as "admin"
    When  I visit "<url>"
    Then  I should get a "200" HTTP response
    And   I should see the text "<title>"
    And   I should see the link "<link>"
    And   I should see the text "<filetype>"
    And   I should see the text "<author>"
    And   I should see the download "<download-link>"

  Examples:
    | url                                                                                            | title                                                             | link                            | filetype                     | author              | download-link |
    | euwi-community-space/document/eecca-regional/armenia-npd-steering-group-meetings               | Armenia NPD Steering Group Meetings                               | Download this document          | Filetype: msword             | Diderich ecaterina  | files/file/26/01/2015_-_1300/SC_1__meeting__22.03.07_Agenda_eng.doc |
    | euwi-africa/document/newsletter/euwi-awg-newsletter-august-2012-bulletin-de-liee-gta-août-2012 | EUWI AWG Newsletter August 2012 // Bulletin de l'IEE GTA août     | EUWI AWG Newsletter August 2012 | Filetype:                    | Sjödin Johanna      |                                                                     |
    | euwi-africa/document/eu-water-initiative-africa-working-group-newsletter-september-2013        | Report of the meeting of the EUWI AWG in Stockholm                | Download this document          | Filetype: pdf                | Le Deunff Hélène    | files/file/26/01/2015_-_1301/Appendix_II_AWG_progress_report.pdf    |
    | euwi-eastern-europe-caucasus-central-asia/document/euwi/ukraine-ru                             | Ukraine Ru                                                        | Download this document          | Filetype: msword             | Celine Dondeynaz    | files/file/%5Bsite-date%5D/715_tmpphptiCgiH_2.doc                   |
    | euwi-monitoring/document/euwi/minutes-2nd-meeting                                              | Minutes of the 2nd meeting                                        | Download this document          | Filetype: msword             | Celine Dondeynaz    | files/file/%5Bsite-date%5D/27_tmpphpwLCujN_2.doc                    |
