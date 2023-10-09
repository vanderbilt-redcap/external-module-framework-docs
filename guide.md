# REDCap External Module Development Beginner's Guide

The initial version of this guide was contributed by the [University of Florida Clinical and Translational Science Institute](https://www.ctsi.ufl.edu/).  See the [acknowledgements](ACKNOWLEDGEMENTS.md) page for details.

This guide provides the technical background developers need to write and test module code. It describes the REDCap classes and resources that simplify module development. This guide has recommendations for reference materials and tools to aid in module development and a series of exercises to create simple modules that extend REDCap in different ways.

## Reference Materials

### REDCap Training Videos

You must understand how to use REDCap before trying to develop modules for it. Vanderbilt provides a series of videos that provide basic training in how to use REDCap to create and administer instruments and surveys. See [https://projectredcap.org/resources/videos/](https://projectredcap.org/resources/videos/)

The University of Colorado Denver has created a series of videos that address more advanced topics. You can access all of those videos at their [YouTube Playlist](https://www.youtube.com/playlist?list=PLrnf34ZtZ9FpHcZyZuNnNFZ9cEbhijNGf).


### REDCap Repo

Vanderbilt publishes modules submitted by the REDCap Community in the [REDCap Repo](https://redcap.vanderbilt.edu/consortium/modules/index.php). The source code for each module is accessible in GitHub and linked from the entries in the REDCap Repo. These modules provide fully functional code examples. As each module in the REDCap Repo must have an open-source license, you are free to use their code in other modules.


### GitHub

Beyond those in the REDCap Repo, [GitHub](https://github.com) is commonly used by developers in the REDCap community to host and share modules. Many module developers tag their modules with the topic 'redcap-external-module'. This shared topic allows you to find them with a [GitHub topic search](https://github.com/search?q=topic%3Aredcap-external-module&type=Repositories)


### Software Setup

People new to development or those teaching a development class will likely find the [University of Florida's EMD-101 Guide](https://ctsit.github.io/redcap_external_module_development_guide/emd101) useful. These instructions install everything required to build a local REDCap development environment using Docker and GitHub tools. As always, you'll need to get a redcap.zip file from your local REDCap Admins, but everything else is freely downloadable from the public internet.

### Building Your First External Module Video

The exercises below are a great step by step guide. For those who might also benefit from seeing some of these steps in action, see the [Building Your First External Module](https://www.youtube.com/watch?v=UEGshbg5gLw) video from REDCapCon 2023.  Please forgive Mark's errors and typos!

## External Module Development Exercises

The External Module Development Guide includes a set of [development exercises](exercises) to use as a guide for module development. Each exercise teaches a different facet of module development. The majority of the exercises are missing essential functionality with comments denoting the regions where you will need to add code to implement a missing feature.

### Getting the development exercises
1. [Download the contents of this repo](https://github.com/vanderbilt-redcap/external-module-framework-docs/archive/refs/heads/main.zip)
1. Extract the downloaded zip
1. Copy each of the following directories from the `exercises/` folder to `<redcap-root>/modules/`, where `<redcap-root>` is the root directory of your local REDCap installation:
    ```
    accessing_variables
    hello_world_v0.0.0
    intro_to_hooks
    intro_to_js
    intro_to_pages
    intro_to_queries
    record_wrangling
    ```
1. Perform these steps for each of the above module directories.   You can do this for all modules now, or wait until you reach the exercise for each below.
    - Append the `_v0.0.0` version suffix to each directory name. A version suffix has already been appended to the `hello_world` exercise as an example.  Version suffixes are required for REDCap to recognize modules, and are intentionally left off to simulate having just cloned each module from a public repository.  For more details on module directory naming, see the [Directory Names](directory-names.md) page. 
    - Enable each module under **Control Center -> External Modules -> Manage**
    - Enable the module on a project under **Any Project Page -> External Modules -> Manage**, and use that project when testing each of the exercises below.  This is required since some hooks only run on project pages by default.
1. Complete each exercise as described and/or by finding and modifying the sections of the code labeled `FIXME` in each module.
1. Compare your solution to the `Example Solution` provided for each exercise

---

### [Hello World](exercises/hello_world_v0.0.0/)
This is a "complete" module intended to be used to make sure your development pipeline is set up properly.  It displays a hello world message on all project pages.  Review the content of this module to see the minimum code required to create a module with a simple hook.  For more information on basic module requirements, see the [Requirements Page](requirements.md).

---

### [Hello Hook](exercises/intro_to_hooks/)

This module serves as an introduction to hooks by providing an alert that notifies users it is active with a friendly "Hello world!" message. You will learn how to utilize hook functions to run arbitrary code - in this case, a small bit of JavaScript that displays an alert. While you will not be _writing_ any JavaScript for this portion, you will see how to load in JavaScript files, and how to expose backend variables to the frontend.

Read the [Using Hooks in Modules](hooks.md) page.

<details>
<summary>Example Solution
</summary>

`HelloHookModule.php`
```php
    // FIXME
    /* Write your code here */
    function redcap_every_page_top($project_id) {
    /* Stop writing code here */
        $this->includeJs('js/hello_hook.js');
```

</details>
<br />

---

### [Intro JS](exercises/intro_to_js/)

This module teaches best practices when including JavaScript in your External Modules by adding a clickable counter to the home page of projects for which it is enabled. It also introduces the use of the REDCap core class, `RCView`; the source for this class is located in the root of your REDCap folder at `Classes/RCView.php`. Note that while clever use of an `onclick` attribute might allow you to complete this module, the purpose is to work with a separate JavaScript file.

Read the [Method Documentation](methods/README.md) for the `getUrl()` method. You might also find it helpful to refer to previous exercises for examples of JavaScript use.

While this module does not use any variables, it is important to remember to [scope JavaScript variables and functions within an object](javascript.md). Two sample helper functions to accomplish this goal in PHP are written below.

```php
    protected function setJsSettings($settings) {
        echo '<script>const myModuleName = ' . json_encode($settings) . ';</script>';
    }

    // Recall that you must instantiate an empty JS object prior to the first call of this function, i.e.
    // echo '<script>const myModuleName = {};</script>';
    protected function setSingleJsSetting($key, $value) {
        echo "<script>myModuleName." . $key . " = " . json_encode($value) . ";</script>";
    }
```

<details>
<summary>Example Solution
</summary>

`IntroJSModule.php`
```php
        // FIXME
        // include a JavaScript file that increments the contents of incrementValue
        // upon clicking the incrementButton
        /* write your code below */
        $this->includeJs('js/intro.js');
    }

    protected function includeJs($file) {
        // Use this function to use your JavaScript files in the frontend
        echo '<script src="' . $this->getUrl($file) . '"></script>';
    }
```

`js/intro.js`
```javascript
$( document ).ready(function() {
    /* Write your code below */
    $('#incrementButton').click(function() {
        let inc = $('#incrementValue').text();
        $('#incrementValue').text(++inc);
    });
});
```

</details>
<br />

---

### [Hello Page](exercises/intro_to_pages/)

This module introduces the use of module pages. The provided module already has a page available for admins in the Control Center; the goal of this exercise is to add a second page accessible _at the project level_. Unlike other modules, you will need to create an entirely new PHP file for this project, referring to `pages/control_center_custom_page.php` should be useful.

Read [the documentation on pages](pages.md).

<details>
<summary>Example Solution
</summary>

`config.json`
```json
   "links": {
      "control-center": [
         {
            "name": "Hello Admin",
            "icon": "fas fa-globe",
            "url": "pages/control_center_custom_page.php"
         }
      ],
      "project": [
         {
            "name": "Hello Project",
            "icon": "fas hand-paper",
            "url": "pages/project_custom_page.php"
         }
      ]
   }
```

`pages/project_custom_page.php`
```php
<?php
require_once APP_PATH_DOCROOT . 'ProjectGeneral/header.php';

$title = RCView::img(['src' => APP_PATH_IMAGES . 'bell.png']) . ' ' . REDCap::escapeHtml('Control Center Page');
echo RCView::h4([], $title);

$module->sayHello();

require_once APP_PATH_DOCROOT . 'ProjectGeneral/footer.php';
```

</details>
<br />

---

### [Accessing Variables](exercises/accessing_variables/)

While working on this module, you will learn how to access constants and variables defined by REDCap. You will also use `project-settings` to allow users to set variables.

You may display this via a hook or a project page.

The goal of this exercise is to create a module that displays a user's:
1. Username
1. Admin (aka superuser) status
1. Their user rights
1. The current page path
1. The current project's `project_id`
1. The value of a variable set in the module's configuration menu

Check the [Method Documentation](methods/README.md) for methods which provide this information for you. Useful phrases to search for are "User" and "projectSetting".

<details>
<summary>Example Solution Via a Hook
</summary>

This is a _bare minimum_ implementation that demonstrates how to access REDCap variables. The output is _ugly_. An attractive display of those variables is left as part of the exercise.  
`AccessingVariablesModule.php`
```php
    //FIXME: Write and use functions to show users pertinent information
    function redcap_every_page_top() {
        $this->displayVars();
    }

    function displayVars() {
        print_r("<pre>"); // Wrap the display area in <pre> tag for formatting

        $userobj = $this->getUser();

        print_r("Username: " . $userobj->getUsername() . "\n");
        print_r("You are " . ( ($userobj->isSuperUser()) ? "" : "not " ) . "a superuser.\n");
        print_r("Your user rights: \n");
        var_dump($userobj->getRights());

        print_r("Page path: " . PAGE . "\n");
        print_r("Project ID: " . $this->getProjectId() . "\n"); // Display project ID via framework function
        // OR
        //print_r("Project ID: " . PROJECT_ID . "\n"); // Display project ID via constant
        print_r("Custom Setting: " . $this->getProjectSetting('custom_setting'));

        print_r("</pre>");
    }
```

`config.json`
```json
    "project-settings": [
        {
            "key": "custom_setting",
            "name": "Custom Setting",
            "type": "text"
        }
    ]
```

</details>
<br />

---

### [Record Wrangling](exercises/record_wrangling/)

**Setup**: A prebuilt project file is provided for this module. You will need to create a new project, select the "Upload a REDCap project XML file" option, and use the `RecordWrangling_project.xml` file located at the root of the module directory.

This module introduces you to interactions with the `redcap_data` table. You will finish a page that will allow a user with admin rights to insert an arbitrary text value into a field across all records of a project. You should look at the documentation for the `getData()` and `saveData()` methods on your REDCap instance under **Control Center -> Plugin, Hook, & External Module Documentation**.

This module uses AJAX. Your updates should be made in `pages/ajaxpage.php`. You should still look through the other files to understand the module as a whole.

<details>
<summary>Example Solution
</summary>

`pages/ajaxpage.php`
```php
    //FIXME: use a function to getData and assign it to a variable called $redcap_data

    $redcap_data = \REDCap::getData([
        'project_id' => $pid,
        'fields' => $field_name
    ]);

    $module->changeField($redcap_data, $field_name, $new_value); // update the $redcap_data array inplace

    //FIXME: use a function to target this project's $pid and use the array $redcap_data to overwrite
    // the database

    \REDCap::saveData($pid, 'array', $redcap_data, 'overwrite');

    /* Log what was done
```

</details>
<br />

---

### [Intro to Queries](exercises/intro_to_queries/)

In this module, you will complete a page that allows admins to assign and revoke privileges for users in bulk. Make your changes in `IntroQueriesModule.php`; you will need to write a few lines of SQL to make an `UPDATE` statement. If you visit the page before you complete the `gatherUsers` function, you will receive a fatal error in `IntroQueriesModule.php`. This error is normal. Work the problem.

You will occasionally have to write SQL queries; most often, this need will arise when writing a module that adds a feature for REDCap admins. Writing your own SQL should be a last resort after you have considered all of your builtin options.

While evaluating builtin options for this exercise, you might be tempted to use `getUser()`. It won't meet your needs, but it's worth exploring why. For this module, you need a class method that lists _all_ users, but `getUser()` does not do that.
When a module call doesn't work, look at its source code to see if it calls a core class. `getUser()` is a wrapper around `\REDCap::getUsers()` which might seem useful, but calling this is _also_ unsuitable since it is only allowed in a project context. As we are writing a page for the Control Center, `\REDCap::getUsers()` will fail. The project context requirement in `\REDCap::getUsers()` forces us to write a SQL query of REDCap's user information table.

You will probably find your [docker environment's PHPMyAdmin container](http://localhost/phpmyadmin/) useful for this exercise. Adjust that URL to match the port number of your docker environment's web server container if it fails.

Read the [Method Documentation](methods/README.md). Search for the `query` function.

<details>
<summary>Example Solution
</summary>

`IntroQueriesModule.php`
```php
    function gatherUsers() {
        // FIXME: use $sql with an appropriate function to get a list of every user

        $sql = 'SELECT username
            FROM redcap_user_information';

        $result = $this->query($sql);

        /* stop writing here */
        // parse the mysqli response object into an array
        $username_array = array_column(
            $result->fetch_all(MYSQLI_ASSOC),
            'username'
        );
        return $username_array;
    }

    function alterUsers($users, $new_value) {
        $users = implode('", "', $users);
        // FIXME: write and run the SQL command, log what was done

        $questionMarks = [];
        foreach($users as $user){
            $questionMarks[] = '?';
        }

        $sql = 'UPDATE redcap_user_information
            SET allow_create_db = ?
            WHERE username IN (' . implode(',', $questionMarks) . ')';

        $result = $this->query($sql, [$new_value, $users]);

        if ($result) {
            // Log what was done if successful
            $this->log("Set allow_db to $new_value for users: \"$users\"");
        }

        return $result;
    }
```

</details>
<br />
