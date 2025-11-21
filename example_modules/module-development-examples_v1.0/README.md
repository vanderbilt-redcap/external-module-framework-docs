# Module Development Examples Module

This module exists solely to demonstrate configuration options, and other module framework functionality.

## Module API Demo

This module demonstrates the use of module API services through the `redcap_module_api` hook by providing a simple API for managing a list.

### API Methods

- `add-item` - Adds an item to the list, returns _item-id_
- `get-item` - Reads back an item, requiring _item-id_ and returning _item-id_ and _item-name_
- `list-items` - List all items
- `remove-item` - Removes an item from the list, requiring _item-id_

Items are stored in the module logs. This demo only supports `json` as return format.

### CURL requests

To try this out, replace **redcap.server** with the URL of a REDCap server where this module is installed, and **API_TOKEN** with an appropriate token.  
Furthermore, replace **ID** with an actual id returned in the responses.

**1. Add an item**

```
curl --request POST \
  --url https://redcap.server/api/ \
  --header 'content-type: application/x-www-form-urlencoded' \
  --data content=externalModule \
  --data prefix=module-development-examples \
  --data token=API_TOKEN \
  --data returnFormat=json \
  --data action=add-item \
  --data 'item-name=My first item!'
```

**2. Add another item**

```
curl --request POST \
  --url https://redcap.server/api/ \
  --header 'content-type: application/x-www-form-urlencoded' \
  --data content=externalModule \
  --data prefix=module-development-examples \
  --data token=API_TOKEN \
  --data returnFormat=json \
  --data action=add-item \
  --data 'item-name=My second item!'
```


**3. List the items**

```
curl --request POST \
  --url https://redcap.server/api/ \
  --header 'content-type: application/x-www-form-urlencoded' \
  --data content=externalModule \
  --data prefix=module-development-examples \
  --data token=API_TOKEN \
  --data returnFormat=json \
  --data action=list-items
```

**4. Get an item**

```
curl --request POST \
  --url https://redcap.server/api/ \
  --header 'content-type: application/x-www-form-urlencoded' \
  --data content=externalModule \
  --data prefix=module-development-examples \
  --data token=API_TOKEN \
  --data returnFormat=json \
  --data action=get-item \
  --data item-id=<INSERT-ITEM-ID>
```

**5. Remove an item**

```
curl --request POST \
  --url https://redcap.server/api/ \
  --header 'content-type: application/x-www-form-urlencoded' \
  --data content=externalModule \
  --data prefix=module-development-examples \
  --data token=API_TOKEN \
  --data returnFormat=json \
  --data action=remove-item \
  --data item-id=<INSERT-ITEM-ID>
```

**6. List the items**

```
curl --request POST \
  --url https://redcap.server/api/ \
  --header 'content-type: application/x-www-form-urlencoded' \
  --data content=externalModule \
  --data prefix=module-development-examples \
  --data token=API_TOKEN \
  --data returnFormat=json \
  --data action=list-items
```

### Unauthenticated Access

**1. List the items**

```
curl --request POST \
  --url https://redcap.server/api/ \
  --header 'content-type: application/x-www-form-urlencoded' \
  --data content=externalModule \
  --data prefix=module-development-examples \
  --data returnFormat=json \
  --data action=list-items
```

**2. Get an item**

```
curl --request POST \
  --url https://redcap.server/api/ \
  --header 'content-type: application/x-www-form-urlencoded' \
  --data content=externalModule \
  --data prefix=module-development-examples \
  --data returnFormat=json \
  --data action=get-item \
  --data item-id=<INSERT-ITEM-ID>
```

**3. Remove an item** - this will fail!

```
curl --request POST \
  --url https://redcap.server/api/ \
  --header 'content-type: application/x-www-form-urlencoded' \
  --data content=externalModule \
  --data prefix=module-development-examples \
  --data returnFormat=json \
  --data action=remove-item \
  --data item-id=<INSERT-ITEM-ID>
```
