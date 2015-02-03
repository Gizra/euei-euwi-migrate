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
    | euwi-africa/documents                               | Report of the meeting of the EUWI AWG in Stockholm                                    | Hélène Le Deunff   |
    | euwi-eastern-europe-caucasus-central-asia/documents | National Policy Dialogues in Armenia related to Integrated Water Resources Management | ecaterina Diderich |
    | euwi-fwg/documents                                  | A Primer for Practitioners and Students in Developing Countries                       | Celine Dondeynaz   |
    | euwi-multi-stakeholder-forum/documents              | EU Council Resolution Water management in developing countries: Policy and            | maria Vink         |



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
    | url                                                                                            | title                                                             | link                            | filetype                     | author              | download-link                           |
    | euwi-community-space/document/eecca-regional/armenia-npd-steering-group-meetings               | Armenia NPD Steering Group Meetings                               | Download this document          | Filetype: msword             | ecaterina Diderich  | /SC_1__meeting__22.03.07_Agenda_eng.doc |
    | euwi-africa/document/newsletter/euwi-awg-newsletter-august-2012-bulletin-de-liee-gta-août-2012 | EUWI AWG Newsletter August 2012 // Bulletin de l'IEE GTA août     | EUWI AWG Newsletter August 2012 | Filetype:                    | Johanna Sjödin      |                                         |
    | euwi-africa/document/eu-water-initiative-africa-working-group-newsletter-september-2013        | Report of the meeting of the EUWI AWG in Stockholm                | Download this document          | Filetype: pdf                | Hélène Le Deunff    | /Appendix_II_AWG_progress_report.pdf    |
    | euwi-eastern-europe-caucasus-central-asia/document/euwi/ukraine-ru                             | Ukraine Ru                                                        | Download this document          | Filetype: msword             | Celine Dondeynaz    | 715_tmpphptiCgiH                        |
    | euwi-monitoring/document/euwi/minutes-2nd-meeting                                              | Minutes of the 2nd meeting                                        | Download this document          | Filetype: msword             | Celine Dondeynaz    | 27_tmpphpwLCujN                         |
