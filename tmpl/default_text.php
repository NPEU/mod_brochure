<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_brochure
 *
 * @copyright   Copyright (C) NPEU 2018.
 * @license     MIT License; see LICENSE.md
 */

defined('_JEXEC') or die;
/*
if ($params->def('prepare_content', 1))
{
    JPluginHelper::importPlugin('content');
    $module->content = JHtml::_('content.prepare', $module->content, '', 'mod_custom.content');
}
*/

$text_content  = $params->get('text_content');
$text_cta_text = $params->get('text_cta_text');
$text_cta_link = $params->get('text_cta_link');

?>
<div class="brochure__content--text">
    <?php echo $text_content; ?>
    
    <?php if (!empty($text_cta_text) && !empty($text_cta_link)) : ?>
    <p class="brochure__cta">
        <a href="<?php echo $text_cta_link; ?>" class="brochure__cta-link"><?php echo $text_cta_text; ?> <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="4.5,15 11.5,8 4.5,1"></polyline></svg></a>
    </p>
    <?php endif; ?>
</div>