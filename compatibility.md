### Module compatibility with specific versions of REDCap and PHP

It may be the case that a module is not compatible with specific versions of REDCap or specific versions of PHP. In this case, the `compatibility` option can be set in the config.json file using any or all of the four options seen below. (If any are listed in the config file but left blank as "", they will just be ignored.) Each of these are optional and should only be used when it is known that the module is not compatible with specific versions of PHP or REDCap. You may provide PHP min or max version as well as the REDCap min or max version with which your module is compatible. If a module is downloaded and enabled, these settings will be checked during the module enabling process, and if they do not comply with the current REDCap version and PHP version of the server where it is being installed, then REDCap will not be allow the module to be enabled.

```JSON
{	
   "compatibility": {
      "php-version-min": "7.4.0",
      "php-version-max": "7.99.99",
      "redcap-version-min": "12.0.0",
      "redcap-version-max": ""
   }
}
```
