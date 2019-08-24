Feature: Create Board
  In order to organize lists of items
  As an api user
  I need to have boards

  Scenario: Successful board creation
    Given I send a POST request to "/boards" with body:
    """
    {
      "id": "af2ece25-9ead-41dd-a2c6-4bf68d92c30f",
      "title": "Ideas"
    }
    """
    Then the response status code should be 202
    And the response should be empty

  Scenario: Board creation with invalid data
    Given I send a POST request to "/boards" with body:
    """
    {
      "id": "1234",
      "title": ""
    }
    """
    Then the response status code should be 400
    And the response should be:
    """
    {
      "errors": {
        "id": "This is not a valid UUID.",
        "title": "This value should not be blank."
      }
    }
    """