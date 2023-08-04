# REDCap External Module Framework Documentation

You've reached the official documentation for REDCap's External Module Framework!  This framework can be used to create External Modules, the recommended way to programmatically extend REDCap's base functionality.  These docs and the External Module Framework itself are both very much a community effort. If/when you notice room for improvement, feel free to [create an issue](https://github.com/vanderbilt-redcap/external-module-framework-docs/issues/new).  It is also highly encouraged to [fork this repo](https://github.com/vanderbilt-redcap/external-module-framework-docs/fork), make any changes you desire, then go to the `Pull Requests` tab from your fork and select `New pull request` to submit them for review.

If you're brand new to module development, see the [beginner's guide](guide.md).

If you have already created a module and wish to share it with the REDCap community, you may submit it to the [REDCap External Modules Submission Survey](https://redcap.vanderbilt.edu/surveys/?s=X83KEHJ7EA). If your module gets approved after submission, it will become available for download by any REDCap administrator from the [REDCap Repo](https://redcap.vanderbilt.edu/consortium/modules/).

### How to contribute to this documentation

These docs (and the External Module Framework itself) are very much a community effort. If/when you notice room for improvement, feel free to [create an issue](https://github.com/vanderbilt-redcap/external-module-framework-docs/issues/new).  It is also highly encouraged to [fork this repo](https://github.com/vanderbilt-redcap/external-module-framework-docs/fork), make any changes you desire, then go to the `Pull Requests` tab from your fork and select `New pull request`.

### How to contribute to the development of the EM Framework itself

Contributions (new features, bugfixes, etc.) to the External Module Framework code repo are welcome too!  To gain access to that repo, email your GitHub username to `mark.mcever@vumc.org`.  Once you have access, read [the that repo's constribution instructions](https://github.com/vanderbilt-redcap/external-module-framework/blob/testing/CONTRIBUTING.md).

### Documentation Backstory

This documentation was moved from the private REDCap Core & External Module Framework repos to this public repo to make it possible to share universal links to specific parts of the documentation, and to reduce barriers for community contribution.  We considered hosting these docs at `redcap.vanderbilt.edu/docs`, but decided that using GitHub directly would be most likely to encourage contributions.  A copy of these docs is also included in the External Module Framework repo via [git-subrepo](https://github.com/ingydotnet/git-subrepo), to ensure that any find/replace actions also update the docs.  Over time, we may want to consider moving other developer docs here from REDCap core.

Framework Features | Descriptions
-|-
[Module Pages](pages.md) | Modules can provide their own pages to perform any arbitrary actions
[Hooks](hooks.md) | Modules can define hook methods that execute in certain places on REDCap core pages
[Methods](methods/README.md) | The framework provides many features via methods on the module object
[SQL Queries](methods/querying.md) | The recommended way to query the database from module code
[Logging](methods/logs.md) | The framework's built-in logging functionality
[AJAX Requests](ajax.md) | The recommended way to perform AJAX requests from module code
[Crons](crons.md) | Scheduled tasks that automatically execute periodically in the background
[Unit Testing](unit-testing.md) | Writing standard PHPUnit tests for your module
[Compatibility](compatibility.md) | Making sure your module correctly specifies required REDCap & PHP versions
[Internationalization Guide](i18n-guide.md) | The guide for support multiple languages within your module
[Security](security.md) | A summary of security related features
[Framework Versioning](versions/README.md) | The mechanism through which backward compatibility is maintained as the framework changes over time

Misc. Resources | Descriptions
-|-
[Beginner's Guide](guide.md) | Start here if you're new to External Module development
[Module Directory Names](directory-names.md) | Information on module directory naming
[Module Code Requirements](requirements.md) | Information on basic module requirements
[config.json](config.md) | Details on the format of the `config.json` file
[JavaScript in Modules](javascript.md) | Recommendations for using javascript in modules
[Dependencies](dependencies.md) | Recommendations for including shared libraries in your module code
[Renaming a module](renaming.md) | Concerns when renaming a module
[Proposing New Hooks](new-hooks.md) | Instructions on how to propose additional hooks that any External Module can call
[Acknowledgments](ACKNOWLEDGEMENTS.md) | Acknowledging contributors that have made these docs possible!
GitHub's Search | Github's search feature (in the top of right corner of this page) is a great way to find keywords throughout these docs
