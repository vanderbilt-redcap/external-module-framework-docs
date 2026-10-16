Feature: External Module Enabling & Disabling Systemwide In Control Center

  Tests external module enabling & disabling systemwide In Control Center

  Scenario: Disable then re-enable the module systemwide In Control Center
    Given I login to REDCap with the user "Test_Admin"
    Then I click on the link labeled "Control Center"
    Then I click on the link labeled "Manage"
    Then I click on the button labeled "Disable" in the row labeled "Module Development Examples"
    Then I click on the button labeled "Disable module"
    Then I click on the button labeled "Enable a module"
    Then I click on the button labeled "Enable" in the row labeled "Module Development Examples"
    Then I click on the button labeled "Enable"
    Then I should see "Module Development Examples - v1.0"
