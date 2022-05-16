<?php
/**
 * JL Content Fields Filter
 *
 * @version 	2.0.0
 * @author		Joomline
 * @copyright	(C) 2017-2020 Arkadiy Sedelnikov, Joomline. All rights reserved.
 * @license 	GNU General Public License version 2 or later; see	LICENSE.txt
 */

defined('_JEXEC') or die;

if (!key_exists('field', $displayData))
{
    return;
}

$moduleId = $displayData['moduleId'];
$field = $displayData['field'];
if(!empty($field->hidden)){
	return;
}
$label = JText::_($field->label);
$value = is_array($field->value) ? $field->value : array();
$options = (array)$field->fieldparams->get('options', array());
$moduleParams = $displayData['params'];
$count_cols = (int)$moduleParams->get('count_cols', 2);
$width = (int)(100/$count_cols);

if(!is_array($options) || !count($options)){
    return;
}

$counter = 0;
foreach($options as $option){
	if(!$option->hidden){
		$counter++;
	}
}

?>
<?php if($counter > 1):?>
<div class="jlmf-section checkbox<?php echo (count($value)) ? ' open' : '';?>">
	<div class="jlmf-label"><?php echo $label;?></div>
	<div class="jlmf-list-<?php echo $count_cols;?>">

		<?php
		$i = 1;
		$groups = array_chunk($options, ceil(count($options) / $count_cols));
		foreach($groups as $options) {
			foreach($options as $k => $v) {
				if (!empty($v->hidden)) {
					continue;
				}
				$checked = in_array($v->value, $value) ? ' checked="checked"' : '';
		?>
		<div class="checkboxes">
			<input
				type="checkbox"
				value="<?php echo $v->value; ?>"
				id="<?php echo $field->name.'-'. $i.'-'.$moduleId; ?>"
				name="filter[<?php echo $field->id; ?>][]"<?php echo $checked; ?>
				class="jlmf-checkbox"
			/>
			<label class="jlmf-sublabel" for="<?php echo $field->name.'-'. $i.'-'.$moduleId; ?>"><span></span><i><?php echo JText::_($v->name);?>&nbsp;<b>(<?php echo JText::_($v->counter);?>)</b></i></label>
		</div>
		<?php
				$i++;
			}
		}
		?>
	</div>
	<?php if(count($value)):?>
	<button class="clear" type="button">Сбросить</button>
	<?php endif;?>
</div>
<?php endif;?>
