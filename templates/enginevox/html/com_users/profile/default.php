<?php
defined('_JEXEC') or die;
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
require_once (JPATH_ADMINISTRATOR.'/components/com_content/models/article.php');
$db = JFactory::getDbo();
$user = JFactory::getUser();
$query = "SELECT * FROM #__users,#__fields_values,#__fields WHERE #__users.id = #__fields_values.item_id AND #__fields_values.value = ".$this->data->id." AND #__fields_values.field_id AND #__fields.context = 'com_users.user' AND #__fields_values.field_id = #__fields.id";
$db->setQuery($query);
$result = $db->loadObjectList();
$fmt = new NumberFormatter('ru_RU',NumberFormatter::CURRENCY);

$query = "SELECT * FROM #__fields_values WHERE item_id = '{$user->id}' AND field_id = 12";
$db->setQuery($query);
$robots = $db->loadObjectList();

if(empty($robots)){
	$robots = false;
}else{
	$robots = $robots[0];
}

function strip_data2($text){
	
	$quotes = array("\x27", "\x22", "\x60", "\t", "\n", "\r", "*", "%", "<", ">", "?", "!");
	$goodquotes = array('-', '+', '#','"');
	$repquotes = array("\-", "\+", "\#","&quot;");
	$text = htmlspecialchars($text);
	$text = stripslashes($text);
	$text = trim(strip_tags($text));
	$text = str_replace($quotes,'',$text);
	$text = str_replace($repquotes,$goodquotes,$text);
	return $text;
	
}

$refId = strip_data2((int)$_COOKIE['refId']);
setcookie('refId',0);
if($refId){
	
	$query = "SELECT * FROM #__fields_values WHERE field_id = 10 AND item_id = '{$user->id}'";
	$db->setQuery($query);
	$result2 = $db->loadObjectList();
	
	$query = "SELECT * FROM #__fields_values WHERE field_id = 10 AND value = '{$user->id}' AND item_id = '{$refId}'";
	$db->setQuery($query);
	$result3 = $db->loadObjectList();
	
	if(empty($result2) && empty($result3)){
		
		if($user->id != $refId){ 
		
			$query = "INSERT INTO #__fields_values (field_id,item_id,value) VALUES (10,'{$user->id}','{$refId}')";
			$db->setQuery($query);
			$db->loadObjectList();
			
			$query = "SELECT value FROM #__fields_values WHERE field_id = 7 AND item_id = '{$refId}'";
			$db->setQuery($query);
			$result = $db->loadObjectList();
			
			if(!empty($result)){
				
				$countRef = ($result[0]->value + 1);
				$query = "UPDATE #__fields_values SET value='{$countRef}' WHERE item_id = '{$refId}' AND field_id = 7";
				$db->setQuery($query);
				$result = $db->execute();
				
			}else{
				
				$query = "INSERT INTO #__fields_values (field_id,item_id,value) VALUES (7,'{$refId}','1')";
				$db->setQuery($query);
				$db->loadObjectList();
				
			}
			
		}
		
	}
	
}

