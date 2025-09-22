<?php
// Here is an example of how to load jQuery (along with REDCap's other bundled javascript libraries) and Bootstrap on a page without REDCap's headers
$module->loadREDCapJS();
$module->loadBootstrap();
?>

<style>
    button{
        display: block;
        margin: 7px;
    }
</style>

<p>This page does not load REDCap's headers, which means CSRF tokens will need to be manually added to forms and all javascript POST requests (including jQuery post()).</p>
<br>
<p>Click the following buttons to post examples with CSRF protection:</p>
<button class='ajax' data-include-csrf-token>POST with CSRF token to non API URL</button>
<button class='ajax' data-include-csrf-token data-api-url>POST with CSRF token to API URL</button>

<br>
<br>
<p>Additional examples:</p>
<button class='ajax'>Test "no-csrf-pages" in config.json</button>

<?php

$module->setupExampleActions();
