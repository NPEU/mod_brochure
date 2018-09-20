<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_brochure
 */

defined('_JEXEC') or die;


//$image_src = $params->get('image_src');
//$image_alt = $params->get('image_alt');

$data_src = $params->get('data_src');
$data_tpl = $params->get('data_tpl');


$data = file_get_contents($data_src);
#var_dump($data); exit;
$json = json_decode($data);

require_once dirname(__DIR__) . '/vendor/autoload.php';

$loader = new Twig_Loader_Array(array(
    'tpl' => $data_tpl
));
$twig = new Twig_Environment($loader);

#$output = $twig->render('index', array('name' => 'Fabien'));
$output = $twig->render('tpl', array('data' => $json));

?>
<div class="brochure__content--data">
    <?php echo $output; ?>
</div>

<?php /*


<figure class="recruitment  recruitment--baby-oscar  badge  badge--baby-oscar">
  <figcaption class="recruitment__title">Recruitment</figcaption>
  {% if data.override_msg is empty %}
  <div class="flexbox  flexbox--fixed">
    <div class="flexbox__item  divider-right">
      <dl class="recruitment__info">
        <dt class="recruitment__total-title">Total</dt>
        <dd class="recruitment__total-value">
          <span class="reel  reel--shaded">
            <span>{{ data.total|pad(3, '0', 'left') }}</span>
          </span>
        </dd><!--
        <dt class="recruitment__target-title">Target</dt>
        <dd class="recruitment__target-value">30</dd>-->
      </dl>
    </div>
    <div class="recruitment__news  flexbox__item">
      {{ data.latest_msg|md|raw }}
    </div>
  </div>
  {% else %}
  <p>
    {{ data.data.override_msg|raw }}
  </p>
  {% endif %}
</figure>

*/ ?>