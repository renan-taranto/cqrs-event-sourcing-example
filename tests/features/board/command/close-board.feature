Feature: Close Board
  In order to exclude boards
  As an api user
  I need to close them

  Scenario: Successful board closing
    Given I send a POST request to "/boards/close" with body:
    """
    {
      "id": "b6e7cfd0-ae2b-44ee-9353-3e5d95e57392"
    }
    """
    Then the response status code should be 202
    And the response should be empty

  Scenario: Closing a nonexistent board
    Given I send a POST request to "/boards/close" with body:
    """
    {
      "id": "12345"
    }
    """
    Then the response status code should be 404
    And the response should be empty
