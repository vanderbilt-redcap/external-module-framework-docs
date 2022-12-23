## Module Logs

Modules have built-in `log()`, `queryLogs()`, and `removeLogs()` methods that can be used for any common logging or historical data storage purposes.  If you have not already, please read the documentation for these three methods on the [method documentation](methods.md) page.  Example usage for each method can be found below:

### Storing Logs
```php
$logId = $module->log("Some simple message");
```

```php
$logId = $this->log(
	"Some message and associated parameters",
	[
		"your_parameter_name"=> 123,
		"your_other_parameter_name"=> "some string"
	]
);
```

### Querying Logs
The `queryLogs()` method works similarly to the `query()` method, and can be used as follows:
```php
$pseudoSql = "select timestamp, user where message = ?"
$parameters = ['some message'];

$result = $module->queryLogs($pseudoSql, $parameters);
while($row = $result->fetch_assoc()){
	...
}
```

Here is an example of more complex query arguments:

```php
$pseudoSql = "
	select log_id, message, ip, your_parameter_name
	where
		timestamp > ?
		and project_id in (?, ?)
		and user in (?, ?)
		or your_parameter_name like ?
	order by timestamp desc
";

$parameters = [
	'2017-07-07',
	'123',
	'456',
	'joe',
	'tom',
	'%' . 'abc' . '%'
];
```

### Removing Logs

```php
$module->removeLogs('your_parameter_name = ?', 'some value');
```

```php
$module->removeLogs('
	timestamp < ?
	and message = ?
	and your_parameter_name in (?,?)
', [
	'2021-01-01',
	'some message',
	'your parameter value',
	'your other parameter value'
]);
```
