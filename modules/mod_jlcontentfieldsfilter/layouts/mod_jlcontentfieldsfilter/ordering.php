<?php
/**
 * JL Content Fields Filter
 *
 * @version 	2.0.0
 * @author		Joomline
 * @copyright	(C) 2017-2019 Arkadiy Sedelnikov, Joomline. All rights reserved.
 * @license 	GNU General Public License version 2 or later; see	LICENSE.txt
 */

defined('_JEXEC') or die;

if(!key_exists('options', $displayData)){
	return;
}

$moduleId = $displayData['moduleId'];
$options = $displayData['options'];
$selected = $displayData['selected'];
$field = $displayData['field'];
$i = 1;

if(!is_array($options) || !count($options)){
	return;
}

if(empty($selected)){
	$selected = 'created';
}
?>

<div class="jlmf-section ordering open">
	<div class="jlmf-label"><?php echo JText::_('MOD_JLCONTENTFIELDSFILTER_ORDERING'); ?></div>
	<div class="jlmf-list-1">
	<?php foreach($options as $option):?>
		<?php $checked = ($option->value == $selected) ? ' checked="checked"' : '';?>
		<div class="radio">
			<input type="radio" value="<?php echo $option->value; ?>" id="<?php echo 'options-'. $i.'-'.$moduleId; ?>" name="filter[ordering]"<?php echo $checked; ?> class="jlmf-checkbox"/>
			<label class="jlmf-sublabel" for="<?php echo 'options-'. $i.'-'.$moduleId; ?>"><span></span><i><?php echo JText::_($option->text); ?></i></label>
		</div>
		<?php $i++;?>
	<?php endforeach;?>
	</div>
</div>
	
	



