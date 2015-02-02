Feature: User profile
  In order to be able to view an user profile
  As an anonymous user
  We need to be able to have access to an user profile page

  @api
  Scenario Outline: Visit user profile page.
    Given I logging in as "admin"
    When  I visit "<url>"
    Then  I should see "<name>" under profile
    And   I should see "<country>" under profile
    And   I should see "<date>" under profile
    And   I should see "<groups>" under groups

  Examples:
    | url                            | name                      | country        | date          | groups                                                                                                                   |
    | people/detail/celinejrc        | Celine Dondeynaz          | italy          | October 2008  | African Renewable Energy Technology Platform; EUWI Africa; EUWI Coordination Group and Secretariat; EUWI Eastern Europe  |
    | people/detail/ray-holland      | Holland Ray               | United Kingdom | January 2010  | African Renewable Energy Technology Platform - AFRETEP                                                                   |
    | people/detail/makuwa           | MOISE MAKUWA              | Burundi        | March 2010    | African Renewable Energy Technology Platform - AFRETEP                                                                   |
    | people/detail/vladimir-garaba  | Garaba vladimir           |                | August 2004   | EUWI Community Space; EUWI Eastern Europe, Caucasus and Central Asia;                                                    |
    | people/detail/arevik-hovsepyan | Hovsepyan arevik          |                | December 2004 | EUWI Community Space; EUWI Eastern Europe, Caucasus and Central Asia;                                                    |
    | people/detail/fragach          | FRAGAKIS christos         |                | July 2007     | EUWI Africa; EUWI Coordination Group; Eastern Europe, Caucasus and Central Asia; EUWI Community Space; EUWI Latin America Water Supply and Sanitation; EUWI Mediterranean; Multi-stakeholder Forum; Research |
    | people/detail/alfredo-guillet  | Guillet (SG obs.) alfredo |                | August 2004   | Africa; Coordination Group; EUWI Community Space                                                                         |
