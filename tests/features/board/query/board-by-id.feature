Feature: Query Board by Id
  In order to retrieve information about a specific board
  As an api user
  I need to query it through the api

  Scenario: Successful board query
    Given I send a GET request to "/boards/b6e7cfd0-ae2b-44ee-9353-3e5d95e57392"
    Then the response status code should be 200
    And the response should be:
    """
    {
      "boardId": "b6e7cfd0-ae2b-44ee-9353-3e5d95e57392",
      "title": "To-Dos",
      "open": true
    }
    """

  Scenario: Query of nonexistent board
    Given I send a GET request to "/boards/12345"
    Then the response status code should be 404
    And the response should be empty
