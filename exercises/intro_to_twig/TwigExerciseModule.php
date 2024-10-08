<?php

namespace ExternalModuleExercises\TwigExerciseModule;
use ExternalModules\AbstractExternalModule;
use Twig\TwigFunction;

class TwigExerciseModule extends AbstractExternalModule
{
	public function getTreeReportData()
	{
		return \REDCap::getData([
			'project_id' => $this->getProjectId(),
			'return_format' => 'json-array',
		]);
	}

	/**
	 * Helper method to get an array of selected labels from a checkbox variable. Useful when 'return_format' => 'json-array'
	 *
	 * @param $record - REDCap Record array containing rows of "'$fieldName___$labelKey'" syntax (from 'return_format' => 'json-array')
	 * @param $variableName - the REDCap variable to get labels from.
	 * @return array - contains labels for a given variable name.
	 */
	public function getCheckboxChoiceLabels($record, $variableName)
	{
		$allLabels = $this->getChoiceLabels($variableName);
		$selectedLabels = [];
		foreach ($record as $labelKey => $isChecked) {
			if(str_contains($labelKey, $variableName.'___') && $isChecked) {
				preg_match('/\d+/', $labelKey, $matches);
				if (isset($matches[0])) {
					$selectedLabels[] = $allLabels[$matches[0]];
				}
			}
		}

		return $selectedLabels;
	}

	public function loadTwigExtensions()
	{
		//TODO Add getCheckboxChoiceLabels to Twig using addFunction().  Link to Twig's addFunction(): https://twig.symfony.com/doc/3.x/advanced.html#functions
	}
}
