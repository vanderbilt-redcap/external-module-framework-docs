# REDCap External Module Framework Documentation

You've reached the official documentation for REDCap's External Module Framework!  This framework can be used to create External Modules, the recommended way to programmatically extend REDCap's base functionality.  These docs and the External Module Framework itself are both very much a community effort. If/when you notice room for improvement, feel free to [create an issue](https://github.com/vanderbilt-redcap/external-module-framework-docs/issues/new).  It is also highly encouraged to [fork this repo](https://github.com/vanderbilt-redcap/external-module-framework-docs/fork), make any changes you desire, then go to the `Pull Requests` tab from your fork and select `New pull request` to submit them for review.

If you're brand new to module development, see the [beginner's guide](guide.md).

If you have already created a module and wish to share it with the REDCap community, you may submit it to the [REDCap External Modules Submission Survey](https://redcap.vanderbilt.edu/surveys/?s=X83KEHJ7EA). If your module gets approved after submission, it will become available for download by any REDCap administrator from the [REDCap Repo](https://redcap.vanderbilt.edu/consortium/modules/).

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