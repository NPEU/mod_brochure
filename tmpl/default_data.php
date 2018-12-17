<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_brochure
 */

defined('_JEXEC') or die;

$data_src        = $params->get('data_src');
$data_tpl        = $params->get('data_tpl');
$data_src_err    = $params->get('data_src_err');
$data_decode_err = $params->get('data_decode_err');

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
