Feature: Change Item Title
  In order to change the title of an item
  As an api user
  I need to be able to update it through the api

  Scenario: Item title change
    Given I send a POST request to "/items/c8f94b93-a41d-490d-85e0-47990bc4792f/change-title" with body:
    """
    {
      "title": "Feature: Change item title"
    }
    """
    Then the response status code should be 202
    And the response should be empty
