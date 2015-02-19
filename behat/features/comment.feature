Feature: Comment
  In order to be able to view a comment
  As admin

  @api
  Scenario Outline: Explore some pages with comments
    Given   I logging in as "admin"
    Then    I should see <author> of the comment
    And     I should see <subject> of the comment

  Examples:
    | url                                                 | link                                                              | author              |
    | euwi-africa/documents                               | Report of the meeting of the EUWI AWG in Stockholm                | Hélène Le Deunff    |
    | euwi-eastern-europe-caucasus-central-asia/documents | New National Policy Dialogues Brochure                            | Nataliya Nikiforova |
    | euwi-fwg/documents                                  | A Primer for Practitioners and Students in Developing Countries   | Celine Dondeynaz    |
    | euwi-multi-stakeholder-forum/documents              | National Policy Dialogue on Financing Strategy                    | ecaterina Diderich |
