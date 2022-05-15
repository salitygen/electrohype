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
$value = $field->value;
?>

<label class="jlmf-label" for="<?php echo $field->name.'-'.$moduleId; ?>"><?php echo $label; ?></label>
<input
    type="text"
    value="<?php echo $value; ?>"
    id="<?php echo $field->name.'-'.$moduleId; ?>"
    name="jlcontentfieldsfilter[<?php echo $field->id; ?>]"
    class="jlmf-input"
/>
