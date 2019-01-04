<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_brochure
 *
 * @copyright   Copyright (C) NPEU 2018.
 * @license     MIT License; see LICENSE.md
 */

defined('_JEXEC') or die;


$image_src = $params->get('image_src');
$image_alt = $params->get('image_alt');

?>
<div class="brochure__content--image">
    <img src="<?php echo $image_src; ?>" alt="<?php echo $image_alt; ?>">
</div>