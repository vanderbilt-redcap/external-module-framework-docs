<?php

use RCView;

require_once APP_PATH_DOCROOT . 'ProjectGeneral/header.php';

if (!SUPER_USER) {
    echo "You must be an administrator to use this feature.";
    require_once APP_PATH_DOCROOT . 'ProjectGeneral/footer.php';
    return;
}


$module->setupProjectPage();

$fields = [0 => '-- choose a field --'];
// retrieve a list of field names for this project
foreach($module->framework->getMetadata(PROJECT_ID) as $field_id => $data) {
    $fields[$field_id] = $data['field_label'];
}

echo RCView::label(['for' => 'field'], "Select a field to change: ", false);
echo RCView::select(['id' => 'field'], $fields);

echo RCView::br();

echo RCView::label(['for' => 'newValue'], "Value to fill in: ", false);
echo RCView::input(['id' => 'newValue', 'type' => 'text', 'placeholder' => 'new value']);

echo RCView::br();

echo RCView::submit(['id' => 'submission', 'disabled' => True]);

require_once APP_PATH_DOCROOT . 'ProjectGeneral/footer.php';
