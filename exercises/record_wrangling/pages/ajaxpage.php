<?php

$field_name = $_POST['field_name'];
$new_value = $_POST['new_value'];

if ($pid = $_GET['pid']) {

    //FIXME: use a function to getData and assign it to a variable called $redcap_data


    $module->changeField($redcap_data, $field_name, $new_value); // update the $redcap_data array inplace

    //FIXME: use a function to target this project's $pid and use the array $redcap_data to overwrite
    // the database


    /* Log what was done
     * While some functions will log that data was saved, logging that
     * your module initiated the change can aid in debugging and auditing
     */
    $module->framework->log("Updated field '$field_name' to value '$new_value' for project_id '$pid'");
}
