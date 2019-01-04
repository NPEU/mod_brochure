<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_brochure
 *
 * @copyright   Copyright (C) NPEU 2018.
 * @license     MIT License; see LICENSE.md
 */

defined('_JEXEC') or die;

$doc = JFactory::getDocument();

$module_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname(__DIR__));

$css_file = $module_path . '/assets/brochure.css';
$doc->addStyleSheet($css_file);


?>
<div class="brochure<?php echo $moduleclass_sfx; ?>">
    <?php if ($params->get('show_title')): ?>
    <p><?php echo $params->get('title'); ?></p>
    <?php endif; ?>
    <pre>
    <?php #var_dump($params); ?>
    </pre>
    
    <div class="brochure__panes<?php if ($single_pane_type !== false): ?>  brochure__panes--single<?php else: ?>  brochure__panes--multiple<?php endif; ?>">
        <?php if ($single_pane_type !== false): ?>
        <div class="brochure__panes--single">
            <?php #echo $single_pane_content; 
            
            require JModuleHelper::getLayoutPath('mod_brochure', 'default_' . $single_pane_type);
            
            ?>
        </div>
        <?php else: ?>
        <div class="brochure__panes--left">
            <?php #echo $left_pane_content; 
            
            require JModuleHelper::getLayoutPath('mod_brochure', 'default_' . $left_pane_type);
            
            ?>
        </div>
        <div class="brochure__panes--right">
            <?php #echo $right_pane_content; 
            
            require JModuleHelper::getLayoutPath('mod_brochure', 'default_' . $right_pane_type);
            
            ?>
        </div>
        <?php endif; ?>
    </div>
</div>