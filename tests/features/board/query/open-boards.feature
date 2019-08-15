Feature: Query Open Boards
  In order to know all open boards
  As an api user
  I need to query them through the api

  Scenario: Successful open boards query
    Given I send a GET request to "/boards/open"
    Then the response status code should be 200
    And the response should be:
    """
    [
      {
        "boardId": "b6e7cfd0-ae2b-44ee-9353-3e5d95e57392",
        "title": "To-Dos",
        "open": true
      },
      {
        "boardId": "4b2baa7e-315b-41cc-857b-8852619d230b",
        "title": "Tasks",
        "open": true
      },
      {
        "boardId": "c62abbe1-fb68-4e6d-a6a3-b41aee8564c8",
        "title": "Issues",
        "open": true
      }
    ]
    """
