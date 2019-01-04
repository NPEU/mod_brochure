<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_brochure
 *
 * @copyright   Copyright (C) NPEU 2018.
 * @license     MIT License; see LICENSE.md
 */

defined('_JEXEC') or die;

// Include the brochure functions only once
JLoader::register('ModBrochureHelper', __DIR__ . '/helper.php');

#$thing = trim($params->get('thing'));


$left_pane_type      = $params->get('leftpane');
$right_pane_type     = $params->get('rightpane');

$single_pane_type    = ($left_pane_type == $right_pane_type) ? $left_pane_type : false;

#$single_pane_content = null;
#$left_pane_content   = null;
#$right_pane_content  = null;

#echo '$left_pane_type:' . $left_pane_type . '<br>'; #exit;
#echo '$right_pane_type: ' . $right_pane_type . '<br>'; #exit;
#echo '$single_pane_type: ' . $single_pane_type; exit;

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');

require JModuleHelper::getLayoutPath('mod_brochure', $params->get('layout', 'default'));