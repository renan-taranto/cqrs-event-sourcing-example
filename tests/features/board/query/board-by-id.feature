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
    }
    """

  Scenario: Query of nonexistent board
    Given I send a GET request to "/boards/12345"
    Then the response status code should be 404
    And the response should be empty
