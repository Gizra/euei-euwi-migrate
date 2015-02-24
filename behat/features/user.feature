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
    And   I should see the picture "<picture>"

  Examples:
    | url                            | name                      | country        | date           | groups                                                                                                                   | picture                                                                              |
    | people/detail/celinejrc        | Celine Dondeynaz          | italy          | October 2008   | African Renewable Energy Technology Platform; EUWI Africa; EUWI Coordination Group and Secretariat; EUWI Eastern Europe  |                                                                                      |
    | people/detail/ray-holland      | Ray Holland               | United Kingdom | January 2010   | African Renewable Energy Technology Platform - AFRETEP                                                                   |                                                                                      |
    | people/detail/makuwa           | MAKUWA MOISE              | Burundi        | March 2010     | African Renewable Energy Technology Platform - AFRETEP                                                                   |                                                                                      |
    | people/detail/vladimir-garaba  | vladimir Garaba           |                | August 2004    | EUWI Community Space; EUWI Eastern Europe, Caucasus and Central Asia;                                                    |                                                                                      |
    | people/detail/arevik-hovsepyan | arevik Hovsepyan          |                | December 2004  | EUWI Community Space; EUWI Eastern Europe, Caucasus and Central Asia;                                                    |                                                                                      |
    | people/detail/fragach          | christos FRAGAKIS         |                | July 2007      | EUWI Africa; EUWI Coordination Group; Eastern Europe, Caucasus and Central Asia; EUWI Community Space; EUWI Latin America Water Supply and Sanitation; EUWI Mediterranean; Multi-stakeholder Forum; Research |  |
    | people/detail/alfredo-guillet  | alfredo Guillet (SG obs.) |                | August 2004    | Africa; Coordination Group; EUWI Community Space                                                                         |                                                                                      |
    | people/detail/peepmardiste     | Peep Mardiste             |                | September 2014 | EUWI Community Space                                                                                                     |                                                                                      |
    | people/detail/beagoor          | Bea Gonzalez              | United Kingdom | February 2011  | African Renewable Energy Technology Platform - AFRETEP                                                                   | picture-326.jpg                                                                      |
    | people/detail/arpedac          | Blaise Mempouo            | Cameroon       | August 2014    | EUWI Community Space;African Renewable Energy Technology Platform - AFRETEP                                              | picture-335.png                                                                      |
