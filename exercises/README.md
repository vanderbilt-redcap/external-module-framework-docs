# External Module Development Exercises

The External Module Development Guide includes a set of development exercises to use as a guide for module development. Each exercise teaches a different facet of module development. The majority of the exercises are missing essential functionality with comments denoting the regions where the functionality should be added.

## Preface

For guidance in developing modules and resources for completing these exercises, see [REDCap External Module Development Guide](https://ctsit.github.io/redcap_external_module_development_guide/). 

## Modules

All of module development exercises reside in the Git repo that houses this document at [`https://github.com/ctsit/redcap_external_module_development_guide/exercises/`](https://github.com/ctsit/redcap_external_module_development_guide/exercises/)

### Hello World
This is a "complete" module intended to be used to make sure your development pipeline is set up properly.

Read the section on [module requirements](https://github.com/vanderbilt/redcap-external-modules/blob/testing/docs/official-documentation.md#module-requirement) until the section on hooks.

### Hello Hook

Read [the official documentation on calling hooks](https://github.com/vanderbilt/redcap-external-modules/blob/testing/docs/official-documentation.md#how-to-call-redcap-hooks).

### Intro JS

Read [the official documentation on module functions, specifically `getUrl`](https://github.com/vanderbilt/redcap-external-modules/blob/testing/docs/framework/v3.md). You may also find it helpful to refer to previous exercises where JavaScript was used.

## Hello Plugin

Read [the official documentation on creating plugin pages](https://github.com/vanderbilt/redcap-external-modules/blob/testing/docs/official-documentation.md#how-to-create-plugin-pages-for-your-module).

## Accessing Variables

Read [the official documentation on module functions](https://github.com/vanderbilt/redcap-external-modules/blob/testing/docs/framework/intro.md). Search for functions containing `User` and `ProjectSetting`.

## Record Wrangling

Read the source code of the following files (relative to the root of your `redcap_vx.y.z` folder), searching for phrases `getData`, `saveData`.
- `ExternalModules/`
    - `AbstractExternalModule.php`
    - `ExternalModules.php`
- `Classes/`
    - `REDCap.php`
    - `Records.php`

## Intro to Queries

Read [the official documentation on module functions](https://github.com/vanderbilt/redcap-external-modules/blob/testing/docs/framework/intro.md). Search for the `query` function.
