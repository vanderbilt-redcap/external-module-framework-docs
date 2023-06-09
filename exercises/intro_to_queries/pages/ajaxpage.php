<?php

extract($_POST);
if ($module->alterUsers($users, $newValue)) {
    echo "Set allow_create_db to $newValue for " . implode(', ', $users);
} else {
    echo 'something went wrong';
}
