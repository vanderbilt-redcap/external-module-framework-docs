### Making requests from JavaScript to modules

The External Module Framework provides the `ajax()` method on the _Javascript Module Object_ (see [documentation](methods/README.md#em-jsmo)), which can be used to make server requests to the module. The module must process the request in the `redcap_module_ajax` hook and (optionally) return a response (see [documentation](hooks.md#em-hooks)).

```js
module.ajax('action', payload).then(function(response) {
   // Process response
}).catch(function(err) {
   // Handle error
});
```

Actions must be declared in `config.json`, separately for authenticated (a user is logged in) and non-authenticated (surveys and other contexts where no user is logged in) contexts.

> `"auth-ajax-actions": [ "action1", "action2" ],`

> `"no-auth-ajax-actions": [ "action2" ],`
