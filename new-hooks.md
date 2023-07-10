# Proposing New Hooks

We need your help making REDCap even more extensible!  If you encounter a need to customize REDCap in a new way that is currently difficult or impossible, please consider proposing that a new hook be added to REDCap's source code.  Please keep in mind that new hooks will only be available in External Modules, where [Framework Versioning](versions/README.md) ensures full backward compatibility.

## Instructions
1. Add the hook to the REDCap source on your localhost using a line like the following:
    - `\ExternalModules\ExternalModules::callHook('redcap_your_new_hook_name');`
1. Add the new hook method to the module you're working on, and fully test it make sure it works as you would expect in your use case(s).
1. Now that you've finished testing, reconsider the following with all developers in the consortium in mind:
    1. The new hook's name
    1. Parameters (if any) that would be appropriate to pass to this new hook.  In addition to your current use case, please try to imagine all possible future use cases.  Please avoid global parameters that can & should be accessed via module methods (e.g. `$module->getProjectId()`, `$module->getRecordId()`, etc.)
    1. A return value (if appropriate)
1. Email `mark.mcever@vumc.org` the new hook line, and the location where it needs to be added to the REDCap source. A copy/paste including surrounding lines is generally the easiest way to communicate this.  Alternatively, PRs are welcome if you happen to have access to REDCap's source repo.
1. Create a PR for this repo that documents the new hook in the list on [this page](hooks.md)

## The Trajectory of Hooks Over Time
The REDCap ecosystem could be compared to the WordPress ecosystem when it comes to the frequency of customization.  It is possible that the number of hooks in REDCap could increase significantly over time, as it has in WordPress.  See the graph at the bottom of [this page](https://adambrown.info/p/wp_hooks) to get a feel for how the number of WordPress hooks has increased since it was created in 2004.