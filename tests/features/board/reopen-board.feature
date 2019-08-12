Feature: Reopen Board
  In order to get back closed boards
  As an api user
  I need to reopen them

  Scenario: Successful board reopening
    Given I send a POST request to "/boards/reopen" with body:
    """
    {
      "id": "00cb8019-9ad9-442f-8908-6c90ba5cb827"
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
