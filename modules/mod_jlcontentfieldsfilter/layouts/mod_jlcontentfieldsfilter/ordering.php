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

if (!key_exists('options', $displayData))
{
	return;
}

$moduleId = $displayData['moduleId'];
$options = $displayData['options'];
$selected = $displayData['selected'];

if(!is_array($options) || !count($options)){
	return;
}

?>
<label class="jlmf-label" for="jlcontentfieldsfilter-ordering-<?php echo $moduleId; ?>"><?php echo JText::_('MOD_JLCONTENTFIELDSFILTER_ORDERING'); ?></label>
<?php
echo JHtml::_('select.genericlist', $options, 'jlcontentfieldsfilter[ordering]',
    'class="jlmf-select" ', 'value', 'text', $selected, 'jlcontentfieldsfilter-ordering-'.$moduleId);
?>


