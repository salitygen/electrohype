jQuery(document).ready(function($){
	
	var csr = document.cookie.split('admtoken')[1].split(';')[0].split('=')[1];
	
	Joomla.submitbutton = function(task){
		if(task.split('.')[0] == 'view'){
			if(task.split('.').length < 3){
				location = location.pathname + '?option=com_jkreview&' + task.split('.')[0]+'=' + task.split('.')[1];
			}else{
				location = location.pathname + '?option=com_jkreview&' + task.split('.')[0]+'=' + task.split('.')[1] + '&id='+ task.split('.')[2];
			}
		}else if(task.split('.')[0] == 'task'){
			if(task.split('.').length < 3){
				location = location.pathname + '?option=com_jkreview&view=jkreview&' + task.split('.')[0]+'=' + task.split('.')[1]+'&csr='+ csr;
			}else{
				location = location.pathname + '?option=com_jkreview&view=jkreview&' + task.split('.')[0]+'=' + task.split('.')[1] + '&id='+ task.split('.')[2]+'&csr='+ csr;
			}
		}else{
			console.log(task);
		}
	}
	$('input[name="id"]').change(function(){
		var k = 0;
		var arr = [];
		$('input[name="id"]').map(function(){
			if($(this).prop('checked')){
				arr[k] = $(this).val();
				k++;
			}
		});
		if(arr.length > 0){
			var ids = '.'+arr.join();
		}else{
			var ids = '';
		}
		$('.button-publish').attr('onclick','Joomla.submitbutton(`task.publish'+ids+'`)');
		$('.button-unpublish').attr('onclick','Joomla.submitbutton(`task.unpublish'+ids+'`)');
		$('#toolbar-delete button').attr('onclick','Joomla.submitbutton(`task.remove'+ids+'`)');
	});
	
	$('input[name="ids"]').change(function(){
		var prop = $(this).prop('checked');
		var k = 0;
		var arr = [];
		$('input[name="id"]').map(function(){
			$(this).prop('checked',prop);
			if(prop){
				arr[k] = $(this).val();
				k++;
			}
		});
		if(prop){
			var ids = '.'+arr.join();
		}else{
			var ids = '';
		}
		$('.button-publish').attr('onclick','Joomla.submitbutton(`task.publish'+ids+'`)');
		$('.button-unpublish').attr('onclick','Joomla.submitbutton(`task.unpublish'+ids+'`)');
		$('#toolbar-delete button').attr('onclick','Joomla.submitbutton(`task.remove'+ids+'`)');
	});
	
	$('.stars').map(function(){
		$(this).starRating({
			initialRating: $(this).attr('rating'),
			starSize: 20,
			ratedColor: '#ffb700',
			activeColor: '#ffb700',
			useGradient: false
		});
		$(this).starRating('setReadOnly', true);
	});  

});