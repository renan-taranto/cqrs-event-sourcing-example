Feature: Change List Title
  In order to change the title of a list
  As an api user
  I need to be able to update it through the api

  Scenario: List title change
    Given I send a POST request to "/lists/197c76a8-dcd9-473e-afd8-3ea6556484f3/change-title" with body:
    """
    {
      "title": "Testing"
    }
    """
    Then the response status code should be 202
    And the response should be empty
