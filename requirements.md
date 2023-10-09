# Module requirements

To function, a module requires both a `config.json` file and a PHP module class file (e.g., `MyAwesomeModule.php`).  For submission to [The Repo](https://redcap.vanderbilt.edu/consortium/modules/), `LICENSE` and `README` files are also required.  The `README` is most often in Markdown (`.md`) file format.  The `config.json` file will contain all the module's basic configuration (display name, author information, configuration dialog settings, etc.). The PHP module class file generally houses most of the business logic for the module.  The module class can be named whatever you like so long as its file name, PHP class name, and the last portion of the namespace in `config.json` all match.  Keep in mind that a matching namespace in `config.json` will have an extra backslash due to escaping (see examples below).

## config.json

The file `config.json` provides all the basic configuration information for the module in JSON format. This file must define the following: **name, namespace, description, framework-version, and authors**. The `name` is the module title.  The `description` summarizes what the module does (typically a sentence or short paragraph).  The `authors` section documents the primary contact for the module, followed by anyone else who aided in its creation.  All of this information is displayed in the [Repo](https://redcap.vanderbilt.edu/consortium/modules/) and on the module management page in Control Center.

The `namespace` is the PHP namespace used in your module class, and helps prevent collisions between classes, functions, and constants defined by different modules. Module namespaces consist of at least two parts separated by backslashes. The first part is typically the name of the organization that created the module, while the second is typically the module's name.  **It is required that the last part of the namespace match the module's class name, as is common in [composer](https://getcomposer.org/) libraries.**

The `framework-version` exists solely for backward compatibility when breaking changes to the module framework are made.  For new modules, it is recommended to set this to the latest framework version supported by the current REDCap LTS version as documented at the bottom of [this page](versions/README.md).  That page also contains more details on framework versioning in general.

Many optional properties are also available.  See the [config.json page](config.md) for details.

Here's an example of the minimum requirements for `config.json`:

``` json
{
    "name": "Example Module",
    "namespace": "MyModuleNamespace\\MyModuleClassName", 
    "description": "This is a description of the module, and will be displayed below the module name in the user interface.",
    "framework-version": 12,
    "authors": [
        {
            "name": "Jon Snow",
            "email": "jon.snow@vumc.org",
            "institution": "Vanderbilt University Medical Center"
        }
    ]
}
```

## Module class

Each module must define a module class that extends `ExternalModules\AbstractExternalModule` (see the example below).  Your module class is the central PHP file that will run all the business logic for the module. You may have many other PHP files (classes or include files), as well as JavaScript, CSS, etc. All other such files are optional, but the module class itself is necessary and drives the module.

```php
<?php
// Set the namespace defined in your config file
namespace MyModuleNamespace\MyModuleClass;

// Declare your module class, which must extend AbstractExternalModule 
class MyModuleClassName extends \ExternalModules\AbstractExternalModule
{
    // Your module methods, constants, etc. go here
}
```
