Feature: Reorder Item
  In order to change the item position in the list
  As an api user
  I need to be able reorder it

  Scenario: Item reordering
    Given I send a POST request to "/items/c8f94b93-a41d-490d-85e0-47990bc4792f/reorder" with body:
    """
    {
      "toPosition": 2
    }
    """
    Then the response status code should be 202
    And the response should be empty
