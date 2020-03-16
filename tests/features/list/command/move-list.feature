Feature: Move List
  In order to change the list location
  As an api user
  I need to be able to move it

  Scenario: List moving
    Given I send a POST request to "/lists/197c76a8-dcd9-473e-afd8-3ea6556484f3/move" with body:
    """
    {
      "position": 2,
      "boardId": "b6e7cfd0-ae2b-44ee-9353-3e5d95e57392"
    }
    """
    Then the response status code should be 202
    And the response should be empty
