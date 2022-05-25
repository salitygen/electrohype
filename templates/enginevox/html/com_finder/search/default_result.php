<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_finder
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
use Joomla\String\StringHelper;

$img = json_decode($this->result->images);
if($img != '' && $img->image_intro_caption != ''):?>
<li>
	<a title="<?php print $img->image_intro_caption;?>" href="<?php echo JRoute::_($this->result->path); ?>"><img  itemprop="thumbnailUrl" src="<?php print $img->image_intro;?>" title="<?php print $img->image_intro_caption;?>" alt="<?php print $img->image_intro_alt;?>"></a>
	<a title="<?php print $img->image_intro_caption;?>" class="link" href="<?php echo JRoute::_($this->result->path); ?>"><h5 itemprop="name"><?php print $img->image_intro_alt;?></h5></a>
</li>
<?php endif;?>


