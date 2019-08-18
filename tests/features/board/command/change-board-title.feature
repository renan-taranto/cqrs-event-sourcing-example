Feature: Change Board Title
  In order to change the title of a board
  As an api user
  I need to be able to change it through the api

  Scenario: Successful board title change
    Given I send a POST request to "/boards/b6e7cfd0-ae2b-44ee-9353-3e5d95e57392/title-change" with body:
    """
    {
      "title": "Features"
    }
    """
    Then the response status code should be 202
    And the response should be empty

  Scenario: Board title change with invalid data
    Given I send a POST request to "/boards/b6e7cfd0-ae2b-44ee-9353-3e5d95e57392/title-change" with body:
    """
    {
      "title": ""
    }
    """
    Then the response status code should be 400
    And the response should be:
    """
    {
      "errors": {
        "title": "This value should not be blank."
      }
    }
    """

  Scenario: Title change of nonexistent board
    Given I send a POST request to "/boards/12345/title-change" with body:
    """
    {
      "title": "Tasks"
    }
    """
    Then the response status code should be 404
    And the response should be empty
