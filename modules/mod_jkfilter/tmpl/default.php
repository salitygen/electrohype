<?php
defined('_JEXEC') or die;
if (!$list) return;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
$basePath = 'modules/mod_jkfilter/layouts';
$validFields = array('radio','checkbox','range');
?>
<form class="moduletable_filter">
<?php for($i=0;$i<count($list);$i++):?>
	<?php if(in_array($list[$i]['template'],$validFields)):?>
	<?php echo LayoutHelper::render($list[$i]['template'],['field_id'=>$i,'field_data'=>$list[$i]],$basePath);?>
	<?php endif;?>
<?php endfor;?>
</form>
