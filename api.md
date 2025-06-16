# Module API

External modules may provide API services by implementing the `redcap_module_api` hook and defining the supported actions (API methods) in _config.json_. Then, these can be accessed through the standard REDCap API mechanism (`/api/` endpoint), thus leveraging REDCap's built-in token management.

## Module API Requests

API requests targeted at an External Module take these forms:

```
curl -F "token=APITOKEN" \
     -F "content=externalModule" \
     -F "prefix=unique_module_prefix" \
     -F "action=some-action" \
     -F "format=json" \
     -F "returnFormat=json" \
     -F "csvDelim=comma" \
     -F "customData=My custom data" \
     https://domain.tld/redcap/api/
```

```
curl -F "token=APITOKEN" \
     -F "content=externalModule" \
     -F "prefix=unique_module_prefix" \
     -F "action=file-upload" \
     -F "returnFormat=json" \
     -F "my-file=@./filename.ext" \
     https://domain.tld/redcap/api/
```

Parameter | | Description
--- | --- | ---
`content` | (**required**) | This must be "**externalModule**".
`prefix` | (**required**) | The unique prefix of the module. A module's prefix is shown on the **Module Manager** pages (Control Center and projects) as well as on the **API** page in projects.
`action` | (**required**) | The name of the action (module API method).
`token` | (optional) | A valid REDCap API token. No-auth actions do not require a token.
`format` | (optional) | One of `json`, `xml` (default), or `odm`.
`returnFormat` | (optional) | The desired return format: `json` (default), `xml`, or `csv`.
`csvDelim` | (optional) | The desired CSV delimiter (`comma`, `semicolon`, `tab`, `pipe`, `caret`, or `space`). This defaults to the delimiter set in the user's profile, or to `comma` in case of no-auth requests.
`(any name)` | (optional) | Any custom payloads as required/supported by the external module for the specified action. **This includes file uploads.**

It is up to the module to define (and _document_) which `format` and `returnFormat` options it supports. Generally, it is recommended for a module to support **JSON**.

### Special Actions

A number of special actions are available, which are fulfilled by the EM Framework. To call these actions, a valid token is required (i.e., they require authentication).

Action | Description
--- | ---
`__version` | Gets version information (REDCap, Framework, Module).
`__actions` | Gets a list of available auth/no-auth actions (with descriptions).
`__info` | Full info (versions, module name/description/authors, actions). In order for authors to be listed, the `include-authors-in-api-info` setting in _config.json_ must be set to `true`.

Example of a full `__info` response, with return format set to `json`:
```json
{
  "redcap-version": "14.7.1",
  "framework-version": 16,
  "module-version": "1.0.2",
  "name": "My API Services Module",
  "description": "The description of this module.",
  "authors": [
    {
      "name": "Jon Snow",
      "email": "jon.snow@vumc.org",
      "institution": "Vanderbilt University Medical Center"
    }
  ],
  "auth-actions": {
    "get-item": "Gets the item specified by item-id.",
    "list-items": "Returns a list of items with item-id and item-name.",
    "add-item": "Adds an item to the list. Specify the item's name in the item-name parameter. The id of the new item will be returned as 'item-id'.",
    "remove-item": "Removes an item from the list. Specifiy the item's id in the item-id parameter."
  },
  "no-auth-actions": {
    "get-item": "Gets the item specified by item-id."
  }
}
```

## Providing API Services

For an external module to provide API services, the module must:
- Implement the `redcap_module_api` hook
- Define API actions in _config.json_

### API Hook

The hook signature is:
```php
function redcap_module_api (
    $action, $payload, $project_id, $user_id, $format, $returnFormat, $csvDelim
) {
    return $response;
}
```

Parameter | Description
--- | ---
`$action` | The name of the action to be executed. This will be one of the actions defined in _config.json_.
`$payload` | Any custom payloads that were part of the request (including uploaded files).
`$project_id` | The project_id. If the token uses is not bound to a project (super API token), or no token has been submitted (no-auth request), this will be `null`.
`$user_id` | The user id of the user associated with the submitted token, or `null` in case of a no-auth request.
`$format` | The format (`json`, `xml`, `csv`).
`$returnFormat` | The requested return format (`json`, `xml`, `csv`).
`$csvDelim` | The requested CSV delimiter. This is supplied ready-to-use, i.e. as the appropriate character (e.g., '`\t`' for `tab`, or ' `;`'  for `semicolon`).

