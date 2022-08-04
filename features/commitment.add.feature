Feature: Adding commitment credit
    In order to monitor the commitment of the cooperators
    As Commitment Controller
    I need to add commitment credits to cooperators

    Context:
        Given The user admin "ADMIN" "Admin" with "admin@coop.fr" is created
        And The user "DOE" "John" with "johndoe@coop.fr" is created
        And The user "PETERS" "Jane" with "janepeters@coop.fr" is created

    Scenario: adding 2 hours commiment credits to a cooperator "johndoe@coop.fr"
        Given I am on the commitment credits monitoring page
        And I am logged with "admin@coop.fr"
        When I go to the add commitment credit form
        And I fill in "label" with "administrative work"
        And I fill in "number of hours" with "2"
        And I fill in "cooperators" with "DOE John"
        And I submit the form
        And I should be  on the commitment credits monitoring page
        And the last commiment credit for Doe John must have 2 hours with the label "administrative work"

    Scenario: adding 1 timeslot commiment credits to cooperators "DOE John" and "PETERS Jane" 
        Given I am on the commitment credits monitoring page
        And I am logged with "admin@coop.fr"
        When I go to the add commitment credit form
        Then fill the add commitment credit form form with:
            | label | shop work | 
            | number of hours | 0 | 
            | number of timeslots | 1 |
            | cooperators | DOE John, PETERS Jane  |
        And I submit the form
        And I should be  on the commitment credits monitoring page
        And the 2 lasts commiment credits for "DOE John" and "PETERS Jane" must have 1 timeslot with the label "shop work"

