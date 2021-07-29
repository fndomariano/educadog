require('./bootstrap');

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

$(function () {
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
		event.preventDefault();
		$(this).ekkoLightbox({
			alwaysShowClose: true
		});
	});
});

$('.select2').select2({
	placeholder: $('.select2').attr('placeholder'),
	allowClear: true
});

$('.modal-video').on('click', function(event) {	
	event.preventDefault();
	let videoClass = $(this).find('video:first').attr('class').split(' ').pop();
	let video = $('.'+videoClass).clone();
	video.attr('controls', '');
	video.appendTo($("#gallery-details .modal-body"));
	$('#gallery-details').modal();
});

$('.modal-img').on('click', function(event) {	
	event.preventDefault();
	let imgClass = $(this).find('img:first').attr('class').split(' ').pop();
	let img = $('.'+imgClass).clone();	
	img.appendTo($("#gallery-details .modal-body"));
	$('#gallery-details').modal();
});

$('#gallery-details').on('hidden.bs.modal', function (event) {
	event.preventDefault();
	$("#gallery-details .modal-body").empty();
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