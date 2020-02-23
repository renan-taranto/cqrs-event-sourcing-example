Feature: Change Board Title
  In order to change the title of a board
  As an api user
  I need to be able to change it through the api

  Scenario: Successful board title change
    Given I send a POST request to "/boards/b6e7cfd0-ae2b-44ee-9353-3e5d95e57392/change-title" with body:
    """
    {
      "title": "Features"
    }
    """
    Then the response status code should be 202
    And the response should be empty
