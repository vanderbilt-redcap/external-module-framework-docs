<?php

require_once APP_PATH_DOCROOT . 'ProjectGeneral/header.php';

// "views" is the default directory, but you can use any directory, like "twig_views"
$module->initializeTwig('twig_views');
$module->loadTwigExtensions();
echo $module->getTwig()->render('example_template.html.twig', [
	'name' => $_POST['name'],
	'feeling' => $_POST['feeling']
]);

require_once APP_PATH_DOCROOT . 'ProjectGeneral/footer.php';
