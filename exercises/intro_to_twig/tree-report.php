<?php
$records = $module->getTreeReportData();
?>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="<?= $module->getUrl('css/report.css') ?>">
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
	<script>
        $(document).ready(function () {
            $('#advance-report').DataTable();
        });
	</script>

	<div class="projhdr">
		<i class="fas fa-tree"></i> Tree Report
	</div>
	<table id="advance-report" class="display" style="width:100%">
		<thead>
		<tr>
			<th>Row #</th>
			<th>Record ID</th>
			<th>Common Name</th>
			<th>Latin Name</th>
			<th>Region</th>
			<th>Foliage Type</th>
			<th>Notes</th>
		</tr>
		</thead>
		<tbody>
		<?php $row = 1 ?>
		<?php foreach ($records as $recordId => $record): ?>
			<tr>
				<td><?= $row++ ?></td>
				<td><?= $recordId ?></td>
				<td><?= $record['common_name'] ?></td>
				<td><?= $record['latin_name'] ?></td>
				<td><?= implode(', ', $module->getCheckboxChoiceLabels($record, 'region')) ?></td>
				<td><?= $module->getChoiceLabel('foliage_type', $record['foliage_type']) ?></td>
				<td><?= substr($record['notes'], 0, 100) ?>...</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

