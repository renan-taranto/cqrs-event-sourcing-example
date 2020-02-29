Feature: Add Item
  In order to add tasks to be done
  As an api user
  I need to have items

  Scenario: Item creation
    Given I send a POST request to "/items" with body:
    """
    {
      "id": "fd62aaa2-5bda-4dec-a365-7f78ab9885d6",
      "title": "Feature: Items",
      "listId": "197c76a8-dcd9-473e-afd8-3ea6556484f3"
    }
    """
    Then the response status code should be 202
    And the response should be empty

  Scenario: Item creation at given position
    Given I send a POST request to "/items" with body:
    """
    {
      "id": "fd62aaa2-5bda-4dec-a365-7f78ab9885d6",
      "title": "Feature: Items",
      "position": 1,
      "listId": "197c76a8-dcd9-473e-afd8-3ea6556484f3"
    }
    """
    Then the response status code should be 202
    And the response should be empty
