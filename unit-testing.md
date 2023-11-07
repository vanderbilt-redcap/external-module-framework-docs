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
