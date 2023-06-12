### JavaScript in Modules

Modules should minimize the number of global JavaScript variables, functions, classes, etc. defined as to not pollute the global namespace.  This will help minimize the chance of conflict between modules and/or REDCap core.  Please consider creating a function as an **IIFE (Immediately Invoked Function Expression)** or instead creating the variables/functions as properties of a **single global scope object** for the module, as seen below.

```JavaScript
<script type="text/javascript">
  // IIFE - Immediately Invoked Function Expression
  (() => {
    // The rest of your code goes here!
  })()
</script>
```

```JavaScript
<script type="text/javascript">
  // Single global scope object containing all variables/functions
  const MyUniquelyNamedModule = {}
  MyUniquelyNamedModule.someVar = "some value"
  MyUniquelyNamedModule.someFunction = () => {
    alert(MyUniquelyNamedModule.someVar)
  }
  MyUniquelyNamedModule.someFunction()
</script>
```
