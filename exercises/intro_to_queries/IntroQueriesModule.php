<?php

namespace ExternalModuleExercises\IntroQueriesModule;

use ExternalModules\AbstractExternalModule;

class IntroQueriesModule extends AbstractExternalModule {

    function buildPage() {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $users = $_POST['users'];
            $newValue = $_POST['newValue'];

            if ($this->alterUsers($users, $newValue)) {
                $message = "Set allow_create_db to $newValue for " . implode(', ', $users);
            } else {
                $message = 'something went wrong';
            }
        }
        else{
            $message = null;
        }

        $this->includeJs('js/iq.js');
        $settings = [
            'users' => $this->gatherUsers(),
            'message' => $message,
        ];
        $this->setJsSettings($settings);
    }

    protected function includeJs($path) {
        echo '<script src="' . $this->getUrl($path, true) . '">;</script>';
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
