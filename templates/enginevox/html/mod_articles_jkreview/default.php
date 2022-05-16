<?php
defined('_JEXEC') or die;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\Component\Fields\Administrator\Model\FieldModel;
$field = new FieldModel;
$default = '/modules/mod_articles_jkreview/assets/img/noavatar.svg';
if(!$list) return;
$doc = &JFactory::getDocument();
//$doc->addScript(JURI::base().'/modules/mod_articles_jkreview/assets/js/jquery.star-rating-svg.js');
//$doc->addScript(JURI::base().'/modules/mod_articles_jkreview/assets/js/jkrevew.js');
//$doc->addStyleSheet(JURI::base().'/modules/mod_articles_jkreview/assets/css/star-rating-svg.css');
//$doc->addStyleSheet(JURI::base().'/modules/mod_articles_jkreview/assets/css/jkrevew.css');
?>
<nav class="moduletable_list_jkreviews">
	<div class="h2">Отзывы</div>
	<?php foreach($list as &$review):?>
		<div class="review" itemscope itemtype="http://schema.org/Review">
			<?php if($review->user_id && $user = &JFactory::getUser($review->user_id)):?>
				<?php $ava = $field->getFieldValues([102],$user->id);?>
				<?php (!empty($ava[102])) ? $avatar = json_decode($ava[102])->imagefile : $avatar = $default;?>
				<div class="avatar" style="background-image:url(<?php echo $avatar;?>);">&nbsp;</div>
			<?php else:?>
				<div class="avatar" style="background-image:url(<?php echo $default;?>);">&nbsp;</div>
			<?php endif;?>
			<div class="h5" itemprop="author"><?php echo $review->user_name;?></div>
			<div class="data" itemprop="datePublished" datetime=""><?php echo JHtml::_('date', $review->public_date, JText::_('DATE_FORMAT_LC1'));?></div>
			<div class="rating">
				<input type="hidden" name="val" value="<?php echo $review->rating;?>">
			</div>
			<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
				<meta itemprop="worstRating" content="1">
				<meta itemprop="ratingValue" content="<?php echo $review->rating;?>">
				<meta itemprop="bestRating" content="5">
			</div>
			<p itemprop="reviewBody"><?php echo $review->text; ?></p>
			<?php if(!empty($review->attachments)):?>
			<ul class="attachments">
			<?php foreach($review->attachments as &$attachment):?>
				<li>
					<a class="attach" data-fancybox="gallery-<?php echo $review->id;?>" class="butt" target="_blank" href="<?php echo $attachment->data;?>" style="background-image:url(<?php echo $attachment->mindata;?>);">
						<img style="display:none;" alt="" src="<?php echo $attachment->mindata;?>">
					</a>
				</li>
			<?php endforeach;?>
			</ul>
			<?php endif;?>
		</div>
	<?php endforeach; ?>
</nav>