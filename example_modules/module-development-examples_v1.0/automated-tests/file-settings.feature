Feature: File Settings

  Test file setting behavior

  Scenario: Enable the module at the system level
    Given I login to REDCap with the user "Test_Admin"
    And I click on the link labeled "Control Center"
    And I click on the link labeled "Manage"
    And I click on the button labeled "Enable a module"
    And I click on the button labeled "Enable" in the row labeled "Module Development Examples"
    And I click on the button labeled "Enable"

  Scenario: Create a project
    When I click on the link labeled "New Project"
    And I enter "Module Development Examples Test Project" into the input field labeled "Project title"
    And I select "Practice / Just for fun" on the dropdown field labeled "Project's purpose"
    And I click on the radio labeled "Empty project (blank slate)"
    And I click on the button labeled "Create Project"

  Scenario: Enable the module at the project level
    And I click on the link labeled "Manage"
    And I click on the button labeled "Enable a module"
    And I click on the button labeled "Enable" in the row labeled "Module Development Examples"

  Scenario: File settings within repeatable subsections
    And I click on the button labeled "Configure" in the row labeled "Module Development Examples"
    And I upload a "csv" format file located at "files/1.csv", by clicking the button near "1. File Sub Setting" to browse for the file
    And I click on the button labeled "Save"
    And I click on the button labeled "Configure" in the row labeled "Module Development Examples"
    And I should see "1.csv" in the row labeled "1. File Sub Setting"
    And I click on the button labeled "Download File" in the row labeled "1. File Sub Setting"
    And I should see the following values in the last file downloaded
        | This is the content of 1.csv |
    
    And I click on the button labeled "+" in the row labeled "1. List of Sub Settings"
    And I upload a "csv" format file located at "files/2.csv", by clicking the button near "2. File Sub Setting" to browse for the file
    And I click on the button labeled "+" in the row labeled "1. List of Sub Settings"
    And I click on the button labeled "Save"
  
  Scenario: File settings that are themselves repeatable
    And I click on the button labeled "Configure" in the row labeled "Module Development Examples"
    And I upload a "csv" format file located at "files/1.csv", by clicking the button near "1. File Upload" to browse for the file
    And I click on the button labeled "Save"
    And I click on the button labeled "Configure" in the row labeled "Module Development Examples"
    And I should see "1.csv" in the row labeled "1. File Upload"
    And I click on the button labeled "Download File" in the row labeled "1. File Upload"
    And I should see the following values in the last file downloaded
        | This is the content of 1.csv |
    
    And I click on the button labeled "+" in the row labeled "1. File Upload"
    And I upload a "csv" format file located at "files/2.csv", by clicking the button near "2. File Upload" to browse for the file
    And I click on the button labeled "+" in the row labeled "1. File Upload"
    And I click on the button labeled "Save"
    And I click on the button labeled "Configure" in the row labeled "Module Development Examples"
    And I should NOT see "csv" in the row labeled "2. File Upload"
    And I should see "2.csv" in the row labeled "3. File Upload"
    And I click on the button labeled "Download File" in the row labeled "3. File Upload"
    And I should see the following values in the last file downloaded
        | This is the content of 2.csv |
    
    And I click on the button labeled "-" in the row labeled "2. File Upload"
    And I click on the button labeled "Save"
    And I click on the button labeled "Configure" in the row labeled "Module Development Examples"
    And I should see "2.csv" in the row labeled "2. File Upload"

    And I click on the button labeled "-" in the row labeled "2. File Upload"
    And I click on the button labeled "Save"
    And I click on the button labeled "Configure" in the row labeled "Module Development Examples"
    And I should NOT see "2.csv"
    
    And I click on the button labeled "Delete File" in the row labeled "1. File Upload"
    And I click on the button labeled "Save"
    And I click on the button labeled "Configure" in the row labeled "Module Development Examples"
    And I should NOT see "1.csv"
