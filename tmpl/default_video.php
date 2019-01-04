<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_brochure
 *
 * @copyright   Copyright (C) NPEU 2018.
 * @license     MIT License; see LICENSE.md
 */

defined('_JEXEC') or die;


//$image_src = $params->get('image_src');
//$image_alt = $params->get('image_alt');

$youtube_id = $params->get('youtube_id');

?>
<div class="brochure__content--video">
    <div>
        <iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $youtube_id; ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
    </div>
</div>