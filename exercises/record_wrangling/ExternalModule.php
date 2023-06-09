<?php

namespace RecordWrangling\ExternalModule;

use ExternalModules\AbstractExternalModule;

class ExternalModule extends AbstractExternalModule {

    function setupProjectPage() {
        $this->includeJs("js/rw.js");
        $settings = [
            'ajaxpage' => $this->framework->getUrl('pages/ajaxpage.php')
        ];
        $this->setJsSettings($settings);
    }

    protected function includeJs($path) {
        echo '<script src="' . $this->framework->getUrl($path, true) . '">;</script>';
    }

    protected function setJsSettings($settings) {
        echo '<script>recordWrangling = ' . json_encode($settings) . ';</script>';
    }

    function changeField(&$redcap_data, $field, $new_value) {
        /* $redcap_data is structured as:
            [
                record_id => [
                    event_id => [
                        field_name => value,
                        ...
                        ],
                    ...
                    ],
                ...
            ]
        */

        // Dig through the data array for the proper field and replace its value inplace
        array_walk_recursive($redcap_data, function(&$value, &$key) use($field, $new_value) {
            if ($key == $field) {
                $value = $new_value;
            }
        });

        /* The above represented as nested foreach loops
        foreach($redcap_data as &$record_id) {
            foreach ($record_id as &$event_id) {
                foreach ($event_id as $field_name => &$value) {
                    if ($field_name == $field) {
                        $value = $new_value;
                    }
                }
            }
        }
        */
    }
}
