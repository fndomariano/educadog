require('./bootstrap');
require('fslightbox');

$('.editor').summernote({
	height: 250
});

$(function() {
	$('.date').daterangepicker({
		singleDatePicker: true,
		locale: {
			format: "DD/MM/YYYY",
			separator: " - ",
			applyLabel: "Aplicar",
			cancelLabel: "Cancelar",
			fromLabel: "De",
			toLabel: "Para",
			customRangeLabel: "Custom",
			weekLabel: "W",
			daysOfWeek: [
				"D", "S", "T", "Q", "Q", "S", "S"
			],
			monthNames: [
				"Janeiro", "Fevereiro", "Março", "Abril", 
				"Maio", "Junho", "Julho", "Agosto", "Setembro", 
				"Outubro", "Novembro", "Dezembro"
			]			
		},
	});
});

$('.select2').select2({
	placeholder: $('.select2').attr('placeholder'),
	allowClear: true
});

$('.delete').on('click', function (event) {
	event.preventDefault();
	
	$('#confirm').modal();	

	let formClass = $(this).attr('class').split(' ').pop();
	let form = $('.'+formClass);

	$('#btn-delete-yes').on('click', function(){
		form.submit();		
	});		
});

$('.delete-media').on('click', function(event) {

	event.preventDefault();
	
	let mediaId = $(this).data('media-id');

	$.ajax({
		method: 'DELETE',
		data: { _token: $('input[name="_token"]').val() },
		url: window.location.origin + '/activity/destroyMedia/' + mediaId,
		complete: function(response) {
			
			$('a[data-media-id="'+mediaId+'"').parent().remove();
			
			if ($('.delete-media').length <= 0) {
				$('.gallery').hide();
			}			
		}	
	});
	
});