$(document).ready(function () {
	$("#confirmar").bootstrapSwitch({'onText' : 'Sí', 'offText' : 'No'});

	$(document).on('keyup', '#folio', function (e) {
		if ($(this).val().length >= 5) {
			searchFolio($(this));
		}else {
			console.log($(this));
		}
	});

	$(document).on('click', '#buscar', function (e) {
		e.preventDefault();
		if ($('#folio').val().length >= 5) {
			searchFolio($('#folio'));
		}else {
			console.log($('#folio'));
		}
	});

	$(document).on('click', '.bootstrap-switch', function (e) {
		e.preventDefault();
		if ($(this).hasClass('bootstrap-switch-on')) {
			$('.cantidad-group').fadeIn("slow");
		}else {
			$('.cantidad-group').fadeOut("slow");
		}
	});

	$(document).on('keyup', '#mensaje', function (e) {
		fillMensaje();
	});

	$('.form-asistencia').submit(function(e) {
		e.preventDefault();
		$.ajax({
			url: $(this).attr('action'),
			type: 'POST',
			dataType: 'JSON',
			data: $(this).serialize(),
			beforeSend: function () { 
				$('.form-asistencia input').each(function () {
					$(this).attr("disabled", "disabled");
				});
			}, error: function () {
				alert("Experimentamos fallas técnicas. Intente más tarde.");
			}, success: function (result) {
				alert(result.msg);
			}, complete: function () {
				$('.form-asistencia input').each(function () {
					$(this).removeAttr("disabled")
				});
			}
		});
	});

	$(document).on('click', '#btnConfirmar', function (e) {
		e.preventDefault();
		$('.form-asistencia').submit();
	});
});

function searchFolio (input) {
	$.ajax({
		url: input.attr('action'),
		type: 'POST',
		dataType: 'JSON',
		data: {'folio': input.val()},
		error: function () {
			alert("Experimentamos fallas técnicas. Intente más tarde.");
		}, success: function (result) {
			if (result.error) {
				alert(result.msg);
			}else {
				$('.info-asistente').removeClass('hidden');
				fillFields(result.data);
			}
		}, complete: function () {
			fillMensaje();
		}
	});
}
function fillMensaje() {
	var leftchars = 250 - $('#mensaje').val().length;
	$('#remaining-chars').text(leftchars);
	if (leftchars < 30) {
		$('#remaining-chars').addClass("alert-danger");
	}else {
		$('#remaining-chars').removeClass("alert-danger");
	}
}
function fillFields (data) {
	$('#nombre').val(data.nombre);
	$('#cantidad-sel').html(data.cantidad);
	$('#correo').val(data.email);
	$('#mensaje').val(data.mensaje);
}