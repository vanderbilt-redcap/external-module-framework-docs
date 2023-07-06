<?php

namespace ExternalModuleExercises\RecordWranglingModule;

use ExternalModules\AbstractExternalModule;

class RecordWranglingModule extends AbstractExternalModule {

    function handlePost(){
        $field_name = $_POST['field_name'];
        $new_value = $_POST['new_value'];

        //FIXME: use a function to getData and assign it to a variable called $redcap_data


        $this->changeField($redcap_data, $field_name, $new_value); // update the $redcap_data array inplace

        //FIXME: use a function to target this project's $pid and use the array $redcap_data to overwrite
        // the database


        /* Log what was done
        * While some functions will log that data was saved, logging that
        * your module initiated the change can aid in debugging and auditing
        */
        $this->log("Updated field '$field_name' to value '$new_value' for project_id '$pid'");

        ?>
        <script> alert(`Inserted ` + <?=json_encode($new_value)?> + ` into ` + <?=json_encode($field_name)?> + ` for all records`)</script>
        <?php
    }

    function includeJs($path) {
        echo '<script src="' . $this->getUrl($path, true) . '">;</script>';
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
