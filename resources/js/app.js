require('./bootstrap');

$('.delete').on('click', function (e) {
  	
	$('#confirm').modal();	

	formClass = $(this).attr('class').split(' ').pop();
	form = $('.'+formClass);

	$('#btn-delete-yes').on('click', function(){
		form.submit();		
	});		
});