<h1>Import Example Data</h1>
<p>Some of the examples use project date. Follow these instructions to load example data:</p>
<ol>
    <li>Download the <a href="<?php echo $module->getUrl('example-module-data-dictionary.csv') ?>">Data Dictionary provided in this module</a></li>
    <li>Upload the data dictionary .CSV file on this project's <a href="<?= APP_PATH_WEBROOT ?>Design/data_dictionary_upload.php?pid=<?= $module->getProjectId() ?>">Data Dictionary Page</a></li>
    <li>Once configured, click the button below to import the example data.</li>
</ol>
<p style="font-style: italic">Note: You may also view this PHP page to learn about importing project data via a .JSON file. </p>

<form method="post" action="<?= $module->getUrl('import-example-data.php') ?>">
    <input type="submit" name="submit" value="Import Example Data">
</form>

<?php
if (!empty($_POST['submit'])) {
	$filename = $module->getModulePath().'example-module-data.json';
	if (!file_exists($filename)) {
		echo $filename.' Error - File not found';
	}
	$data = file_get_contents($filename);

	$result = REDCap::saveData([
		'project_id' => $module->getProjectId(),
		'data' => $data,
		'dataFormat' => 'json',
	]);

	if (!empty($result['errors'])) {
		echo '<p>Error importing data:<pre>' . $module->escape(print_r($result, true)) . '</pre></p>';
	} else {
		$choiceLabelsUrl = $module->getUrl('example-choice-labels.php');
		echo '<p>Imported data successfully! Visit the <a style="text-decoration: underline" href="'.$choiceLabelsUrl.'">Example Choice Labels page to see this data in an example.</a></p>';
	}
}
