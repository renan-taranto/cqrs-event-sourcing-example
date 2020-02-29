Feature: Create List
  In order to organize task items
  As an api user
  I need to have lists

  Scenario: List creation
    Given I send a POST request to "/lists" with body:
    """
    {
      "id": "be31d1ac-304b-4894-b419-c1a9e01d15f4",
      "title": "Backlog",
      "boardId": "b6e7cfd0-ae2b-44ee-9353-3e5d95e57392"
    }
    """
    Then the response status code should be 202
    And the response should be empty

  Scenario: List creation at a given position
    Given I send a POST request to "/lists" with body:
    """
    {
      "id": "be31d1ac-304b-4894-b419-c1a9e01d15f4",
      "title": "Backlog",
      "position": 1,
      "boardId": "b6e7cfd0-ae2b-44ee-9353-3e5d95e57392"
    }
    """
    Then the response status code should be 202
    And the response should be empty
