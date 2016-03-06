Feature: Windstream address Search  
  testing sz moeting
  A a new user
  I need to put my address in the form

@javascript
Scenario: Address Search
  Given I go to "http://isg-db-webdev/windstream/index.php"
  When I fill in "address_number" with "701"
  And I press "Submit"
  And I wait for ajax
  And I wait for ajax
  Then I should see "Select A Package"