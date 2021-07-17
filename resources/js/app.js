require('./bootstrap');

$('.select2').select2({
	placeholder: "Selecione um cliente",
	allowClear: true
});

$('.delete').on('click', function (e) {
  	
	$('#confirm').modal();	

	let formClass = $(this).attr('class').split(' ').pop();
	let form = $('.'+formClass);

	$('#btn-delete-yes').on('click', function(){
		form.submit();		
	});		
});