$.event.special.touchstart = {
	setup: function( _, ns, handle ){
		this.addEventListener("touchstart", handle, { passive: true });
	}
};

jQuery(document).ready(function($){
	
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

	$(document).mouseup(function (e){
		var div = $(".formHtml,.formLogin");
		if (!div.is(e.target) && div.has(e.target).length === 0){
			$('body').removeClass('activeForm');
			$('body').removeClass('activeLoginForm');
		}
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
	
	$('nav.moduletable_list_cat ul').slick({
		slidesToShow: 3,
		slidesToScroll: 3,
		autoplay: false,
		prevArrow: '<button class="arrow prev"></button>',
        nextArrow: '<button class="arrow next"></button>',
		responsive: [
		{
		  breakpoint: 655,
		  settings: {
			centerMode: false,
			slidesToShow: 1,
			slidesToScroll: 1
		  }
		}
		]
	});
	
	$('.mod-banners.bannergroup').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		autoplay:true,
		prevArrow: '<button class="arrow prev"></button>',
        nextArrow: '<button class="arrow next"></button>'
	});
	
	$('.jlmf-label').click(function(){
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
	
	$('body').on('click','div#form form button.remove,.complite.visible button.remove',function(){
		$('body').removeClass('openForm');
		$('div#form').remove();
		$('.complite').removeClass('visible');
	});
	
	$('div#cookies button.close').click(function(){
		$('div#cookies').remove();
		document.cookie = 'cookie=yes';
	});
 	
	$('body').on('click','.calculate button.minus',function(){
		var value = parseInt($(this).parent().find('input').val());
		var id = $(this).parent().data('id');
		if(value > 1){
			value = value - 1;
		}else{
			value = 1;
		}
		sendCart(id,value);
	});
	
	$('body').on('change','.calculate input',function(){
		var value = parseInt($(this).val());
		var id = $(this).parent().data('id');
		if(value < 1){
			value = 1;
		}else{
			value = value;
		}
		sendCart(id,value);
	});
	
	$('body').on('click','.calculate button.plus',function(){
		var value = parseInt($(this).parent().find('input').val());
		var id = $(this).parent().data('id');
		value = value + 1;
		sendCart(id,value);
	});
	
	$('body').on('click','.cart .more button.cartButton.remove',function(){
		$(this).parent().parent().addClass('delete');
		var id = $(this).data('id');
		sendCart(id,'remove');
	});
	
	function sendCart(id,value){
		
		if(value !=='remove'){
			$('body').addClass('process');
		}
		
        var update = {
			id:id,
			value:value
		}
		
		$.ajax({
			url:'/cart/',
            type: 'post',
			data:{'update':'['+JSON.stringify(update)+']'},
			success: function(data){
				$('body').removeClass('process');
				$('#cover').remove();
				if($(data).find('.blog.cart .left li').length){
					$('.blog.cart .left').html($(data).find('.blog.cart .left'));
					$('p.totalOrder').html($(data).find('p.totalOrder'));
				}else{
					$('.blog.cart').html($(data).find('.blog.cart'));
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
			url:'/cart/?check=1',
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
			
			if($('div#content .left').height() <= $('div#content .right').height()){
				
				var leftPos = $('div#ajaxUpdate .blog .left').height() - $(window).scrollTop() - $('div#ajaxUpdate .blog .left').offset().top;
				var rightPos = $('div#ajaxUpdate .blog .right').height() - $(window).scrollTop() - $('div#ajaxUpdate .blog .right').offset().top;
				var delta = $(window).height() - $('div#footer').height() + 50;

				if(leftPos <= delta){
					$('body').addClass('stop-left');
					if(rightPos <= delta){
						$('body').addClass('left-end');
					}else{
						$('body').removeClass('left-end');
					}
				}else{
					$('body').removeClass('stop-left');
				}
				
			}else{
				
				if($('.form-search').length){
					
					var leftPos = $('div#ajaxUpdate .blog .left').height() - $(window).scrollTop() - $('div#ajaxUpdate .blog .left').offset().top;
					var rightPos = $('div#ajaxUpdate .blog .right').height() - $(window).scrollTop() - $('div#ajaxUpdate .blog .right').offset().top;
					var delta = $(window).height() - $('div#footer').height() + 50;

					if(rightPos <= delta){
						$('body').addClass('stop-right');
						if(leftPos <= delta){
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
			data: jsonItems,
			dataType: 'json',
			success: function(data){
				$('body').removeClass('process');
				$('#cover').remove();
				$('.complite').addClass('visible');
				checkCart();
			},
			error: function(){
				checkCart();
				$('body').removeClass('process');
				$('#cover').remove();
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
			
			$(this).addClass('active');
			$(this).addClass('remove');

			let allItems = {
				id:		$(this).data('id'),
				count:	1
			};

			var jsonItems = JSON.stringify(allItems);
		
			$.ajax({
				url: '/jkmagazine/addcompare/',
				type: 'POST',
				data: jsonItems,
				dataType: 'json',
				success:function(){
					$('body').removeClass('process');
				},
				error: function(){
					alert('Произошла ошибка');
				}
			});
			
		}else{
			
			$(this).removeClass('active');
			$(this).removeClass('remove');
			
			var update = {
				id:$(this).data('id'),
				value:'remove'
			}
			
			$.ajax({
				url:'/compare/',
				type: 'post',
				data:{'update':'['+JSON.stringify(update)+']'},
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
						$('div#ajaxUpdate .blog.compare').append('<div class="noResult"><div class="cnt"><img src="/templates/enginevox/img/logo-min.svg" alt="Electrohype"><p>Вы не добавили товары в сравнение</p><a href="/" class="goCatalog">Перейти в каталог</a></div></div>');
					}
					
				}
				$('.'+itemClass).remove();
			}
			
		}
		

	});
	
	$('body').on('click','button.addFavorites',function(){
		
		$('body').addClass('process');
		
		if(!$(this).hasClass('active')){
			
			$(this).addClass('active');
			$(this).addClass('remove');

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
				data: jsonItems,
				dataType: 'json',
				success:function(){
					$('body').removeClass('process');
				},
				error: function(){
					alert('Произошла ошибка');
				}
			});
			
		}else{
			
			$(this).removeClass('active');
			$(this).removeClass('remove');
			
			var update = {
				id:$(this).data('id'),
				value:'remove'
			}
			
			$.ajax({
				url:'/favorites/',
				type: 'post',
				data:{'update':'['+JSON.stringify(update)+']'},
				success:function(){
					$('body').removeClass('process');
				},
				error: function (error) {
					alert('Произошла ошибка');
				}
			});
			
			if($(this).hasClass('fav')){
				$(this).parent().parent().remove();
				if($('.blog.favorites .right li').length < 1){
					$('.right ul').remove();
					$('div#ajaxUpdate .blog.favorites .right').append('<div class="noResult"><div class="cnt"><img src="/templates/enginevox/img/logo-min.svg" alt="Electrohype"><p>Вы не добавили товары в избранное</p><a href="/" class="goCatalog">Перейти в каталог</a></div></div>');
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
	
/* 	$('body').on('input paste keyup','.formLogin input[name="password"], .formLogin input[name="confirm_password"]',function(){
		if($('.formLogin input[name="password"]').val() != ''){
			if($('.formLogin input[name="password"]').val() == $('.formLogin input[name="confirm_password"]').val()){
				$('.formLogin form button.fine').addClass('pass-valid');
			}else{
				$('.formLogin form button.fine').removeClass('pass-valid');
			}
		}else{
			$('.formLogin form button.fine').removeClass('pass-valid');
		}
	}); */
	
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