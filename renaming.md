### Renaming a module

The display name for a module can be safely renamed at any time by updating the `name` in `config.json` (as documented later).  The module directory name on the system may also be changed at any time.  Module specific URLs changing is typically the only side effect, but directory renames should still be tested in a non-production environment first to make sure all module features still work as expected.  To rename a module directory, follow these steps:
1. Deploy the module (to all web nodes if there are multiple) under the new directory name (prefix) with a version suffix that matches the version currently enabled on the system.
    - This new deployment can contain code changes as well (e.g. renaming the module in `config.json`, renaming the module's main class, etc.)
1. Run the following query:
    ```
    update redcap_external_modules
    set directory_prefix = 'new_directory_prefix'
    where directory_prefix = 'old_directory_prefix'
    ```
1. Test to make sure the module still functions as expected.
    - All project enables, settings, logs, crons, etc. should be preserved.
1. Once enough time has passed for any running cron jobs to finish, delete the old directory (on all web nodes if there are multiple)
