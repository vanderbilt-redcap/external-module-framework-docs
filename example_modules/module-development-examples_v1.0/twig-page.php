<?php

require_once APP_PATH_DOCROOT . 'ProjectGeneral/header.php';

$module->initializeTwig();
$module->loadTwigExtensions();
echo $module->getTwig()->render('example_template.html.twig', [
	'name' => $_POST['name'],
	'feeling' => $_POST['feeling']
]);

require_once APP_PATH_DOCROOT . 'ProjectGeneral/footer.php';
