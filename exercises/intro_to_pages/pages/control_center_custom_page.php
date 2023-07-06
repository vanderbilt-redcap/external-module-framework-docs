<?php

// Include context appropriate header
/* While reading the official documentation, you may have noticed a way to
 * include this via config.json
*/
require_once APP_PATH_DOCROOT . 'ControlCenter/header.php';

$title = RCView::img(['src' => APP_PATH_IMAGES . 'bell.png']) . ' ' . REDCap::escapeHtml('Control Center Page');
echo RCView::h4([], $title);

// Your module class is instantiated automatically as $module
$module->sayHello();

require_once APP_PATH_DOCROOT . 'ControlCenter/footer.php';
