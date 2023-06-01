### Directory Names

Modules must follow a specific naming scheme for the module directory that will sit on the REDCap web server. Each version of a module will have its own directory (like each version of REDCap itself) and will be located in the `<redcap-root>/modules/` directory on the server. A module directory name consists of three parts: 
1. **Directory Prefix** - This will be this module's unique identifier on a given server.  It is recommended to pick a directory prefix that is not currently used in the REDCap Repo.  To see if a given directory prefix is already in use, open the [Module Submission Survey](https://redcap.vanderbilt.edu/surveys/?s=X83KEHJ7EA), choose `Upgrade of an existing module`, enter the desired directory prefix in the `For which module is this an upgrade?` field, and see whether any existing modules are listed as typeahead options. [Snake case](https://en.wikipedia.org/wiki/Snake_case) is recommended.
1. **Version Separator ("_v")** Simply an underscore followed by the letter "v", as in `version`.
1. **Module Version Number** - The version number for the module code contained in this directory.  Deploying the code for each version of a module to separate directories allows for easy switching between module versions in the REDCap UI.  [Semantic Versioning](https://semver.org/) is recommended (e.g. `1.2.3`), although simpler `#.#` versioning is also supported (e.g. `1.2`).

The diagram below shows the general directory structure of some hypothetical  modules to illustrate how modules will sit on the REDCap web server alongside other REDCap files and directories.

```
redcap
|-- modules
|   |-- my_module_name_v1.0.0
|   |-- other_module_v2.9
|   |-- other_module_v2.10
|   |-- other_module_v2.11
|   |-- yet_another_module_v1.5.3
|-- redcap_vX.X.X
|-- redcap_connect.php
|-- ...
```