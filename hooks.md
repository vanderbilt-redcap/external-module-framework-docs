### Using Hooks in Modules

One of the more powerful things that modules can do is to utilize REDCap Hooks, which allow you to execute PHP code in specific places in REDCap. For general information on REDCap hook functions, open **Control Center**, click **Plugin, Hook, & External Module Documentation**, and scroll down to the **Hook functions** section in the left menu. To use a hook in your module you must **add a method in your module class with the exact same name as the name of the desired hook function**. For example, in the HideHomePageEmails class below, there is a method named `redcap_project_home_page`, which means that when REDCap calls the redcap_project_home_page hook, it will execute the module's redcap_project_home_page method.

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