The module should return any data that is to be returned in response to the request as a response object or a string from the hook. If the requested action does not produce a response, `null` may be returned (or nothing at all). In case of an error, an error response can be returned.

The general return format (PHP array) is
```php
[
    "status" => 200,
    "body" => "..."
]
```
for text responses, or
```php
[
    "status" => 200,
    "file" => [
        "path" => "path-to-file",
        "name" => "Filename.txt",
        "type" => "text/plain"
    ]
]
```
for file responses.  

Any status code other than 200 will be treated as an error response. It is recommended to only use status codes that are officially supported by REDCap, i.e., 200, 400, 401, 403, 404, 406, 500, and 501 (see the REDCap API Documentation).  

Returning `null` or a string from the hook is equivalent to returning 
```php
[
    "status" => 200,
    "body" => "" | "String content"
]
```
An error response will be the same as a regular response but with an error status codes instead of 200.

There are several helper methods provided by the EM Framework facilitating the creation of an API response:

Method | Parameters
--- | ---
`apiResponse`| ($body = "")
`apiFileResponse`| ($path, $filename = "", $type = "text/plain")
`apiFileContentsResponse`| ($contents, $filename = "", $type = "text/plain")
`apiErrorResponse`| ($error_message = "", $status = 500)
`apiJsonResponse` | ($data, $force_object = false, $flags = 0)
`apiJsonFileResponse` | ($data, $filename, $force_object = false, $flags = 0)
`apiCsvResponse` | ($data, $delim = ",", $add_bom = false)
`apiCsvFileResponse` | ($data, $filename, $delim = ",", $add_bom = true)

Thus, to e.g., send a JSON response representing an associcative PHP array, the hook could exit with:
```php
return $this->framework->apiJsonResponse($my_array);
```

Note:
- `$path` must be the full path to an **existing** file on the local file system. It is recommended to create the file with the _createTempFile()_ framework method.
- `$filename` is the filename to passed down to the recipient.
- `$type` must be a valid MIME type, such as _text/plain_ or _application/json_.
- `$data` must be a data structure that can be encoded by _json_encode()_ or, in case of CSV responses, compatible with the built-in CSV encoder (see below).
- `$force_object` specifies whether to add the _JSON_FORCE_OBJECT_ flag to the _json_encode()_.
- `$flags` are additional format flags to be passed through _json_encode()_.
- `$delim` is the desired CSV delimiter (supplied as actual character).
- `$add_bom` specifies whether the UTF8 byte-order mark should be added to the output file. This is generally required for CSV files if they need to be opened by Microsoft Excel.

#### Handling File Uploads

Files uploaded as part of a request will be included in the `$payload` parameter as a standard PHP file structure. This example show `$payload` for a file (_test.pdf_) uploaded to the _my-file_ custom request parameter:
```php
[
    "my-file" => [
        "name" => "test.pdf",
        "full_path" => "test.pdf",
        "type" => "application/pdf",
        "tmp_name" => "/tmp/phpGzKYTR",
        "error" => 0,
        "size" => 25205
    ]
]
```
The file can then be processed after obtaining its path and name:
```php
$file_path = $payload["my-file"]["tmp_name"];
$file_name = $payload["my-file"]["name"];
```

Any uploaded files will be **automatically deleted** when the requests ends.

### API Actions

API actions, along with their descriptions and allowed modes of access, are defined in _config.json_. Only defined actions can be requested.  
Action identifiers
- must start with a lower or upper case letter (A-Z, a-z),
- may contain letters, numbers (0-9), hyphens (-), and underscores (_), and
- must end with a letter or number.

Actions are defined in the `api-actions` object. For each action, a description must be specified. Optionally, the modes of access can be specified (the default is to allow authenticated access only).
In the following example, the _list-items_ action can be called in both, authenticated and non-authenticated contexts, whereas the _add-items_ action requires authentication (i.e., a valid token to be submitted with the request).

```json
"api-actions": {
    "list-items": {
        "description": "A method that lists all available items.",
        "access": [ "auth", "no-auth" ]
    },
    "add-items": {
        "description": "Adds an item to the list."
    }
}
```

Action descriptions are shown to admins when a module is enabled as well as to users on the API page.

API action descriptions may contain a limited set of HTML elements: `a`, `acronym`, `b`, `br`, `code`, `div`, `em`, `i`, `hr`, `label`, `li`, `ol`, `p`, `pre`, `span`, `strike`, `strong`, `style`, `sub`, `sup`, `table`, `tbody`, `td`, `tfoot`, `th`, `thead`, `tr`, `u`, `ul`

