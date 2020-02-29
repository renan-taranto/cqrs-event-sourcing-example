Feature: Restore Item
  In order to send an archived item back to the list
  As an api user
  I need to be able to restore it

  Scenario: Item restoring
    Given I send a POST request to "/items/a7bb5c80-0b83-41f2-83cc-b1477a298434/restore"
    Then the response status code should be 202
    And the response should be empty
