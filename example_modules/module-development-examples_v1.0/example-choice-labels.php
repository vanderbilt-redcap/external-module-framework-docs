<h1>Choice Label Examples</h1>

<?php
$data = REDCap::getData([
	'project_id' => $module->getProjectId(),
	'return_format' => 'json-array',
]);
if (empty($data)) {
	?><h3>No Data in this project</h3>
	<p>This example requires the Example Project Data Dictionary and Example Data. See instructions on the <a href="<?php echo $module->getUrl('import-example-data.php') ?>">Import Example Data page</a>.</p>
	<?php
	return;
} else {
	$firstRecord = $data[0];
	if (!isset($firstRecord['common_name'])):
		?><h3>Incorrect Data Format</h3>
		<p>This example requires the Example Project Data Dictionary and Example Data</a>. See instructions on the <a href="<?php echo $module->getUrl('import-example-data.php') ?>">Import Example Data page</a>. </p>
		<?php
		return;
	endif;

}
?>
<h3>Getting All a Variable's Labels Using getChoiceLabels()</h3>
<p>This project has several selection variables, including Foliage Type (Variable Name: "foliage_type") and Region
	(Variable Name: "region"). You can use the method getChoiceLabels() to get labels for these selections using
	getChoiceLabels(). View this page's PHP file to see how the following is rendered:</p>
<h4>Foliage Type:</h4>
<?php
$foliageLabels = $module->getChoiceLabels('foliage_type');
?>
<ul>
	<?php
	foreach ($foliageLabels as $labelKey => $label) {
		echo "<li>Value: ".$module->escape($labelKey)." | Label: '".$module->escape($label)."'</li>";
	}
?>
</ul>
<h4>Regions:</h4>
<?php
$foliageLabels = $module->getChoiceLabels('region');
?>
<ul>
	<?php
	foreach ($foliageLabels as $labelKey => $label) {
		echo "<li>Value: ".$module->escape($labelKey)." | Label: '".$module->escape($label)."'</li>";
	}
?>
</ul>
<h3>Getting a Selected Variable's Label Using getChoiceLabel() for Radio Buttons and Single Select Dropdowns</h3>
<p>If you're looking display the label for the selected option, you'll need to use getChoiceLabel(). See the code for
    how to use this method.</p>
<h4>Foliage Type</h4>
<?php
foreach ($data as $record) {
	echo "<p><strong>".$module->escape($record['common_name']).": </strong>: ".$module->escape(
		$module->getChoiceLabel(
			'foliage_type',
			$record['foliage_type']
		)
	)."</p>";
}
?>
<h3>Working with Checkboxes</h3>
<p>Because multiple values can be selected, checkboxes require and additional step. When working with the
	"'return_format' => 'json-array'" for REDCap's "REDCap::getData()", checkbox data returns in
	"'$variableName___$labelKey' => 1 (or 0)" syntax. You can collect all the selected options using the
	"getSelectedCheckboxes()" Methods. </p>
	<h4>Region</h4>
<?php
foreach ($data as $record) {
	$regionSelectedCheckboxes = $module->getSelectedCheckboxes($record, 'region');
	$labels = [];
	foreach ($regionSelectedCheckboxes as $selectedValue) {
		$labels[] = $module->getChoiceLabel('region', $selectedValue);
	}
	echo "<p><strong>".$module->escape($record['common_name']).": </strong>: ".implode(', ', $module->escape($labels))."</p>";
}
?>
