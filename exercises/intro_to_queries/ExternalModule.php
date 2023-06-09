<?php

namespace IntroQueries\ExternalModule;

use ExternalModules\AbstractExternalModule;

class ExternalModule extends AbstractExternalModule {

    function buildPluginPage() {
        // Load in resources for Select2
        $this->includeJsResource('select2.js');
        $this->includeCssResource('select2.css');

        $this->includeJs('js/iq.js');
        $settings = [
            'users' => $this->gatherUsers(),
            'ajaxpage' => $this->framework->getUrl('pages/ajaxpage.php')
        ];
        $this->setJsSettings($settings);
    }

    protected function includeJs($path) {
        echo '<script src="' . $this->framework->getUrl($path, true) . '">;</script>';
    }

    protected function includeJsResource($library) {
        echo '<script src="' . APP_PATH_JS . $library . '">;</script>';
    }

    protected function includeCssResource($library) {
        echo '<link href="' . APP_PATH_CSS . $library . '" rel="stylesheet" />';
    }

    protected function setJsSettings($settings) {
        echo '<script>introQueries = ' . json_encode($settings) . ';</script>';
    }

    function gatherUsers() {
        // FIXME: use $sql with an appropriate function to get a list of every user

        $sql = 'SELECT username
            FROM redcap_user_information';

        /* stop writing here */
        // parse the mysqli response object into an array
        $username_array = array_column(
                $result->fetch_all(MYSQLI_ASSOC),
                'username'
                );
        return $username_array;
    }

    function alterUsers($users, $new_value) {
        $users = implode('", "', $users);
        // FIXME: write and run the SQL command, log what was done

        return $result;
    }

}
