# Security

The External Module Framework is specifically designed to encourage security best practices.  To maximize security, use framework provided solutions for common features (e.g. [queries](methods/querying.md), [AJAX requests](ajax.md), etc.).  Perhaps the most important security feature is the **automated security scan** mentioned at the top of the [REDCap Repo](https://redcap.vanderbilt.edu/consortium/modules/).  To learn how to run this scan on your local REDCap instance, see `Control Center -> External Modules -> Manage -> Module Security Scanning`.  This scan will often recommend methods to address specific security concerns.  The following methods exist solely to address security concerns.  See the [Method Documentation](methods/README.md) for details on each:
<b>
- sanitizeAPIToken
- sanitizeFieldName
- escape
- getSafePath
</b>