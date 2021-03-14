Feature:
    I can interact with bookmark elements

    Scenario: I list all bookmarks
        Given 5 bookmarks
        When I list all bookmarks
        Then I have a list of 5 bookmarks
    
    Scenario: I add a bookmark
        When I add a bookmark
        And I list all bookmarks
        Then I have a list of 1 bookmark

    Scenario: I delete a bookmark
        Given 1 bookmarks
        And I delete this bookmark
        And I list all bookmarks
        Then I have a list of 0 bookmark

    Scenario: I edit a bookmark
        Given 5 bookmarks
        When I edit bookmark
        And I list all bookmarks
        Then I have my edited bookmark
