<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php
if(class_exists('WooCommerce') && class_exists('WOOMULTI_CURRENCY_F')) {
$wooSettings = new WOOMULTI_CURRENCY_F_Data();

$curencyList = $wooSettings->get_currencies();
$currentCurrency = $wooSettings->get_current_currency();
$links = $wooSettings->get_links();
?>

<?php if (count($curencyList) > 1): ?>
    <div class="stm-multi-currency">
        <div class="stm-multi-currency__info">
            <div class="stm-multi-curr__text stm-multi-curr__text_nomargin">
                <?php echo wp_kses((!empty($element['data']['title'])) ? $element['data']['title'] : esc_html__('Currency: ', 'motors' ), array('br' => array())); ?>
            </div>
        </div>
        <div class="stm-multicurr-select">
            <select id="stm-multi-curr-select">
                <?php
                foreach ($curencyList as $k => $item) {
                    $selected = ($currentCurrency == $item) ? "selected" : '';
                    $link = ($currentCurrency != $item) ? $links[$item] : '';

                    echo '<option ' . $selected . ' value="' . $link . '">' . $item . ' (' . get_woocommerce_currency_symbol( $item ) . ')</option>';
                }
                ?>
            </select>
        </div>
    </div>
    <script>
        (function($) {
            $(document).ready(function () {
                $('#stm-multi-curr-select').on('change', function () {
                    window.location = $(this).val();
                });
            });
        })(jQuery);
    </script>
<?php endif; ?>
<?php } else {
    echo esc_html__('Pleace install WooCommerce & Multi Currency for WooCommerce', 'motors');
}?>
