## Querying The Database

The module framework is designed to encourage parameterized queries because they are commonly considered the [simplest and most effective way](https://cheatsheetseries.owasp.org/cheatsheets/SQL_Injection_Prevention_Cheat_Sheet.html) to prevent the quite common [SQL injection attack](https://www.owasp.org/index.php/SQL_Injection).  To ensure protection, all dynamic values in queries should be passed as parameters instead of being manually appended to the query string.  This includes user input, GET/POST data, values sourced from the database, etc.  See the **Breaking Changes** for [Framework Version 4](versions/v4.md) for the differences between parameterized & traditional queries. 


### Basic Queries

Here is a basic query example using the `query()` method:
```php
$result = $module->query(
  '
    select *
    from redcap_data
    where
      project_id = ?
      and record = ?
  ',
  [
    $project_id,
    $record_id
  ]
);
```
In the uncommon case of queries that really should not have any parameters, an empty array must be specificed to show that the use of parameters was seriously considered:
```php
$result = $module->query('select count(*) from redcap_user_information', []);
```

### Complex Queries & Query Objects
It is possible to use parameters in even the most complex dynamic queries.  Here is one example for how to generate a dynamic number of question marks for parameters on the fly:

```php
$sql = '
  select * from some_table
  where a = ?
'

$params = [$valueA];

if(!empty($someArray)){
  $questionMarks = [];
  foreach($someArray as $someValue){
    $questionMarks[] = '?'
    $params[] = $someValue;
  }

  $sql .= '
    and b in (' . implode(',', $questionMarks) . ')
  ';
}
```

A query object is also available to simplify query building if desired:
```php
$query = $module->createQuery();

$table = \Records::getDataTable($project_id);
$query->add("
  select *
  from $table a
  join $table b
  on a.record = b.record
  where
    project_id = ?
", $project_id);

if(is_array($event_ids)){
  $query->add('and')->addInClause('a.event_id', $event_ids);
}

if($record_id && $instance){
  $query->add('and record = ? and instance = ?', [$record_id, $instance]);
}

$query->add('group by a.record');

$result = $query->execute();

while($row = $result->fetch_assoc()){
    //Do something
}
```

Query objects can also be used to get the number of affected rows since the `db_affected_rows()` method will not work with parameters:
```php
$query = $module->createQuery();
$query->add('update my_custom_table where column = ?', $value);
$query->execute();
$affected_rows = $query->affected_rows;
```

The following query object properties are supported:

Property | Description
-- | --
affected_rows | Returns the number of rows affected by the query just like `db_affected_rows()` does for queries without parameters.

The following query object methods are supported:

Method | Description
-- | --
add($sql, $parameters) | Adds any SQL & parameters to the query.  The `$parameters` argument behaves the same way as described in the `query()` method documentation.
addInClause($column_name, $values) | Adds a SQL `IN` clause for the specified column and list of values.  An `OR IS NULL` clause is also added if any value in the list is `null`.  This is simply a convenience method to cover the most common use cases.  More complex `IN` clauses can still be built manually using `add()`.
execute() | Executes the SQL and parameters that have been added, and returns the standard [mysqli_result](https://www.php.net/manual/en/class.mysqli-result.php) object.

There are several ways of using the functions. For example, the following are all equivalent:
```php
$this->query("
  select *
  from foo
  join some_table
")
```
```php
$query = $module->createQuery();
$query->add("
  select *
  from foo
  join some_table
")
$query->execute();
```
```php
$query = $module->createQuery();
$query->add("select *");
$query->add("from foo");
$query->add("join some_table");
$query->execute();
```

### Differences With & Without Parameters
Queries with parameters have a couple of behavioral differences from queries with an empty parameter array specified.  This is due to MySQLi historical quirks.  The differences are as follows:

- The `db_affected_rows()` method does not work for queries with parameters.  See the documentation above for an alternative.
- Numeric column values will return as the `int` type in PHP where they previously returned as `string`.  This may require changes to any type sensitive operations like triple equals checking.  The simplest solution to prevent potential issues without refactoring is to cast the numeric columns in either SQL or PHP.
    - In PHP you can cast all integer columns to strings manually, or by using the following utility method on each fetched row:
      - `$row = $module->convertIntsToStrings($row);`
    - In SQL you can cast values individually.  For example:
      - Before: `select project_id`
      - After: &nbsp;&nbsp;`select cast(project_id as char) as project_id`.
