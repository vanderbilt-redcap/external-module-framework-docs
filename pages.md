### Module Pages & Links

A module can have pages, similar to traditional REDCap plugins.  While traditional plugin pages are accessible directly from the web (e.g., https://example.com/redcap/plugins/my-plugin/my-page.php), module pages must be accessed through a url returned by the `getUrl()` method (e.g., https://example.com/redcap/redcap_vX.X.X/ExternalModules/?prefix=my_module&page=my-page). Thus it is important to note that PHP files in a module's directory cannot be accessed directly from the web browser (e.g., https://example.com/redcap/redcap/modules/my_module_v#.#.#/my-page.php).

Note: When building links to module pages in module code, make sure to use the `getUrl()` method (documented [here](methods/README.md)) to build all page URLs on the fly.  Manually building URLs to pages will not work in all cases.


#### Left-Hand Menu Project Links
Links to pages can be configured to appear in REDCap's left-hand menu by adding them to `config.json`.  These can include module pages or links to outside websites.  Links configured under the `control-center` section (shown below) will appear only in REDCap's **Control Center**. Links configured under the `project` section (shown below) will be visible by default **only for users with design rights**, on all project pages, on projects where the module is enabled.  Display behavior/permission and link details (name, url, etc.) can be modified on the fly via the `redcap_module_link_check_display()` hook (see the [method documentation](hooks.md#em-hooks) for details).  See [the config.json docs](config.md) for details on link configuration options.

``` json
{
    "links": {
        "control-center": [
            {
                "name": "My Control Center Page",
                "icon": "fas fa-receipt",
                "url": "control-center-page.php",
                "show-header-and-footer": true
            }
        ],
        "project": [
            {
                "name": "My Project Page",
                "icon": "fas fa-receipt",
                "url": "project-page.php",
                "show-header-and-footer": true
            }
        ]
    }
}
```

#### Disabling authentication for specific pages
If a module page should not enforce REDCap's authentication but instead should be publicly viewable to the web, it must be added to the list of `no-auth-pages` in `config.json` (without the `.php` extension, as shown below). Also, any URLs accessing that page will need to include a `NOAUTH` GET parameter.  This GET parameter is automatically appended to URLs built using `getUrl()` when the third parameter to that function is set to `true`.

``` json
{
    "no-auth-pages": [
        "my-no-auth-page"
    ],
}
```

**Example page code:**

```php
<?php
// A $module variable will automatically be available and set to an instance of your module.
// It can be used like so:
$value = $module->getProjectSetting('my-project-setting');
// More things to do here, if you wish
```
