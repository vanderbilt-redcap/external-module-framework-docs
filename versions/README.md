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


| Framework Version    | First Standard Release | First LTS Release   |
|:---------------------|:-----------------------|:--------------------|
| [Version 17](v17.md) | 17.0.1 (2026-04-09)    | 17.3.5 (2026-07-30) |
| [Version 16](v16.md) | 14.6.4 (2024-08-29)    | 15.0.9 (2025-01-30) |
| [Version 15](v15.md) | 14.0.2 (2023-12-14)    | 14.0.5 (2023-12-28) |
| [Version 14](v14.md) | 13.7.0 (2023-06-08)    | 13.7.3 (2023-06-28) |
| [Version 13](v13.md) | 13.4.11 (2023-04-27)   | 13.7.3 (2023-06-28) |
| [Version 12](v12.md) | 13.1.0 (2022-12-09)    | 13.1.5 (2022-12-28) |
| [Version 11](v11.md) | 12.5.9 (2022-09-09)    | 13.1.5 (2022-12-28) |
| [Version 10](v10.md) | 12.4.1 (2022-05-26)    | 12.4.6 (2022-06-27) |
| [Version 9](v9.md)   | 12.0.4 (2021-12-10)    | 12.0.8 (2021-12-28) |
| [Version 8](v8.md)   | 11.1.1 (2021-06-04)    | 11.1.5 (2021-06-30) |
| [Version 7](v7.md)   | 10.8.2 (2021-02-12)    | 11.1.5 (2021-06-30) |
| [Version 6](v6.md)   | 10.4.1 (2020-11-06)    | 10.6.4 (2020-12-30) |
| [Version 5](v5.md)   | 9.10.0 (2020-05-21)    | 10.0.5 (2020-06-23) |
| [Version 4](v4.md)   | 9.7.8 (2020-03-12)     | 10.0.5 (2020-06-23) |
| [Version 3](v3.md)   | 9.1.1 (2019-06-21)     | 9.1.3 (2019-06-27)  |
| [Version 2](v2.md)   | 8.11.6 (2019-03-15)    | 9.1.3 (2019-06-27)  |
| [Version 1](v1.md)   | 8.0.0 (2017-11-03)     | 8.1.2 (2017-12-28)  |
