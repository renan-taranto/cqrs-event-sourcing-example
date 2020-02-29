Feature: Archive Item
  In order to remove an item from the list
  As an api user
  I need to be able to archive it

  Scenario: Item archiving
    Given I send a POST request to "/items/c8f94b93-a41d-490d-85e0-47990bc4792f/archive"
    Then the response status code should be 202
    And the response should be empty
