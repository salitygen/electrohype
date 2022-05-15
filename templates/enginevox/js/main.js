$.event.special.touchstart = {
	setup: function( _, ns, handle ){
		this.addEventListener("touchstart", handle, { passive: true });
	}
};

jQuery(document).ready(function($){
	
	$(window).keydown(function(event){
		if(event.keyCode == 13) {
			event.preventDefault();
			return false;
		}
	});
	
	var resize = true;
	
	$(window).resize(function(){
		downPanel();
		slickCheckDesctop();
	});
	
	function downPanel(){
		if($(document).width() < 983){
			if(resize){
				resize = false;
				$('a.cart.btn').append('<span class="tile">Корзина</span>')
				$('body').append('<div class="down_panel"><div class="center">'+$('.buttons.user').html()+'</div></div>');
			}
		}else{
			resize = true;
			$('a.cart.btn .tile').remove();
			$('body .down_panel').remove();
		}
	}
	
	downPanel();
	
	$('body').on('click','.overflowBox button.arrow.up',function() {
		$('.imgScrollSlider ul.imgSlider').animate({
			scrollTop:$('.imgScrollSlider ul.imgSlider').scrollTop()-200
		},500);
	});
	
	$('body').on('click','.overflowBox button.arrow.down',function() {
		$('.imgScrollSlider ul.imgSlider').animate({
			scrollTop:$('.imgScrollSlider ul.imgSlider').scrollTop()+200
		},500);
	});
	
	$('body').on('change','.cartbox .variability input',function(){
		let val = $(this).val();
		if(val !== 'this'){
			variabilityChange(val);
		}
	});
	
	$('.cartbox a.rating').bind('click.smoothscroll',function(e){
		e.preventDefault();
		var target = this.hash,
		$target = $(target);
		$('html, body').stop().animate({
			'scrollTop': $target.offset().top - 120
		}, 300, 'linear', function () {
			window.location.hash = target;
		});
	});
	
	$('body').on('click','button.openSpecifications',function(){
		$('div#specifications').toggleClass('open');
		if($('div#specifications.open').length){
			$(this).text('Свернуть');
		}else{
			$(this).text('Показать все');
		}
	});
	
	$(document).mouseup(function(e){
		var div = $(".formHtml,.formLogin,button.see,.form-search");
		if (!div.is(e.target) && div.has(e.target).length === 0){
			$('body').removeClass('activeForm');
			$('body').removeClass('activeLoginForm');
			$('button.see').remove();
		}
	});
	
	$('body').on('change input paste onchange','.jlmf-section .inputs input',function(){
		$('button.see').remove();
		$(this).parent().parent().append('<button class="see" type="button">Показать</button>');
	});
	
	$('body').on('click','button.clear',function(){
		$('button.see').remove();
		$(this).parent().find('input').prop('checked',false);
		$(this).parent().find('input').attr('checked',false);
		$(this).parent().find('.checkboxes:last-child').append('<button class="see" type="button">Показать</button>');
		$(this).remove();
	});
	
	$('body').on('click','.jlmf-section.checkbox label',function(){
		$('button.see').remove();
		$(this).parent().parent().parent().find('button.clear').remove();
		$(this).parent().append('<button class="see" type="button">Показать</button>');
		if(!$(this).parent().find('input').prop('checked')){
			$(this).parent().parent().parent().append('<button class="clear" type="button">Сбросить</button>');
		}else{
			if(($(this).parent().parent().parent().find('input:checked').length - 1) == 0){
				$(this).parent().parent().parent().find('button.clear').remove();
			}else{
				$(this).parent().parent().parent().append('<button class="clear" type="button">Сбросить</button>');
			}
		}
	});
	
	$('body').on('click','.jlmf-section.ordering label',function(){
		$('button.see').remove();
		$(this).parent().parent().parent().find('button.clear').remove();
		$(this).parent().append('<button class="see" type="button">Показать</button>');
	});
	
	$('body').on('click','button.see',function(e){
		e.preventDefault();
		$('form.form-search').submit();
	});
	
	$('.blog.cart .right .order').slick({
		speed: 180,
		infinite: false,
		slidesToShow: 1,
		slidesToScroll: 1, 
		autoplay: false,
		dots: false,
		arrows: false,
		swipe: false,
		accessibility: false,
		variableWidth: true
	});
	
	var nextSlideTrigger = false;
	
	$('body').on('click','.blog.cart .right .order .shipment label input[type="radio"]',function(){
		var delivery = $(this).val();
		$('.blog.cart .right .order').slick('slickPrev');
		if(delivery == 'Самовывоз'){
			$('.blog.cart .right .order .delivery input').attr('disabled','disabled');
			$('.delivery').addClass('display-none');
			nextSlideTrigger = true;
		}else{
			$('.blog.cart .right .order .delivery input').prop('disabled',false);
			$('.delivery').removeClass('display-none');
			nextSlideTrigger = false;
		}
	});

	$('body').on('focus','.calculate input',function(){
		$(this).removeAttr('readonly');
	});
	
	$('body').on('blur','.calculate input',function(){
		$(this).attr('readonly','readonly');
	});
	
	$('button.prevOrderStep').click(function(){
		$('.blog.cart .right .order').slick('slickPrev');
	});
	
	$('body').on('click','.delivery button.nextOrderStep',function(){
		if(nextSlideTrigger){
			$('.blog.cart .right .order').slick('slickNext');
		}else{
			if(!document.querySelector('.delivery input').checkValidity()){
				document.querySelector('.delivery input').reportValidity();
			}else{
				$('.blog.cart .right .order').slick('slickNext');
			}
		}
	});

	$('body').on('click','.shipment button.nextOrderStep',function(){
		if(nextSlideTrigger){
			$('.blog.cart .right .order').slick('slickGoTo',3);
		}else{
			$('.blog.cart .right .order').slick('slickNext');
		}
	});

	$('body').on('click', '.password-checkbox', function(){
		if($(this).is(':checked')){
			$('label.eye').addClass('on');
			$('label.eye input').prop('checked',true);
			$('input[name="password"], input[name="confirm_password"]').attr('type','text');
		}else{
			$('label.eye').removeClass('on');
			$('label.eye input').prop('checked',false);
			$('input[name="password"], input[name="confirm_password"]').attr('type','password');
		}
	}); 
	
	$('body').append('<div class="formHtml"></div><div class="formLogin"></div>');
	
	$('a.button.login').click(function(){
		
		var formLogin = '<i class="close"></i>';
		formLogin += '<input type="radio" name="tab-btn" id="tab-btn-1" value="">';
		formLogin += '<label for="tab-btn-1">Регистрация</label>';
		formLogin += '<input type="radio" name="tab-btn" id="tab-btn-2" value="" checked="">';
		formLogin += '<label for="tab-btn-2">Вход</label>';
		
		formLogin += '<form action="/jkmagazine/addUser/" method="POST" id="form-1">';
		
		formLogin += '<div class="step s1 visible">';
		formLogin += '<h3><b>Шаг 1 из 3</b> <i>(получение кода подтверждения)</i></h3>';
		formLogin += '<label>Ваше имя:</label>';
		formLogin += '<input type="text" name="name" placeholder="Имя" required="required" autocomplete="off">';
		formLogin += '<label>Ваш телефон:</label>';
		formLogin += '<input type="tel" name="username" placeholder="+7(___)___-__-__" required="required" autocomplete="username">';
		formLogin += '<p>На указанный номер телефона, будет отправлен код подтверждения. Нажимая кнопку "Получить код", Вы соглашаетесь c условиями <a href="/politika-konfidentsialnosti" target="_blank">политики конфиденциальности</a></p>';
		formLogin += '<button type="submit" class="getCode">Получить код</button>';
		formLogin += '<input type="hidden" name="get_code" value="1">';
		formLogin += '</div>';
		
		formLogin += '<div class="step s2">';
		formLogin += '<h3><b>Шаг 2 из 3</b> <i>(подтверждение номера телефона)</i></h3>';
		formLogin += '<label>Введите полученый код:</label>';
		formLogin += '<input type="text" name="check_code" placeholder="_ _ _ _ _ _" required="required" disabled="disabled" autocomplete="off">';
		formLogin += '<p>Мы отправили код проверки, на указанный Вами номер <i class="number">+7(000)000-00-00</i>. Обычно код приходит сразу, если этого не случилось, повторите попытку через <b class="timer">10:00</b> минут.</p>';
		formLogin += '<button type="button" class="next lock">Далее</button>';
		formLogin += '</div>';
		
		formLogin += '<div class="step s3">';
		formLogin += '<h3><b>Шаг 3 из 3</b> <i>(введите новый пароль)</i></h3>';
		formLogin += '<label>Введите пароль:</label>';
		formLogin += '<div class="password-box">';
		formLogin += '<label class="eye"><input type="checkbox" class="password-checkbox"></label>';
		formLogin += '<input type="password" name="password" placeholder="" required="required" disabled="disabled">';
		formLogin += '</div>';
		formLogin += '<label>Повторите пароль:</label>';
		formLogin += '<div class="password-box">';
		formLogin += '<label class="eye"><input type="checkbox" class="password-checkbox"></label>';
		formLogin += '<input type="password" name="confirm_password" placeholder="" required="required" disabled="disabled">';
		formLogin += '</div>';
		formLogin += '<p>Введите свой новый пароль, он пригодится для входа на сайт, после Вы получите возможность отслеживания статуса своих заказов и возможность просматривать историю покупок!</p>';
		formLogin += '<input type="hidden" name="'+$('form.token input').attr('name')+'" value="1">';
		formLogin += '<button type="submit" class="fine">Создать учетную запись!</button>';
		formLogin += '</div>';

		formLogin += '</form>';
		formLogin += '<form action="/" method="POST" id="form-2">';
		
		formLogin += '<h3>Авторизация на сайте</h3>';
		formLogin += '<label>Ваш телефон:</label>';
		formLogin += '<input type="tel" name="username" placeholder="+7(___)___-__-__" required="required" autocomplete="username">';
		formLogin += '<label>Ваш пароль:</label>';
		formLogin += '<div class="password-box">';
		formLogin += '<label class="eye"><input type="checkbox" class="password-checkbox"></label>';
		formLogin += '<input type="password" name="password" placeholder="" required="required">';
		formLogin += '</div>';
		formLogin += '<p>Профиль дает возможность отслеживания статуса заказов на сайте, а так же позволяет хранить и просматривать историю Ваших покупок!</p>';
		formLogin += '<input type="hidden" name="'+$('form.token input').attr('name')+'" value="1">';
		formLogin += '<input type="hidden" name="option" value="com_users">';
		formLogin += '<input type="hidden" name="task" value="user.login">';
		formLogin += '<input type="hidden" name="return" value="'+$('form.token input.return').val()+'">';
		formLogin += '<button type="submit">Войти</button>';
		formLogin += '<a href="/" class="getNewPass">Забыли пароль?</a>';
		formLogin += '</form>';

		$('body .formLogin').html(formLogin);
		validate();
		$('input[name="check_code"]').mask('9 9 9 9 9 9');
		$('body').addClass('activeLoginForm');

		$('form#form-1').slick({
			speed: 180,
			infinite: false,
			slidesToShow: 1,
			slidesToScroll: 1, 
			autoplay: false,
			dots: false,
			arrows: false,
			swipe: false,
			accessibility: false,
			variableWidth: true
		});
		
		return false;
		
	});
	
	function appendForm(name=false){
		
		if(!name){
			name = '';
		}
		
		var formHtml = '<i class="close"></i><h3>'+name+'</h3>';
			formHtml += '<p>Оставьте Ваше сообщение, мы обязательно на него ответим!</p>';
			formHtml += '<form>';
			formHtml += '<label>Ваше имя:</label>';
			formHtml += '<input type="text" name="name" placeholder="Имя" required="required">';
			formHtml += '<label>Ваш контактный телефон:</label>';
			formHtml += '<input type="tel" name="phone" placeholder="+7(___)___-__-__" required="required">';
			formHtml += '<label>Ваша электронная почта:</label>';
			formHtml += '<input type="email" name="email" placeholder="" required="required">';
			formHtml += '<label>Сообщение:</label>';
			formHtml += '<textarea name="text" required="required"></textarea>';
			formHtml += '<label>Прикрепить файл: </i>(необязательно)</i></label>';
			formHtml += '<input type="file" name="file[]" multiple>';
			formHtml += '<input type="hidden" name="formName" value="'+name+'">';
			formHtml += '<button type="submit">Отправить</button>';
			formHtml += '</form>';

		$('body .formHtml').html(formHtml);
		getCsr();
		$('input[type="tel"]').mask('+7(999)999-99-99');
		$('body').addClass('activeForm');

	}
	
	$('body').on('input paste change blur keyup', 'input[type="tel"]',function(){
		let val = $('input[type="tel"]').val();
		if (val[0] == 8 || val[0] == 7){
			$('input[type="tel"]').val(val.slice(1));
		}
	});
	
	function getCsr(){
		
		$.ajax({ 
			url:'/ajax/csr.php',
			dataType: 'html',
			async:false,
			success: function(data){
				$('body form .csr').remove();
				$('body form').append('<input class="csr" type="hidden" name="сsr" value="'+data+'">');
			}
		});
		
	}
	
	$('button.message').click(function(){
		appendForm($(this).data('name'));
	});
	
	$('body').on('click','i.close',function(){
		$('body').removeClass('activeForm');
		$('body').removeClass('activeLoginForm');
	});
	
	function starRatingInit(){
		
		$('.rating').map(function(){
			$(this).starRating({
				initialRating: $(this).find('input').val(),
				starSize: 15,
				ratedColor: '#ffb700',
				activeColor: '#ffb700',
				useGradient: false
			});
			$(this).starRating('setReadOnly', true);
		}); 
		
	}
	
	starRatingInit();
	
	function slickCheckDesctop(){
		
		if($('.cartbox nav.moduletable_list_related ul.slick-initialized').length == 0){
			$('.cartbox nav.moduletable_list_related ul').slick(relatedCbSlick());
		}

		if($('.mobile_related nav.moduletable_list_related ul.slick-initialized').length == 0){
			$('.mobile_related nav.moduletable_list_related ul').slick(relatedSlick());
		}
		
		if($('nav.moduletable_list_cat.similar ul.slick-initialized').length == 0){
			$('nav.moduletable_list_cat.similar ul').slick(similarSlick());
		}

		if($('.imgScrollSlider ul.imgSlider.slick-initialized').length == 0){
			$('.imgScrollSlider ul.imgSlider').slick(imgScrollSlider());
		}

	}
	
	slickCheckDesctop();

	$('body').on('click','.overflowBox button.arrow',function(){
		if($(this).hasClass('up')){
			$('.imgScrollSlider ul.imgSlider').slick('slickPrev');
		}
		if($(this).hasClass('down')){
			$('.imgScrollSlider ul.imgSlider').slick('slickNext');
		}
	});

	$('body').on('click','nav.moduletable_list_cat.similar button.arrow',function(){
		if($(this).hasClass('prev')){
			$('nav.moduletable_list_cat.similar ul').slick('slickPrev');
		}
		if($(this).hasClass('next')){
			$('nav.moduletable_list_cat.similar ul').slick('slickNext');
		}
	});
	
	$('body').on('click','nav.moduletable_list_related button.arrow',function(){
		if($(this).hasClass('prev')){
			$('nav.moduletable_list_related ul').slick('slickPrev');
		}
		if($(this).hasClass('next')){
			$('nav.moduletable_list_related ul').slick('slickNext');
		}
	});

	function imgScrollSlider(){
		return {
	        vertical: true,
	        verticalSwiping: true,
	        slidesToShow: 3,
	        slidesToScroll: 3,
	        autoplay: false,
			infinite: false,
			dots: false,
			arrows: false,
			responsive: [
				{
				  breakpoint: 999,
				  settings: {
				  	vertical: false
				  }
				}
			]
		}
	}

	function relatedSlick(){
		return {
			slidesToShow: 1,
			slidesToScroll: 1,
			infinite: true,
			autoplay: true,
			arrows: false,
			responsive: [
				{
				  breakpoint: 999,
				  settings: {
					slidesToShow: 2,
					slidesToScroll: 2
				  }
				},
				{
				  breakpoint: 671,
				  settings: {
					slidesToShow: 1,
					slidesToScroll: 1
				  }
				}
			]
		}
	}

	function similarSlick(){
		return {
			slidesToShow: 1,
			slidesToScroll: 1,
			infinite: true,
			autoplay: true,
			arrows: false,
			responsive: [
				{
				  breakpoint: 999,
				  settings: {
					slidesToShow: 2,
					slidesToScroll: 2
				  }
				},
				{
				  breakpoint: 671,
				  settings: {
					slidesToShow: 1,
					slidesToScroll: 1
				  }
				}
			]
		}
	}
	
	function relatedCbSlick(){
		if($('.cartbox.max').length){
			var count = 3;
		}else{
			var count = 2;
		}
		return {
			slidesToShow:count,
			slidesToScroll:count,
			infinite: true,
			autoplay: false,
			arrows: false,
			responsive: [
				{
				  breakpoint: 999,
				  settings: {
					slidesToShow: 3,
					slidesToScroll: 3
				  }
				},
				{
				  breakpoint: 671,
				  settings: {
					slidesToShow: 2,
					slidesToScroll: 2
				  }
				}
			]
		}
	}

	$('.mod-banners.bannergroup').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		autoplay:true,
		prevArrow: '<button class="arrow next"></button>',
        nextArrow: '<button class="arrow prev"></button>'
	});
	
	$('.ordering .jlmf-label,.checkbox .jlmf-label').click(function(){
		$(this).parent().toggleClass('open');
	});
	
	$('button.openMenu').click(function(){
		$('div#head .buttons .moduletable.top_menu').toggleClass('visible');
	});
	
	
	if($('span.calcTotal').length){
		var total = Number($('span.calcTotal').text().replace(" ","").replace(",","."));
		if($('select[name="shipping"]').val()!=0){
			var totalTax = total + 300;
			$('span.calcShipping').text(totalTax.toLocaleString());
			$('td.shp span').text('+300 руб.');
		}else{
			$('span.calcShipping').text(total.toLocaleString());
			$('td.shp span').text('');
		}
	}

	$('body').on('change','select[name="shipping"]',function(){
		var total = Number($('span.calcTotal').text().replace(" ","").replace(",","."));
		if($(this).val()!=0){
			if(total < 3000){
				var totalTax = total + 300;
				$('span.calcShipping').text(totalTax.toLocaleString());
				$('td.shp span').text('+300 руб.');
				document.cookie = 'tax='+$(this).val();
			}else{
				$('span.calcShipping').text(total.toLocaleString());
				$('td.shp span').text('Бесплатно!');
				document.cookie = 'tax='+$(this).val();
			}
		}else{
			if(total < 1000){
				var totalTax = total + 300;
				$('span.calcShipping').text(totalTax.toLocaleString());
				$('td.shp span').text('+300 руб.');
				document.cookie = "tax=0";
			}else{
				$('span.calcShipping').text(total.toLocaleString());
				$('td.shp span').text('Бесплатно!');
				document.cookie = "tax=0";
			}
		}
	});
	
	$('div#system-message a.close').click(function(){
		$('div#system-message-container').remove();
	});
	
	$('body').on('click','.controls button.minus',function(){
		var value = parseInt($('.controls input').val());
		if(value > 1){
			value = value - 1;
		}else{
			value = 1;
		}
		$('.controls input').val(value);
	});
	
	$('body').on('change','.controls .calculate input',function(){
		var value = parseInt($(this).val());
		if(value < 1){
			value = 1;
		}else{
			value = value;
		}
		$(this).val(value);
	});
	
	$('body').on('click','.controls button.plus',function(){
		var value = parseInt($('.controls input').val());
		value = value + 1;
		$('.controls input').val(value);
	});
	
	$('div#cookies button.close').click(function(){
		$('div#cookies').remove();
		document.cookie = 'cookie=yes';
	});
 	
	$('body').on('click','.calculate button.minus',function(){
		var count = parseInt($(this).parent().find('input').val());
		var id = $(this).parent().data('id');
		if(count > 1){
			count = count - 1;
		}else{
			count = 1;
		}
		sendCart(id,count);
	});
	
	$('body').on('change','.calculate input',function(){
		var count = parseInt($(this).val());
		var id = $(this).parent().data('id');
		if(count < 1){
			count = 1;
		}else{
			count = count;
		}
		sendCart(id,count);
	});
	
	$('body').on('click','.calculate button.plus',function(){
		var count = parseInt($(this).parent().find('input').val());
		var id = $(this).parent().data('id');
		count = count + 1;
		sendCart(id,count);
	});
	
	$('body').on('click','.cart .more button.cartButton.remove',function(){
		$(this).parent().parent().addClass('delete');
		var id = $(this).data('id');
		sendCart(id,'remove');
	});
	
	function sendCart(id,count){
		
		if(count !=='remove'){
			$('body').addClass('process');
		}
		
        var update = {
			id:id,
			count:count
		}
		
		$.ajax({
			url:'/cart/',
            type: 'post',
			data:{'update':JSON.stringify(update)},
			success: function(data){
				$('body').removeClass('process');
				$('#cover').remove();
				if($(data).find('.blog.cart .left li').length){
					$('.blog.cart .left').html($(data).find('.blog.cart .left').html());
					$('p.totalOrder').html($(data).find('p.totalOrder').html());
				}else{
					$('.blog.cart').html($(data).find('.blog.cart').html());
				}
				checkCart();
				starRatingInit();
			},
			error: function (error) {
				$('body').removeClass('process');
				$('form').trigger('reset');
				checkCart();
				alert('Произошла ошибка отправки формы, попробуйте позже');
			}
		});
	};
	
	function checkCart(){
		
		$.ajax({
			url:'/jkmagazine/checkCart/?check=1',
			type: 'get',
			success: function(data){
				$('span.cart_item_counter').html(data);
			},
			error: function (error) {
				$('span.cart_item_counter').html(0);
			}
		});
		
	}
	
	checkCart();
	
	$('body').append('<div id="preloader"></div>');
	
	function checkLeft(){
		
		if($(window).scrollTop() >= 50){
			$('body').addClass('stop-head');
		}else{
			$('body').removeClass('stop-head');
		}
		
		if($('tr.gatgets').length){
			if($('div#head').offset().top >= 140){
				$('body').addClass('stop-gatgets');
			}else{
				$('body').removeClass('stop-gatgets');
			}
		}

		if($('.blog.cart').length == 0){
			
			let chk = $(window).height() + $('div#content').offset().top;
			
			if($('.item').length){
				if($('nav.moduletable_list_cat.similar').length){
					var offsetTop = $('div#content').offset().top + $('div#content .item').outerHeight() + $('div#content nav.moduletable_list_cat.similar').outerHeight() + 60;
				}else{
					var offsetTop = $('div#content').offset().top + $('div#content .item').outerHeight() + 60;
				}
			}else{
				var offsetTop = $('div#content').offset().top + 25;
			}
			
			var offsetButtom = $(document).height() - $('div#footer').outerHeight() - $(window).height() - 67;

			if($('div#content .left').height() < ($('div#content').height() - offsetTop)){
				if(chk < $('div#content .left').height()){
					var offset = (offsetTop + $('div#content .left').outerHeight()) - $(window).height();
					if(offset <= $(window).scrollTop()){
						$('body').addClass('stop-left');
						if($(window).scrollTop() >= offsetButtom){
							$('body').addClass('left-end');
						}else{
							$('body').removeClass('left-end');
						}
					}else{
						$('body').removeClass('stop-left');
					}
				}
			}else{
				if(chk < $('div#content .right').height()){
					var offset = (offsetTop + $('div#content .right').outerHeight()) - $(window).height();
					if(offset <= $(window).scrollTop()){
						$('body').addClass('stop-right');
						if($(window).scrollTop() >= offsetButtom){
							$('body').addClass('right-end');
						}else{
							$('body').removeClass('right-end');
						}
					}else{
						$('body').removeClass('stop-right');
					}
				}
			}
		}
	}
	
	$(window).scroll(function() {
		checkLeft();
	});
	
	checkLeft();

	$('body').on('click','button.addToCart',function(){
		
		if($(this).parent().find('.controls input').val()){
			var count = $(this).parent().find('.controls input').val();
		}else{
			count = 1;
		}

		let allItems = {
			id:		$(this).data('id'),
			count:	count
		};

		$('body').addClass('process');
		
		var jsonItems = JSON.stringify(allItems);
	
		$.ajax({
			url: '/jkmagazine/addcart/',
			type: 'POST',
			data: {'json':jsonItems},
			dataType: 'json',
			success: function(data){
				$('body').removeClass('process');
				checkCart();
			},
			error: function(){
				checkCart();
				$('body').removeClass('process');
				alert('Произошла ошибка отправки формы, пожалуйста, обновите страницу и попробуйте еще раз');
			}
		});

	});
	
	if($('a.page-link.next').length > 0){
		
		var url = $('a.page-link.next').attr('href');
		
		$('body').on('click','button.loadNext',function(){
			
			$('body').addClass('process');
			
			$.ajax({ 
				url:url,
				dataType: 'html',
				success: function(data){
					$('body').removeClass('process');
					$('ul.producktList').append($(data).find('ul.producktList').html());
					$('.pagination').html($(data).find('.pagination').html());
					if($('a.page-link.next').length > 0){
						url = $('a.page-link.next').attr('href');
						starRatingInit();
					}else{
						$('button.loadNext').remove();
					}
					
				}
			});
			
		});
		
	}
	
	$('body').on('click','button.addCompare',function(){
		
		$('body').addClass('process');
		
		if(!$(this).hasClass('active')){

			$('button.addCompare[data-id="'+$(this).data('id')+'"]').addClass('active');
			$('button.addCompare[data-id="'+$(this).data('id')+'"]').addClass('remove');

			let allItems = {
				id:		$(this).data('id'),
				count:	1
			};
			
			if(!$(this).hasClass('com')){
				$(this).find('span').text('В сравнении');
			}

			var jsonItems = JSON.stringify(allItems);
		
			$.ajax({
				url: '/jkmagazine/addcompare/',
				type: 'POST',
				data: {'json':jsonItems},
				dataType: 'json',
				success:function(){
					$('body').removeClass('process');
				},
				error: function(){
					alert('Произошла ошибка');
				}
			});
			
		}else{
			
			$('button.addCompare[data-id="'+$(this).data('id')+'"]').removeClass('active');
			$('button.addCompare[data-id="'+$(this).data('id')+'"]').removeClass('remove');
			
			var remove = {
				id:$(this).data('id'),
			}
			
			$.ajax({
				url:'/compare/',
				type: 'post',
				data:{'remove':JSON.stringify(remove)},
				success:function(){
					$('body').removeClass('process');
				},
				error: function (error) {
					alert('Произошла ошибка');
				}
			});
			
			if($(this).hasClass('com')){
				var itemClass = $(this).parent().parent().parent().attr('class');
				var itemLength = $('tr.gatgets td').length;
				if(itemLength < 5){
					$('.blog.compare table').addClass('min-'+(itemLength-1));
					$('.blog.compare table').removeClass('min-'+itemLength);
					if(itemLength == 1){
						$('table').remove();
						$('.blog.compare').append('<div class="noResult"><div class="cnt"><img src="/templates/enginevox/img/logo-min.svg" alt="Electrohype"><p>Вы не добавили товары в сравнение</p><a href="/" class="goCatalog">Перейти в каталог</a></div></div>');
					}
					
				}
				$('.'+itemClass).remove();
			}else{
				$(this).find('span').text('В сравнение');
			}
			
		}
		

	});
	
	$('body').on('click','button.addFavorites',function(){
		
		$('body').addClass('process');
		
		if(!$(this).hasClass('active')){
			
			$('button.addFavorites[data-id="'+$(this).data('id')+'"]').addClass('active');
			$('button.addFavorites[data-id="'+$(this).data('id')+'"]').addClass('remove');

			let allItems = {
				id:		$(this).data('id'),
				count:	1
			};
			
			if(!$(this).hasClass('fav')){
				$(this).find('span').text('В избранном');
			}
			
			var jsonItems = JSON.stringify(allItems);
		
			$.ajax({
				url: '/jkmagazine/addfavorites/',
				type: 'POST',
				data: {'json':jsonItems},
				dataType: 'json',
				success:function(){
					$('body').removeClass('process');
				},
				error: function(){
					alert('Произошла ошибка');
				}
			});
			
		}else{

			$('button.addFavorites[data-id="'+$(this).data('id')+'"]').removeClass('active');
			$('button.addFavorites[data-id="'+$(this).data('id')+'"]').removeClass('remove');
			
			var remove = {
				id:$(this).data('id'),
			}
			
			$.ajax({
				url:'/favorites/',
				type: 'post',
				data:{'remove':JSON.stringify(remove)},
				success:function(){
					$('body').removeClass('process');
				},
				error: function (error) {
					alert('Произошла ошибка');
				}
			});
			
			if($(this).hasClass('fav')){
				$(this).parent().parent().remove();
				if($('.blog.favorites li').length < 1){
					$('.blog.favorites ul').remove();
					$('.blog.favorites').append('<div class="noResult"><div class="cnt"><img src="/templates/enginevox/img/logo-min.svg" alt="Electrohype"><p>Вы не добавили товары в избранное</p><a href="/" class="goCatalog">Перейти в каталог</a></div></div>');
				}
			}else{
				$(this).find('span').text('В избранное');
			}
			
		}
		
	});
	
	$('form.search').append('<div class="ajaxResults"></div>');
	$('form.search').append('<button type="button" class="close"></button>');
	
	$(document).mouseup(function (e){
		var div = $("form.search");
		if (!div.is(e.target) && div.has(e.target).length === 0){
			$('form.search .ajaxResults').html('');
			$('body').removeClass('search-fine');
			$('body').removeClass('searching');
			$('body').removeClass('input-search');
			$('form.search').trigger('reset');
		}
	}); 
	
	$('body').on('click','form.search button.close',function(e){
		e.preventDefault();
		$('form.search .ajaxResults').html('');
		$('body').removeClass('search-fine');
		$('body').removeClass('searching');
		$('body').removeClass('input-search');
		$('form.search').trigger('reset');
	});
	
	$('body').on('change input','form.search input',function(){
		
		if($(this).val().length > 2){
			
			$('body').addClass('searching');
			$('body').removeClass('search-fine');
			
			$.ajax({
				url:'/search/?tmpl=component&searchword='+$(this).val(),
				type: 'GET',
				success:function(data){
					$('form.search .ajaxResults').html(data);
					$('body').removeClass('searching');
					$('body').addClass('search-fine');
				},
				error: function (error) {
					$('body').addClass('search-fine');
					$('body').removeClass('searching');
					alert('Произошла ошибка');
				}
			});
			
			if(!$('body').hasClass('input-search')){
				$('body').addClass('input-search');
			}
			
		}else{
			$('body').removeClass('search-fine');
			$('body').removeClass('searching');
			$('body').removeClass('input-search');
		}
		
	});
	
	$('body').on('submit','form.order',function(e){
		e.preventDefault();
		return false;
	});
	
    function update() {
		$('.formLogin form .step.s2 button.prev').remove();
        var myTime = $('b.timer').html();
        var ss = myTime.split(":");
        var dt = new Date();
        dt.setHours(0);
        dt.setMinutes(ss[0]);
        dt.setSeconds(ss[1]);
        var dt2 = new Date(dt.valueOf() - 1000);
        var temp = dt2.toTimeString().split(" ");
        var ts = temp[0].split(":");
        $('b.timer').html(ts[1]+":"+ts[2]);
		if((ts[1]+ts[2]) != '0000'){
			setTimeout(update, 1000);
		}else{
			$('b.timer').html('00:00');
			$('.formLogin form .step.s2').append('<button type="button" class="prev">Шаг назад</button>');
			$('body').on('click','.formLogin button.prev',function(){
				$('form#form-1').slick('slickPrev');
				$('.formLogin input[name="check_code"]').prop('disabled',true);
				$('i.number').text('+7(000)000-00-00');
				$('b.timer').html('10:00');
				$(this).remove();
			});
		}
    }
	
	var vefify = true;
	var old_code = '';
	var codeErrorIsSend = false;
	
	$('body').on('input paste keyup','.formLogin input[name="check_code"]',function(){
		var code = $(this).val().replace(/[^0-9]/g,'');
		if(code.length == 6 && old_code != code){
			old_code = code;
			$.ajax({
				type : 'POST',
				url : '/jkmagazine/addUser/',
				data:{check_code:code},
				dataType: "json",
				success: function(data){
					if(data.status == 'ok'){
						$('b.timer').html('00:00');
						$('.formLogin input[name="check_code"]').prop('disabled',true);
						$('.formLogin input[type="password"]').prop('disabled',false);
						$('.formLogin input[name="get_code"]').prop('disabled',true);
						if(vefify){
							$('form#form-1').append('<input type="hidden" name="verify_code" value="'+code+'">');
							vefify = false;
						}
						$('button.next.lock').removeClass('lock');
						$('form#form-1').slick('slickNext');
						$('body').on('click','.formLogin button.next',function(){
							$('form#form-1').slick('slickNext');
						});
					}else if(data.code == '6'){
						codeErrorIsSend = true;
						document.querySelector('#form-1 input[name="check_code"]').setCustomValidity('Не действительный код проверки!');
						document.querySelector('#form-1 input[name="check_code"]').reportValidity();
					}else if(data.code == '0'){
						alert('Сервис временно не доступен, попробуйте позже!');
					}
				}
			});
		}else{
			if(codeErrorIsSend){
				document.querySelector('#form-1 input[name="check_code"]').setCustomValidity('Не действительный код проверки!');
				document.querySelector('#form-1 input[name="check_code"]').reportValidity();
			}else{
				document.querySelector('#form-1 input[name="check_code"]').setCustomValidity('');
			}
		}
	});
	
	var issetErrorUserPhone = false;
	
	$('body').on('input paste keyup','#form-2 input[name="username"],#form-2 input[name="password"]',function(){
		document.querySelector('#form-2 input[name="username"]').setCustomValidity('');
		document.querySelector('#form-2 input[name="password"]').setCustomValidity('');
	});
	
	$('body').on('input paste keyup','#form-1 input[name="username"],#form-1 input[name="password"],#form-1 input[name="check_code"],#form-1 input[name="confirm_password"]',function(){
		if(issetErrorUserPhone){
			validate();
			issetErrorUserPhone = false;
		}
		document.querySelector('#form-1 input[name="password"]').setCustomValidity('');
		document.querySelector('#form-1 input[name="confirm_password"]').setCustomValidity('');
	});
	
	$('body').on('submit','form#form-1',function(e){
		
		var data = $(this).serialize();
		var dataForm = $(this).serializeArray();
		var ajax = true;
		var pass = '';
		var confirm_pass = '';

		dataForm.forEach((item,index) => {
			
			if(ajax){
				
				if(item.name == 'password'){
					pass = item.value;
				}
				
				if(item.name == 'confirm_password'){
					confirm_pass = item.value;
				}
				
				if(pass != '' && confirm_pass != ''){
					if(confirm_pass == pass){
						ajax = false;
					}
				}
				
			}
			
		});

		if(ajax){
			
			e.preventDefault();
			$.ajax({
				type : 'POST',
				url : '/jkmagazine/addUser/',
				data:data,
				dataType: "json",
				success: function(data){
					if(data.status == 'ok'){
						$('.formLogin input[name="check_code"]').prop('disabled',false);
						$('i.number').text(data.phone);
						$('form#form-1').slick('slickNext');
						setTimeout(update,1000);
					}else{
						if(data.code == '4'){
							issetErrorUserPhone = true;
							document.querySelector('#form-1 input[name="username"]').setCustomValidity('Пользователь с таким номером уже существует');
							document.querySelector('#form-1 input[name="username"]').reportValidity();
						}else if(data.code == '8'){
							document.querySelector('#form-1 input[name="confirm_password"]').setCustomValidity('Проверочный пароль не совпадает!');
							document.querySelector('#form-1 input[name="confirm_password"]').reportValidity();
						}else if(data.code == '7'){
							alert('Произошла ошибка авторизации, попробуйте позже!');
						}else if(data.code == '3'){
							alert('Произошла ошибка отравки кода попробуйте позже!');
						}else if(data.code == '0'){
							alert('Сервис временно не доступен, попробуйте позже!');
						}else{
							alert('Сервис временно не доступен, попробуйте позже!');
						}
					}
				}
			});
			
			return false;
			
		}
	
	});

	$('body').on('submit','form#form-2',function(e){
		
		e.preventDefault();
		var data = $(this).serialize();
		
		$.ajax({
			type : 'POST',
			url : '/jkmagazine/checkUser/',
			data:data,
			dataType: "json",
			success: function(data){
				if(data.status == 'ok'){
					location.href = '/profile';
				}else{
					if(data.code == '1'){
						document.querySelector('#form-2 input[name="username"]').setCustomValidity('Пользователь с таким номером не найден!');
						document.querySelector('#form-2 input[name="username"]').reportValidity();
					}else if(data.code == '2'){
						document.querySelector('#form-2 input[name="password"]').setCustomValidity('Пароль указан не верно!');
						document.querySelector('#form-2 input[name="password"]').reportValidity();
					}else if(data.code == '0'){
						alert('Сервис временно не доступен, попробуйте позже!');
					}else{
						alert('Сервис временно не доступен, попробуйте позже!');
					}
				}
			}
		});
		
		return false;

	});
	
	function variabilityChange(val){
		
		$('body').addClass('process');

 		$.ajax({
			type : 'GET',
			url : val,
			cache:false,
			success: function(data){
				$('.item').html($(data).find('.item').html());
				$('body').removeClass('process');
				$('h1').html($(data).find('h1').html());
				$('div#breadcrumbs').html($(data).find('div#breadcrumbs').html());
				$('div#specifications').html($(data).find('div#specifications').html());
				if($(data).find('nav.moduletable_list_jkreviews').length){
					$('nav.moduletable_list_jkreviews').html($(data).find('nav.moduletable_list_jkreviews').html());
				}else{
					if($('nav.moduletable_list_jkreviews').length){
						$('nav.moduletable_list_jkreviews').html('');
					}
				}
				$('nav.moduletable_list_cat.similar').html($(data).find('nav.moduletable_list_cat.similar').html());
				$('nav.moduletable_list_related').html($(data).find('nav.moduletable_list_related').html());
				if($(data).find('.blog.tabs .right div#specifications').length){
					$('.blog.tabs .right div#specifications').html($(data).find('.blog.tabs .right div#specifications').html());
				}
				if($(data).find('.blog.tabs .right div#description').length){
					$('.blog.tabs .right div#description').html($(data).find('.blog.tabs .right div#description').html());
				}
				if($(data).find('.blog.tabs .right div#reviews').length){
					$('.blog.tabs .right div#reviews').html($(data).find('.blog.tabs .right div#reviews').html());
				}
				
				starRatingInit();
				slickCheckDesctop();
				history.pushState(null,null,val);
			}
		});
		
	}
	
	
	
	$('body').on('submit','.form-search',function(e){
		
		$('body').addClass('process');
		e.preventDefault();
		var senddata = $(this).serialize();
		
 		$.ajax({
			type : 'GET',
			url : $(this).attr('action'),
			data:senddata,
			cache:false,
			success: function(data){
				$('div#ajaxUpdate').html($(data).find('div#ajaxUpdate').html());
				starRatingInit();
				$('button.see').remove();
				$('body').removeClass('process');
				history.pushState(null,null,window.location.pathname+'?'+senddata);
			}
		});
		
		return false;

	});
	
	function validate(){
		
		$('input[type="tel"]').mask('+7(999)999-99-99');

		var arrCodes  = [861,904,900,901,902,903,905,906,908,909,910,911,912,913,914,915,916,917,918,919,920,921,922,923,924,925,926,927,928,929,930,931,932,933,934,936,937,938,939,950,951,952,953,958,960,961,962,963,964,965,966,967,968,969,978,980,981,982,983,984,985,986,987,988,989,992,994,995,996,997,999];
		
		var valid = false;
		
		document.querySelectorAll('input[type="tel"]').forEach(function (element) {
			addEvents(element);
		});
		
		function addEvents(element){
			element.addEventListener('change', function(event){
				check(event);
			});
			element.addEventListener('keyup', function(event){
				check(event);
			});
			element.addEventListener('paste', function(event){
				check(event);
			});
		}
			
		function check(e){
			
			if(e.target.value != ''){
				
				var keystr = e.target.value.split('(')[1].split(')')[0].replace('__','').replace('_','');
				var num = parseInt(e.target.value.replace(/\D+/g,""));
				
				if(String(num).length != 11){
					
					e.target.classList.add('error_field');
					e.target.setCustomValidity("Неверный номер телефона");
					$('form button').removeClass('valid-phone');
					
				}else{
					
					if(keystr.length == 3){
						for(i = 0; i < arrCodes.length; i++){
							if(arrCodes[i] == keystr){
								valid = true;
							}
						}
						if(valid){
							e.target.setCustomValidity("");
							e.target.classList.remove('faq-display');
							valid = false;
							$('form button').addClass('valid-phone');
						}else{
							e.target.classList.add('error_field');
							e.target.setCustomValidity("Неверный код оператора");
							$('form button').removeClass('valid-phone');
						}
					}else{
						e.target.classList.add('error_field');
						e.target.setCustomValidity("Неверный код оператора");
						$('form button').removeClass('valid-phone');
					}
					
				}
				
			}
			
		}
		
	}
	
	validate();

});