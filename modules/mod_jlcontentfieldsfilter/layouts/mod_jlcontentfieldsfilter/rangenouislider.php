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

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Factory;

if (!key_exists('field', $displayData))
{
	return;
}

$moduleId = $displayData['moduleId'];
$min = $displayData['min'];
$max = $displayData['max'];
$field = $displayData['field'];
if(!empty($field->hidden)){
	return;
}
$label = JText::_($field->label);
$value = $field->value;
$from = !empty($value['from']) ? $value['from'] : $min;
$to =  !empty($value['to']) ? $value['to'] : $max;

$fromPlaceholder = $min !== '' ? JText::sprintf('MOD_JLCONTENTFIELDSFILTER_MIN', $min) : '';
$toPlaceholder = $max !== '' ? JText::sprintf('MOD_JLCONTENTFIELDSFILTER_MAX', $max) : '';

$doc = Factory::getDocument();
$doc->addScript('/modules/mod_jlcontentfieldsfilter/assets/javascript/nouislider.min.js');
$doc->addScript('/modules/mod_jlcontentfieldsfilter/assets/javascript/sliders.js');
$doc->addStyleSheet('/modules/mod_jlcontentfieldsfilter/assets/css/nouislider.min.css');
$doc->addStyleSheet('/modules/mod_jlcontentfieldsfilter/assets/css/range.css');

?>
<div class="jlmf-label ranges"><?php echo $label; ?></div>
<div class="jlmf-list-2 range-sliders">
	<div class="inputs">
		<div class="pre">
			<span><?php echo JText::_('MOD_JLCONTENTFIELDSFILTER_FROM');?></span>
			<input
					type="number"
					placeholder="<?php echo $fromPlaceholder; ?>"
					value="<?php echo $from ?>"
					id="<?php echo $field->name.'-from-'.$moduleId; ?>"
					name="jlcontentfieldsfilter[<?php echo $field->id; ?>][from]"
					class="jlmf-input input-min"
					data-min="<?php echo $min ?>"
			/>
		</div>
		<div class="pre">
			<span><?php echo JText::_('MOD_JLCONTENTFIELDSFILTER_TO');?></span>
			<input
					type="number"
					placeholder="<?php echo $toPlaceholder; ?>"
					value="<?php echo $to ?>"
					id="<?php echo $field->name.'-to-'.$moduleId; ?>"
					name="jlcontentfieldsfilter[<?php echo $field->id; ?>][to]"
					class="jlmf-input input-max"
					data-max="<?php echo $min ?>"
			/>
		</div>
	</div>
    <div class="jlmf-range-block">
        <div class="jlmf-range" data-min="<?php echo $min ?>" data-max="<?php echo $max ?>" data-from="<?php echo $from ?>" data-to="<?php echo $to ?>"></div>
    </div>
</div>
