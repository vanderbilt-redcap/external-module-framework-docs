---
layout: default
title: REDCap External Module Development for Developers
---

# REDCap External Module Development for Developers

This guide provides the technical background developers need to write and test module code. It describes the REDCap classes and resources that simplify module development. This guide has recommendations for reference materials and tools to aid in module development and a series of exercises to create simple modules that extend REDCap in different ways.

## Reference Materials

### REDCap Training Videos

You must understand how to use REDCap before trying to develop modules for it. Vanderbilt provides a series of videos that provide basic training in how to use REDCap to create and administer instruments and surveys. See [https://projectredcap.org/resources/videos/](https://projectredcap.org/resources/videos/)

The University of Colorado Denver has created a series of videos that address more advanced topics. You can access all of those videos at their [YouTube Playlist](https://www.youtube.com/playlist?list=PLrnf34ZtZ9FpHcZyZuNnNFZ9cEbhijNGf).


### Vanderbilt's External Module Documentation

You will likely find it helpful to keep Vanderbilt's [official External Module documentation](https://github.com/vanderbilt/redcap-external-modules/blob/testing/docs/official-documentation.md) available while completing these exercises; this document will link relevant sections for review.

**Note:** this document repeatedly references the GitHub repository for External Modules; unfortunately, that repository has been made private and its links will no longer work. Luckily, the documentation exists in your local REDCap instance at **Control Center > Plugin, Hook, & External Module Documentation > External Module Documentation**.


### REDCap Repo

Vanderbilt publishes modules submitted by the REDCap Community in the [REDCap Repo](https://redcap.vanderbilt.edu/consortium/modules/index.php). The source code for each module is accessible in GitHub and linked from the entries in the REDCap Repo. These modules provide fully functional code examples. As each module in the REDCap Repo must have an open-source license, you are free to use their code in other modules.


### GitHub

Beyond those in the REDCap Repo, [GitHub](https://github.com) is commonly used by developers in the REDCap community to host and share modules. Many module developers tag their modules with the topic 'redcap-external-module'. This shared topic allows you to find them with a [GitHub topic search](https://github.com/search?q=topic%3Aredcap-external-module&type=Repositories)


### Software Setup

People new to development or those teaching a development class will likely find the [EMD-101: Building a REDCap Environment](emd101) useful. These instructions install everything required to build a local REDCap development environment using Docker and GitHub tools. As always, you'll need to get a redcap.zip file from your local REDCap Admins, but everything else is freely downloadable from the public internet.


## Setting Up Your Environment
Read the section of the official documentation on [naming a module](https://github.com/vanderbilt/redcap-external-modules/blob/testing/docs/official-documentation.md#naming-a-module). You should copy the contents of this directory to the `modules` directory of your REDCap instance. Assuming you are using Andy Martin's [redcap-docker-compose environment](https://github.com/123andy/redcap-docker-compose), the modules directory is located at `www/modules/`.  
Please note that - except for **Hello World** - you will need to mark the [semantic version](https://semver.org/) of each module - in short, you will need to append `_v0.0.0` to each directory - for the EM Framework to recognize it is a module. These are intentionally left off to simulate having just `git clone`d a module from a public repository. Public repositories should _not_ have semantic versions in their titles. REDCap uses the directory name to determine module versions. As such, each version will have a separate directory. Use `git tag`s to apply version numbers to the appropriate commit.


## External Module Development Exercises

The External Module Development Guide includes a set of [development exercises](https://github.com/ctsit/redcap_external_module_development_guide/exercises/) to use as a guide for module development. Each activity teaches a different facet of module development. Most of the modules are intentionally incomplete. They generally have comments denoting regions where you will need to add code to implement a missing feature.

### Getting the development exercises

- Use GitHub Desktop to clone `ctsit/redcap_external_module_development_guide` (Go to File > Clone Repository, then paste `ctsit/redcap_external_module_development_guide` in the "github.com" tab).
- Use GitHub Desktop to open this new repo (REMDG) in Finder/Explorer. Copy everything in the `exercises/` folder to folder `redcap-docker-compose/www/modules/`. Don't copy the `exercises` folder; copy its _contents_.  These files should be inside the `redcap-docker-compose/www/modules/` folder

```
accessing_variables
hello_world_v0.0.0
intro_to_hooks
intro_to_js
intro_to_plugins
intro_to_queries
README.md
record_wrangling
```

---

### [Hello World]({{ site.repo_root }}exercises/hello_world_v0.0.0/)
This is a "complete" module intended to be used to make sure your development pipeline is set up properly.

Read the section on [module requirements](https://github.com/vanderbilt/redcap-external-modules/blob/testing/docs/official-documentation.md#module-requirement) until the section on hooks.

---

### [Hello Hook]({{ site.repo_root }}exercises/intro_to_hooks/)

This module serves as an introduction to hooks. You will learn how to utilize hook functions to run arbitrary code - in this case, a small bit of JavaScript that displays an alert. While you will not be _writing_ any JavaScript for this portion, you will see how to load in JavaScript files, and how to expose backend variables to the frontend.

Read [the official documentation on calling hooks](https://github.com/vanderbilt/redcap-external-modules/blob/testing/docs/official-documentation.md#how-to-call-redcap-hooks).

<details>
<summary>Example Solution
</summary>

`ExternalModule.php`
```php
    // FIXME
    /* Write your code here */
    function redcap_every_page_top($project_id) {
    /* Stop writing code here */
        $this->includeJs('js/hello_hook.js');
```

`config.json`
```json
    "permissions": [
        "redcap_every_page_top"
    ],
```

</details>
<br />

---

### [Intro JS]({{ site.repo_root }}exercises/intro_to_js/)

This module teaches best practices when including JavaScript in your External Modules. It also introduces the use of the REDCap core class, `RCView`; the source for this class is located in the root of your REDCap folder at `Classes/RCView.php`. Note that while clever use of an `onclick` attribute might allow you to complete this module, the purpose is to work with a separate JavaScript file.

Read [the official documentation on module functions, specifically `getUrl`](https://github.com/vanderbilt/redcap-external-modules/blob/testing/docs/framework/v3.md). You might also find it helpful to refer to previous exercises for examples of JavaScript use.

While this module does not use any variables, note that when working with JavaScript it is [recommended to scope the variables within an object](https://github.com/vanderbilt/redcap-external-modules/blob/testing/docs/official-documentation.md#javascript-recommendations). Two sample helper functions to accomplish this goal in PHP are written below.

```php
    protected function setJsSettings($settings) {
        echo '<script>myModuleName = ' . json_encode($settings) . ';</script>';
    }

    // Recall that you must instantiate an empty JS object prior to the first call of this function, i.e.
    // echo '<script>myModuleName = {};</script>';
    protected function setSingleJsSetting($key, $value) {
        echo "<script>myModuleName." . $key . " = " . json_encode($value) . ";</script>";
    }
```

<details>
<summary>Example Solution
</summary>

`ExternalModule.php`
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
        increase();
    });
});

/* If you wish, make a function */
function increase() {
    let inc = $('#incrementValue').text();
    $('#incrementValue').text(++inc);
}
```

</details>
<br />

---

### [Hello Plugin]({{ site.repo_root }}exercises/intro_to_plugins/)

This module introduces the use of plugins. The provided module already has a plugin page available for admins in the Control Center; the goal of this exercise is to add a second plugin page accessible _at the project level_. Unlike other modules, you will need to create an entirely new PHP file for this project, referring to `pages/control_center_custom_page.php` should be useful.

Read [the official documentation on creating plugin pages](https://github.com/vanderbilt/redcap-external-modules/blob/testing/docs/official-documentation.md#how-to-create-plugin-pages-for-your-module).

Plugins appear as links in the left-hand menu. The EM framework allows you to decorate the links with icons set in a `link` object under `config.json`. In framework version >= 3, you have access to [Font Awesome](https://fontawesome.com/icons?d=gallery) icons.  
When assigning Font Awesome icons, the entry will appear as follows: `fa<style> fa-<icon_name>`, where available `<style>`s are:
- s: solid
- r: regular
- l: light
- d: duotone

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

Don't forget to update docs when you add new features!  
`README.md`
```markdown
Provides pages that say "Hello, world" in the control center and in projects.
```

</details>
<br />

---

### [Accessing Variables]({{ site.repo_root }}exercises/accessing_variables/)

While working on this module, you will learn how to access constants and variables defined by REDCap. You will also use `project-settings` to allow users to set variables.

You may display this via a hook or a project plugin page.

The goal of this exercise is to create a module that displays a user's:
1. Username
1. Admin (aka superuser) status
1. Their user rights
1. The current page path
1. The current project's `project_id`
1. The value of a variable set in the module's configuration menu

Check the [official External Module documentation on functions](https://github.com/vanderbilt/redcap-external-modules/blob/testing/docs/framework/intro.md) for functions which provide this information for you. Useful phrases to search for are "User" and "projectSetting".

Note that there is an unlisted property of the [`User` object](https://github.com/vanderbilt/redcap-external-modules/blob/testing/docs/framework/intro.md#user-method) that you will need: 

```php
$User = $this->framework->getUser();
$User->username;
```

If your programming environment has a debugger available, you can use it to see the accessible constants. If you do not have a debugger or cannot connect it to your REDCap docker container, please view the list below. Note that most of these are available as functions.  
<details>
<summary>Useful global constants
</summary>

- **PAGE**: The path portion of the URL for the current web page. This is the only constant listed that is not easily determined by a function
- **PROJECT_ID**: The numerical ID of the project being viewed
- **USERID**: The username of the user viewing the page
- **SUPER_USER**: A boolean integer indication if the user from **USERID** has admin privileges

</details>
<br />

As before, documentation _must_ be updated when introducing features. This is even more important when adding functionality that users interact with (e.g. an entry in `project-settings` in the `config.json`)!

<details>
<summary>Example Solution Via a Hook
</summary>

This is a _bare minimum_ implementation that demonstrates how to access REDCap variables. The output is _ugly_. An attractive display of those variables is left as an exercise for the student.  
`ExternalModule.php`
```php
    //FIXME: Write and use functions to show users pertinent information
    function redcap_every_page_top() {
        $this->displayVars();
    }

    function displayVars() {
        print_r("<pre>"); // Wrap the display area in <pre> tag for formatting

        $userobj = $this->framework->getUser();

        print_r("Username: " . $userobj->username . "\n");
        print_r("You are " . ( ($userobj->isSuperUser()) ? "" : "not " ) . "a superuser.\n");
        print_r("Your user rights: \n");
        var_dump($userobj->getRights());

        print_r("Page path: " . PAGE . "\n");
        print_r("Project ID: " . $this->framework->getProjectId() . "\n"); // Display project ID via framework function
        // OR
        //print_r("Project ID: " . PROJECT_ID . "\n"); // Display project ID via constant
        print_r("Custom Setting: " . $this->framework->getProjectSetting('custom_setting'));

        print_r("</pre>");
    }
```

`config.json`
```json
    "permissions": [
        "redcap_every_page_top"
    ],
    "project-settings": [
        {
            "key": "custom_setting",
            "name": "Custom Setting",
            "type": "text"
        }
    ]
```

An unordered list is a great way to explain simple options to users.  
`README.md`
```markdown
Displays information relevant to users, including their user permissions and location in REDCap.

...

## Project Configuration
- **Custom Setting**: A message that will be displayed along with other information

```

</details>
<br />

---

### [Record Wrangling]({{ site.repo_root }}exercises/record_wrangling/)

**Setup**: A prebuilt project file is provided for this module. You will need to create a new project, select the "Upload a REDCap project XML file" option, and use the `RecordWrangling_project.xml` file located at the root of the module directory.

This module introduces you to interactions with the `redcap_data` table. You will finish a plugin that will allow a user with admin rights to insert an arbitrary text value into a field across all records of a project. You should look in `Classes/REDCap.php` and `ExternalModules/AbstractExternalModule.php` in the root of your REDCap directory for `getData` and `saveData` functions.

This module uses AJAX. Your updates should be made in `pages/ajaxpage.php`. You should still look through the other files to understand the module as a whole.

You may have noticed the `setData` function in the official module documentation. At first glance, this function may appear suitable for this exercise, but plugins do not provide the context it requires. It is more suited to _hooks_. While `setData` takes only 3 arguments (`$record`, `$fieldName`, and `$values`), it actually requires an `event_id` - a numerical identifier for the event - to be detected for it to function properly; this is fine for a hook that is intended to run on a record page. `setData`'s lack of logging is also an issue. Any write events to the REDCap database short of logging itself should be logged.

The solutions provided below use both built-in module methods and the REDCap class implementations of `getData` and `saveData`.

<details>
<summary>Example Solution
</summary>

`pages/ajaxpage.php`
```php
    //FIXME: use a function to getData and assign it to a variable called $redcap_data

    /* method 1 a: use the module getData method
     * passing project ID and an empty array for record ID to get data ALL data for the project ID
     * Not listed in official documentation, must read AbstractExternalModule source code
     */
    $redcap_data = $module->framework->getData($pid, []);

    /* method 1 b: use the REDCap class directly
     * pass only the project ID
     * returns ALL data for the project ID
     */
    $redcap_data = \REDCap::getData($pid);

    /* method 2: use the REDCap class directly
     * build an array specifying project ID and fields
     * returns only the relevant field(s)
     */
    $get_data = [
        'project_id' => $pid,
        'fields' => $field_name
        ];
    $redcap_data = \REDCap::getData($get_data);

    $module->changeField($redcap_data, $field_name, $new_value); // update the $redcap_data array inplace

    //FIXME: use a function to target this project's $pid and use the array $redcap_data to overwrite
    // the database

    /* method 1: use the module saveData method
     * iterate over the altered $redcap_data array
     * repeatedly call the saveData method
     */
    foreach($redcap_data as $record_id => $events) {
        foreach($events as $event_id => $data) {
            $response = $module->framework->saveData($pid, $record_id, $event_id, $data);
        }
    }

    /* method 2: use the REDCap class directly
     * use the altered $redcap_data array in a single function call
     */
    \REDCap::saveData($pid, 'array', $redcap_data, 'overwrite');

    /* Log what was done
```

</details>
<br />

---

### [Intro to Queries]({{ site.repo_root }}exercises/intro_to_queries/)

In this module, you will complete a plugin page that allows admins to assign and revoke privileges for users in bulk. Make your changes in `ExternalModule.php`; you will need to write a few lines of SQL to make an `UPDATE` statement. If you visit the plugin page before you complete the `gatherUsers` function, you will receive a fatal error in `ExternalModule.php`. This error is normal. Work the problem.

You will occasionally have to write SQL queries; most often, this need will arise when writing a module that adds a feature for REDCap admins. Writing your own SQL should be a last resort after you have considered all of your builtin options.

While evaluating builtin options for this exercise, you might be tempted to use `framework->getUser`. It won't meet your needs, but it's worth exploring why. For this module, you need a class method that lists _all_ users, but `framework->getUser` does not do that.
When a module call doesn't work, look at its source code to see if it calls a core class. `framework->getUser` is a wrapper around `\REDCap::getUsers()` which might seem useful, but calling this is _also_ unsuitable since it is only allowed in a project context. As we are writing a plugin page for the Control Center, `\REDCap::getUsers()` will fail. The project context requirement in `\REDCap::getUsers()` forces us to write a SQL query of REDCap's user information table.

You will probably find your [docker environment's PHPMyAdmin container](http://localhost/phpmyadmin/) useful for this exercise. Adjust that URL to match the port number of your docker environment's web server container if it fails.

**NB**: Those of you familiar with SQL may wonder about the use of prepared statements. These are _not_ currently implemented but will be soon - in Framework Version 4 - read the [official documentation on queries](https://github.com/vanderbilt/redcap-external-modules/blob/release/docs/querying.md) for more information. An example of using prepared statements is provided in the solution below.

<details>
<summary>Example Solution
</summary>

`ExternalModule.php`
```php
    function gatherUsers() {
        // FIXME: use $sql with an appropriate function to get a list of every user

        $sql = 'SELECT username
            FROM redcap_user_information';

        $result = $this->framework->query($sql);

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

        $sql = 'UPDATE redcap_user_information
            SET allow_create_db = ' . $new_value . '
            WHERE username IN ("' . $users . '")';

        $result = $this->framework->query($sql);

         /* Example of a prepared statement in Framework v4
         $sql = 'UPDATE redcap_user_information
            SET allow_create_db = ?
            WHERE username IN (?)';
          */
        //$result = $this->framework->query($sql, [$new_value, $users]);

        if ($result) {
            // Log what was done if successfull
            $this->framework->log("Set allow_db to $new_value for users: \"$users\"");
        }

        return $result;
    }
```

</details>
<br />
