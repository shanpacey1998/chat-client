Feature: User
  In order to access the app and it's features
  As a web user
  I need to be able to login/ register

  Scenario: Checking the application's kernel environment
    Then the application's kernel should use "test" environment

  Scenario: Register a user
    Given the user "test@123.com" with password "test123" and username "test123" does not exist
    Given I am on "/"
    When I follow "Register"
    And I fill in "Email" with "test@123.com"
    And I fill in "Password" with "test123"
    And I fill in "Repeat Password" with "test123"
    And I fill in "Username" with "test123"
    And I check "Agree Terms"
    And I press "Register"
    Then I should see "Homepage"

  Scenario: Login a user
    Given I am on "/"
    When I fill in "Email" with "test@123.com"
    And I fill in "Password" with "test123"
    And I check "Agree Terms"
    And I press "Login"
    Then I should see "Homepage"

    Scenario: Check login password validation
      Given I am on "/"
      When I fill in "Email" with "test@123.com"
      And I fill in "Password" with "test124"
      And I check "Agree Terms"
      And I press "Login"
      Then I should see "Invalid Credentials"

  Scenario: Check login email validation
    Given I am on "/"
    When I fill in "Email" with "test@124.com"
    And I fill in "Password" with "test123"
    And I check "Agree Terms"
    And I press "Login"
    Then I should see "I'm sorry, it appears we can't find you :("

  Scenario: Check register validation
    Given I am on "/"
    When I follow "Register"
    And I fill in "Email" with "test@123.com"
    And I fill in "Password" with "test123"
    And I fill in "Repeat Password" with "test123"
    And I fill in "Username" with "test123"
    And I check "Agree Terms"
    And I press "Register"
    Then I should see "Sorry, that username is taken"
    And I should see "There is already an account with this email"

  Scenario: A logged in user can send messages
    Given I am on "/"
    When I fill in "Email" with "test@123.com"
    And I fill in "Password" with "test123"
    And I check "Agree Terms"
    And I press "Login"
    Then I should see "Homepage"
    Then I follow "test123"
    And I should see "Chat with test123"
    When I fill in "Enter message here..." with "Hello"
    And I press "Send"
    Then I should see "Hello"