# Twig
## Configuration
### 1. Including Twig
Twig comes included in the External Module Framework as of REDCap version 14.6.4.  Each module has its own Twig `Environment` that comes pre-loaded with several EM framework methods.  The "Example Twig Page" from the Module Development Examples module bundled with REDCap for working example.
### 2. Initialize Twig
When you're ready to use Twig, you first need to call `initializeTwig($templateNameDirectory = 'views')`.  This will load the twig classes into the Autoloader. It's best practice to use a a directory named "`views`", and this is the default directory name.
```
//../modules/ExampleModule.php

// loads creates a Twig Environment for your module
// default template directory is 'views' in your module's root directory
$this->initializeTwig();

//Use Twig to render templates
$this->getTwig()->render(...);
```

## Usage
If your module extends `AbstractExternalModule`, simply call `$this->getTwig()` or `$module->getTwig()` depending on your context.  The most common use case is calling Twig's `render()` which may look like this:
```
$this->getTwig()->render('reports/enrollment_summary.html.twig', [
    'title' => $title,
    'data' => $data,
    'fontSize' => 10,
]);
```
## Calling Module Methods From Twig
Module object methods that are generally appropriate to use in a front-end context may be accessed directly in Twig templates like so:
```
<a href="{{ getUrl('public-page.php') }}">My Link</a>
```
Any other methods defined on your module class can also be made accessible by extending Twig as described below.
## Extending Twig
Functions, filters and tags can be [added to Twig by extending it](https://twig.symfony.com/doc/3.x/advanced.html).  Be sure to call `initializeTwig()` before using any Twig Classes like `TwigFilter()` or `TwigFunction()`.  Here is an example of what a module's twig extensions may look like: 
```
private function loadTwigExtensions(): void
{	
    $this->initializeTwig();
    $this->getTwig()->addFunction(new TwigFunction('reportRoute', function ($reportRoute) {
        return $this->getUrl('report.php') . '&reportRoute=' . $reportRoute;
    }));
}
```
## Module Methods Included in Twig
We've included some of the key templating methods in the EM Framework in Twig already. The list of those can be found in the EM Framework codebase, in a file named `/classes/framework/FrameworkTwigExtensions.php`.
# Best Practices
## Directory
All views should be stored in a `/views` directory, with subfolders as needed.
## Template Naming
1. Lowercase `snake_case` for template names, directories and variables  (e.g. `user_profile instead` of `userProfile` and `product/edit_form.html.twig` instead of `Product/EditForm.html.twig`) as outlined in the [Symfony Best Practices for templating](https://symfony.com/doc/current/best_practices.html#templates).
1. Prefix template fragments (also called "partial templates" or "partial") with an underscore to better differentiate them from complete templates (e.g. `_user_metadata.html.twig` or `_caution_message.html.twig`) as outlined in the [Symfony Best Practices for templating](https://symfony.com/doc/current/best_practices.html#templates).
1. Include an additional extension before the `.twig` extension to better indicate the rendered output (e.g. `nav.html.twig` or `ajax-script.js.twig`)
## Template Structure and Inheritance
1. Use a base template called `base.html.twig` as the foundation of your templating.  This file should include HTML, CSS and JS needed for all pages, as well as Twig `blocks` for sections to be filled in by other templates as outlined in [Twig's Template Inheritance documentation](https://twig.symfony.com/doc/3.x/templates.html#template-inheritance). This way, child templates only contain the blocks specific to their rendering.
1. Use template fragments and Twig's `include()` function so template code is not duplicated between templates.  For example, consider this mark up for a title used across several templates:
 ``` 
<div class="projhdr">
   <i class="fas fa-clipboard-check"></i> {{ title }}
</div>
```
Instead of repeating this few lines on several templates, create a template fragment named `_title.html.twig`.  and include on templates using `{{ include('_title.html.twig' with {title: title})`.
3. When including another template, explicitly define the context.  Twig allows includes to reference variables in the global context, but this can be confusing, as it isn't clear what variables are required without carefully reading the template fragment. Instead, explicitly define each template fragment's context (e.g. `{{ include('_title.html.twig' with {title: title})` instead of `{{ include('_title.html.twig')}}`)
4. 