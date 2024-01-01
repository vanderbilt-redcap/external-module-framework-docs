## External Module Framework Versioning

#### Introduction to Module Framework Versioning

The versioning feature of the **External Module Framework** allows for backward compatibility while the framework changes over time.  To allow existing modules to remain backward compatible, a new `framework-version` is released each time a breaking change is made. These breaking changes are documented at the top of each framework version page linked in the table below.  

New REDCap versions support all previous framework versions indefinitely, giving module authors the flexibility to update to newer framework versions at a time of their choosing (addressing breaking changes at that time).  While there are no current plans to drop support for older framework versions, that is expected to change down the road.

All new features (e.g. new [methods](../methods/README.md)) are available to framework versions `2` and above. In Framework versions `2-4`, the now deprecated `$module->framework->whateverMethod()` syntax is required to access newer methods.

Modules should specify the `framework-version` in `config.json` as follows:
 
```
{
  ...
  "framework-version": #,
}
```

...where the `#` is replaced by the latest framework version integer (as opposed to string) that is available on the minimum REDCap version they intend to support (per the table below).  If a `framework-version` is not specified, the module will default to framework version `1`.

<br/>

#### Framework Versions & REDCap Versions

Modules will only work on REDCap versions that support their `framework-version` per the following table.  It is NOT required to specify a `redcap-version-min` in addition to `framework-version`, as the latter is automatically considered during REDCap minimum version checking, per the table below.  The `redcap-version-min` will effectively be overridden if it is omitted or is older than the REDCap version required by the `framework-version`.

|Framework Version |First Standard Release|First LTS Release|
|---------------------|-------|-------|
|[Version 15](v15.md) |14.0.2 |14.0.5 |
|[Version 14](v14.md) |13.7.0 |13.7.3 |
|[Version 13](v13.md) |13.4.11|13.7.3 |
|[Version 12](v12.md) |13.1.0 |13.1.5 |
|[Version 11](v11.md) |12.5.9 |13.1.5 |
|[Version 10](v10.md) |12.4.1 |12.4.6 |
|[Version 9](v9.md)   |12.0.4 |12.0.8 |
|[Version 8](v8.md)   |11.1.1 |11.1.5 |
|[Version 7](v7.md)   |10.8.2 |11.1.5 |
|[Version 6](v6.md)   |10.4.1 |10.6.4 |
|[Version 5](v5.md)   |9.10.0 |10.0.5 |
|[Version 4](v4.md)   |9.7.8  |10.0.5 |
|[Version 3](v3.md)   |9.1.1  |9.1.3  |
|[Version 2](v2.md)   |8.11.6 |9.1.3  |
|[Version 1](v1.md)   |8.0.0  |8.1.2  |
