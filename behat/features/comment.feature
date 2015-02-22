Feature: Comment
  In order to be able to view a comment
  As admin

  @api
  Scenario Outline: Explore some pages with comments
    Given   I logging in as "admin"
    When    I visit "<url>"
    Then    I should see the author <author> of the comment
    And     I should see the date <date> of the comment
    And     I should see the body <body> of the comment

  Examples:
    | url                                                                             | author           | date            | body                                                           |
    | afretep/document/feed-tariff-system-case-study-ghana                            | Magda Moner      | 19 January 2012 | Very interesting presentation.                                 |
    | euwi-fwg/document/training-manual-economics-sustainable-water-management        | Nuria San Millán | 11 January 2010 | This manual is not available in spanish in the site.           |
    | euwi-coordination/event/euwi-coordination-group-meeting-04-december-2009        | Celine Dondeynaz | 9 December 2009 | Presentation C.Dondeynaz - EUWI website Status and way forward |
    | euwi-coordination/event/eu-water-initiative-multi-stakeholder-forum-31-aug-2014 | Celine Dondeynaz | 29 August 2014  | please find the updated agenda for the MSF 2014                |


  @api
  Scenario: Validate nested comments
    Given I logging in as "admin"
    When  I visit "euwi-fwg/document/training-manual-economics-sustainable-water-management"
    Then  I should see the text "26 January 2010"
    Then  I should see the text "Manuals in all languages are available"

