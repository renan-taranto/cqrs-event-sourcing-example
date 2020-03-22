Feature: Query Boards Overview
  In order to know all existing boards
  As an api user
  I need to get an overview of them

  Scenario: Overview of all boards
    Given I send a GET request to "/boards"
    Then the response status code should be 200
    And the response should be:
    """
    [
      {
        "id": "b6e7cfd0-ae2b-44ee-9353-3e5d95e57392",
        "title": "To-Dos",
        "open": true
      },
      {
        "id": "4b2baa7e-315b-41cc-857b-8852619d230b",
        "title": "Tasks",
        "open": true
      },
      {
        "id": "d81805d3-a350-4ef0-81f0-9eb122b4c1ea",
        "title": "Jobs",
        "open": false
      },
      {
        "id": "37d22c48-17f7-4849-8fb2-dc67f29496f1",
        "title": "Backlog",
        "open": false
      },
      {
        "id": "c62abbe1-fb68-4e6d-a6a3-b41aee8564c8",
        "title": "Issues",
        "open": true
      }
    ]
    """

  Scenario: Overview of open boards
    Given I send a GET request to "/boards?open=true"
    Then the response status code should be 200
    And the response should be:
    """
    [
      {
        "id": "b6e7cfd0-ae2b-44ee-9353-3e5d95e57392",
        "title": "To-Dos",
        "open": true
      },
      {
        "id": "4b2baa7e-315b-41cc-857b-8852619d230b",
        "title": "Tasks",
        "open": true
      },
      {
        "id": "c62abbe1-fb68-4e6d-a6a3-b41aee8564c8",
        "title": "Issues",
        "open": true
      }
    ]
    """

  Scenario: Overview of closed boards
    Given I send a GET request to "/boards?open=false"
    Then the response status code should be 200
    And the response should be:
    """
    [
      {
        "id": "d81805d3-a350-4ef0-81f0-9eb122b4c1ea",
        "title": "Jobs",
        "open": false
      },
      {
        "id": "37d22c48-17f7-4849-8fb2-dc67f29496f1",
        "title": "Backlog",
        "open": false
      }
    ]
    """
