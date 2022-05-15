<?php

defined('_JEXEC') or die;

if(!empty($displayData) && is_array($displayData)){
	$list = $displayData['field_data'];
	$id	  = $displayData['field_id'];
}else{
	return false;
}
?>
<fieldset class="radio">
<legend><?php echo $list['title'];?></legend>
<ul>
<?php for($i=0;$i<count($list['values']);$i++):?>
<li>
	<input id="field_<?php echo $id;?>_<?php echo $i;?>" type="radio" name="filter[<?php echo $id;?>][]" value="<?php echo $list['values'][$i]['value'];?>">
	<label for="field_<?php echo $id;?>_<?php echo $i;?>">
		<span>&nbsp;</span>
		<b><?php echo $list['values'][$i]['value'];?></b> 
		<i>(<?php echo $list['values'][$i]['count'];?>)</i>
	</label>
</li>
<?php endfor;?>
</ul>
</fieldset>