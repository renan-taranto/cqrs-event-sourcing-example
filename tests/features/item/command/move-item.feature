Feature: Move Item
  In order to change the item location
  As an api user
  I need to be able move it

  Scenario: Item moving
    Given I send a POST request to "/items/c8f94b93-a41d-490d-85e0-47990bc4792f/move" with body:
    """
    {
      "position": 0,
      "listId": "78a03a97-6643-4940-853b-0c89ada22bf2"
    }
    """
    Then the response status code should be 202
    And the response should be empty
