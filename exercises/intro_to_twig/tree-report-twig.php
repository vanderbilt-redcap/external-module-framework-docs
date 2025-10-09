<?php

$module->initializeTwig();
$module->loadTwigExtensions();
echo $module->getTwig()->render('report.html.twig', [
	'records' => $module->getTreeReportData(),
	'title' => 'Tree Report'
]);
