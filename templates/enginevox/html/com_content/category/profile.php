<?php
defined('_JEXEC') or die;
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
$user = &JFactory::getUser();

foreach(FieldsHelper::getFields('com_users.user',$user) as &$field) {
	$user->jcfields[$field->id] = $field;
}

if(empty($_SESSION['cart'])){
	if(!empty($user->jcfields[109]->rawvalue)){
		if($user->jcfields[109]->rawvalue !== 'null'){
			$_SESSION['cart'] = array();
			foreach(json_decode($user->jcfields[109]->rawvalue) as &$data){
				$_SESSION['cart'] = json_decode($user->jcfields[109]->rawvalue,true);
			}
		}
	}
}

if(empty($_SESSION['favorites'])){
	if(!empty($user->jcfields[110]->rawvalue)){
		if($user->jcfields[110]->rawvalue !== 'null'){
			$_SESSION['favorites'] = array();
			foreach(json_decode($user->jcfields[110]->rawvalue) as &$id){
				array_push($_SESSION['favorites'],$id);
			}
			$_SESSION['favorites'] = array_values(array_unique($_SESSION['favorites']));
		}
	}
}

if(empty($_SESSION['compare'])){
	if(!empty($user->jcfields[111]->rawvalue)){
		if($user->jcfields[111]->rawvalue !== 'null'){
			$_SESSION['compare'] = array();
			foreach(json_decode($user->jcfields[111]->rawvalue) as &$id){
				array_push($_SESSION['compare'],$id);
			}
			$_SESSION['compare'] = array_values(array_unique($_SESSION['compare']));
		}
	}
}

if(empty($_SESSION['latest'])){
	if(!empty($user->jcfields[112]->rawvalue)){
		if($user->jcfields[112]->rawvalue !== 'null'){
			$_SESSION['latest'] = array();
			foreach(json_decode($user->jcfields[112]->rawvalue) as &$id){
				array_push($_SESSION['latest'],$id);
			}
			$_SESSION['latest'] = array_values(array_unique($_SESSION['latest']));
		}
	}
}

if(!empty($user->jcfields[102]->rawvalue)){
	$avatar = json_decode($user->jcfields[102]->rawvalue)->imagefile;
}else{
	$avatar = '/images/styles/profile.svg';
}

function phone_format($phone){
	
	$phone = substr($phone,0,20);
	$phone = trim($phone);
	
	$res = preg_replace(
		array(
			'/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{3})[-|\s]?\)[-|\s]?(\d{3})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
			'/[\+]?([7|8])[-|\s]?(\d{3})[-|\s]?(\d{3})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
			'/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{4})[-|\s]?\)[-|\s]?(\d{2})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
			'/[\+]?([7|8])[-|\s]?(\d{4})[-|\s]?(\d{2})[-|\s]?(\d{2})[-|\s]?(\d{2})/',	
			'/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{4})[-|\s]?\)[-|\s]?(\d{3})[-|\s]?(\d{3})/',
			'/[\+]?([7|8])[-|\s]?(\d{4})[-|\s]?(\d{3})[-|\s]?(\d{3})/',					
		), 
		array(
			'+7($2)$3-$4-$5', 
			'+7($2)$3-$4-$5', 
			'+7($2)$3-$4-$5', 
			'+7($2)$3-$4-$5', 	
			'+7($2)$3-$4',
			'+7($2)$3-$4', 
		),
		$phone
	);
	
	return $res;
	
}

?>
<div class="blog user-profile<?php echo $this->pageclass_sfx; ?>" itemscope itemtype="https://schema.org/Blog">
	<div class="profile">
		<div class="userData">
			<div class="lb">
				<label>Ник (для отзывов)</label>
				<input name="nick" value="<?php echo $user->jcfields[108]->rawvalue;?>">
			</div>
			<h5>Данные для доставки</h5>
			<div class="lb">
				<label>Фамилия</label>
				<input name="surname" value="<?php echo $user->jcfields[106]->rawvalue;?>">
				<label>Имя</label>
				<input name="name" value="<?php echo $user->name;?>">
				<label>Отчество</label>
				<input name="middle_name" value="<?php echo $user->jcfields[107]->rawvalue;?>">
			</div>
			<div class="lb">
				<label>Телефон (логин)</label>
				<input name="username" value="<?php echo phone_format($user->username);?>" disabled>
				<label>Почтовый адрес</label>
				<input name="address" value="<?php echo $user->jcfields[103]->rawvalue?>">
				<label>Почтовый индекс</label>
				<input name="zip" value="<?php echo $user->jcfields[104]->rawvalue;?>">
			</div>
		</div>
		<div class="avatar" style="background-image: url(<?php echo $avatar;?>)">&nbsp;</div>
		<p class="reg"><b>Дата&nbsp;регистрации:</b><br><i><?php echo date('d.m.Y H:i:s', strtotime($user->registerDate));?></i></p>
		<p class="last"><b>Последний&nbsp;визит:</b><br><i><?php echo date('d.m.Y H:i:s', strtotime($user->lastvisitDate));?></i></p>
		<a class="logout" href="index.php?option=com_users&task=user.logout&<?php echo JSession::getFormToken();?>=1">Выйти</a>
	</div>
</div>