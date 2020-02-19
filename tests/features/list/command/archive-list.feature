Feature: Archive List
  In order to remove a list from the board
  As an api user
  I need to be able to archive it

  Scenario: List archiving
    Given I send a POST request to "/lists/197c76a8-dcd9-473e-afd8-3ea6556484f3/archive"
    Then the response status code should be 202
    And the response should be empty
