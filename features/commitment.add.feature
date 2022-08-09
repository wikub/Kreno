Feature: Adding commitment credit
    In order to monitor the commitment of the cooperators
    As Commitment Controller
    I need to add commitment credits to cooperators

    Background:
        Given The user admin "ADMIN" "Admin" with "admin@coop.fr" is created
        And The user "DOE" "John" with "johndoe@coop.fr" is created
        And The user "PETERS" "Jane" with "janepeters@coop.fr" is created
        And I am logged with "admin@coop.fr"

    Scenario: adding 2 hours commiment credits to a cooperator "johndoe@coop.fr"
        Given I am on the commitment credits monitoring page
        When I go to the add commitment credit form
        And I fill in "Commentaire" with "administrative work"
        And I fill in "Nombre d'heure" with "2"
        And I fill in "Nombre de créneau" with "0"
        And I select "DOE John" from "Coopérateurs"
        And I press "Ajouter"
        Then I should be  on the commitment credits monitoring page
        And I should see the last commiment credit is for "DOE John" must have "2" "hours" with the comment "administrative work"

    Scenario: adding 1 timeslot commiment credits to cooperators "DOE John" and "PETERS Jane" 
        Given I am on the commitment credits monitoring page
        When I go to the add commitment credit form
        And I fill in "Commentaire" with "Shop work"
        And I fill in "Nombre d'heure" with "0"
        And I fill in "Nombre de créneaux" with "1"
        And I select "PETERS Jane" from "Coopérateurs"
        And I additionally select "DOE John" from "Coopérateurs"
        And I press "Ajouter"
        Then I should be  on the commitment credits monitoring page
        And I should see the last commiment credit is for "PETERS Jane" must have "1" "timeslot" with the comment "Shop work"
        And I should see the last commiment credit is for "DOE John" must have "1" "timeslot" with the comment "Shop work"
        

