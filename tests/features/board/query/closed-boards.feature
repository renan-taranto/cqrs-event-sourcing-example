Feature: Query Closed Boards
  In order to know all closed boards
  As an api user
  I need to query them through the api

  Scenario: Successful closed boards query
    Given I send a GET request to "/boards/closed"
    Then the response status code should be 200
    And the response should be:
    """
    [
      {
        "boardId": "d81805d3-a350-4ef0-81f0-9eb122b4c1ea",
        "title": "Jobs",
        "open": false,
        "lists": [],
        "archivedLists": []
      },
      {
        "boardId": "37d22c48-17f7-4849-8fb2-dc67f29496f1",
        "title": "Backlog",
        "open": false,
        "lists": [],
        "archivedLists": []
      }
    ]
    """
