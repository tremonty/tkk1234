<?php
/**
 * Created by PhpStorm.
 * User: Dima
 * Date: 6/11/2018
 * Time: 4:48 PM
 */

class DiscountByDays
{
    const META_KEY_INFO = 'rental_discount_days_info';

    public function __construct()
    {
        add_action('stm_disc_by_days', array($this, 'discountByDaysView'));
        add_action('save_post', array($this, 'add_days_post_meta'), 10, 2);
        add_filter( 'woocommerce_product_get_price', array($this, 'updateVariationPriceWithDiscount'), 30, 2);
        add_filter( 'woocommerce_product_variation_get_price', array($this, 'updateVariationPriceWithDiscount'), 30, 2);
        add_filter( 'stm_cart_items_content', array($this, 'updateCart'), 40, 1);
    }

    public static function add_days_post_meta ($post_id, $post) {
        if(isset($_POST['days'][0]) && !empty($_POST['days'][0]) && isset($_POST['percent'][0]) && !empty($_POST['percent'][0])) {
            $data = array();

            foreach ($_POST['days'] as $key => $val) {
                if(!empty($val) && !empty($_POST['percent'][$key])) {
                    $data[$val] = array('days' => $val, 'percent' => $_POST['percent'][$key]);
                }
            }

            update_post_meta($post->ID, self::META_KEY_INFO, $data);
        } else {
            delete_post_meta($post->ID, self::META_KEY_INFO);
        }
    }

    public static function get_days_post_meta ($id) {
        return get_post_meta($id, self::META_KEY_INFO, true);
    }

    public static function updateCart($cartItems) {
        if(isset($cartItems['car_class']['total']) && isset($cartItems['car_class']['id'])) {
            $cartItems['car_class']['total'] = $cartItems['car_class']['total'] - ($cartItems['car_class']['total'] * self::getPercent($cartItems['car_class']['id']));
        }

        return $cartItems;
    }

    public static function updateVariationPriceWithDiscount ($price, $product) {
        if($product->get_type() == 'car_option') return $price;
        $varId = $product->get_type() == 'variation' ? $product->get_parent_id() : $product->get_id();
        return (!empty(self::getPercent($varId))) ? $price - ($price * self::getPercent($varId)) : $price;
    }

    public static function getPercent ($varId) {
        $discounts = self::get_days_post_meta($varId);

        $orderCookieData = stm_get_rental_order_fields_values();
        if($orderCookieData['calc_pickup_date'] != '--' && $orderCookieData['calc_return_date'] != '--') {
            $date1 = new DateTime($orderCookieData['calc_pickup_date']);
            $date2 = new DateTime($orderCookieData['calc_return_date']);

            $diff = $date2->diff($date1)->format("%a.%h");

            if (empty($diff)) {
                $diff = 1;
            }

            if (!empty(get_post_meta($varId, 'rental_price_per_hour_info', true))) {
                $dh = explode('.', $diff);
                $dates = $dh[0];
            } else {
                $dates = ceil($diff);
            }

            if (!empty($discounts)) {
                $nearId = 0;
                $minDays = 0;

                foreach ($discounts as $k => $val) {
                    if(!empty($k)) {
                        if ($minDays == 0) {
                            if (($dates - $k) >= 0) {
                                $minDays = ($dates - $k);
                                $nearId = $k;
                            }
                        } else {
                            if (((int)($dates - $k) >= 0) && (($dates - $k) <= $minDays) && ($dates >= $k)) {
                                $minDays = ($dates - $k);
                                $nearId = $k;
                            }
                        }
                    }
                }

                return (isset($discounts[$nearId])) ? $discounts[$nearId]['percent'] / 100 : 0;
            }
        }

        return 0;
    }

    public static function discountByDaysView() {

        $periods = get_post_meta(get_the_ID(), self::META_KEY_INFO, true);
        ?>
        <div class="discount-by-days-wrap">
            <ul class="discount-by-days-list">
                <?php
                if(!empty($periods)) :
                    $i=1;
                    foreach ($periods as $k => $val) : ?>
                        <li>
                            <div class="repeat-days-number"><?php echo stm_do_lmth($i);?></div>
                            <table>
                                <tr>
                                    <td>
                                        Days
                                    </td>
                                    <td>
                                        <input type="text" value="<?php echo esc_attr($val['days']); ?>" name="days[]" />
                                    </td>
                                    <td>>=</td>
                                </tr>
                                <tr>
                                    <td>
                                        Discount
                                    </td>
                                    <td>
                                        <input type="text" value="<?php echo esc_attr($val['percent']); ?>" name="percent[]" />
                                    </td>
                                    <td>
                                        %
                                    </td>
                                </tr>
                            </table>
                            <div class="btn-wrap">
                                <button class="remove-days-fields button-secondary">Remove</button>
                            </div>
                        </li>
                    <?php
                        $i++;
                    endforeach;
                else:
                    ?>
                    <li>
                        <div class="repeat-days-number">1</div>
                        <table>
                            <tr>
                                <td>
                                    Days
                                </td>
                                <td>
                                    <input type="text" name="days[]" />
                                </td>
                                <td>>=</td>
                            </tr>
                            <tr>
                                <td>
                                    Discount
                                </td>
                                <td>
                                    <input type="text" name="percent[]" />
                                </td>
                                <td>
                                    %
                                </td>
                            </tr>
                        </table>
                        <div class="btn-wrap">
                            <button class="remove-days-fields button-secondary">Remove</button>
                        </div>
                    </li>
                <?php
                endif;
                ?>
                <li>
                    <button class="repeat-days-fields button-primary button-large">Add</button>
                </li>
            </ul>
            <input type="hidden" name="remove-days" />
        </div>
        <?php
    }
}

new DiscountByDays();