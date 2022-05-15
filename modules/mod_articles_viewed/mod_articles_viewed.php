<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_viewed
 *
 * @copyright   (C) 2010 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\Module\ArticlesViewed\Site\Helper\ArticlesViewedHelper;

$list = ArticlesViewedHelper::getList($params);

require ModuleHelper::getLayoutPath('mod_articles_viewed', $params->get('layout', 'horizontal'));
