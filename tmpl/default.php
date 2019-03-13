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

if ($load_assets) {
    $css_file = $module_path . '/assets/brochure.css';
    $doc->addStyleSheet($css_file);
    
    #$css_file_2 = $module_path . '/assets/ff-widths.min.css';
    #$doc->addStyleSheet($css_file_2);
}

$pane_classes = array('brochure__panes');

if ($single_pane_type !== false) {
    $pane_classes[] = 'brochure__panes--single';
} else {
    $pane_classes[] = 'brochure__panes--multiple';    
    
    if (!empty($pane_balance)) {
        $pane_classes[] = 'brochure__panes--multiple--' . $pane_balance; 
    }
}

$pane_classes = implode('  ', $pane_classes);
?>
<div class="brochure<?php echo $moduleclass_sfx; ?>">    
    <div class="<?php echo $pane_classes; ?>">
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