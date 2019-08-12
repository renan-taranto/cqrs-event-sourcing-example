Feature: Change Board Title
  In order to change the title of a board
  As an api user
  I need to be able to change it through the api

  Scenario: Successful board title change
    Given I send a POST request to "/boards/change-title" with body:
    """
    {
      "id": "00cb8019-9ad9-442f-8908-6c90ba5cb827",
      "title": "Tasks"
    }
    """
    Then the response status code should be 202
    And the response should be empty

  Scenario: Board title change with invalid data
    Given I send a POST request to "/boards/change-title" with body:
    """
    {
      "id": "00cb8019-9ad9-442f-8908-6c90ba5cb827",
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

  Scenario: Title change of nonexistent Board
    Given I send a POST request to "/boards/change-title" with body:
    """
    {
      "id": "12345",
      "title": "Tasks"
    }
    """
    Then the response status code should be 404
    And the response should be empty
