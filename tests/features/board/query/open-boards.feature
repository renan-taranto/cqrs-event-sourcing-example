Feature: Query Open Boards
  In order to know all open boards
  As an api user
  I need to query them through the api

  Scenario: Successful open boards query
    Given I send a GET request to "/boards"
    Then the response status code should be 200
    And the response should be:
    """
    [
      {
        "boardId": "b6e7cfd0-ae2b-44ee-9353-3e5d95e57392",
        "title": "To-Dos",
        "open": true,
        "lists": [
          {
            "id": "197c76a8-dcd9-473e-afd8-3ea6556484f3",
            "title": "To Do",
            "items": []
          },
          {
            "id": "78a03a97-6643-4940-853b-0c89ada22bf2",
            "title": "Doing",
            "items": []
          },
          {
            "id": "c69fdf67-353d-4196-b8e8-2d8f1d475208",
            "title": "Done",
            "items": []
          }
        ],
        "archivedLists": [
          {
            "id": "d33a1a8e-5933-4fbc-b60c-0f37d201b2b4",
            "title": "Reviewing",
            "items": []
          }
        ]
      },
      {
        "boardId": "4b2baa7e-315b-41cc-857b-8852619d230b",
        "title": "Tasks",
        "open": true,
        "lists": [],
        "archivedLists": []
      },
      {
        "boardId": "c62abbe1-fb68-4e6d-a6a3-b41aee8564c8",
        "title": "Issues",
        "open": true,
        "lists": [],
        "archivedLists": []
      }
    ]
    """
