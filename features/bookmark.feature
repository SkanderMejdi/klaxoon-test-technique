Feature:
    I can interact with bookmark elements

    Scenario: I list all bookmarks
        Given some bookmarks
        When I list all bookmarks
        Then I have a list of all bookmarks
