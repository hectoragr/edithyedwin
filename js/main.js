$(document).ready(function () {
	$("#confirmar").bootstrapSwitch({'onText' : 'Sí', 'offText' : 'No'});
	$(document).on('click', '#buscar', function (e) {
		e.preventDefault();
	});
});