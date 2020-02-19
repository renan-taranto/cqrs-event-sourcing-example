Feature: Reorder List
  In order to change the list position in the board
  As an api user
  I need to be able reorder it

  Scenario: List reordering
    Given I send a POST request to "/lists/197c76a8-dcd9-473e-afd8-3ea6556484f3/reorder" with body:
    """
    {
      "toPosition": 2
    }
    """
    Then the response status code should be 202
    And the response should be empty
