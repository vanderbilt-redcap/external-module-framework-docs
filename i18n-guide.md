# Internationalization of REDCap External Modules

## Background

EMs are incredibly useful. Some of their great benefits over e.g. hooks and plugins, are ease of use (installation), maintainability (both for admins and developers), and reusability by / tranferability to others ([EM Repo](https://redcap.vanderbilt.edu/consortium/modules/index.php)). This last point, however, is massively hampered when EMs provide a rich user interface for _end users_ (non-admins, non-developers) who may not understand English well enough or at all. Thus, module authors in non-English-speaking countries will often have no choice but to implement their modules in their respective language. Even if they would deem them general enough to benefit others (and hence the modules would be a candidates for the EM Repo), they may opt to not release them because they cannot maintain a separate version in English. Even if they released a non-English version of their module to e.g. GitHub and maybe even the Repo, other REDCap admins might not use it, despite it potentially being a perfect match for their needs _functionally_, because they aren't able to (or can't or won't) make the necessary translations directly in the module's code so it would qualify for their audiences.

In order to address these issues, support for internationalization (_i18n_) has been built into REDCap's External Module Framework.

External Module support for i18n is transparent. This means that existing modules will not have to be changed and will continue to work as they are. For a module to support i18n, module developers will have to opt-in to the i18n features provided by the EM framework.

### How it works

The mechanism to provide i18n for EMs is quite simple and is the same that REDCap Core is using already: `Language.ini` files with translatable strings indexed by unique keys. When an EM is initialized, the EM Framework checks whether the modules directory contains a subdirectory named `lang` exists (and whether it contains any `Language.ini` files), and if so, reads the one set to use (via a new system-level setting and a project-level override) and adds all language strings to REDCap's global `$lang` array. To avoid naming conflicts, all EM-provided keys will be prefixed uniquely for each module (for esample, the language key _my_setting_1_ defined in an EM named _my_awsome_module_ would be stored in `$lang` using _emlang_my_awsome_module_my_setting_1_ as key). This happens automatically in the background.

As in REDCap Core, translations contained in a `Language.ini` file are superimposed over those defined in a master language file (`English.ini`). Module authors should always provide this master file and it should contain all keys (and corresponding strings) used by the module. Thus, translations can be partial: When a key is not provided for a translated file, the corresponding string from `English.ini` is used. Thus, REDCap admins can provide their own translations by putting `OtherLanguage.ini` files with translations for all keys (or only a subset) into the same folder. The filename of each such file will be shown in the list of available languages in the module's system and project configuration dialog.

The framework provides several methods (see below) to access and manage language file entries, both in the PHP and JavaScript objects representing the module. Additionally, translation of strings contained in `config.json` is supported as well.

## Making External Modules translatable

The following steps should be followed to create an External Module that supports internationalization:

### 1. Extract translatable strings into a language file

Prerequisite to making a module translatable is to include a master language file (`English.ini`) containing all translatable strings. This file must be located in a subdirectory `lang` of the module's root directory. Thus, the module's directory will look similar to this:

```ascii
my_awsome_module_v1.0  
+- lang
|  +- English.ini  
+- config.json
+- LICENSE
+- MyAwsomeExternalModule.php
+- README.md
```

`English.ini` is a standard configuration file that must be parsable by PHP's `parse_ini_file()` function (see documentation and example configuration files [here](https://www.php.net/manual/en/function.parse-ini-file.php)). Keep in mind that `parse_ini_file()` does not work when certain reserved words are used as keys (e.g. yes, no, true, false, on, off).  Basically, it looks like this:

```ini
; This is a comment.
key_1 = "Use keys consisting of [a-z0-9_] only."
key_2 = "Enclose strings in double quotes. You may have to escape \"internal\" quotes inside strings."
key_3 = "Strings can even
span accross multiple lines."
key_4 = "Strings can include placeholders, e.g. key_5 is a greeting with a placeholder for a name."
key_5 = "Hello {0}!"
```

Note that language strings can contain placeholders (an identifier enclosed by curly braces) as shown for _key_5_ in the example above). Placeholders are replaced by values that are provided at the time of retrieval of a language string, either as a series of arguments in addition to the key, or as an array. This is commonly referred to as [string interpolation](https://en.wikipedia.org/wiki/String_interpolation). Thus, placeholders are usually just the (zero-based) index into this array (of arguments): {0}, {1}, {2}, etc. When the interpolation values are passed as an associative array (or an object in JavaScript), more descriptive names can be used, such as e.g., {a_more_descriptive_placeholder_name}, which must of course exist as array key or object property on the passed array/object. In case it doesn't, the placeholder will remain in the returned string.

### 2. Add translation support for module settings

To enable translation of the module's name, description, as well as configuration prompts and values, as displayed in a module's configuration dialog, module authors have to signal the EM Framework that translated strings are available by providing the corresponding language file key for each translatable item (basically anything that is a string). To do so, additional keys starting with `tt_` are added at the same level as the key that contains a translatable value. Thus, to provide the language key associated with the `description` of a module, add `tt_description` with a value corresponding to the key in `English.ini` that contains the description. The EM Framework will insert the appropriate translations into the configuration when displayed for the user.

These keys in `config.json` can be translated: `name` (not within the _authors_ section), `description`, `documentation`, `icon`, `url`, `default`, `cron_description`, as well as `required` and `hidden`. 

For readability of `config.json` (as well as for backward compatibility, see [here](https://github.com/grezniczek/localization_demo)), the actualy string can and probably should be duplicated from `English.ini`. Note that the string from the language file will take precedence.

Here is an example of a config file supporting internationalization:

```json
{
    "name": "My awsome module",
    "tt_name": "module_name",
    "description": "It does awsome stuff.",
    "tt_description": "module_desc",
    "framework-version": 12,
    "system-settings": [
        {
            "key": "some_key",
            "name": "Level of awsomeness",
            "tt_name": "loa",
            "type": "dropdown",
            "choices": [
                { "value": "0", "name": "Regular awsome", "tt_name": "loa_reg" },
                { "value": "1", "name": "Super awsome", "tt_name": "loa_super" }
            ]
        },
        { "...": "..." }
    ],
    "...": "..."
}
```

For the example above, `English.ini` would need to contain the following keys: _module_name_, _module_desc_, _loa_, _loa_reg_, _loa_super_.

Alternatively, the `tt_`-settings can be set to `true`, in which case the framework expects the corresponding setting to contain the language key. Note, however, that this mechanism **must not be used** for _name_, _description_, and _authors_ information, as on some occasions, these information are extracted from config files without first instantiating the modules, in which case their language strings are not available. Therefore, it is essential that default fallback values are available in `config.json`.

```json
{
    "...": "...",
    "system-settings": [
        {
            "key": "some_key",
            "name": "language_file_key_for_some_key",
            "tt_name": true,
            "type": "text"
        }
    ],
    "...": "..."
}
```

### 3. Localization of a module's documentation

This is achieved by utilizing the language file infrastructure. In `config.json`, the `documentation` field provided by the EM framework **has** to be set rather than to let the framework look for a `README` file. Then, `tt_documentation` with a language file key is added. In `Language.ini`, the value for this key contains the appropriate path to a translated file within the module's directory, or a url to a resource on the Internet. This provides great flexibiity, as partial translations are supported naturally (i.e. strings are translated, but not docs, or vice versa).

Example:

```json
{
    "name": "My awsome module",
    "tt_name": "name",
    "description": "It does awsome stuff.",
    "tt_description": "desc",
    "documentation": "README.md",
    "tt_documentation": "doc_path",
    "...": "..."
}
```

`English.ini` will then contain:

```ini
; English
name = "My awsome module"
desc = "It does awsome stuff."
doc_path = "README.md"
...
```

and `Deutsch.ini` (German) might have this:

```ini
; German
name = "Mein tolles Modul"
desc = "Es macht ganz tolle Dinge!"
doc_path = "README.de.md"
...
```

### 4. Using strings from language files in PHP

The EM Framework (version 3 and above) provides the `$module->tt()` function (_tt_ is an acronym for _translatable text_) which returns the string corresponding to a given key, and optionally performs string interpolation, replacing any placeholders with the values provided. For ease of use, `tt()` is available directly from the module instance: `$module->tt()`, or `$this->tt()` (from within the module).

The function signature of `tt()`is as follows:

```php
function tt($key, ...$values) { }
```

Argument | Description
-- | --
`$key` | A valid key in the language file of the module. If no entry with the key can be found, the message _"Language key 'key' is not defined for module 'unique module name"_ will be returned instead. If `null` or an empty value is passed, an exception will be thrown.
`$values` | Optional values passed as separate arguments to be used for interpolation (i.e. to replace placeholders in the language string, in the order passed). If the first argument after `$key` is an array, it's members will be used and any further arguments will be ignored. Values are submitted to htmlspecialchars() before interpolation.

### 5. Using strings from language files in JavaScript

To facilitate translatability of strings used in JavaScript files, the EM framework provides utility functions to shuttle language strings from PHP to JavaScript. These strings can then be accessed in JavaScript code through a `tt()` function exposed in the module's _**JavaScript Module Object**_. This function behaves exactly the same as it's PHP counterpart. Please see the [method documentation](methods/README.md) for more details on how to create and use the _JavaScript Module Object_.

To transfer (optionally interpolated) strings to JavaScript, module authors first need to initialize the _JavaScript Module Object_. Two methods assist in the transfer of language strings to JavaScript:

Method | Description
-- | --
`tt_transferToJavascriptModuleObject()` | Used to transfer (and optionally interpolate) strings from language files.
`tt_addToJavascriptModuleObject()` | Used to transfer (or add) an arbitrary key/value pair to the _JavaScript Module Object_. The value is not limited strings but can be anything that, after being run through [json_encode()](https://www.php.net/manual/en/function.json-encode.php), can be interpreted by JavaScript as a JSON literal, such as numbers, booleans, or arrays. This method is available from the _JavaScript Module Object_ as well, allowing addition of new key/value pair in the browser.

These methods support the following scenarios:

- Transfer of a single string without interpolation - example 1.
- Transfer of a single string with interpolation - example 2.
- Transfer of multiple strings (no interpolation possible) - example 3.
- Transfer of all strings (no interpolation possible) - example 4.
- Transfer of a new key/value pair - example 5.

```php
// Need to initialize the JavaScript Module Object first!
$module->initializeJavascriptModuleObject();

// Example 1 - Single
$module->tt_transferToJavascriptModuleObject("a_key");

// Example 2 - Single w/interpolation
$module->tt_transferToJavascriptModuleObject("greeting", $user['name']);

// Example 3 - Several keys
$keys = array ("a_key", "another_key", "third_key");
$module->tt_transferToJavascriptModuleObject($keys);

// Example 4 - All
$module->tt_transferToJavascriptModuleObject();

// Example 5 - New (not from language file)
$module->tt_addToJavascriptModuleObject("new_number", 5);
$module->tt_addToJavascriptModuleObject("new_text", "Just a plain old string");
$module->tt_addToJavascriptModuleObject("new_boolean", false);
$stuff = array ("There", "are", 5, "elements", "here");
$module->tt_addToJavascriptModuleObject("new_array", $stuff);
```

To access the transferred strings (or other data), the `tt()` function of the _JavaScript Module Object_ is used. Consider this example:

```php
<?php
$this->initializeJavascriptModuleObject();
// greeting = "Hello from {0}!"
$this->tt_transferToJavascriptModuleObject("greeting");
?>
<script>
    $(function(){
        var module = <?=$this->getJavascriptModuleObjectName()?>;
        console.log(module.tt('greeting', 'JavaScript'));
        // The console should show:
        // Hello from JavaScript!
    })
</script>
```

First, the _JavaScript Module Object_ is initialized, i.e. a `<script>` block containing the necessary object initialization code will be included on the page sent to the browser. Then, a string from the language file corresponding to the key _'greeting'_ will be transferred, without interpolation. Next, a code block is sent to the browser that will, when executed, retrieve the _JavaScript Module Object_ in the `module` variable. Finally, the greeting message will be interpolated using the value 'JavaScript' and logged to the console.

## Further considerations

### Localization Demo Module

The [Localization Demo](https://github.com/grezniczek/localization_demo) EM, available on GitHub, provides a full example of an EM supporting internationalization. Check it out!

### Backward compatibility

Is it possible to make full use of the features described above and still be compatible with older REDCap versions that do not yet support EM localization?

The answer is yes, at least partly, and it will require taking on an additional dependency - the **EM-i18n-Polyfill**. It provides a backfill for the relevant mechanisms and methods provided by the EM Framework and is maintained as part of the [Localization Demo](https://github.com/grezniczek/localization_demo) EM. Note, however, that translations of strings in `config.json` is **not** possible with the polyfill.

### Tips for writing code supporting easy translation

- Always store/use complete phrases or sentences. Do not build sentences in code by concatenating individual parts, in order to inject some dynamic value. Due to different languages having different rules how to construct sentences, these insertions may end up in the wrong place. Thus, use as single string with a placeholder instead, as the placeholder can easily be moved to the appropriate place in the translation.
- Never construct singular / plural expressions in code (e.g., by adding an 's' to a string). As formation of plural differs from language to language, leave this up to the translator. Simply include two version of the complete phrase, one for singular, the other for plural, and use your logic to choose the appropriate version.
