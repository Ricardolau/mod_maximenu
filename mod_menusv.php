<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once __DIR__ . '/helper.php';


// Carga de   CSS
//Carga de ficheros .CSS y JS
$doc = JFactory::getDocument();

//$doc->addScript(JURI::base(true).'/modules/mod_carousel/lib/jquery.bxslider.min.js', 'text/javascript');
$doc->addStyleSheet(JURI::base(true).'/modules/mod_menusv/css/menuSv.css');

$list      = ModMenusvHelper::getList($params);
$base      = ModMenusvHelper::getBase($params);
$active    = ModMenusvHelper::getActive($params);

$active_id = $active->id;
$path      = $base->tree;
$showAll   = $params->get('showAllChildren');
$class_sfx = htmlspecialchars($params->get('class_sfx'));
$ItMenuNi  = ModMenusvHelper::getMaximenu($list);

if (count($list))
{
	require JModuleHelper::getLayoutPath('mod_menusv', $params->get('layout', 'default'));
}
