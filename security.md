# Security

The External Module Framework is specifically designed to encourage security best practices.  To maximize security, use framework provided solutions for common features (e.g. [queries](methods/querying.md), [AJAX requests](ajax.md), etc.).  Perhaps the most important security feature is the **automated security scan** mentioned at the top of the [REDCap Repo](https://redcap.vumc.org/consortium/modules/).  To learn how to run this scan on your local REDCap instance, see `Control Center -> External Modules -> Manage -> Module Security Scanning`.  This scan will often recommend methods to address specific security concerns.  The following methods exist solely to address security concerns.  See the [Method Documentation](methods/README.md) for details on each:
<b>
- sanitizeAPIToken
- sanitizeFieldName
- escape
- getSafePath
</b>

## Module manager files

Manager file operations require both the module/setting configuration permission and
a live eDoc owned by the current project (or the system in Control Center).
The file must be referenced by that setting or uploaded for it in the current
session. File-name and download requests include the module prefix and setting key;
repeatable keys may include their `____` indexes.

An overridable system file may be read from its corresponding project setting,
but project actions cannot delete the system eDoc. Clearing that reference only
changes the project override. Settings saves and exports validate file references,
including nested/repeatable values and rich-text file lists.
Imports initially persist empty file references, retaining archive IDs only in
memory until files have been restored from the archive. Missing archive files
cannot become references to existing global eDocs, even after a failed import.

The Control Center duplicate-reference repair additionally checks configuration
permission and Design rights on each source project before copying. These checks
do not change the unscoped internal eDoc query/copy APIs used for trusted project
copies and migrations. Re-upload inaccessible legacy references rather than
assigning an arbitrary eDoc ID to a setting.

Inaccessible file references display an error and disable downloading instead of
silently presenting a blank filename. Run the isolated file-field UI regression with
`node ExternalModules\tests\ManagerFileFieldTest.js` from the REDCap codebase directory.