Feature: Reopen Board
  In order to get back closed boards
  As an api user
  I need to reopen them

  Scenario: Successful board reopening
    Given I send a POST request to "/boards/reopen" with body:
    """
    {
      "id": "d81805d3-a350-4ef0-81f0-9eb122b4c1ea"
    }
    """
    Then the response status code should be 202
    And the response should be empty

  Scenario: Reopening a nonexistent board
    Given I send a POST request to "/boards/reopen" with body:
    """
    {
      "id": "12345"
    }
    """
    Then the response status code should be 404
    And the response should be empty
