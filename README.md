# REDCap External Module Framework Documentation
You've reached the official documentation for REDCap's External Module Framework!  These docs and the External Module Framework itself are both very much a community effort. If/when you notice room for improvement, feel free to [create an issue](https://github.com/vanderbilt-redcap/external-module-framework-docs/issues/new).  It is also highly encouraged to [fork this repo](https://github.com/vanderbilt-redcap/external-module-framework-docs/fork), make any changes you desire, then go to the `Pull Requests` tab from your fork and select `New pull request` to submit them for review.

Development Resources | Descriptions
-|-
[Beginner's Guide](guide.md) | Start here if you're new to External Module development
[General Documentation](general.md) | Technical details relating to many aspects of module development
GitHub's Search | Github's search feature (in the top of right corner of this page) is a great way to find keywords throughout these docs

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
[Dependencies](dependencies.md) | Recommendations for including shared libraries in your module code
[Renaming a module](renaming.md) | Concerns when renaming a module