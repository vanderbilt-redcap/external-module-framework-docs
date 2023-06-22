## External Module Framework - Official Documentation

"External Modules" is a class-based framework replacing legacy plugins and hooks in REDCap. Modules can utilize any of the "REDCap" class methods (e.g., \REDCap::getData), and they also come with many other helpful built-in methods to store and manage settings for a given module, as well as provide support for internationalization (translation of displayed strings) of modules. The documentation provided on this page will be useful for anyone creating an external module.

If you have created a module and wish to share it with the REDCap community, you may submit it to the [REDCap External Modules Submission Survey](https://redcap.vanderbilt.edu/surveys/?s=X83KEHJ7EA). If your module gets approved after submission, it will become available for download by any REDCap administrator from the [REDCap Repo](https://redcap.vanderbilt.edu/consortium/modules/).

### Available developer methods in External Modules

The External Modules framework provides objects representing a module, both in **PHP** and **JavaScript**.

The publicly supported methods that module creators may utilize depend on the framework version they opt into via the configuration file and are documented [here](methods/README.md).

Modules should **not** reference any undocumented methods, classes, files, etc. (like the *ExternalModules* class).  Undocumented code can change at any time. If you'd like additional functionality to be officially supported, please create an issue or pull request in GitHub.  Email mark.mcever@vumc.org in order to gain access to the [External Module Framework GitHub Repo](https://github.com/vanderbilt-redcap/external-module-framework).

### Logging from module code

The External Modules framework provides mechanism to log to the `redcap_external_modules_log` and `redcap_external_modules_log_parameters` tables. See the PHP and JavaScript versions of the `log()` method [here](methods/README.md).

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
