# `config.json`

Below is a list of all items that can be added to **config.json**. **An extensive example of config.json is provided at the very bottom of this page** if you wish to see how all these items will be structured.

* Module **name**
* Module  **description**
* **documentation** can be used to provide a filename or URL for the "View Documentation" link in the module list.  If this setting is omitted, the first filename that starts with "README" will be used if it exists.  If a markdown file is used, it will be automatically rendered as HTML.
* For module **authors**, enter their **name**,  **email**, and **institution**. At least one author is required to run the module.
* **DEPRECATED:** Prior to framework version 12, a **permissions** section was required to specify each hook you wish you use (e.g., **redcap_save_record**).  From framework version 12 forward, hooks work automatically and the **permissions** section must be removed.
* The **framework-version** version used by the module ([click here](versions/README.md) for details).
* **links** specify any links to show up on the left-hand menu. For details, see [Pages & Links](pages.md) page. A **link** consists of:
	* A **name** to be displayed on the site
	* A **url**.  Either a relative path to a module page, or external URL starting with either 'http://' or 'https://'. Javascript links are also supported; these need to start with 'javascript:' and may only use single quotes.
   * An optional **key** (unique within _links_) to identify the link (optional, limited to [-a-zA-Z0-9]). The key (prefixed with the module's prefix and a dash) will be output in the 'data-link-key' attribute of the rendered a tag.
	* An optional **icon**
		* For framework version 3 and later, the **icon** must either be the [Font Awesome](https://fontawesome.com/icons?d=gallery) classes (e.g. `fas fa-user-friends`) or a path to an icon file within the module itself (e.g. `images/my-icon.png`).
		* For framework versions prior to 3, the filename of a REDCap icon in the `Resources/images` folder must be specified without the extension (e.g. `user_list`).  This is deprecated because those icons are no longer used by REDCap itself, and may be modified or removed at any time.
   * An optional  **target** that will be used for the 'target' attribute of the rendered a tag.
   * An optional **show-header-and-footer** boolean.  When **true**, automatically includes the REDCap header and footer on this page.  Defaults to **false** when omitted.
* **system-settings** specify settings configurable at the system-wide level (this Control Center).  Settings do NOT have to be defined in config.json to be used programmatically.  
* **project-settings** specify settings configurable at the project level, different for each project.  Settings do NOT have to be defined in config.json to be used programmatically.  
* A setting consists of:
	* A **key** that is the unique identifier for the item. Dashes (-'s) are preferred to underscores (_'s).
	* A **name** that is the plain-text label for the identifier. You have to tell your users what they are filling out.
	* **required** is a boolean to specify whether the user has to fill this item out in order to use the module.
	* **type** is the data type. Available options are: 
		* button
		* checkbox
		* color-picker
		* dag-list
		* dashboard-list (added in 14.0.3)
		* date
			* Date fields currently use jQuery UI's datepicker and include validation to ensure that dates entered follows datepicker's default date format (MM/DD/YYYY).  This could be expanded to include other date formats in the future.
		* descriptive
		* dropdown
		* email
			* Includes validation to ensure that the value specified is a valid email address.
		* event-list
		* field-list
		* file
		* form-list
		* json
		* password
		* project-id
		* radio
		* report-list (added in 14.0.0)
		* rich-text
		* sub_settings
		* text
		* textarea
		* user-list
		* user-role-list
	* **choices** consist of a **value** and a **name** for selecting elements (dropdowns, radios).
	* **super-users-only** can be set to **true** to only allow super users to access a given setting.
	* **repeatable** is a boolean that specifies whether the element can repeat many times. **If it is repeatable (true), the element will return an array of values.**
   * **allow-project-overrides** is a boolean option for system settings available since REDCap 14.1.6.  When set to `true`, that setting will also appear in the project configuration dialog (under the name defined by the `project-name` option).  Calls to `$module->getProjectSetting()` will then return the system value if no project value is set.  This features only works on top-level settings (not `sub_settings`).
	* **autocomplete** is a boolean that enables autocomplete on dropdown fields.
	* **field-type** is a string that can limit a field-list setting to only fields of the given type. "enum" is a special type that includes radio, select, checkbox, true/false and yes/no fields.
   * **project-name** is a string option available since REDCap 13.1.2.  When used in conjunction with `allow-project-overrides`, this is the setting name that will display in project configuration dialogs.
   * **visibility-filter** is a string that can be applied to a form-list, report-list, or dashboard-list. `public` limits the field to showing only publicly visible forms (i.e., surveys), reports, or dashboards. `nonpublic` similarly limits the field to showing only forms, reports, or dashboard that are *not* publicly visible. Omitting this setting will show the default behavior: all forms, reports, or dashboards.
	* **validation** is a string that can limit a field-list setting to only fields with a given validation type, such as email or phone. "date" and "datetime" are special validation types that include all formats for date_* and datetime_* respectively.
	* **branchingLogic** is an structure which represents a condition or a set of conditions that defines whether the field should be displayed. See examples at the end of this section.
	  * **WARNING:** There are known issues with sub-settings and `branchingLogic` currently.  If anyone would like to help resolve them, the best course of action might be to help [grezniczek](https://github.com/grezniczek) complete his [new configuration interface](https://github.com/grezniczek/redcap_em_config_study), which already has an improved implementation of sub-setting branching logic.
    * **hidden** is a boolean that when present and set to `true` will not display this setting in the settings dialog.  This feature is especially useful for deprecating settings while ensuring no setting with the same name is ever re-added.
	* When type = **sub_settings**, the sub_settings element can specify a group of items that can be repeated as a group if the sub_settings itself is repeatable. The settings within sub_settings follow the same specification here.  It is also possible to nest sub_settings within sub_settings.
	* As a reminder, true and false are specified as their actual values (true/false not as the strings "true"/"false"). Other than that, all values and variables are strings.
	* **DEPRECATED (for now): Default values do NOT currently work consistently, and will likely need to be re-implemented.** Both project-settings and system-settings may have a **default** value provided (using the attribute "default"). This will set the value of a setting when the module is enabled either in the project or system, respectively.
* Any _Action Tags_ provided by the module can be added in the **action-tags** array. For each action tag, provide an object with these keys:
  * **tag** - the name of the action tag, e.g. "@MODULE-ACTION"
  * **description** - the description of this action tag (HTML is allowed)  
  
  These action tags and descriptions will be added to the _@ Action Tags_ popup.
* To support **internationalization** of External Modules (translatability of strings displayed by modules), many of the JSON keys in the configuration file have a _companion key_ that is prepended by "**tt_**", such as *tt_name* or *tt_description* (full list of translatable keys: _name_, _description_, _documentation_, _icon_, _url_, _default_, _cron_description_, as well as _required_ and _hidden_). When provided with a value that corresponds to a key in a language file supplied with the module, the value for the setting will be replaced with the value from the language file. For details, please refer to the [internationalization guide](i18n-guide.md).
* **Attention!** If your JSON is not properly specified, an Exception will be thrown.

## Examples of branching logic

A basic case.

``` json
"branchingLogic": {
    "field": "source1",
    "value": "123"
}
```

Specifying a comparison operator (valid operators: "=", "<", "<=", ">", ">=", ">", "<>").

``` json
"branchingLogic": {
    "field": "source1",
    "op": "<",
    "value": "123"
}
```

Multiple conditions.

``` json
"branchingLogic": {
    "conditions": [
        {
            "field": "source1",
            "value": "123"
        },
        {
            "field": "source2",
            "op": "<>",
            "value": ""
        }
    ]
}
```

Multiple conditions - "or" clause.

``` json
"branchingLogic": {
    "type": "or",
    "conditions": [
        {
            "field": "source1",
            "op": "<=",
            "value": "123"
        },
        {
            "field": "source2",
            "op": ">=",
            "value": "123"
        }
    ]
}
```

Obs.: when `op` is not defined, "=" is assumed. When `type` is not defined, "and" is assumed.

## Example config.json file

For reference, below is a nearly comprehensive example of the types of things that can be included in a module's config.json file.

``` json
{
   "name": "Configuration Example",

   "namespace": "Vanderbilt\\ConfigurationExampleExternalModule",

   "description": "Example module to show off all the options available",
   
   "documentation": "README.pdf",

   "authors": [
      {
         "name": "Jon Snow",
         "email": "jon.snow@vumc.org",
         "institution": "Vanderbilt University Medical Center"
      },
      {
         "name": "Arya Stark",
         "email": "arya.stark@vumc.org",
         "institution": "Vanderbilt University Medical Center"
      }
   ],

   "framework-version": 12,

   "enable-every-page-hooks-on-system-pages": false,

   "links": {
      "project": [
         {
            "name": "Example Project Page",
            "icon": "fas fa-receipt",
            "url": "example-project-page.php"
         }
      ],
      "control-center": [
         {
            "name": "Example Control Center Page",
            "icon": "fas fa-receipt",
            "url": "example-control-center-page.php"
         }
      ],
   },

   "no-auth-pages": [
      "public-page"
   ],

   "enable-ajax-logging": true,

   "enable-no-auth-logging": false,

   "auth-ajax-actions": [
      "action-1",
      "action-2"
   ],

   "no-auth-ajax-actions": [
      "action-1"
   ],

   "action-tags": [
      {
         "tag": "@MODULE-ACTION-TAG",
         "description": "A description for this action tag."
      }
   ],

   "system-settings": [
      {
         "key": "system-file",
         "name": "System Upload",
         "required": false,
         "type": "file",
         "repeatable": false
      },
      {
         "key": "system-checkbox",
         "name": "System Checkbox",
         "required": false,
         "type": "checkbox",
         "repeatable": false
      },
      {
         "key": "system-project",
         "name": "Project",
         "required": false,
         "type": "project-id",
         "repeatable": false
      },
      {
         "key": "test-list",
         "name": "List of Sub Settings",
         "required": true,
         "type": "sub_settings",
         "repeatable":true,
         "sub_settings":[
            {
               "key": "system_project_sub",
               "name": "System Project",
               "required": true,
               "type": "project-id"
            },
            {
               "key": "system_project_text",
               "name": "Sub Text Field",
               "required": true,
               "type": "text"
            }
         ]
      }
   ],

   "project-settings": [
      {
         "key": "descriptive-text",
         "name": "This is just a descriptive field with only static text and no input field.",
         "type": "descriptive"
      },
      {
         "key": "instructions-field",
         "name": "Instructions text box",
         "type": "textarea"
      },
      {
         "key": "test-list2",
         "name": "List of Sub Settings",
         "required": true,
         "type": "sub_settings",
         "repeatable":true,
         "sub_settings":[
            {
               "key": "form-name",
               "name": "Form name",
               "required": true,
               "type": "form-list"
            },
            {
               "key": "survey-name",
               "name": "Survey name",
               "type": "form-list",
               "visibility-filter": "public"
            },
            {
               "key": "arm-name",
               "name": "Arm name",
               "required": true,
               "type": "arm-list"
            },
            {
               "key": "event-name",
               "name": "Event name",
               "required": true,
               "type": "event-list"
            },
            {
            "key": "test-text",
            "name": "Text Field",
            "required": true,
            "type": "text"
            }
         ]
      },
      {
         "key": "text-area",
         "name": "Text Area",
         "required": true,
         "type": "textarea",
         "repeatable": true
      },
      {
         "key": "rich-text-area",
         "name": "Rich Text Area",
         "type": "rich-text"
      },
      {
         "key": "field",
         "name": "Field",
         "required": false,
         "type": "field-list",
         "repeatable": false
      },
      {
         "key": "field-date",
         "name": "Field Date",
         "required": false,
         "type": "field-list",
         "validation": "date",
         "repeatable": false
      },
      {
         "key": "field-datetime",
         "name": "Field DateTime",
         "required": false,
         "type": "field-list",
         "validation": "datetime",
         "repeatable": false
      },
      {
         "key": "field-calc",
         "name": "Field Calc",
         "required": false,
         "type": "field-list",
         "field-type": "calc",
         "repeatable": false
      },
      {
         "key": "field-file",
         "name": "Field File",
         "required": false,
         "type": "field-list",
         "field-type": "file",
         "repeatable": false
      },
      {
         "key": "field-enum",
         "name": "Field Radio, Select, Checkbox, etc.",
         "required": false,
         "type": "field-list",
         "field-type": "enum",
         "repeatable": false
      },
      {
         "key": "dag",
         "name": "Data Access Group",
         "required": false,
         "type": "dag-list",
         "repeatable": false
      },
      {
         "key": "user",
         "name": "Users",
         "required": false,
         "type": "user-list",
         "repeatable": false
      },
      {
         "key": "user-role",
         "name": "User Role",
         "required": false,
         "type": "user-role-list",
         "repeatable": false
      },
      {
         "key": "file",
         "name": "File Upload",
         "required": false,
         "type": "file",
         "repeatable": false
      },
      {
         "key": "checkbox",
         "name": "Test Checkbox",
         "required": false,
         "type": "checkbox",
         "repeatable": false
      },
      {
         "key": "project",
         "name": "Other Project",
         "required": false,
         "type": "project-id",
         "repeatable": false
      },
      {
         "key": "form",
         "name": "All Forms",
         "type": "form-list"
      },
      {
         "key": "form-public",
         "name": "Public Forms (i.e., surveys)",
         "type": "form-list",
         "visibility-filter": "public"
      },
      {
         "key": "form-nonpublic",
         "name": "Nonpublic Forms (i.e., data entry forms without surveys)",
         "type": "form-list",
         "visibility-filter": "nonpublic"
      },
      {
         "key": "report",
         "name": "All Reports",
         "type": "report-list"
      },
      {
         "key": "report-public",
         "name": "Public Reports",
         "type": "report-list",
         "visibility-filter": "public"
      },
      {
         "key": "nonpublic-report",
         "name": "Nonpublic Reports",
         "type": "report-list",
         "visibility-filter": "nonpublic"
      },
      {
         "key": "dashboard",
         "name": "All Dashboards",
         "type": "dashboard-list"
      },
      {
         "key": "dashboard-public",
         "name": "Public Dashboards",
         "type": "dashboard-list",
         "visibility-filter": "public"
      },
      {
         "key": "dashboard-nonpublic",
         "name": "Nonpublic Dashboards",
         "type": "dashboard-list",
         "visibility-filter": "nonpublic"
      }
   ],
   "crons": [
      {
         "cron_name": "cron1",
         "cron_description": "Cron that runs every 30 minutes to do Y",
         "method": "cron1",
         "cron_frequency": "1800",
         "cron_max_run_time": "60"
      },
      {
         "cron_name": "cron2",
         "cron_description": "Cron that runs daily to do YY",
         "method": "some_other_method",
         "cron_frequency": "86400",
         "cron_max_run_time": "1200"
      },
      {
         "cron_name": "cron3",
         "cron_description": "Cron that runs daily at 1:15 am to do YYY",
         "method": "some_other_method_3",
         "cron_hour": 1,
         "cron_minute": 15
      },
      {
         "cron_name": "cron4",
         "cron_description": "Cron that runs on Mondays at 2:25 pm to do YYYY",
         "method": "some_other_method_4",
         "cron_hour": 14,
         "cron_minute": 25,
         "cron_weekday": 1
      },
      {
         "cron_name": "cron5",
         "cron_description": "Cron that runs on the second of each month at 4:30 pm to do YYYYY",
         "method": "some_other_method_5",
         "cron_hour": 16,
         "cron_minute": 30,
      }
   ],
   "compatibility": {
      "php-version-min": "7.4.0",
      "php-version-max": "7.99.99",
      "redcap-version-min": "12.0.0",
      "redcap-version-max": ""
   }
}
```
