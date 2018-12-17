<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_brochure
 */

defined('_JEXEC') or die;

$data_src = $params->get('data_src');
$data_tpl = $params->get('data_tpl');


if (!$data = file_get_contents($data_src)) {
    $output = $params->get('data_src_err');
} else {
    if (!$json = json_decode($data)) {
        $output = $params->get('data_decode_err');
    } else {
        require_once dirname(__DIR__) . '/vendor/autoload.php';

        $loader = new Twig_Loader_Array(array(
            'tpl' => $data_tpl
        ));
        $twig = new Twig_Environment($loader);

        $output = $twig->render('tpl', array('data' => $json));
    }
}
?>
<div class="brochure__content--data">
    <?php echo $output; ?>
</div>
