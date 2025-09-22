if(typeof ExternalModulesOptional === 'undefined') {
	var ExternalModulesOptional = {};
}

ExternalModulesOptional.customTextAlert = function(textSelector) {
	textSelector.focus(function() {
		console.log($(this).val());
	});
};