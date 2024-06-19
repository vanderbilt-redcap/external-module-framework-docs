# Using Hooks in Modules

One of the more powerful things that modules can do is to utilize REDCap Hooks, which allow you to execute PHP code in specific places in REDCap. For general information on REDCap hook functions, open **Control Center**, click **Plugin, Hook, & External Module Documentation**, and scroll down to the **Hook functions** section in the left menu. To use a hook in your module you must **add a method in your module class with the exact same name as the name of the desired hook function**. For example, in the `ExampleModule` class below, there is a method named `redcap_data_entry_form`, which means that when REDCap calls the redcap_data_entry_form hook, it will execute the module's redcap_data_entry_form method.

``` php
<?php 
namespace Vanderbilt\ExampleModule;

class ExampleModule extends \ExternalModules\AbstractExternalModule 
{
    // This method will be called by the redcap_data_entry_form hook
    function redcap_data_entry_form($project_id, $record, $instrument, $event_id, $group_id, $repeat_instance) 
    {
        // Put your code here to get executed by the hook
    }
}
```

Remember that each hook function has different method parameters that get passed to it (e.g., $project_id), so be sure to include the correct parameters as seen in the hook documentation for the particular hook function you are defining in your module class.

## Special note regarding the `redcap_email` hook
When used in an External Module, this hook **must** return an actual boolean value (either `true` or `false`). Do not return 0, 1, or other truthy/falsy values. The results of multiple modules using this hook will be combined with logical AND, i.e. as long as one implementation returns `false`, the email will not be sent by REDCap.

## Every Page Hooks
By default, every page hooks will only execute on project specific pages (and only on projects with the module enabled).  However, you can allow them to execute on pages that aren't project specific by setting the following flag in `config.json`.  **WARNING: This flag is risky and should ONLY be used if absolutely necessary.  It will cause your every page hooks to fire on literally ALL non-project pages (the login page, control center pages, "My Projects", etc.).  You will need strict and well tested checking at the top of your hook to make sure it only executes in exactly the contexts desired:**

`"enable-every-page-hooks-on-system-pages": true`

## Proposing New Hooks
See [Proposing New Hooks](new-hooks.md) for instructions on how to propose additional hooks that any External Module can call.

<h2 id='em-hooks'>Hooks provided by the External Module Framework</h2>
The following hooks are available for use in external modules only:

Method<br><br>&nbsp; | Minimum<br>REDCap<br>Version | Description<br><br>&nbsp;
--- | --- | ---
redcap_module_ajax($action, $payload, $project_id, $record, $instrument, $event_id, $repeat_instance, $survey_hash, $response_id, $survey_queue_hash, $page, $page_full, $user_id, $group_id) | 12.5.9 | Triggered by calling the `ajax()` method of the _Javascript Module Object_. `$action` (must be a string) and `$payload` are the parameters submitted to `module.ajax()`; the other parameters give context information that, when set, can be trusted to be correct (as with REDCap hooks). Allowed actions (in authenticated and non-authenticated contexts) must be explicitly declared in `config.json` through the _auth-ajax-actions_ and _no-auth-ajax-actions_ settings (arrays of strings), respectively.
redcap_module_api_before($project_id, $post) | 14.1.5 | Triggered just before REDCap's API is called (i.e., after REDCap has determined which API method is going to be called but before actually calling it). API requests may be disallowed by returning an error message string.  The `$post` variable is a copy of `$_POST` with default values added and some values normalized or formatted. For example, if the REDCap API call is `Export Records`, then `$post['content']` will be _"record"_ and  `$post['action']` will be _"export"_. _Note: `$post['action']` will not necessarily match the value of `action` in the body of the user's API request. The user's actual request details can be obtained by accessing the $\_POST superglobal within the hook function._
redcap_module_configuration_settings($project_id, $settings) | 11.0.0 | Triggered when the system or project configuration dialog is displayed for a given module.  This hook allows modules to dynamically modify and return the settings that will be displayed.
redcap_module_system_enable($version) | 9.0.0 | Triggered when a module is enabled or changed to a different version in Control Center.  It is not recommended to use this hook as a primary means of determining when to transition modified module settings, as there are many edge cases that could conflict with such logic (e.g. temporarily downgrading a module).  It is instead recommended to transition settings based solely on the state of the values currently stored.
redcap_module_system_disable($version) | 9.0.0 | Triggered when a module gets disabled on Control Center.
~~redcap_module_system_change_version($version, $old_version)~~ | 9.0.0 | This hook is no longer used.  Since REDCap 12.0.4 the `redcap_module_system_enable()` hook has been called in its place. See [this community post](https://redcap.vumc.org/community/post.php?id=142034) for details.
redcap_module_project_enable($version, $project_id) | 9.0.0 | Triggered when a module gets enabled on a specific project.
redcap_module_project_delete_after($project_id, $action, $user_id) | 14.3.2 | **BETA: This  is a newer hooks that has some [design concerns](https://redcap.vumc.org/community/post.php?id=227409&comment=227600).  Use it with caution.** Allows custom actions to be performed after a delete action has been initiated, but before being redirected. This allows for close control of the delete operation on a project. The function is executed after project delete action has been initiated but, <strong>BEFORE</strong> redirected back to the My Projects page. The $action parameter will contain a string indicating the type of action performed. The possible values for $action are "prompt" (for a prompt), "prompt_undelete" (for an undelete), and "delete" (for deleting a project).
redcap_module_project_disable($version, $project_id) | 9.0.0 | Triggered when a module gets disabled on a specific project.
redcap_module_project_save_after($project_id, $msg_flag, $project_title, $user_id) | 14.3.2 | **BETA: This  is a newer hooks that has some [design concerns](https://redcap.vumc.org/community/post.php?id=227409&comment=227600).  Use it with caution.** Allows custom actions to be performed after a project has been saved from a newly created, copied, or modified project. This allows for close control of the create, copy, and modify operations on a project (e.g. capturing any values that were added to the form through the $_POST array). The function is executed after a project has been saved to the database, <strong>BEFORE</strong> a redirect to the ProjectSetup/index.php page. The $msg_flag parameter will contain a string indicating the type of project save that was performed. The possible values for $msg are "newproject" (for a new project), "copiedproject" (for a project copy), and "projectmodified" (for a modified project).
redcap_module_configure_button_display() | 9.0.0 | Triggered when each enabled module defined is rendered.  Return `null` if you don't want to display the Configure button and `true` to display.
redcap_module_link_check_display($project_id, $link) | 9.0.0 | Triggered when each link defined in `config.json` is rendered, allowing link visibility to be controlled dynamically.  This method also controls whether pages will load if their URL is accessed directly.  Override this method and return `null` to prevent a given link from displaying, or modify and return the `$link` parameter as desired. The `$link` parameter is an array matching the link definition in `config.json` with an additional `url` value added.
redcap_module_save_configuration($project_id) | 9.0.0 | Triggered after a module configuration is saved.
redcap_module_import_page_top($project_id) | 9.0.0 | Triggered at the top of the Data Import Tool page.

#### Examples:
``` php
<?php

function redcap_module_system_enable($version) {
    // Do stuff
}

function redcap_module_system_disable($version) {
    // Do stuff
}
```
