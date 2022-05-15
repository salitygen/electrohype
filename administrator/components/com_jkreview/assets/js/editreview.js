jQuery(document).ready(function($) {
	
	Joomla.submitbutton = function(task) {
		if(task.split('.')[0] == 'view'){
			location = location.pathname + '?option=com_jkreview&' + task.split('.')[0]+'=' + task.split('.')[1];
		}else if(task.split('.')[0] == 'task'){
			var action = location.pathname + '?option=com_jkreview&' + task.split('.')[0]+'=' + task.split('.')[1];
			$('form').attr('action',action);
			$('form').submit();
		}
	}
	
	$('div#stars').starRating({
		initialRating: getRating(),
		starSize: 25,
		ratedColor: '#ffb700',
		useGradient: false,
		activeColor: '#ffb700',
		disableAfterRate: false,
		callback: function(currentRating){
			$('input[name="rating"]').val(currentRating);
		}
	});
	
	var csr = document.cookie.split('admtoken')[1].split(';')[0].split('=')[1];
	jQuery('input[name="tоkеnсsrf"]').attr("name","tokencsrf");
	jQuery('input[name="tokencsrf"]').val(csr);
	
	function getRating(){
		var ratyng = $('input[name="rating"]').val();
		var isnew = $('input[name="rating"]').attr('is-new');
		if(isnew == 1){
			return 3.5;
		}else{
			return ratyng;
		}
	}
	
	$('.attachments span.icon-unpublish').click(function(){
		var id = $(this).attr('data-id');
		$(this).parent().remove();
		$.ajax({
			type:'POST',
			url:'../administrator/index.php?option=com_jkreview&task=delattachment',
			data:{id:id,tokencsrf:csr},
			success:function(){

			},
			error:function(data){
				console.log(data);
			}
		});
	});
	
	$('.faike_input').click(function(){
		$(this).parent().addClass('edit');
		check();
	});
	
	$('.catselect option').click(function(){
		$(this).attr('selected',true);
		$(this).prop('selected',true);
		var attrId = $(this).val();
		var text = $(this).text();
		$('.faike_input').append('<span class="cat_b" data-id="'+attrId+'">'+text+'<i class="icon-unpublish"></i></span>');
		$('.catselect option[selected="selected"]').attr('selected', true);
		$('.catselect option[selected="selected"]').prop('selected', true);
		check();
	});
	
	function check(){
		if($('.edit select.catselect option[selected]').length == $('.edit select.catselect option').length){
			$('select.catselect').css({'display':'none'});
		}else if($('.edit select.catselect option[selected]').length == 0){
			if($('.faike_input span.no').length == 0){
				$('.faike_input').append('<span class="cat_b no">Нет выбранных категорий</span>');
			}
			$('select.catselect').css({'display':''});
		}else{
			$('.faike_input span.no').remove();
			$('select.catselect').css({'display':''});
		}
	}
	
	$('body').on('click','form fieldset.public label', function(){
		var radioId = $(this).attr('for');
		$('#'+radioId).prop('checked',true);
		$('#'+radioId).attr('checked',true);
		if($('#'+radioId).val() == 1){
			var radioIdRemove = $('form label.btn-danger').attr('for');
			$('#'+radioIdRemove).prop('checked',false);
			$('#'+radioIdRemove).attr('checked',false);
			$(this).addClass('active btn-success');
			$('form .btn-danger').removeClass('active btn-danger');
		}else{
			var radioIdRemove = $('form label.btn-success').attr('for');
			$('#'+radioIdRemove).prop('checked',false);
			$('#'+radioIdRemove).attr('checked',false);
			$(this).addClass('active btn-danger');
			$('form .btn-success').removeClass('active btn-success');
		}
	});
	
	$('input[name="username"]').change(function(){
		var thisText = $(this).val();
		var userId = $('input[name="userid"]').val();
		var userName = $('.hideselect select option[value="'+userId+'"]').text();
		if(thisText == userName){
			$('input[name="checkuserid"]').val(userId);
		}else{
			$('input[name="checkuserid"]').val('0');
		}
	});
	
	$(document).mouseup(function (e){
		var div = $("div#cat_label");
		if (!div.is(e.target)
		    && div.has(e.target).length === 0) {
			div.removeClass('edit');
		}
		check();
	});
	
	$('body').on('click','span.cat_b .icon-unpublish', function(){
		var id = $(this).parent().attr('data-id');
		$('select.catselect option[value="'+id+'"]').removeAttr("selected");
		$(this).parent().remove();
		check();
	});
	
	$('.hideselect select').change(function(){
		var value = $(this).val();
		var text = $(this).find('option[value="'+value+'"]').text();
		$('input[name="userid"]').val(value);
		$('input[name="checkuserid"]').val(value);
		$('input[name="username"]').val(text);
	});
	
	check();

});