## Methods Provided by the External Module Framework

**Some method behavior differs between framework versions.  [Click here](../versions/README.md) for more information on framework versioning in general.**

The following **PHP** and **JavaScript** methods are provided by the framework.  Modules that have been updated to framework version `5` or greater can access methods directly on the module object (e.g. `$module->getModuleName()`).  Modules on older framework versions can access methods via the framework object (e.g. `$module->framework->getModuleName()`).  Older methods may also be accessible directly on the module class even in on framework versions (for backward compatibility).  Unless otherwise stated, module methods throw standard PHP exceptions if any errors occur.  Any uncaught exception triggers an email to the REDCap admin address, avoiding the need for any error checking boilerplate in many cases.

Please also make sure you are aware of the built-in developer methods on your REDCap instance under **Control Center -> Plugin, Hook, & External Module Documentation** page, as well as the PHP constants under **redcap_info()**.  Eventually, that & this documentation will ideally be merged.

Modules should **not** reference any undocumented methods, classes, files, etc. (like the *ExternalModules* class).  Undocumented code can change at any time. If you'd like additional functionality to be officially supported, please create an issue or pull request for this repo with example documentation for the method(s) you'd like to be supported.

Method<br><br>&nbsp; | Minimum<br>REDCap<br>Version | Description<br><br>&nbsp;
--- | --- | --- 
addAutoNumberedRecord([$pid]) | 8.0.0 | Creates the next auto numbered record and returns the record id.  If the optional PID parameter is not specified, the current PID will be automatically detected.
compareGetDataImplementations(...) | 10.8.2 | **BETA:** Compares `REDCap::getData()` and `$module->getData()` results.  See [this page](query-data.md) for details.
convertIntsToStrings($row) | 9.7.6 | Returns a copy of the specified array with any integer values cast to strings.  This method is intended to aid in converting queries to use parameters with minimal refactoring.
countLogs($whereClause, $parameters) | 9.9.1 | Returns the count of log statements matching the specified where clause.  Example: `countLogs("message = ? and timestamp > ?", [$message, $dateTimeObject])`
createDAG($name) | 8.0.0 | Creates a DAG with the specified name, and returns it's ID.
createPassthruForm(<br>&emsp;$projectId,<br>&emsp;$recordId,<br>&emsp;<br>&emsp;// optional<br>&emsp;$surveyFormName,<br>&emsp;$eventId<br>) | 8.11.8 | Outputs the HTML for opening/continuing the survey submission for the specified record.  If a return code is required, a link is returned.  Otherwise, a self submitting form is returned.
createProject($title, $purpose, [, $projectNote]) | 9.7.6 | Creates a new redcap project and returns the project id.
createQuery() | 9.7.8 | Creates a `Query` object to aid in building complex queries using parameters.  See the [Query Documentation](querying.md) page for more details.
createTempDir() | 12.2.3 | Creates a temp directory which is automatically deleted when the PHP process finishes, and returns its path.
createTempFile() | 12.2.3 | Creates a temp file which is automatically deleted when the PHP process finishes, and returns its path.
dataDictionaryCSVToMetadataArray($csvFilePath) | 10.3.1 | Given a CSV data dictionary file path, returns that file into a metadata array.
delayModuleExecution() | 8.0.0 | When called within a hook, this method causes the current hook to be "delayed", which means it will be called again after all other enabled modules (that define that hook) have executed.  This allows modules to interact with each other to control their run order.  For example, one module may wait for a second module to set a field value before it finishes executing.  A boolean value of `true` is returned if the hook was successfully delayed, or `false` if the hook cannot be delayed any longer and this is the module's last chance to perform any required actions.  If the delay was successful, hooks normally `return;` immediately after calling this method to stop the current execution of hook.
deleteDAG($groupId) | 8.2.1 | Given a Group ID number, deletes the DAG and all Users and Records assigned to it.
disableModule($pid, $prefix = null) | 13.1.2 | Disables a module on a specified project. If the optional prefix parameter is not specified, the current module will be disabled.
enableModule($pid, $prefix = null) | 9.9.1 | Enables a module on a specified project. If the optional prefix parameter is not specified, the current module will be enabled.
escape($value) | 13.1.2 | Ensures that the given `$value` is safe for display within HTML.  Arrays are walked, escaping every child value recursively.  For primitive types, this method preserves the exact same behavior as if the `$value` was included directly instead of the call to this method. For strings, objects, and resources, this method is equivalent to `htmlspecialchars($value, ENT_QUOTES)`. For booleans, integers, and floats, this method is equivalent to a cast to the respective type.  This informs REDCap's Psalm based security scan that the `$value` is safe, while preserving its type in case any strict checking is performed on the `$value` prior to display. For null, null is returned.
exitAfterHook() | 8.2.0 | Calling this method inside of a hook will schedule PHP's exit() function to be called after ALL modules finish executing for the current hook.  Do NOT call die() or exit() manually afterward (the framework will call it for you).  Calls to this method inside nested hook calls are ignored (e.g. an email hook inside of an every page top hook).
getChoiceLabel($fieldName, $value, [, $pid]) | 8.0.0 | Get the label associated with the specified field & value.  The project ID parameter will be automatically detected if possible.
getChoiceLabels($fieldName[, $pid]) | 8.0.0 | Returns an array mapping all choice values to labels for the specified field.
getConfig() | 8.0.0 | Returns an array representation of `config.json`, with reserved settings added.
getCSRFToken() | 11.1.1 | Returns the CSRF token that REDCap will expect on the next POST request.  This token will be automatically added in many cases.  See the [v8 page](../versions/v8.md) for more details, and the `Configuration Example` module bundled with REDCap for examples in difference scenarios.
getDAG($recordId) | 10.3.1 | Return the Group ID number for the given record ID on the current project.
getData(...) | 10.8.2 | **BETA:** An experimental `queryData()` based alternative to `REDCap::getData()`.  This method requires a `framework-version` of `7` or higher, as there was an old undocumented and problematic implementation of `getData()` prior to then.  See [this page](query-data.md) for details.
getDataTable([$pid]) | 14.0.0 | Returns the data table for current or specified project ID.
getEnabledModules([$pid]) | 9.9.1 | Returns an array with the modules enabled on the system or for the project with the given project id. The array is of the form "prefix" => "version".
getEventId() | 9.7.6 | Returns the current event ID.  If an 'event_id' GET parameter is specified, it will be returned.  If not, and the project only has a single event, that event's ID will be returned.  If no 'event_id' GET parameter is specified and the project has multiple events, an exception will be thrown.
getFieldLabel($fieldName) | 8.2.3 | Returns the label for the specified field name.
getFieldNames($formName[, $pid]) | 9.10.0 | Returns an array of field names for the specified form.
getFormsForEventId($eventId) | 11.4.0 | Returns an array of form names for the given event ID.
getJavascriptModuleObjectName() | 8.10.12 | Returns the name of the javascript object for this module.
getModuleDirectoryName() | 8.0.0 | get the directory name of the current external module
getModuleName() | 8.0.0 | get the name of the current external module
getModulePath() | 8.0.0 | Get the path of the current module directory (e.g., /var/html/redcap/modules/votecap_v1.1/)
getProject([$projectId]) | 8.11.10 | Returns a `Project` object for the given project ID, or the current project if no ID is specified.  This `Project` object is documented below.
getProjectId() | 8.7.2 | A convenience method for returning the current project id.
getProjectsWithModuleEnabled() | 8.11.6 | Returns an array of project ids for which the  current module is enabled (especially useful in cron jobs).  Projects are excluded that are in analysis/cleanup status, or have been completed or deleted.
getProjectSetting($key&nbsp;[,&nbsp;$pid]) | 8.0.0 | Returns the value stored for the specified key for the current project.  For non-repeatable settings, `null` is returned if no value is set.  For repeatable settings, an array with a single `null` value is returned if no value is set.  In most cases the project id can be detected automatically, but it can optionally be specified as a parameter instead.
getProjectSettings([$pid]) | 9.10.0 | Gets all project settings as an array.  If sub-settings are used, the `getSubSettings()` method is likely more useful, while this method is likely more useful for cases when you may be creating a custom config page for the external module in a project. The behavior of this method changed in [framework v5](../versions/v5.md).
getProjectStatus([$pid]) | 9.9.1 | Returns the status of the specified project (project id is inferred if not given). Status can be: "DEV" (development mode), "PROD" (production mode), "AC" (analysis/cleanup mode), "DONE" (completed). In case the project does not exist, NULL is returned.
getPublicSurveyHash($pid=null) | 9.9.1 | Returns the survey hash code for the current project. If a project_id is specified it will return the hash for that specific project. If the hash does not exist it will return null.
getPublicSurveyUrl($pid=null) | 8.2.3 | Returns the public survey url for the current project. If a project_id is specified it will return the link for that specific project. If the link does not exist it will return null.
getQueryLogsSql($sql) | 8.7.3 | Returns the raw SQL that would run if the supplied parameter was passed into **queryLogs()**. 
getRecordId() | 8.7.2 | Returns the current record id if called from within a hook that includes the record id.
getRecordIdField([$pid]) | 9.3.5 | Returns the name of the record ID field. Unlike the same method on the `REDCap` class, this method accepts a `$pid`, and also works outside a project context when a `pid` GET parameter is set.
getRepeatingForms([$eventId, $pid]) | 9.7.6 | Returns an array of repeating form names for the current or specified event & pid.
getSafePath($path[, $root]) | 9.7.6 | Ensures that a [path traversal attack](https://www.owasp.org/index.php/Path_Traversal) is not in progress by verifying that the `$path` is within either the module directory, or the `$root` directory (if specified).  If a potential attack is detected, an exception is thrown.  Using this method is important when generating paths using strings created from user input.  The `$path` can be relative to the `$root`, or include it.  The `$root` can be either absolute or relative to the module directory.
getSettingConfig($key) | 8.0.0 | Returns the configuration for the specified setting.
getSettingKeyPrefix() | 8.0.0 | This method can be overridden to prefix all setting keys.  This allows for multiple versions of settings depending on contexts defined by the module.
getSubSettings($key&nbsp;[,&nbsp;$pid]) | 8.0.0 | Returns the sub-settings under the specified key in a user friendly array format.  In most cases the project id can be detected automatically, but it can optionally be specified as a parameter instead.  System settings are supported as of framework version 14.
getSystemSetting($key) | 8.0.0 | Returns the value stored for the specified key at the system level.  For non-repeatable settings, `null` is returned if no value is set.  For repeatable settings, an array with a single `null` value is returned if no value is set
getUrl($path [, $noAuth=false [, $useApiEndpoint=false]]) | 8.0.0 | Get the url to a resource (php page, js/css file, image etc.) at the specified path relative to the module directory. A `$module` variable representing an instance of your module class will automatically be available in PHP files.  If the `$noAuth` parameter is set to true, then "&NOAUTH" will be appended to the URL.  This method works for both project and non-project pages.  If `$_GET['pid']` is present when `getUrl()` is called, it will automatically be appended to the returned URL as well.  The returned URL string can also be modified to add or remove `&pid=#` and/or other parameters as desired.<br><br>By default, `getUrl()` returns URLs that contain the REDCap version directory (e.g. https://example.com/redcap/redcap_vX.X.X/ExternalModules/?prefix=your_module&page=index&pid=33).  A version-less URL may be obtained by passing `true` as the third parameter to this method (e.g. `getUrl('my-page.php', false, true)`).  This will return an API endpoint based URL (e.g., https://example.com/redcap/api/?type=module&prefix=your_module&page=index&pid=33).
getUser([$username]) | 8.11.9 | Returns a `User` object for the given username, or the current user if no username is specified.  This `User` object is documented below.  If the specified or current user cannot be found, an exception is thrown to encourage explicit handling of that scenario.  NOAUTH scenarios can be handled as follows:<br><code>if($module->isAuthenticated()){<br>&nbsp;&nbsp;&nbsp;&nbsp;$user = $module->getUser();<br>}</code>
getUserSetting($key) | 8.3.0 | Returns the value stored for the specified key for the current user and project.  Null is always returned on surveys and NOAUTH pages.
importDataDictionary($projectId,$path) | 9.7.6 | Given a project id and a path, imports a data dictionary CSV file.
initializeJavascriptModuleObject() | 8.7.2 | Returns a JavaScript block that initializes the JavaScript version of the module object (documented below).
isAuthenticated() | 13.4.11 | Returns `true` in authenticated contexts and `false` in NOAUTH contexts.
isModuleEnabled($prefix [, $pid]) | 9.9.1 | Returns true if the module with the given prefix is enabled on the system (when no project id is supplied) or the given project; or false otherwise.
isModulePage([$path]) | Determines whether the current page is provided by the current module, and optionally whether the given `$path` parameter matches the `page` URL parameter.  If `$path` is not specified, `true` is returned on any page provided by the current module.
isPage($path) | 9.7.6 | Returns true if the current page matches the supplied file/dir path.  The path can be any file/dir under the versioned REDCap directory (e.g. `Design/online_designer.php`).
isREDCapPage($path) | 14.0.3 | An alias of `isPage()` that clarifies the distinction from `isModulePage()`.  Once most REDCap servers have updated to a version that includes this method, `isPage()` will officially become deprecated in favor of this method.
isRoute($routeName) | 8.11.10 | Returns true if the 'route' GET/URL parameter matches the specified string.
isSuperUser() | 13.1.2 | Returns true if the current user is a super user (a.k.a. the "Access to all projects and data with maximum user privileges" permission).  The "View project as user" impersonation feature is taken into account.
isSurveyPage() | 8.4.3 | Returns true if the current page is a survey.  This is primarily useful in the **redcap_every_page_before_render** and **redcap_save_record** hooks.
isValidProjectId($pid [, $condition]) | 9.9.1 | Checks whether a project id is valid (under the given conditions) and returns true or false. Condition can be true = the project must exist or any of "DEV", "PROD", "AC" (Analysis/Cleanup), "DONE" (completed) or a combination (given as array, e.g. ["AC", "DONE"]) = the project must be in (any of) the given state(s).
log($message[, $parameters]) | 8.7.2 | Stores a log entry including a message string and optional array of key-value pair parameters for later retrieval using the **queryLogs()** method.  If messages, parameter keys, or parameter values come from user input (like `$_GET`), an allow list or other [input validation](https://cheatsheetseries.owasp.org/cheatsheets/Input_Validation_Cheat_Sheet.html#implementing-input-validation) method should be use to ensure your module cannot be exploited in unexpected ways.  The inserted **log_id** is returned.  The **timestamp**, **username**, **ip**, **project_id**, and **record** paraemters are stored automatically if available.  The **timestamp**, **project_id**, and **record** parameters can be overridden if desired.  Only alphanumeric, space, dash, underscore, or dollar sign characters are allowed for log parameter names.  To see log related examples, [click here](logs.md). Note that for framework version 11+, logging in non-authenticated contexts must be explicitly allowed by setting the _enable-no-auth-logging_ flag in `config.json`.
query($sql, $parameters) | 8.0.0 | Executes a SQL query and returns a [mysqli_result](https://www.php.net/manual/en/class.mysqli-result.php) compatible result object.  Query errors are automatically detected, and an Exception is thrown.  Parameters should NOT be escaped manually, as escaping is handled automatically by a prepared statement under the hood. There are subtle differences in the way queries behave when using vs. not using parameters (see the [v4 page](../versions/v4.md) for details).  If no parameters are required (not common), an empty array can be specified to show that use of parameters was seriously considered.  See the [Query Documentation](querying.md) page for more details on querying the database.
queryLogs($sql, $parameters) | 8.7.2 | Queries log entries added via the **log()** method using SQL-like syntax with the "from" portion omitted, and returns a MySQL result resource (just like **mysql_query()**).  The `$parameters` argument behaves the same way as described in the `query()` method documentation.  Queries can include standard "select", "where", "order by", and "group by" clauses.  Available columns include **log_id**, **timestamp**, **username**, **ip**, **project_id**, **record**, **message**, and any parameter name passed to the **log()** method.  All columns must be specified explicitly ("select \*" syntax is not supported).  If the `external_module_id` or `project_id` columns are not specified in the where clause, queries are limited to the current module and project (if detected) by default.  For complex queries, the log table can be manually queried (this method does not have to be used).  The raw SQL being executed by this method can be retrieved by calling **getQueryLogsSql()**.  To see log related examples, [click here](logs.md).
records->lock($recordIds) | 8.11.6 | Locks all forms/instances for the given record ids.
redirectAfterHook($url, $forceJS = false) | 13.10.1 | Calling this method inside of a hook will schedule a redirect to the given URL to be issued after ALL modules finish executing for the current hook. The module setting the URL last will determine the target of the redirection (a module cannot cancel a redirection once set). 
removeLogs($whereClause, $parameters) | 8.7.2 | Removes log entries matching the current module, current project (if detected), and the specified pseudo SQL `$whereClause`.  The current project can be overridden by specifying a `project_id` column in the `$whereClause`.  Any key/value pair parameters associated with the removed log entries are also removed. The `$parameters` argument behaves the same way as described in the `query()` method documentation.  From framework version 10 forward, the number of removed rows is returned.  In older framework versions, `true` is returned.  To see log related examples, [click here](logs.md).
removeProjectSetting($key&nbsp;[,&nbsp;$pid]) | 8.0.0 | Remove the value stored for this project and the specified key.  In most cases the project id can be detected automatically, but it can optionaly be specified as a parameter instead. 
removeSystemSetting($key) | 8.0.0 | Removes the value stored systemwide for the specified key.
removeUserSetting($key) | 8.3.0 | Removes the value stored for the specified key for the current user and project.  This method does nothing on surveys and NOAUTH pages.
renameDAG($groupId, $name) | 8.0.0 | Renames the DAG with the given Group ID number to the specified name.
requireInteger($mixed) | 8.11.10 | Throws an exception if the supplied value is not an integer or a string representation of an integer.  Returns the integer equivalent of the given value regardless.
resetSurveyAndGetCodes(<br>&emsp;$projectId, $recordId<br>&emsp;[, $surveyFormName, $eventId]<br>) | 8.11.8 | Resets the survey status so that REDCap will allow the survey to be accessed again (completed surveys can't be edited again without changing the survey settings).  A survey participant and respondent are also created if they doesn't exist.
sanitizeAPIToken($token) | 12.2.5 | Removes any characters that are not numbers or uppercase letters A-F, and returns the resulting string.
sanitizeFieldName($fieldName) | 13.8.0 | Removes any characters that are not numbers, letters, or underscores, and returns the resulting string.
setDAG($record, $groupId) | 8.0.0 | Sets the DAG for the given record ID to given Group ID number.
setProjectId($projectId) | 13.4.2 | Sets the current project ID to be used for all module framework functionality.  REDCap core classes are NOT guaranteed to respect this call.
setProjectSetting($key,&nbsp;$value&nbsp;[,&nbsp;$pid]) | 8.0.0 | Sets the setting specified by the key to the specified value for this project.  In most cases the project id can be detected automatically, but it can optionally be specified as a parameter instead.  This method is NOT restricted to settings that exist in `config.json`.
setProjectSettings($settings[, $pid]) | 9.10.0 | Saves all project settings (to be used with getProjectSettings).  Useful for cases when you may create a custom config page or need to overwrite all project settings for an external module. Note: Due to a bug, this method was broken (did nothing) in framework versions <5.
getSurveyResponses([<br>&emsp;'pid' => ...,<br>&emsp;'event' => ...,<br>&emsp;'form' => ...,<br>&emsp;'record' => ...,<br>&emsp;'instance'' => ...,<br>]) | 10.9.3 | Returns a MySQL result resource containing all survey responses and related columns that match the parameters in the supplied array.  An single array is expected as a parameter as shown to the left.  All array parameter values are optional, but at least one must be specified. The `pid` will default to `$_GET['pid']` if not specified.  Keep in mind that for public surveys, multiple response rows exist in some cases (though the first participant is often what is needed).
setSystemSetting($key,&nbsp;$value) | 8.0.0 | Set the setting specified by the key to the specified value systemwide (shared by all projects).  This method is NOT restricted to settings that exist in `config.json`.
setUserSetting($key, $value) | 8.3.0 |  Sets the setting specified by the key to the given value for the current user and project.  This method does nothing on surveys and NOAUTH pages.  
throttle($sql, $parameters, $seconds, $maxOccurrences) | 10.8.1 | Used to limit certain actions to a specified number of occurrences within a given time period.  Any actions that should be throttled are expected be logged using the `log()` method.  The first two arguments work just like `queryLogs()`.  This method will search for logs matching the given `$sql` as far back in time as the `$seconds` argument.  If the number of logs found is less than `$maxOccurrences`, `false` is returned, and the action should not be throttled.  If the number of logs found is greater than or equal to `$maxOccurrences`, `true` is returned, and the action should be throttled.  <br>[Click here for examples](throttle.md)
tt($key[, $value, ...]) | 9.5.0 | Returns the language string identified by `$key`, optionally interpolated using the values supplied as further arguments (if the first value argument is an array, its elements will be used for interpolation and any further arguments ignored). Refer to the [internationalization guide](../i18n-guide.md) for more details.
tt_addToJavascriptModuleObject(<br>&emsp;$key, $item<br>) | 9.5.0 | Adds an item (such as a string, number, or array), identified by the given key, to the _JavaScript Module Object_'s language string store, where it then can be retrieved using the `tt()` function of the _JavaScript Module Object_.
tt_transferToJavascriptModuleObject(<br>&emsp;[$key[, $value[, ...]]]<br>) | 9.5.0 | Transfers one (interpolated) or many language strings (without interpolation) to the _JavaScript Module Object_. When no arguments are passed, or `null` for `$key`, all strings defined in the module's language file are transferred. An array of keys can be passed to transfer multiple language strings. When `$key` is a string, further arguments can be passed which will be used for interpolation (if the first such argument is an array, its elements will be used for interpolation and any further arguments ignored).
validateSettings($settings) | 8.0.2 | Override this method in order to validate settings at save time.  If a non-empty error message string is returned, it will be displayed to the user, and settings will NOT be saved.

#### Deprecated Methods

The following methods still exist for backward compatibility, but should NOT be used going forward as they could be removed or modified in the future:

Method<br><br>&nbsp; | Minimum<br>REDCap<br>Version | Description<br><br>&nbsp;
--- | --- | ---
~~hasPermission($permissionName)~~ | 8.0.0 | Checks whether the current External Module has permission for $permissionName.  This method was deprecated because it is not helpful to module authors (only the framework internally).
~~saveFile($filePath[, $pid])~~ | 8.0.0 | Saves a file and returns the new edoc ID.  This method was deprecated because:<br> - It deletes the file passed into it.<br> - It does not check the existence of the file passed into it, but instead returns an edoc ID of zero when a file doesn't exist (an exception would likely be more appropriate).<br> - It does not provide a way to save the new edoc ID to a field.<br><br>[Issue #356](https://github.com/vanderbilt-redcap/external-module-framework/issues/356) was created to track ideas for more complete solutions.
~~saveMetadata($pid, $metadata[, $preventLogging=false])~~ | 10.3.1 | Given a project id and a metadata array, expected format returned by dataDictionaryCSVToMetadataArray(), saves the metadata. The `$preventLogging` argument prevents logging the metadata changes, and by default is false.  This method was deprecated because it never actually worked.
~~setData($record, $fieldName, $values)~~ | 8.0.0 | Sets the data for the given record and field name to the specified value or array of values.  This method was deprecated because there are many cases it does not handle. It was a first draft of an idea that never matured.

#### Project Object
The following methods are available on the `Project` object returned by `$module->getProject()`.  Starting in framework version 7, these methods may also be called directly from the module object if a `pid` GET variable is set.  For example, `$module->getUsers()` may be used in place of `$module->getProject()->getUsers()`.

Method<br><br>&nbsp; | Minimum<br>REDCap<br>Version | Description<br><br>&nbsp;
--- | --- | ---
addRole($roleName, $rights) | 10.4.1 | Add a role with the given name to a project.  The `$rights` argument is expected in the format returned by `getRights()`.
addUser($username[, $rights]) | 10.4.1 | Adds a user to a project, optionally with the specified rights in the format returned by `getRights()`.
addOrUpdateInstances($instances, $keyFieldNames) | 10.3.1 | Allows adding or updating of repeating form instances by matching the values of one or more "key" fields on each instance.  This method is useful for keeping a repeating instrument in sync with an external data source that uses IDs that aren't compatible with `redcap_repeat_instance`, or uses a combination of multiple fields as a unique identifier.  The `$instances` specified should be in the format returned by `REDCap::getData()` when using the `json` type and using `json_decoding($data, true)` to convert the data into a PHP array.  This method does not remove existing fields or instances that are not specified (though it could be modified to do so if anyone wants to work on it).  The result of the underlying `REDCap::saveData()` call will be returned, and should be checked for any errors or warnings.  For convenience, this method may also be called directly on the module object for the current project.
getField($fieldName) | 10.8.2 | Returns a `Field` object as documented below for the given field name.
getForm($formName) | 10.8.2 | Returns a `Form` object as documented below for the given form name.
getFormForField($fieldName) | 10.3.1 | Returns the form name for the given field.
getLogTable() | 10.8.2 | Returns the name of the event log table used for this project.
getProjectId() | 9.8.1 | Returns the project id.
getRepeatingForms([$eventId]) | 10.8.2 | Returns an array of repeating form names for this project, and optionally the given event ID.
getRights($username) | 10.4.1 | Returns the rights for the given user on the current project.
getTitle() | 10.2.2 | Returns the project title.
getUsers() | 8.11.10 | Returns an array of `User` objects for each user with rights on the project.
queryData($sql) | 10.8.2 | **BETA:** An experimental alternative to `REDCap::getData()` that executes filter logic via SQL rather than PHP.  See [this page](query-data.md) for details.
removeRole() | 10.4.1 | Removes a role from a project.
removeUser($username) | 10.4.1 | Removes a user from a project.
setRights($username, $rights) | 10.4.1 | Sets the rights for a specified user.  The `$rights` argument is expected in the format returned by `getRights()`.
setRoleForUser($roleName, $username) | 10.4.1 | Sets the role for the given user.

#### Form Object
The following methods are available on the `Form` object returned by `$project->getForm()`.

Method<br><br>&nbsp; | Minimum<br>REDCap<br>Version | Description<br><br>&nbsp;
--- | --- | ---
getFieldNames() | 10.8.2 | Returns the field names on this form.


#### Field Object
The following methods are available on the `Field` object returned by `$project->getField()`.

Method<br><br>&nbsp; | Minimum<br>REDCap<br>Version | Description<br><br>&nbsp;
--- | --- | ---
getType() | 10.8.2 | Returns the field type.


#### User Object
The following methods are available on the `User` object returned by `$module->getUser()`.

Method<br><br>&nbsp; | Minimum<br>REDCap<br>Version | Description<br><br>&nbsp;
--- | --- | ---
getUsername() | 9.8.1 | Returns the username.
getEmail() | 8.11.10 | Returns the user's primary email address.
getRights([$projectIds]) | 8.11.10 | Returns this user's rights on the specified project id(s).  If a single project id is specified, the rights for that project are returned.  If multiple project ids are specified, an array is returned with project id indexes pointing to rights arrays.  If no project ids are specified, rights for the current project are returned.
hasDesignRights([$projectId]) | 8.11.10 | Returns true if the user has design rights on the specified project.  The current project is used if no project id is specified.
isSuperUser() | 8.11.10 | Returns true if the user is a super user.

<h4 id='em-jsmo'>JavaScript Module Object</h4>

A JavaScript version of any module object can be initialized by including the JavaScript code block returned by the PHP module object's `initializeJavascriptModuleObject()` method at any point in any hook. The name of the _JavaScript Module Object_ is returned by the framework method `getJavascriptModuleObjectName()`. Here is a basic example of how to initialize and use the _JavaScript Module Object_ from any PHP hook:

```php
<?=$this->initializeJavascriptModuleObject()?>

<script>
    $(function() {
        const module = <?=$this->getJavascriptModuleObjectName()?>;

        module.log('Hello from JavaScript!').then(function(logId) {
            // Do stuff with the logId
        }).catch(function(err) {
            // Report error
        });

        const data = {
            greeting: "Hello Action"
        };
        module.ajax('MyAction', data).then(function(response) {
            // Do stuff with response
        }).catch(function(err) {
            // Report error
        });
    })
</script>
```

The _JavaScript Module Object_ provides the following methods framework version 2 and up:

Method<br><br>&nbsp; | Minimum<br>REDCap<br>Version | Description<br><br>&nbsp;
--- | --- | ---
afterRender(action) | 12.2.10 | Accepts a function to be called after the page has finished rendering AND if/when it is re-rendered when switching languages using the Multi-Language Management feature. Please make sure it is safe to call the given `action` multiple times. This method could be expanded in the future to handle other scenarios where the page is re-rendered. Actions can be registered at any time, even before the DOM is ready, but will be called the earliest when the DOM is ready.
ajax(action, data) | 12.5.9 | Performs a server request (POST) with the string _action_ and the payload _data_ (any type) and returns a `Promise`. Response and errors can then be acted upon in its `.then` and `.catch` methods (see example above). The module must implement the `redcap_module_ajax` hook to process the request.  See [this page](../ajax.md) for more details.
getUrl(path, noAuth = false) | 11.2.3 | Works similarly to the PHP method with the same name, except that API endpoints are always returned.
getUrlParameter(name) | 8.11.10 | Returns the value for the specified GET/URL parameter.
getUrlParameters() | 8.11.10 | Returns an object containing all GET parameters for the current URL.
isImportPage() | 8.11.10 | Returns true if the current page is a **Data Import Tool** page.
isImportReviewPage() | 8.11.10 | Returns true if the current page is the **Data Import Tool** review page.
isImportSuccessPage() | 8.11.10 | Returns true if the current page is the **Data Import Tool** success page.
isRoute(routeName) | 8.11.10 | See the description for the PHP version of this method (above). 
log(message[, parameters]) | 8.11.10 | See the description for the PHP version of this method (above). The requirement for allow lists or other [input validation](https://cheatsheetseries.owasp.org/cheatsheets/Input_Validation_Cheat_Sheet.html#implementing-input-validation) for user sourced data is even more important in this context. The _enable-ajax-logging_ flag must be set to `true` in `config.json` to enable this method.  In a non-authenticated context, the _enable-no-auth-logging_ flag must also be set to `true` for all framework versions.  From framework version 11 on, a promise is returned that resolves to the ID of the log added.
tt(key[, value[, ...]]) | 9.5.0 | Returns the string identified by `key` from the language store, optionally interpolated with the values passed as additional arguments (if the first such value is an array or object, its elements/members are used for interpolation and any further arguments are ignored). Refer to the [internationalization guide](../i18n-guide.md) for more details.
tt_add(key, item) | 9.5.0 | Adds a (new) item (typically a string), identified by `key`, to the language store of the _JavaScript Module Object_. If an entry with the same name already exists in the store, it will be overwritten.
