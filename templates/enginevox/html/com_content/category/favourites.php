<?php
defined('_JEXEC') or die;
JLoader::register('JKHelper', JPATH_BASE . '/components/com_jkreview/helpers/jkhelper.php');
use \Joomla\Component\Content\Site\Model\ArticleModel;
use \Joomla\Component\Fields\Administrator\Helper\FieldsHelper;

$session = JFactory::getSession();
//$session->clear();
$article = new ArticleModel;
$count = 0;
$total = 0;
$option = 0;

$arrData = $session->get('compare');
$com = array();
foreach($arrData as $data){
	$com[$data['id']] = $data['id'];
}

if(isset($_COOKIE['tax']) && $_COOKIE['tax'] != ''){
	$option = (int)$_COOKIE['tax'];
}

if(isset($_GET['check'])){
	$sess = $session->get('favorites');
	if(!empty($sess)){
		foreach($sess as $s){
			$count = $count + (int)strip_data($s['count']);
		}
		echo $count;
	}else{
		echo 0;
	}
	die();
}

if(isset($_POST['update']) && $_POST['update'] !=''){
	
	$json = strip_data($_POST['update']);
	$arr = json_decode($json,true);
	
	if(is_array($arr[0]) && count($arr[0])){
		if(!empty($session->get('favorites'))){
			if(strip_data($arr[0]['value']) !== 'remove'){
				$sess = $session->get('favorites');
				$sess[strip_data($arr[0]['id'])]['count'] = (int)strip_data($arr[0]['value']);
				$session->set('favorites',$sess);
			}else{
				$sess = $session->get('favorites');
				unset($sess[strip_data($arr[0]['id'])]);
				if(!empty($sess)){
					$session->set('favorites',$sess);
				}else{
					$session->clear('favorites');
				}
			}	
		}
	}
}

$arr = array();
$arrData = $session->get('favorites');

if(isset($arrData) && is_array($arrData) && count($arrData)){
	
	foreach($arrData as $k => $data){
		$item = $article->getItem((int)strip_data($data['id']));
		foreach(FieldsHelper::getFields('com_content.article',$item,true) as $field) {
			$item->jcfields[$field->id] = $field;
		}
		$item->count = $data['count'];
		$arr[$k] = $item;
	}
	
}else{
	$arr = false; 
}

function strip_data($text){
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

?>

<div id="ajaxUpdate" class="wt">
	<div class="blog favorites<?php echo $this->pageclass_sfx; ?>" itemscope itemtype="https://schema.org/Blog">
		<div class="right">
			<h1 class="hr"><?php echo $this->category->title;?></h1>
			<?php if(!empty($arr)):?>
			<ul class="audio">
			<?php foreach ($arr as $i => $item):?>
				<li class="" itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
					<a href="<?php print JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid, $item->language)); ?>" class="img" style="background-image:url(<?php print json_decode($item->images)->image_intro;?>)"></a>
					<?php if($data = JKHelper::getTotalByItemId($item->id)):?>
					<div class="rating">
						<?php if($data->count !== 0):?>
						<input type="hidden" name="val" value="<?php print (int)$data->sum;?>" tabindex="0">
						<i class="counter"><?php print $data->count;?></i>
						<?php else: ?>
						<i class="counter emty">Нет отзывов</i>
						<input type="hidden" name="val" value="0" tabindex="0">
						<?php endif; ?>
					</div>
					<?php endif; ?>
					<div class="more">
						<a class="link" itemprop="name" href="<?php print JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid, $item->language)); ?>"><?php print $item->title; ?></a>
						<p class="price"><?php print number_format($item->jcfields[15]->value,0,'',' ');?> руб.</p>
						<?php if(!empty($item->jcfields[35]->value)):?>
						<p class="old price"><?php print number_format($item->jcfields[35]->value,0,'',' ');?> руб.</p>
						<?php endif; ?>
						<button class="addToCart"  data-id="<?php print $item->id;?>" data-key="<?php print md5(date("Y-m-d H:i:s").''.rand(0,10000));?>"></button>
						<button class="addCompare <?php echo (isset($com[$item->id])) ? 'remove active' : '';?>"  data-id="<?php echo $item->id;?>"  data-id="<?php print $item->id;?>"></button>
						<button class="addFavorites fav remove active" type="button" data-id="<?php echo $item->id;?>"></button>
					</div>
				</li>
			<?php endforeach; ?>
			</ul>
			<?php else:?>
			<div class="noResult"><div class="cnt"><img src="/templates/enginevox/img/logo-min.svg" alt="Electrohype"><p><?php print 'Вы не добавили товары в избранное';?></p><a href="/" class="goCatalog">Перейти в каталог</a></div></div>
			<?php endif; ?>
		</div>
		<div class="left">
		<?php if(count(JModuleHelper::getModules('filtres'))):?>
			<?php foreach(JModuleHelper::getModules('filtres') as $module) : ?>
				<?php print JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if(count(JModuleHelper::getModules('cat_menu'))):?>
			<?php foreach(JModuleHelper::getModules('cat_menu') as $module) : ?>
				<?php print JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
			<?php endforeach; ?>
		<?php endif; ?>
		</div>
	</div>
</div>