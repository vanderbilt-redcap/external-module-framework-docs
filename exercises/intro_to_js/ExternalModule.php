<?php

namespace IntroJS\ExternalModule;

use ExternalModules\AbstractExternalModule;
use RCView;

class ExternalModule extends AbstractExternalModule {

    function redcap_project_home_page($project_id) {

        // Define attributes for html elements
        $button_attributes = [
            'class' => 'btn btn-primary',
            'id' => 'incrementButton'
        ];
        $button_text = "Click to increment";

        // call a prebuilt button maker
        echo RCView::button($button_attributes, $button_text);

        echo RCView::p(['id' => 'incrementValue'], '0');

        // FIXME
        // include a JavaScript file that increments the contents of incrementValue
        // upon clicking the incrementButton
        /* write your code below */
    }

}
