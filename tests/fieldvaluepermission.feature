Feature: ACL's based on a Custom Field and its value

Scenario: Creating a new ACL based on a custom field and its value (http://dmaster/civicrm/acl?action=add&reset=1)

Given the for "Type of Data" the user selects "Contact with Custom Field Value"
And and a <customField> which uses an option list or text box
And for that custom field <customFieldValue> is selected
Then an ACL group will be created based on a hidden smart group of all contacts with that custom field value for that custom field

Scenario: Editing an existing ACL based on a custom field and its value

Given the user is editing an existing acl group
And for "Type of Data" the "Contact with Custom Field Value" option is selected
And and a <customField> which uses an option list or text box
And for that custom field <customFieldValue> is selected
Then the hidden smart group in the database will bue updated for that ACL group.