?>
<div class="headBlock">
<h1>Личный кабинет</h1>
<form action="/" method="post" id="login-form" class="form-vertical">
	<div class="logout-button">
		<input type="submit" name="Submit" class="btn btn-primary" value="Выйти">
		<input type="hidden" name="option" value="com_users">
		<input type="hidden" name="task" value="user.logout">
		<input type="hidden" name="return" value="aW5kZXgucGhwP0l0ZW1pZD0xMDE=">
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
<form class="pf" action="/payment/" method="POST">
<input type="text" name="summ" value="" placeholder="Введите сумму">
<input type="hidden" name="payment" value="1">
<button type="submit" class="topay">Пополнить</button>
</form>
</div>
<div class="profile">
	<table>
		<tr>
			<td rowspan="8">
				<div class="avatar" style="background-image:url(<?php print $this->data->jcfields[8]->value;?>)"></div>
			</td>
			<td>
				<span>Логин:</span>
			</td>
			<td>
				<?php print $this->data->username;?>
			</td>
		</tr>
		<tr>
			<td>
				<span>ФИО (видно только Вам):</span>
			</td>
			<td>
				<form class="member-profile" action="<?php echo JRoute::_('index.php?option=com_users&task=profile.save'); ?>" method="post">
					<input disabled="disabled" type="text" <?php if($this->data->name == $this->data->username) echo 'placeholder="Не заполнено"'; else echo 'value="'.$this->data->name.'"';?> name="jform[name]">
					<input value="<?php print $this->data->username;?>" type="hidden" name="jform[email1]">
					<input value="<?php print $this->data->username;?>" type="hidden" name="jform[email2]">
					<input value="<?php print $this->data->username;?>" type="hidden" name="jform[username]">
					<input type="hidden" name="option" value="com_users" />
					<input type="hidden" name="task" value="profile.save" />
					<?php echo JHtml::_('form.token'); ?>
					<button type="button" class="edit"></button>
				</form>
			</td>
		</tr>
		<tr>
			<td>
				<span>Пароль:</span>
			</td>
			<td>
				<form class="member-profile" action="<?php echo JRoute::_('index.php?option=com_users&task=profile.save'); ?>" method="post">
					<input disabled="disabled" value="" placeholder="••••••••••" type="password" name="jform[password]">
					<input type="hidden" <?php if($this->data->name == $this->data->username) echo 'value="Не заполнено"'; else echo 'value="'.$this->data->name.'"';?> name="jform[name]">
					<input value="<?php print $this->data->username;?>" type="hidden" name="jform[email1]">
					<input value="<?php print $this->data->username;?>" type="hidden" name="jform[email2]">
					<input value="<?php print $this->data->username;?>" type="hidden" name="jform[username]">
					<input type="hidden" name="option" value="com_users" />
					<input type="hidden" name="task" value="profile.save" />
					<?php echo JHtml::_('form.token'); ?>
					<button type="button" class="edit"></button>
				</form>
			</td>
		</tr>
		<?php foreach($this->data->jcfields as $fields):?>
			<?php if($fields->name != 'avatar' && $fields->name != 'spisok-robotov' && $fields->name != 'robot-schet'):?>
				<?php if($fields->value == 'не заполнено'):?>
				<tr>
					<td>
						<span><?php print $fields->title;?>:</span>
					</td>
					<td>
						<input disabled="disabled" type="text" placeholder="<?php print $fields->value;?>" name="jform[com_fields][<?php print $fields->name;?>]">
						<button type="button" class="edit"></button>
					</td>
				</tr>
				<?php else:?>
					<?php if($fields->name == 'balance'):?>
					<tr>
						<td>
							<span><?php print $fields->title;?>:</span>
							<span class="balance"><?php print $fmt->formatCurrency($fields->value,"RUR");?></span>
						</td>
						<td>
							<form id="withdrawal" action="/withdrawal/" method="post">
								<label>Выберите систему для вывода:</label>
								<select name="payment_system">
									<option value="На карту" selected>На карту</option>
									<option value="Яндекс кошелек">Яндекс кошелек</option>
									<option value="Qiwi кошелек">Qiwi кошелек</option>
									<option value="Webmoney кошелек">Webmoney кошелек</option>
								</select>
								<label class="card select active">Введите номер карты:</label>
								<label class="yandex select">Введите номер кошелька:</label>
								<label class="qiwi select">Введите номер телефона:</label>
								<label class="webmoney select">Введите номер кошелька:</label>
								<input type="text" name="account_number" value="">
								<label>Введите сумму для вывода:</label>
								<input type="text" name="summ" value="<?php print $fields->value;?>">
								<b>Руб.</b>
								<button class="withdraw" type="submit">Вывести</button> 
								<input type="hidden" name="withdrawal" value="1">
							</form>
						</td>
					</tr>
					<?php else:?>
					<tr>
						<td>
							<span><?php print $fields->title;?>:</span>
						</td>
						<td>
							<?php if($fields->name == 'refer' && $fields->value == 'admin'):?>
							<span>—</span>
							<?php else:?>
							<span><?php print $fields->value;?></span>
							<?php endif;?>
						</td>
					</tr>
					<?php endif;?>
				<?php endif;?>
			<?php endif;?>
		<?php endforeach;?>
		<tr>
			<td>
				<span>Ваша реферальная ссылка:</span>
			</td>
			<td>
				<span><a href="<?php print JURI::base().'?ref='.$this->data->id;?>"><?php print JURI::base().'?ref='.$this->data->id;?></a></span>
			</td>
		</tr>
	</table>
