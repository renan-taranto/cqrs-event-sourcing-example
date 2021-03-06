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
      "id": "b6e7cfd0-ae2b-44ee-9353-3e5d95e57392",
      "title": "To-Dos",
      "open": true,
      "lists": [
        {
          "id": "197c76a8-dcd9-473e-afd8-3ea6556484f3",
          "title": "To Do",
          "items": [
            {
              "id": "c8f94b93-a41d-490d-85e0-47990bc4792f",
              "title": "Feature: Items",
              "description": "In order to add tasks to be done..."
            },
            {
              "id": "fbac36d6-fbbc-4013-bed3-2a0fdfd92956",
              "title": "Async Messaging",
              "description": ""
            },
            {
              "id": "e8d36d62-4de4-4d46-afd3-24b2cfd9d39f",
              "title": "Update: Add indexes to mongo documents",
              "description": ""
            }
          ],
          "archivedItems": [
            {
              "id": "a7bb5c80-0b83-41f2-83cc-b1477a298434",
              "title": "Update: Improve mongo queries performance",
              "description": ""
            }
          ]
        },
        {
          "id": "78a03a97-6643-4940-853b-0c89ada22bf2",
          "title": "Doing",
          "items": [],
          "archivedItems": []
        },
        {
          "id": "c69fdf67-353d-4196-b8e8-2d8f1d475208",
          "title": "Done",
          "items": [],
          "archivedItems": []
        }
      ],
      "archivedLists": [
        {
          "id": "d33a1a8e-5933-4fbc-b60c-0f37d201b2b4",
          "title": "Reviewing",
          "items": [],
          "archivedItems": []
        }
      ]
    }
    """
