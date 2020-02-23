Feature: Reopen Board
  In order to get back closed boards
  As an api user
  I need to reopen them

  Scenario: Successful board reopening
    Given I send a POST request to "/boards/d81805d3-a350-4ef0-81f0-9eb122b4c1ea/reopen"
    Then the response status code should be 202
    And the response should be empty
