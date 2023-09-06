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

To prevent modules from unnecessarily bogging down the cron server and/or database, shorter & more frequent crons are preferred over long running crons.  Shorter & more frequent crons will also behave more predictably in REDCap's shared server environment. They will be less likely to fail due to conflicts, timeouts, or resource usage issues.  They will also recover more gracefully from server outages and high load times.

Any long running process that executes multiple lines of code can be broken down into smaller chunks.  A queue/worker pattern is recommended, where module cron functions complete a small batch of work at a time, then return, yielding system resources to other crons.  This allows REDCap crons to function like a FIFO jobs queue where CPU & DB time alternates between modules, even while processing large tasks.  For example, a cron that takes one minute and runs every minute for an hour will generally impact overall system performance less than a cron that runs once but takes an hour.  This is especially true when several module crons are processing large work queues at the same time.  Individual module actions may take a little bit longer using this design, but that is preferred to potentially bogging down the entire REDCap system.

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
