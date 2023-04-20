## External Module Framework - Official Documentation

"External Modules" is a class-based framework for plugins and hooks in REDCap. Modules can utilize any of the "REDCap" class methods (e.g., \REDCap::getData), and they also come with many other helpful built-in methods to store and manage settings for a given module, as well as provide support for internationalization (translation of displayed strings) of modules. The documentation provided on this page will be useful for anyone creating an external module.

If you have created a module and wish to share it with the REDCap community, you may submit it to the [REDCap External Modules Submission Survey](https://redcap.vanderbilt.edu/surveys/?s=X83KEHJ7EA). If your module gets approved after submission, it will become available for download by any REDCap administrator from the [REDCap Repo](https://redcap.vanderbilt.edu/consortium/modules/).

### Naming a module

Modules must follow a specific naming scheme for the module directory that will sit on the REDCap web server. Each version of a module will have its own directory (like REDCap) and will be located in the `/redcap/modules/` directory on the server. A module directory name consists of three parts: 
1. A **unique name** (so that it will not duplicate any one else's module in the consortium) in [snake case](https://en.wikipedia.org/wiki/Snake_case) format
1. "_v" (an underscore followed by the letter "v")
1. A **module version number**.  [Semantic Versioning](https://semver.org/) is recommended (e.g. `1.2.3`), although simpler `#.#` versioning is also supported (e.g. `1.2`).

The diagram below shows the general directory structure of some hypothetical  modules to illustrate how modules will sit on the REDCap web server alongside other REDCap files and directories.

```
redcap
|-- modules
|   |-- my_module_name_v1.0.0
|   |-- other_module_v2.9
|   |-- other_module_v2.10
|   |-- other_module_v2.11
|   |-- yet_another_module_v1.5.3
|-- redcap_vX.X.X
|-- redcap_connect.php
|-- ...
```

### Renaming a module

The display name for a module can be safely renamed at any time by updating the `name` in `config.json` (as documented later).  The module directory name on the system may also be changed at any time.  Module specific URLs changing is typically the only side effect, but directory renames should still be tested in a non-production environment first to make sure all module features still work as expected.  To rename a module directory, follow these steps:
1. Deploy the module (to all web nodes if there are multiple) under the new directory name (prefix) with a version suffix that matches the version currently enabled on the system.
    - This new deployment can contain code changes as well (e.g. renaming the module in `config.json`, renaming the module's main class, etc.)
1. Run the following query:
    ```
    update redcap_external_modules
    set directory_prefix = 'new_directory_prefix'
    where directory_prefix = 'old_directory_prefix'
    ```
1. Test to make sure the module still functions as expected.
    - All project enables, settings, logs, crons, etc. should be preserved.
1. Once enough time has passed for any running cron jobs to finish, delete the old directory (on all web nodes if there are multiple)


### Module requirements

To function, a module requires both a `config.json` file, and a PHP module class file (e.g., `MyAwesomeModule.php`).  For submission to [The Repo](https://redcap.vanderbilt.edu/consortium/modules/), `LICENSE` and `README` files are also required.  The `README` is most often in Markdown (`.md`) file format.  The `config.json` file will contain all the module's basic configuration (display name, author information, configuration dialog settings, etc.). The PHP module class file generally houses most of the business logic for the module.  It can be named whatever you like so long as its file name, PHP class name, and the last portion of the namespace in `config.json` all match.

#### 1) Module class

Each module must define a module class that extends `ExternalModules\AbstractExternalModule` (see the example below).  Your module class is the central PHP file that will run all the business logic for the module. You may have many other PHP files (classes or include files), as well as JavaScript, CSS, etc. All other such files are optional, but the module class itself is necessary and drives the module.

```php
<?php
// Set the namespace defined in your config file
namespace MyModuleNamespace\MyModuleClass;

// Declare your module class, which must extend AbstractExternalModule 
class MyModuleClass extends \ExternalModules\AbstractExternalModule {
     // Your module methods, constants, etc. go here
}
```

A module's class name can be named whatever you wish. The module class file must also have the same name as the class name (e.g., **MyModuleClass.php** containing the class **MyModuleClass**). Also, the namespace is up to you to name. Please note that the full namespace declared in a module must exactly match the "namespace" setting in the **config.json** file (with the exception of there being a double backslash in the config file because of escaping in JSON). For example, while the module class may have `namespace MyModuleNamespace\MyModuleClass;`, the config file will have `"namespace": "MyModuleNamespace\\MyModuleClass"`.

#### 2) Configuration file

The file `config.json` provides all the basic configuration information for the module in JSON format. This file must define the following: **name, namespace, description, framework-version, and authors**. The `name` is the module title.  The `description` summarizes what the module does (typically a sentance or short paragraph).  The `authors` section documents the primary contact for the module, followed by anyone else who aided in its creation.  All of this information is displayed in the [Repo](https://redcap.vanderbilt.edu/consortium/modules/) and on the module management page in Control Center.

The `namespace` is the PHP namespace used in your module class, and helps prevent collisions between classes, functions, and constants defined by different modules. Module namespaces consist of at least two parts separated by backslashes. The first part is typically the name of the organization that created the module, while the second is typically the module's name.  **It is required that the last part of the namespace match the module's class name, as is common in [composer](https://getcomposer.org/) libraries.**

The `framework-version` exists solely for backward compatibility when breaking changes to the module framework are made.  For new modules, it is recommended to set this to the latest framework version supported by the current REDCap LTS version as documented at the bottom of [this page](framework/intro.md).  That page also contains more details on framework versioning in general.

Here's an example of the minimum requirements for `config.json`:

``` json
{
   "name": "Example Module",
   "namespace": "MyModuleNamespace\\MyModuleClass", 
   "description": "This is a description of the module, and will be displayed below the module name in the user interface.",
   "framework-version": 10,
   "authors": [
       {
            "name": "Jon Snow",
            "email": "jon.snow@vumc.org",
            "institution": "Vanderbilt University Medical Center"
        }
    ]
}
```

Below is a list of all items that can be added to **config.json**. **An extensive example of config.json is provided at the very bottom of this page** if you wish to see how all these items will be structured.

* Module **name**
* Module  **description**
* **documentation** can be used to provide a filename or URL for the "View Documentation" link in the module list.  If this setting is omitted, the first filename that starts with "README" will be used if it exists.  If a markdown file is used, it will be automatically rendered as HTML.
* For module **authors**, enter their **name**,  **email**, and **institution**. At least one author is required to run the module.
* Grant **permissions** for all of the operations, including hooks (e.g., **redcap_save_record**).
* The **framework-version** version used by the module ([click here](framework/intro.md) for details).
* **links** specify any links to show up on the left-hand toolbar. These include stand-alone webpages (substitutes for plugins) or links to outside websites. These are listable at the control-center level or at the project level.  Link URLs and names can be modified before display with the `redcap_module_link_check_display` hook ([click here](methods/README.md#em-hooks) for details).  A **link** consists of:
	* A **name** to be displayed on the site
   * A **key** (unique within _links_) to identify the link (optional, limited to [-a-zA-Z0-9]). The key (prefixed with the module's prefix and a dash) will be output in the 'data-link-key' attribute of the rendered a tag.
	* An **icon**
		* For framework version 3 and later, the **icon** must either be the [Font Awesome](https://fontawesome.com/icons?d=gallery) classes (e.g. `fas fa-user-friends`) or a path to an icon file within the module itself (e.g. `images/my-icon.png`).
		* For framework versions prior to 3, the filename of a REDCap icon in the `Resources/images` folder must be specified without the extension (e.g. `user_list`).  This is deprecated because those icons are no longer used by REDCap itself, and may be modified or removed at any time.
	* A **url** either in the local directory or external to REDCap. External links need to start with either 'http://' or 'https://'. Javascript links are also supported; these need to start with 'javascript:' and may only use single quotes.
   * A **target** that will be used for the 'target' attribute of the rendered a tag.
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
		* rich-text
		* sub_settings
		* text
		* textarea
		* user-list
		* user-role-list
	* **choices** consist of a **value** and a **name** for selecting elements (dropdowns, radios).
	* **super-users-only** can be set to **true** to only allow super users to access a given setting.
	* **repeatable** is a boolean that specifies whether the element can repeat many times. **If it is repeatable (true), the element will return an array of values.**
   * **allow-project-overrides** is a boolean option for system settings.  When set to `true`, that setting will also appear in the project configuration dialog (under the name defined by the `project-name` option).  Calls to `$module->getProjectSetting()` will then return the system value if no project value is set.  This features only works on top-level settings (not `sub_settings`).
	* **autocomplete** is a boolean that enables autocomplete on dropdown fields.
	* **field-type** is a string that can limit a field-list setting to only fields of the given type. "enum" is a special type that includes radio, select, checkbox, true/false and yes/no fields.
   * **project-name** is a string.  When used in conjunction with `allow-project-overrides`, this is the setting name that will display in project configuration dialogs.
	* **validation** is a string that can limit a field-list setting to only fields with a given validation type, such as email or phone. "date" and "datetime" are special validation types that include all formats for date_* and datetime_* respectively.
	* **branchingLogic** is an structure which represents a condition or a set of conditions that defines whether the field should be displayed. See examples at the end of this section.
	  * **WARNING:** There are known issues with sub-settings and `branchingLogic` currently.  If anyone would like to help resolve them, the best course of action might be to help [grezniczek](https://github.com/grezniczek) complete his [new configuration interface](https://github.com/grezniczek/redcap_em_config_study), which already has an imrpoved implementation of sub-setting branching logic.
    * **hidden** is a boolean that when present and set to `true` will not display this setting in the settings dialog.  This feature is especially useful for deprecating settings while ensuring no setting with the same name is ever re-added.
	* When type = **sub_settings**, the sub_settings element can specify a group of items that can be repeated as a group if the sub_settings itself is repeatable. The settings within sub_settings follow the same specification here.  It is also possible to nest sub_settings within sub_settings.
	* As a reminder, true and false are specified as their actual values (true/false not as the strings "true"/"false"). Other than that, all values and variables are strings.
	* **DEPRECATED (for now): Default values do NOT currently work consistently, and will likely need to be re-implemented.** Both project-settings and system-settings may have a **default** value provided (using the attribute "default"). This will set the value of a setting when the module is enabled either in the project or system, respectively.
* To support **internationalization** of External Modules (translatability of strings displayed by modules), many of the JSON keys in the configuration file have a _companion key_ that is prepended by "**tt_**", such as *tt_name* or *tt_description* (full list of translatable keys: _name_, _description_, _documentation_, _icon_, _url_, _default_, _cron_description_, as well as _required_ and _hidden_). When provided with a value that corresponds to a key in a language file supplied with the module, the value for the setting will be replaced with the value from the language file. For details, please refer to the [internationalization guide](i18n-guide.md).
* **Attention!** If your JSON is not properly specified, an Exception will be thrown.

#### Examples of branching logic

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


### How to call REDCap Hooks

One of the more powerful things that modules can do is to utilize REDCap Hooks, which allow you to execute PHP code in specific places in REDCap. For general information on REDCap hook functions, see the hook documentation. To use a hook in your module you must **add a method in your module class with the exact same name as the name of the desired hook function**. For example, in the HideHomePageEmails class below, there is a method named `redcap_project_home_page`, which means that when REDCap calls the redcap_project_home_page hook, it will execute the module's redcap_project_home_page method.

``` php
<?php 
namespace Vanderbilt\HideHomePageEmails;

class HideHomePageEmails extends \ExternalModules\AbstractExternalModule 
{
    // This method will be called by the redcap_data_entry_form hook
    function redcap_data_entry_form($project_id, $record, $instrument, $event_id, $group_id, $repeat_instance) 
    {
	// Put your code here to get executed by the hook
    }
}
```

Remember that each hook function has different method parameters that get passed to it (e.g., $project_id), so be sure to include the correct parameters as seen in the hook documentation for the particular hook function you are defining in your module class.

##### Special note regarding the `redcap_email` hook
When used in an External Module, this hook **must** return an actual boolean value (either `true` or `false`). Do not return 0, 1, or other truthy/falsy values. The results of multiple modules using this hook will be combined with logical AND, i.e. as long as one implementation returns `false`, the email will not be sent by REDCap.

##### Every Page Hooks
By default, every page hooks will only execute on project specific pages (and only on projects with the module enabled).  However, you can allow them to execute on pages that aren't project specific by setting the following flag in `config.json`.  **WARNING: This flag is risky and should ONLY be used if absolutely necessary.  It will cause your every page hooks to fire on literally ALL non-project pages (the login page, control center pages, "My Projects", etc.).  You will need strict and well tested checking at the top of your hook to make sure it only executes in exactly the contexts desired:**

`"enable-every-page-hooks-on-system-pages": true`

##### Extra hooks provided by External Modules
[Click here](methods/README.md#em-hooks) for a few extra hooks dedicated for modules use.

<br/>
### How to create plugin pages for your module

A module can have plugin pages (or what resemble traditional REDCap plugins). They are called "plugin" pages because they exist as a new page (i.e., does not currently exist in REDCap), whereas a hook runs in-line inside of an existing REDCap page/request. 

The difference between module plugin pages and traditional plugins is that while you would typically navigate directly to a traditional plugin's URL in a web browser (e.g., https://example.com/redcap/plugins/votecap/pluginfile.php?pid=26), module plugins cannot be accessed directly but can only be accessed through the External Modules framework's directory (e.g., https://example.com/redcap/redcap_vX.X.X/ExternalModules/?prefix=your_module&page=pluginfile&pid=26). Thus it is important to note that PHP files in a module's directory (e.g., /redcap/modules/votecap/pluginfile.php) cannot be accessed directly from the web browser.

Note: If you are building links to plugin pages in your module, you should use the  `getUrl()` method (documented in the methods list below), which will build the URL all the required parameters for you.

**Add a link on the project menu to your plugin:** Adding a page to your module is fairly easy. First, it requires adding an item to the `links` option in the config.json file. In order for the plugin link to show up in a project where the module is enabled, put the link settings (name, icon, and url) under the `project` sub-option, as seen below, in which *url* notes that index.php in the module directory will be the endpoint of the URL, *"VoteCap"* will be the link text displayed. See the **Config.json** section above for details on the *icon* parameter. You may add as many links as you wish.  By default, project links will only display for superusers and users with design rights, but this can be customized in each module (see the *redcap_module_link_check_display()* documentation above). 

``` json
{
   "links": {
      "project": [
         {
            "name": "VoteCap",
            "key": "votecap",
            "icon": "fas fa-receipt",
            "url": "index.php",
            "show-header-and-footer": true
         }
      ]
   }
}
```

The following optional settings may also be specified for each project link:

Setting&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; | Description
------- | -----------
show-header-and-footer | Specify **true** to automatically show the REDCap header and footer on this page.  Defaults to **false** when omitted.

**Adding links to the Control Center menu:**
If you want to similarly add links to your plugins on the Control Center's left-hand menu (as opposed to a project's left-hand menu), then you will need to add a `control-center` section to your `links` settings, as seen below.

``` json
{
   "links": {
      "project": [
         {
            "name": "VoteCap",
            "key": "votecap",
            "icon": "fas fa-receipt",
            "url": "index.php"
         }
      ],
      "control-center": [
         {
            "name": "VoteCap System Config",
            "key": "config",
            "icon": "fas fa-receipt",
            "url": "config.php"
         }
      ]
   }
}
```

**Disabling authentication in plugins:** If a plugin page should not enforce REDCap's authentication but instead should be publicly viewable to the web, then in the config.json file you need to 1) **append `?NOAUTH` to the URL in the `links` setting**, and then 2) **add the plugin file name to the `no-auth-pages` setting**, as seen below. Once those are set, all URLs built using `getUrl()` will automatically append *NOAUTH* to the plugin URL, and when someone accesses the plugin page, it will know not to enforce authentication because of the *no-auth-pages* setting. Otherwise, External Modules will enforce REDCap authentication by default.

``` json
{
   "links": {
      "project": [
         {
            "name": "VoteCap",
            "key": "votecap",
            "icon": "fas fa-receipt",
            "url": "index.php?NOAUTH"
         }
      ]
   },
   "no-auth-pages": [
      "index"
   ],
}
```

The actual code of your plugin page will likely reference methods in your module class. It is common to first initiate the plugin by instantiating your module class and/or calling a method in the module class, in which this will cause the External Modules framework to process the parameters passed, discern if authentication is required, and other initial things that will be required before processing the plugin and outputting any response HTML (if any) to the browser.

**Example plugin page code:**

```php
<?php
// A $module variable will automatically be available and set to an instance of your module.
// It can be used like so:
$value = $module->getProjectSetting('my-project-setting');
// More things to do here, if you wish
```

### Available developer methods in External Modules

The External Modules framework provides objects representing a module, both in **PHP** and **JavaScript**.

The publicly supported methods that module creators may utilize depend on the framework version they opt into via the configuration file and are documented [here](methods/README.md).

**Attention!** Modules should _not_ reference any other methods or files that exist in the External Modules framework (like the *ExternalModules* class) as they could change at any time. If a method you believe should be supported by these module objects is missing, please feel free add it via a pull request.  Email mark.mcever@vumc.org in order to gain access to the [External Module Framework GitHub Repo](https://github.com/vanderbilt/redcap-external-modules).

### Logging from module code

The External Modules framework provides mechanism to log to the `redcap_external_modules_log` and `redcap_external_modules_log_parameters` tables. See the PHP and JavaScript versions of the `log()` method [here](methods/README.md).




### Making requests from JavaScript to modules

The External Module framework provides the `ajax()` method on the _Javascript Module Object_ (see [documentation](methods/README.md#em-jsmo)), which can be used to make server requests to the module. The module must process the request in the `redcap_module_ajax` hook and (optionally) return a response (see [documentation](methods/README.md#em-hooks)).

```js
module.ajax('action', payload).then(function(response) {
   // Process response
}).catch(function(err) {
   // Handle error
});
```

Actions must be declared in `config.json`, separately for authenticated (a user is logged in) and non-authenticated (surveys and other contexts where no user is logged in) contexts.

> `"auth-ajax-actions": [ "action1", "action2" ],`

> `"no-auth-ajax-actions": [ "action2" ],`


### Utilizing Cron Jobs for Modules

Modules can have their own cron jobs that are run at a given interval by REDCap (alongside REDCap's internal cron jobs). This allows modules to have processes that are not run in real time but are run in the background at a given interval. There is no limit on the number of cron jobs that a module can have, and each can be configured to run at different intervals for different purposes. 

Crons are registered when a module is enabled or updated.  If a cron is added without updating a module's version, you will need to disable then re-enable that module to register the cron.

Module cron jobs must be defined in `config.json` as seen below, in which each has a `cron_name` (alphanumeric name that is unique within the module), a `cron_description` (text that describes what the cron does), and a `method` (refers to a PHP method in the module class that will be executed when the cron is run). The `cron_frequency` and `cron_max_run_time` must be defined as integers (in units of seconds). The cron_max_run_time refers to the maximum time that the cron job is expected to run (once that time is passed, if the cron is still listed in the state of "processing", it assumes it has failed/crashed and thus will automatically enable it to run again at the next scheduled interval).  Here is an example cron method definition:
```
/**
 * @param array $cronAttributes A copy of the cron's configuration block from config.json.
 */
function myCronMethodName($cronAttributes){
    // ...
}
```

#### Avoiding Long Running Crons

To prevent modules from unnecessarily bogging down the cron server and/or database, shorter and more frequent crons are preferred over long running crons.   A queue/worker pattern is recommended, where module cron functions complete a small amount of work at a time, then return, yielding system resources to other crons.  This allows REDCap crons to function like a FIFO jobs queue where CPU & DB time alternates between modules, even while processing large tasks.  For example, a cron that takes one minute and runs every minute for an hour will generally impact overall system performance less than a cron that runs once but takes an hour.  This is especially true when several module crons are processing large work queues at the same time.  Individual module actions may take a little bit longer using this design, but that is preferred to potentially bogging down the entire REDCap system.

REDCap will prevent a single module from running two instances of the same cron concurrently (as long as `cron_max_run_time` has not passed).  However, REDCap allows different cron jobs to run concurrently. Since REDCap starts new crons each minute, those that last longer than one minute (from `$_SERVER['REQUEST_TIME']`) can begin compounding.  The longer crons last, the higher the likelihood that they will overlap with other longer running crons.   While often a non-issue, it is important to understand that a poorly designed module cron can unexpectedly bring an entire REDCap system to a crawl.

#### Setting a Safe Maximum Run Time

The `cron_max_run_time` is the amount of time that REDCap will wait for a cron that runs longer than it's `cron_frequency` to finish before starting another instance of the same cron.  If a cron runs longer than it's `cron_max_run_time`, REDCap will assume it has either crashed or been killed, and will allow a new instance of the same cron to start.  If set too low, multiple crons could run at the same time and cause either the module or the entire server to crash.  It is recommended to set a `cron_max_run_time` larger than the longest amount of time a cron could possibly run in a near worst case scenario.

For example, let's say we have a cron that runs once a minute (a `cron_frequency` value of `60` seconds) and normally takes 30 seconds to finish.  Consider the following scenarios:
- If the amount of data processed could increase and cause this cron to take 90 seconds to finish, any `cron_max_run_time` less than 90 seconds would be unsafe.  Even if concurrent crons are not problematic for the module itself, this could cause the number active cron processes to pile up over time and crash the server.
- If the amount of data processed could increase and cause this cron to occasionally take hours to finish, it may be prudent to set much larger `cron_max_run_time` to be safe (perhaps 24 hours, or `86400` seconds).

#### Setting a Project Context Within a Cron
Using methods like `$module->getProjectId()` will not work by default inside a cron because crons do not run in a project context.  Here is one common way of simulating a project context in a cron method:
```
function cron($cronInfo){
	foreach($this->getProjectsWithModuleEnabled() as $localProjectId){
		$this->setProjectId($localProjectId);

		// If setProjectId() is not available in your REDCap version, the following will have the same effect:
		$_GET['pid'] = $localProjectId;

		// Project specific method calls go here.
		$someValue = $this->getProjectSetting('some_key');
	}

	return "The \"{$cronInfo['cron_description']}\" cron job completed successfully.";
}
```

#### Cron Configuration Examples

``` json
{
   "crons": [
      {
         "cron_name": "cron1",
         "cron_description": "Cron that runs every 30 minutes to do X",
         "method": "cron1",
         "cron_frequency": "1800",
         "cron_max_run_time": "86400"
      },
      {
         "cron_name": "cron2",
         "cron_description": "Cron that runs daily to do YY",
         "method": "some_other_method",
         "cron_frequency": "86400",
         "cron_max_run_time": "172800"
      }
   ]
}
```

#### Timed Crons (Deprecated)

> **Warning** **Timed crons have been deprecated.**  There are currently no plans to remove this feature, but it may remain deprecated indefinitely pending the following concerns:
> - Timed cron run times cannot be guaranteed.
>   - As currently implemented, timed crons can be delayed up to however long it takes other timed crons scheduled on the same minute to execute (potentially hours, days, or longer).  While it is possible to manually reschedule timed crons via the "Configure Cron Start Times" link, the automatic scheduling nature of regular `cron_frequency` crons generally avoids any noticeable delays by default.
>   - Timed crons can be skipped due to system maintenance/downtime, while regular crons automatically run whenever the system is back online.
> - Timed crons run according to whatever the time zone is in `php.ini`.  This is set to UTC on some systems, and the local time zone on others, creating ambiguity around when jobs will run.
> - Timed crons encourage designs based on longer running cron jobs (see the *Avoiding Long Running Crons* section above).
> - Timed crons do not currently appear in the cron logs table/page
> 
> It is possible to effectively emulate timed cron behavior with a regular cron, and with more scheduling flexibility.  For example, the API Sync module allows each project to configure it's own hour/minute to run. This "timed" behavior is implemented via a regular cron that runs every minute, loops through project configuration and stored state, then determines what jobs to perform at a given time. It's effectively a jobs queue where things get added to the queue automatically when their scheduled time passes.
>
> We could potentially change timed crons to function more like regular crons by adding/updating them in the `redcap_crons` table at their scheduled run time with whatever `cron_frequency` causes them to run at that time.  This would allow the normal cron scheduler to automatically run them on the next available minute regardless of long running crons from other modules.  It would also make them show up in the cron log.  If we wanted to, we could even keep the existing functionality of emailing when they run past their expected end time and requiring user intervention to continue (instead of using `cron_max_run_time`).  Until someone has time/incentive to consider something like this, timed crons will remain deprecated.

Instead of specifying a `cron_frequency` and `cron_max_run_time`, the "timed" crons feature allows modules to specify `cron_hour` and `cron_minute` instead.  In addition, `cron_weekday` (0 [Sundays] - 6 [Saturdays]) or `cron_monthday` (day of the month) can be optionally be specified as well.  Here are some "timed" cron configuration examples:

``` json
{
   "crons": [
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
         "cron_monthday": 2
      }
   ]
}
```

### Module compatibility with specific versions of REDCap and PHP

It may be the case that a module is not compatible with specific versions of REDCap or specific versions of PHP. In this case, the `compatibility` option can be set in the config.json file using any or all of the four options seen below. (If any are listed in the config file but left blank as "", they will just be ignored.) Each of these are optional and should only be used when it is known that the module is not compatible with specific versions of PHP or REDCap. You may provide PHP min or max version as well as the REDCap min or max version with which your module is compatible. If a module is downloaded and enabled, these settings will be checked during the module enabling process, and if they do not comply with the current REDCap version and PHP version of the server where it is being installed, then REDCap will not be allow the module to be enabled.

```JSON
{	
   "compatibility": {
      "php-version-min": "7.4.0",
      "php-version-max": "7.99.99",
      "redcap-version-min": "12.0.0",
      "redcap-version-max": ""
   }
}
```

### JavaScript recommendations

If your module will be using JavaScript, it is *highly recommended* that your JavaScript variables and functions not be placed in the global scope. Doing so could cause a conflict with other modules that are running at the same time that might have the same variable/function names. As an alternative, consider creating a function as an **IIFE (Immediately Invoked Function Expression)** or instead creating the variables/functions as properties of a **single global scope object** for the module, as seen below.

```JavaScript
<script type="text/javascript">
  // IIFE - Immediately Invoked Function Expression
  (function($, window, document) {
      // The $ is now locally scoped

      // The rest of your code goes here!

  }(window.jQuery, window, document));
  // The global jQuery object is passed as a parameter
</script>
```

```JavaScript
<script type="text/javascript">
  // Single global scope object containing all variables/functions
  var MCRI_SurveyLinkLookup = {};
  MCRI_SurveyLinkLookup.modulevar = "Hello world!";
  MCRI_SurveyLinkLookup.sayIt = function() {
    alert(this.modulevar);
  };
  MCRI_SurveyLinkLookup.sayIt();
</script>
```

### Constructor Related Pitfalls

Adding constructors to modules is not recommended because all module features are not available in constructors under all conditions (like calling `setSystemSetting()` when enabling modules at the system level).  Instead, [lazy instantiation](https://en.wikipedia.org/wiki/Lazy_initialization) of any required resources is recommended inside the getter method for each resource at the time it is first used.  If you must implement a constructor, calling `parent::__construct();` on the first line (as shown below) will make as many module features available as possible.

```php
class MyModuleClass extends AbstractExternalModule {
   public function __construct()
   {
      parent::__construct();
      // Other code to run when object is instantiated
   }
}
```

### Including Dependencies/Libraries in your Module

If your module uses a third party library (i.e. PHPMailer) that is available in the [packagist.org](https://packagist.org) repo, please use [composer](https://getcomposer.org/) to include it.  While this adds an extra step when submitting to the module repo, it greatly reduces the chances of conflicts between modules that can cause those modules and/or REDCap to crash.  Composer's class loader automatically handles cases like calling **require** for the same class from multiple modules.  While this does mean that modules could potentially end up using the version of a dependency from another module instead of their own, this is rarely an issue in practice (as evidenced by the WordPress community's reliance on composer for plugin & theme dependencies).  Implementing a more complex dependency management system similar to Drupal's has been discussed, but such an effort is not likely since the current method is generally not an issue in practice.

If you would like to create a library to share between multiple modules, [composer can also use github as a repo](https://getcomposer.org/doc/05-repositories.md#loading-a-package-from-a-vcs-repository).

### Unit Testing

Standard PHPUnit unit testing is supported within modules.  While testing, project & system settings will automatically be stored in and retrieved from memory instead of the database, and values will be cleared between tests.  To bypass this behavior, see the `disableTestSettings()` method documented below.

If anyone is interested in collaborating to add support for javascript unit testing as well, please let us know.  PHP test classes can be added under the `tests` directory in your module as follows.

```php
<?php namespace YourNamespace\YourExternalModule;

// For now, the path to "redcap_connect.php" on your system must be hard coded.
require_once __DIR__ . '/../../../redcap_connect.php';

class YourExternalModuleTest extends \ExternalModules\ModuleBaseTest
{
   function testYourMethod(){
      $expected = 'expected value';
      $actual1 = $this->module->yourMethod();

      // Shorter syntax without explicitly specifying "->module" is also supported.
      $actual2 = $this->yourMethod();

      $this->assertSame($expected, $actual1);
      $this->assertSame($expected, $actual2);
   }
}
```

The `ModuleBaseTest` provides following methods:
Method | Description
-- | --
assertThrowsException() | A convenience method that allows for asserting multiple actions causing exceptions within a single test method.
disableTestSettings() | While not recommended, this method may be used to bypass in-memory settings during the current test, and read/write to/from the database instead.

### Example config.json file

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
