<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_brochure
 *
 * @copyright   Copyright (C) NPEU 2018.
 * @license     MIT License; see LICENSE.md
 */

defined('_JEXEC') or die;

$data_src        = $params->get('data_src');
$data_tpl        = $params->get('data_tpl');
$data_src_err    = $params->get('data_src_err');
$data_decode_err = $params->get('data_decode_err');

// Allow for relative data src URLs:
if (strpos($data_src, 'http') !== 0) {
    $s        = empty($_SERVER['SERVER_PORT']) ? '' : ($_SERVER['SERVER_PORT'] == '443') ? 's' : '';
    $protocol = preg_replace('#/.*#',  $s, strtolower($_SERVER['SERVER_PROTOCOL']));
    $domain   = $protocol.'://'.$_SERVER['SERVER_NAME'];
    $data_src = $domain . '/' . trim($data_src, '/');
}

if (!$data = file_get_contents($data_src)) {
    $output = $data_src_err;
} else {
    if (!$json = json_decode($data)) {
        $output = $data_decode_err;
    } else {
        $twig = ModBrochureHelper::getTwig(array(
            'tpl' => $data_tpl
        ));

        $output = $twig->render('tpl', array('data' => $json));
    }
}
?>
<div class="brochure__content--data">
    <?php echo $output; ?>
</div>
