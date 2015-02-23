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
    | url                                                 | link                                                              | author              |
    | euwi-africa/documents                               | Report of the meeting of the EUWI AWG in Stockholm                | Hélène Le Deunff    |
    | euwi-eastern-europe-caucasus-central-asia/documents | New National Policy Dialogues Brochure                            | Nataliya Nikiforova |
    | euwi-fwg/documents                                  | A Primer for Practitioners and Students in Developing Countries   | Celine Dondeynaz    |
    | euwi-multi-stakeholder-forum/documents              | National Policy Dialogue on Financing Strategy                    | ecaterina Diderich |



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
    And   I should see the text "<terms>" in terms
    And   I should see the content count "<count>"
    And   I should see the text "<date>" in the date

  Examples:
    | url                                                                                            | title                                                             | link                            | filetype         | author              | download-link    | terms                                                                                                        | count | date       |
    | euwi-community-space/document/eecca-regional/armenia-npd-steering-group-meetings               | Armenia NPD Steering Group Meetings                               | Download this document          | Filetype: zip    | ecaterina Diderich  | /euwi_22188.zip  | Water Policies - Strategies;Financing;Financing strategies;Event;English;ARMENIA;EECCA, REGIONAL;            | 260   | 09/07/2009 |
    | euwi-africa/document/newsletter/euwi-awg-newsletter-august-2012-bulletin-de-liee-gta-août-2012 | EUWI AWG Newsletter August 2012 // Bulletin de l'IEE GTA août     | EUWI AWG Newsletter August 2012 | Filetype:        | Johanna Sjödin      |                  | Newsletter                                                                                                   | 842   | 21/08/2012 |
    | euwi-africa/document/eu-water-initiative-africa-working-group-newsletter-september-2013        | Report of the meeting of the EUWI AWG in Stockholm                | Download this document          | Filetype: zip    | Hélène Le Deunff    | /euwi_25096.zip  | Highlighted;Meeting Report                                                                                   | 38    | 26/09/2013 |
    | euwi-eastern-europe-caucasus-central-asia/document/euwi/ukraine-ru                             | Ukraine Ru                                                        | Download this document          | Filetype: msword | Celine Dondeynaz    | 715_tmpphptiCgiH | EUWI activities;Strategy;Report;Russian                                                                      | 1979  | 06/12/2006 |
    | euwi-monitoring/document/euwi/minutes-2nd-meeting                                              | Minutes of the 2nd meeting                                        | Download this document          | Filetype: msword | Celine Dondeynaz    | 27_tmpphpwLCujN  | meeting minutes                                                                                              | 0     | 06/02/2006 |
