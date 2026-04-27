## User-defined SQL Queries

External modules can allow administrators to define and save their own SQL queries that can be run via the External Module Framework.  This is done by setting the **enable-user-queries** flag to `true` in the module's `config.json` file. Once enabled, the module can use the following methods:

* `runUserQuery` - Runs a user-defined `SELECT` query identified by `$key` with the specified parameters. Only `SELECT` queries are supported. Returns a standard [mysqli_result](https://www.php.net/manual/en/class.mysqli-result.php) object.
* `saveUserQuery` - Saves a user-defined query identified by `$key` with the specified SQL.  If a query with the same key already exists, it will be overwritten.  Only administrators can save user-defined queries.
* `deleteUserQuery` - Deletes the user-defined query identified by `$key`.  Only administrators can delete user-defined queries.
* `getUserQuery` - Retrieves the SQL for the user-defined query identified by `$key`.  Returns `null` if no query with that key exists.
* `getUserQueryKeys` - Retrieves an array of all user-defined query keys.


These methods were written to allow user-defined queries to be saved and used by external modules while still maintaining security. Only administrators can save, delete, or list user-defined queries.  Regular users can only run queries that have been saved by an administrator.  Additionally, only `SELECT` queries are allowed to be saved and run. Finally, the methods described here represent an approved way to run user-defined queries that facilitates taint analysis.

### Example Usage

These methods are meant to be used by administrators to save custom SQL `SELECT` queries that can then be run by other (regular) users of the module.


For example, if a module wants to allow administrators to save a custom query and then allow other users to run that query, the code might look like this:
```php
// Save a user-defined system-level query
$module->saveUserQuery('my_query', 'SELECT * FROM redcap_user_information WHERE id = ?');

// Run the user-defined system query with a parameter
$userQuery = $module->getUserQuery('my_query'); // = 'SELECT * FROM redcap_user_information WHERE id = ?'
$module->runUserQuery($userQuery, [1]);
```
The same query could be saved at the project level by passing a project ID as the third parameter to `saveUserQuery`:
```php
// Save a user-defined project-level query
$project_id = $module->getProjectId();
$module->saveUserQuery('my_project_query', 'SELECT * FROM redcap_user_information WHERE id = ?', $project_id);

// Run the user-defined project query with a parameter
$userQuery = $module->getUserQuery('my_project_query', $project_id);
$module->runUserQuery($userQuery, [1]);
```

Admins can run queries whether or not they are saved, but regular users can only run queries that have been saved by an admin:

```php
$nonSavedQuery = $_POST['query'];

// This is allowed for admins, but not for regular users
$module->runAdminQuery($nonSavedQuery);

// This is not allowed
$module->runUserQuery($nonSavedQuery);
```

To delete a user-defined query, an admin would do the following:
```php
$module->deleteUserQuery('my_query');
``` 

To list all user-defined queries, an admin would do the following:
```php
$systemLevelUserQueryKeys = $module->getUserQueryKeys();
$projectLevelUserQueryKeys = $module->getUserQueryKeys($project_id);
```
