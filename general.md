## External Module Framework - Official Documentation

"External Modules" is a class-based framework replacing legacy plugins and hooks in REDCap. Modules can utilize any of the "REDCap" class methods (e.g., \REDCap::getData), and they also come with many other helpful built-in methods to store and manage settings for a given module, as well as provide support for internationalization (translation of displayed strings) of modules. The documentation provided on this page will be useful for anyone creating an external module.

If you have created a module and wish to share it with the REDCap community, you may submit it to the [REDCap External Modules Submission Survey](https://redcap.vanderbilt.edu/surveys/?s=X83KEHJ7EA). If your module gets approved after submission, it will become available for download by any REDCap administrator from the [REDCap Repo](https://redcap.vanderbilt.edu/consortium/modules/).

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

### How to create pages for your module

A module can have pages, similar to traditional REDCap plugins.  While traditional plugin pages are accessible directly from the web (e.g., https://example.com/redcap/plugins/my-plugin/my-page.php), module pages must be accessed through a url returned by the `getUrl()` method (e.g., https://example.com/redcap/redcap_vX.X.X/ExternalModules/?prefix=my_module&page=my-page). Thus it is important to note that PHP files in a module's directory cannot be accessed directly from the web browser (e.g., https://example.com/redcap/redcap/modules/my_module_v#.#.#/my-page.php).

Note: When building links to module pages in module code, make sure to use the `getUrl()` method (documented [here](methods/README.md)) to build all page URLs on the fly.  Manually building URLs to pages will not work in all cases.

**Add a link on the project menu to your page:** Adding a page to your module is fairly easy. First, it requires adding an item to the `links` option in the config.json file. In order for the link to show up in a project where the module is enabled, put the link settings (name, icon, and url) under the `project` sub-option, as seen below, in which *url* notes that index.php in the module directory will be the endpoint of the URL, *"VoteCap"* will be the link text displayed. See the **Config.json** section above for details on the *icon* parameter. You may add as many links as you wish.  By default, project links will only display for superusers and users with design rights, but this can be customized in each module (see the *redcap_module_link_check_display()* documentation above). 

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
If you want to similarly add links to your pages on the Control Center's left-hand menu (as opposed to a project's left-hand menu), then you will need to add a `control-center` section to your `links` settings, as seen below.

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

**Disabling authentication for specific pages:** If a module page should not enforce REDCap's authentication but instead should be publicly viewable to the web, then in the `config.json` file you need to 1) **append `?NOAUTH` to the URL in the `links` setting**, and then 2) **add the file name to the `no-auth-pages` setting**, as seen below. Once those are set, all URLs built using `getUrl()` will automatically append *NOAUTH* to the page URL, and when someone accesses the page, it will know not to enforce authentication because of the *no-auth-pages* setting. Otherwise, External Modules will enforce REDCap authentication by default.

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

**Example page code:**

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

Modules should **not** reference any undocumented methods, classes, files, etc. (like the *ExternalModules* class).  Undocumented code can change at any time. If you'd like additional functionality to be officially supported, please create an issue or pull request in GitHub.  Email mark.mcever@vumc.org in order to gain access to the [External Module Framework GitHub Repo](https://github.com/vanderbilt-redcap/external-module-framework).

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