<div class="topayFrame">
<?php print $this->loadTemplate('payment');?>
</div>
<?php if(!empty($robots)):?>
	<h3>Ваши торговые эксперты</h3>
	<table class="buyExperts">
	<?php foreach(json_decode($robots->value,true) as $k => $robot):?>
		<?php $article = new ContentModelArticle; $material = $article->getItem($robot['Робот']); $material->jcfields = FieldsHelper::getFields('com_content.article',$material,true);?>
		<tr>
			<td><a href="<?php print JRoute::_('index.php?option=com_content&view=article&id='.$material->id);?>"><?php print '<img src="'.$material->images['image_intro'].'">';?></a></td>
			<td><a href="<?php print JRoute::_('index.php?option=com_content&view=article&id='.$material->id);?>"><?php print $material->title;?></a></td>
			<td><?php print $material->jcfields[3]->value;?> руб.</td>
			<td class="desctop">
			<?php if(!empty($robot['Номер счета'])):?>
				<p class="schet">Номер счета: <b><?php print $robot['Номер счета'];?></b></p>
			<?php else:?>
				<form action="/ajax/" method="post" class="sc">
					<input type="hidden" name="robot-schet[key]" value="<?php print $k;?>">
					<input type="hidden" name="robot-schet[Робот]" value="<?php print $robot['Робот'];?>">
					<input type="text" name="robot-schet[Номер счета]" value="" placeholder="Введите номер счета">
					<button type="submit">Сохранить</button>
				</form>
			<?php endif;?>
			</td>
		</tr>
		<tr class="mobile"> 
			<td colspan="3">
				<?php if(!empty($robot['Номер счета'])):?>
					<p class="schet">Номер счета: <b><?php print $robot['Номер счета'];?></b></p>
				<?php else:?>
					<form action="/ajax/" method="post" class="sc">
						<input type="hidden" name="robot-schet[key]" value="<?php print $k;?>">
						<input type="hidden" name="robot-schet[Робот]" value="<?php print $robot['Робот'];?>">
						<input type="text" name="robot-schet[Номер счета]" value="" placeholder="Введите номер счета">
						<button type="submit">Сохранить</button>
					</form>
				<?php endif;?>
			</td>
		</tr>
	<?php endforeach;?>
	</table>
<?php endif;?> 
<?php if(!empty($result)):?>
<h3>Ваши рефералы</h3>
<table>
	<tr><th>Аватар</th><th>Логин пользователя</th><th class="desctop">Куплено</th></tr>
	<?php foreach($result as $ref):?>
	<?php $ref->jcfields = FieldsHelper::getFields('com_users.user',JFactory::getUser($ref->username),true);?>
	<tr>
		<td>
			<div class="avatar min" style="background-image:url(<?php print $ref->jcfields[2]->value;?>)"></div>
		</td>
		<td><?php print $ref->username;?></td>
		<td class="desctop">
			<?php $robots = json_decode($ref->jcfields[4]->rawvalue,true);?>
			<?php if(!empty($robots)):?>
				<table class="refSale">
					<?php foreach($robots as $robot):?>
					<tr>
						<?php $article = new ContentModelArticle; $material = $article->getItem($robot['Робот']); $material->jcfields = FieldsHelper::getFields('com_content.article',$material,true);?>
						<td><a href="<?php print JRoute::_('index.php?option=com_content&view=article&id='.$material->id);?>"><?php print '<img src="'.$material->images['image_intro'].'">';?></a></td>
						<td><a href="<?php print JRoute::_('index.php?option=com_content&view=article&id='.$material->id);?>"><?php print $material->title;?></a></td>
						<td><?php print $material->jcfields[3]->value;?> руб.</td>
					</tr>
					<?php endforeach;?>
				</table>
			<?php endif;?>
		</td>
	</tr>
	<?php if(!empty($robots)):?>
	<tr class="mobile"> 
		<th colspan="2" style="text-align:center;">Куплено</th>
	</tr>
	<tr class="mobile"> 
		<td colspan="2">
			<table class="refSale">
				<?php foreach($robots as $robot):?>
				<tr>
					<?php $article = new ContentModelArticle; $material = $article->getItem($robot['Робот']); $material->jcfields = FieldsHelper::getFields('com_content.article',$material,true);?>
					<td><a href="<?php print JRoute::_('index.php?option=com_content&view=article&id='.$material->id);?>"><?php print '<img src="'.$material->images['image_intro'].'">';?></a></td>
					<td><a href="<?php print JRoute::_('index.php?option=com_content&view=article&id='.$material->id);?>"><?php print $material->title;?></a></td>
					<td><?php print $material->jcfields[3]->value;?> руб.</td>
				</tr>
				<?php endforeach;?>
			</table>
		</td>
	</tr>
	<?php endif;?>
	<?php endforeach;?>
</table> 
<?php endif;?>
</div>
