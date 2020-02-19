Feature: Restore List
  In order to send an archived list back to the board
  As an api user
  I need to be able to restore it

  Scenario: List restoring
    Given I send a POST request to "/lists/d33a1a8e-5933-4fbc-b60c-0f37d201b2b4/restore"
    Then the response status code should be 202
    And the response should be empty
