## The queryData() Method

**The `queryData()` method and related methods listed below are currently in BETA testing.  Use them at your own risk as they can cause system instability due to long running queries depending on the use case.  While not likely, it is possible that the behavior of these methods will change in non-backward compatible ways in future REDCap versions.  Any and all feedback is very much appreciated.**

The `queryData()` method is an experimental alternative to `REDCap::getData()` that executes filter logic via SQL rather than PHP.  The current implementation often uses less memory than `REDCap::getData()`, and is sometimes faster on larger projects when only a few fields are referenced and a large amount of filter logic is used.  However, it is currently slower in other cases (sometimes dangerously), and only supports a subset of `REDCap::getData()` functionality.  Performance is heavily dependent on project size & the particular filter logic used.  Single calls with monolithic filter logic are discouraged in favor of combining the results of several smaller queries that "include" or "exclude" certain record IDs, and a final query to pull the necessary data for the list of relevant record IDs.

### Supported Functionality
- The `queryData()` method accepts `$sql` and `$parameters` arguments containing pseudo-SQL similar to `queryLogs()`.  Standard REDCap filter logic or it's equivalent without the brackets can be included AS the `WHERE` clause.  Here are some query examples:
  - `SELECT [record_id], [some_other_field_name] WHERE [some_other_field_name] = 'some value'`
  - `SELECT record_id WHERE field_one = '' or datediff(field_one, now(), 'd') < 7`
- Basic arithmetic, boolean logic, and `datediff()` calls are fully supported.
- Advanced filter logic is not yet supported (like smart variables).
- A MySQL Result object is returned whose output should be identical to `REDCap::getData()` except that `*_complete` form values are NOT returned unless explicitly requested.

### Related Methods
- `$module->getData()` - A wrapper method around `queryData()` with `REDCap::getData()` compatible arguments and return values for easy testing/transition of existing code.  Framework version 7 or greater is required.  An older undocumented `getData()` method existed prior to that, and is still in us by some old modules.  The `compareGetDataImplementations()` method below may be used to determine if it is safe to switch to this method.  If you have verified that `$module->getData()` behaves as expected in your use cases, comparable `queryData()` calls will likely also behave as you'd expect, and may provide additional functionality.  
- `$module->compareGetDataImplementations()` - A convenience method that accepts the same parameters as `REDCap::getData()`, automatically compares the results of `REDCap::getData()` and `$module->getData()`, then returns a summary object.  Results are reported as "identical" even if `*_complete` values are returned from `REDCap::getData()` but not `$module->getData()`.

### Ideas For Future Improvements
- Reimplement it using a different kind of pivot strategy than joins
  - SELECTed fields can be efficiently returned via GROUP_CONCAT() (see the getFieldSQL() implementation).  We will likely need to split that GROUP BY into an inner GROUP BY to get rid of any duplicate rows in redcap_data, then an outer GROUP BY that executes GROUP_CONCAT().
  - WHERE clauses likely need to be programmatically split into include & exclude clauses that return a list of record IDs & instances.  This would likely apply to ORDER BYs and IF statements in SELECTs as well.  Maybe we shouldn't worry about those and only reimplement getData() rather than queryData().
  - If GROUP_CONCAT() has any hard limitations, [other pivot strategies](https://www.databasestar.com/mysql-pivot) may be worth considering.
- Splitting the logic up into smaller field/instance specific sections when possible and executing each as field/instance specific "include" or "exclude"...
  - ...inner selects that return only the record/instance.
  - ...top level queries that return only the record/instance and are joined via PHP.  This is similar to what the private *Advanced Reporting* module does currently at Vanderbilt, and is significantly faster than `REDCap::getData()` in the cases used by the *COVID Data Mart* project.
- Further optimize `REDCap::getData()` using some of the concepts learned here.  Even just modifying the functions returned by `LogicParser::parse()` to cache their results and prevent re-processing of duplicate data might go a long way.  It may also be possible to detect when filter logic is compatible enough to delegate some of the longer running portions of a `REDCap::getData()` call to `queryData()` automatically under the hood.
- Building this feature into `REDCap::getData()`.  One or more variations of this approach really do have the potential to entirely replace the implementation of `REDCap::getData()` over the very long term!  However, that is a non-trivial undertaking that would likely require phases over many years.  If we wanted to start down that path today, we could add this feature as an alternate "query engine" in REDCap core, then automatically use it in scenarios where it's guaranteed to be safe & optimal.
- On systems with multiple database instances (like read only mirrors), we could somehow mark certain queries as safe to execute on the mirror instead of the primary database.
- Potentially providing an alternate storage mechanism for the `redcap_data` table, like Elasticsearch
- Creating database views (potentially more performant)
- Additional/alternate indexing of the `redcap_data` table (like the `value` column)
