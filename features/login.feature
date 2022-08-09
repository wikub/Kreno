Feature: Loggin

Context:
    Given the user "DOE" "John" with "johndoe@coop.fr" is created

Scenario: log in
    Given I am on the login page
    And the user "DOE" "John" with "johndoe@coop.fr" is created
    When I log in with my username "johndoe@coop.fr" and my password "johndoe@coop.fr"
    Then I must be on my homepage
