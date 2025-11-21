<h2>Example NOAUTH Page</h2>
<p>Click the following button to test a post to a NOAUTH page with CSRF protection.  This strategy also works for ajax requests from survey pages:</p>
<button class='ajax' data-include-csrf-token data-noauth>POST with CSRF token to NOAUTH page</button>

<?php

$module->setupExampleActions();