### Logging

REDCap will log module API requests as any other REDCap API requests in the _redcap_log_view_ table. Modules may want to add additional logging as desired or required, e.g. to the **External Module Logs** via the Framework's `log()` method.

Unhandled exceptions in the _redcap_module_api_ hook will be output to the web server's standard error log.

## Example Module

A full working example demonstrating the simple API showcased in the documentation above is included in the **Configuration Example** external module which is bundled with the EM Framework. This module implements a simple API for managing a list of items, where items can be
- added (`add-item`, returning _item-id_),
- read back (`get-item`, requiring _item-id_, returning _item-id_ and _item-name_),
- listed (`list-items`), and
- removed (`remove-item`, requiring _item-id_).

### Configuration _(config.json)_

```json
{
    "name": "Configuration Example",

    (...)

    "include-authors-in-api-info": true,
	"api-actions": {
		"get-item": {
			"description": "Gets the item specified by <code>item-id</code>.",
			"access": ["auth", "no-auth"]
		},
		"list-items": {
			"description": "Returns a list of items with <i>item-id</i> and <i>item-name</i>.",
			"access": ["auth", "no-auth"]
		},
		"add-item": {
			"description": "Adds an item to the list. Specify the item's name in the <code>item-name</code> parameter. The id of the new item will be returned as <i>'item-id'</i>."
		},
		"remove-item": {
			"description": "Removes an item from the list. Specifiy the item's id in the <code>item-id</code> parameter."
		}
    },
}
```

### Module Class

```php
<?php namespace Vanderbilt\ConfigurationExampleExternalModule;

use ExternalModules\AbstractExternalModule;

class ConfigurationExampleExternalModule extends AbstractExternalModule {

    // ...

    function redcap_module_api($action, $payload, $project_id, $user_id, $format, $returnFormat, $csvDelim) {
        if ($returnFormat != "json") {
            return $this->framework->apiErrorResponse("This API only supports JSON as return format!", 400);
        }
        switch ($action) {
            case "get-item": return $this->get_item($payload);
            case "list-items": return $this->list_items();
            case "add-item": return $this->add_item($payload);
            case "remove-item": return $this->remove_item($payload);
        }
    }

    #region API Methods

    const ITEM_STORE = "MyItemStore";

    function add_item($payload) {
        $name = "". ($payload["item-name"] ?? "");
        if ($name == "") return $this->framework->apiErrorResponse("Must specify 'item-name'!", 400);
        $id = \Crypto::getGuid();
        $this->framework->log(self::ITEM_STORE, [
            "id" => $id,
            "name" => $name
        ]);
        return $this->framework->apiJsonResponse([
            "item-id" => $id
        ]);
    }

    function get_item($payload) {
        $id = "". ($payload["item-id"] ?? "");
        if ($id == "") return $this->framework->apiErrorResponse("Must specify 'item-id'!", 400);
        $result = $this->framework->queryLogs("SELECT name WHERE message = ? AND id = ?", [self::ITEM_STORE, $id]);
        while ($row = $result->fetch_assoc()) {
            return $this->framework->apiJsonResponse([
                "item-id" => $id,
                "item-name" => $row["name"]
            ]);
        }
        return $this->framework->apiErrorResponse("Could not find item with id '$id'.", 404);
    }

    function list_items() {
        $list = [];
        $result = $this->framework->queryLogs("SELECT id, name WHERE message = ?", [self::ITEM_STORE]);
        while ($row = $result->fetch_assoc()) {
            $list[] = [
                "item-id" => $row["id"],
                "item-name" => $row["name"]
            ];
        }
        return $this->framework->apiJsonResponse($list);
    }

    function remove_item($payload) {
        $id = "". ($payload["item-id"] ?? "");
        if ($id == "") return $this->framework->apiErrorResponse("Must specify 'item-id'!", 400);
        $result = $this->framework->queryLogs("SELECT 1 WHERE message = ? AND id = ?", [self::ITEM_STORE, $id]);
        if ($result->num_rows !== 1) {
            return $this->framework->apiErrorResponse("No item with id '$id'.", 404);
        }
        else {
            $this->framework->removeLogs("message = ? AND id = ?", [
                self::ITEM_STORE, $id
            ]);
        }
        return $this->framework->apiResponse(); // Could be null or void
    }

    #endregion
}
```
