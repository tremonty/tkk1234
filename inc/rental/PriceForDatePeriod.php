<?php

class PriceForDatePeriod {

    private static $wpdbObj;

    public function __construct()
    {
        global $wpdb;

        self::$wpdbObj = $wpdb;
        self::createPriceInfoDB();
        add_action('stm_date_period', array($this, 'priceForDateView'));
        add_action( 'admin_enqueue_scripts', array($this, 'loadScriptStyles') );
        add_action('save_post', array($this, 'stm_save_customer_note_meta'), 10, 2);
        add_filter( 'woocommerce_product_get_price', array($this, 'updateVariationPrice'), 20, 2);
        add_filter( 'woocommerce_product_variation_get_price', array($this, 'updateVariationPrice'), 20, 2);
        add_filter( 'stm_cart_items_content', array($this, 'updateCart'), 30, 1);
    }

    private function createPriceInfoDB () {
        $charset_collate = self::$wpdbObj->get_charset_collate();
        $table_name = self::$wpdbObj->prefix. "rental_price_date";

        if(self::$wpdbObj->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {

            $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            variation_id INT NOT NULL,
            starttime INT NOT NULL,
            endtime INT NOT NULL,
            price INT NOT NULL,
            PRIMARY KEY  (id)
          ) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }

    public static function updateVariationPrice ($price, $product) { // update total cart
        if($product->get_type() == 'car_option') return $price;

        $table_name = self::$wpdbObj->prefix. "rental_price_date";

        $orderCookieData = stm_get_rental_order_fields_values();

        $varId = $product->get_type() == 'variation' ? $product->get_parent_id() : $product->get_id();
        $endDate = (isset($orderCookieData['calc_return_date']) && !empty($orderCookieData['calc_return_date'])) ? strtotime($orderCookieData['calc_return_date']) : '' ;
        $dates = stm_getDateRangeWithTime($orderCookieData['calc_pickup_date'], $orderCookieData['calc_return_date']);

        $newPrice = 0;

        $countDays = $orderCookieData['order_days'];

        foreach ($dates as $k => $date) {
            $date = strtotime($date);

            $endDate = ($date != $endDate && isset($dates[$k + 1])) ? strtotime($dates[$k + 1]) : $endDate;

            $response = self::$wpdbObj->get_row(self::$wpdbObj->prepare("SELECT * FROM {$table_name}
                          WHERE
                          (variation_id = %d) AND
                          (starttime BETWEEN %s AND %s OR
                          endtime BETWEEN %s AND %s OR
                          (starttime <= %s AND endtime >= %s)) ORDER BY id DESC LIMIT 1", array($varId, $date, $endDate, $date, $endDate, $date, $endDate)));

            if ($k < $countDays) {
                $price = (!empty($price)) ? $price : 0;
                $newPrice += (!empty($response) && isset($response->price)) ? $response->price : $price;
            }
        }

        $newPrice = ($newPrice == 0) ? $price : $newPrice;

        return apply_filters('rental_variation_price_manipulations', $newPrice);
    }

    public static function getTotalByDays ($price, $varId) {
        $table_name = self::$wpdbObj->prefix. "rental_price_date";

        $orderCookieData = stm_get_rental_order_fields_values();

        $endDate = (isset($orderCookieData['calc_return_date']) && !empty($orderCookieData['calc_return_date'])) ? strtotime($orderCookieData['calc_return_date']) : '' ;

        $dates = stm_getDateRangeWithTime($orderCookieData['calc_pickup_date'], $orderCookieData['calc_return_date']);

        $newPrice = 0;

        $countDays = $orderCookieData['order_days'];

        foreach ($dates as $k => $date) {
            $date = strtotime($date);

            $endDate = ($date != $endDate && isset($dates[$k + 1])) ? strtotime($dates[$k + 1]) : $endDate;

            $response = self::$wpdbObj->get_row(self::$wpdbObj->prepare("SELECT * FROM {$table_name}
                          WHERE
                          (variation_id = %d) AND
                          (starttime BETWEEN %s AND %s OR
                          endtime BETWEEN %s AND %s OR
                          (starttime <= %s AND endtime >= %s)) ORDER BY id DESC LIMIT 1", array($varId, $date, $endDate, $date, $endDate, $date, $endDate)));

            if($k < $countDays) {
                $price = (!empty($price)) ? $price : 0;
                $newPrice += (!empty($response) && isset($response->price)) ? $response->price : $price;
            }
        }

        return ($newPrice == 0) ? $price : $newPrice;
    }

    public static function updateCart($cartItems) {
        if(isset($cartItems['car_class']['id']) && !empty(self::getPeriods($cartItems['car_class']['id']))) {
            $total_sum = stm_get_cart_current_total();
            $fields = stm_get_rental_order_fields_values();
            $cart = WC()->cart->get_cart();

            $cart_items = array(
                'has_car' => false,
                'option_total' => 0,
                'options_list' => array(),
                'car_class' => array(),
                'options' => array(),
                'total' => $total_sum,
                'option_ids' => array(),
                'oldData' => 0
            );

            if (!empty($cart)) {
                $cartOldData = (isset($_GET['order_old_days']) && !empty(intval($_GET["order_old_days"]))) ? $_GET['order_old_days'] : 0;

                foreach ($cart as $cart_item) {
                    $id = $cart_item['product_id'];
                    $post = $cart_item['data'];

                    $buy_type = (get_class($cart_item['data']) == 'WC_Product_Car_Option') ? 'options' : 'car_class';

                    if ($buy_type == 'options') {
                        $cartItemQuant = $cart_item['quantity'];

                        if ($cartOldData > 0) {
                            if ($cart_item['quantity'] != 1) {
                                $cartItemQuant = ($cart_item['quantity'] / $cartOldData);
                            } else {
                                $cartItemQuant = 1;
                            }
                        }

                        $priceStr = $cart_item['data']->get_data();

                        $total = $cartItemQuant * $priceStr['price'];
                        $cart_items['option_total'] += $total;
                        $cart_items['option_ids'][] = $id;

                        $cart_items[$buy_type][] = array(
                            'id' => $id,
                            'quantity' => $cartItemQuant,
                            'name' => $post->get_title(),
                            'price' => $priceStr['price'],
                            'total' => $total,
                            'opt_days' => $fields['ceil_days'],
                            'subname' => get_post_meta($id, 'cars_info', true),
                        );

                        $cart_items['options_list'][$id] = $post->get_title();
                    } else {

                        $variation_id = 0;
                        if (!empty($cart_item['variation_id'])) {
                            $variation_id = $cart_item['variation_id'];
                        }

                        if (isset($_GET['pickup_location'])) {
                            $pickUpLocationMeta = get_post_meta($id, 'stm_rental_office');
                            if (!in_array($_GET['pickup_location'], explode(',', $pickUpLocationMeta[0]))) {
                                WC()->cart->empty_cart();
                            }
                        }

                        $item = $cart_item['data']->get_data();

                        $cart_items[$buy_type][] = array(
                            'id' => $id,
                            'variation_id' => $variation_id,
                            'quantity' => $cart_item['quantity'],
                            'name' => $post->get_title(),
                            'price' => stm_getDefaultVariablePrice($id),
                            'total' => self::getTotalByDays($item['price'], $item['parent_id']),
                            'subname' => get_post_meta($id, 'cars_info', true),
                            'payment_method' => get_post_meta($variation_id, '_stm_payment_method', true),
                            'days' => $fields['order_days'],
                            'hours' => (isset($fields['order_hours'])) ? $fields['order_hours'] : 0,
                            'ceil_days' => $fields['ceil_days'],
                            'oldData' => $cartOldData
                        );

                        $cart_items['has_car'] = true;
                    }
                }

                /*Get only last element*/
                if (count($cart_items['car_class']) > 1) {
                    $rent = array_pop($cart_items['car_class']);
                    $cart_items['delete_items'] = $cart_items['car_class'];
                    $cart_items['car_class'] = $rent;
                } else {
                    if (!empty($cart_items['car_class'])) {
                        $cart_items['car_class'] = $cart_items['car_class'][0];
                    }
                }

                return $cart_items;
            }
        }

        return $cartItems;
    }

    public static function getPeriods($varId = '') {
        $id = ($varId != '') ? $varId : get_the_ID();

        $result = self::$wpdbObj->get_results("SELECT * FROM " . self::$wpdbObj->prefix . "rental_price_date WHERE `variation_id` = {$id} ORDER BY id ASC");

        return $result;
    }

    public static function addPeriodIntoDB ($varId, $datePickup, $dateDrop, $price) {
        $table_name = self::$wpdbObj->prefix. "rental_price_date";

        if(!is_null(self::checkEntry($datePickup, $dateDrop, $varId))) {
            self::$wpdbObj->update(
                $table_name,
                array(
                    'variation_id' => $varId,
                    'starttime' => strtotime($datePickup),
                    'endtime' => strtotime($dateDrop),
                    'price' => $price
                ),
                array(
                    'variation_id' => $varId,
                    'starttime' => strtotime($datePickup),
                    'endtime' => strtotime($dateDrop),
                ),
                array(
                    '%d',
                    '%d',
                    '%d',
                    '%d'
                ),
                array(
                    '%d',
                    '%d',
                    '%d'
                )
            );
        } else {
            self::$wpdbObj->insert(
                $table_name,
                array(
                    'variation_id' => $varId,
                    'starttime' => strtotime($datePickup),
                    'endtime' => strtotime($dateDrop),
                    'price' => $price
                ),
                array(
                    '%d',
                    '%d',
                    '%d',
                    '%d'
                )
            );
        }
    }

    public static function deleteEntry ($ids) {
        $table_name = self::$wpdbObj->prefix. "rental_price_date";
        foreach (explode(',', $ids) as $item) {
            self::$wpdbObj->delete( $table_name, array( 'id' => $item ) );
        }
    }

    public static function checkEntry ($startTime, $endTime, $varId) {
        $startTime = strtotime($startTime);
        $endTime = strtotime($endTime);
        $result = self::$wpdbObj->get_var(self::$wpdbObj->prepare("SELECT id FROM " . self::$wpdbObj->prefix ."rental_price_date WHERE `variation_id` = %d AND `starttime` = %s AND `endtime` = %s", array($varId, $startTime, $endTime)));

        if(!empty($result)) {
            return $result;
        }

        return null;
    }

    public static function loadScriptStyles () {
        wp_enqueue_script( 'rental-price-js', get_template_directory_uri() . '/inc/rental/assets/js/rental-price.js', array('jquery') );
        wp_enqueue_style( 'rental-price-styles', get_template_directory_uri() . '/inc/rental/assets/css/rental-price.css');
    }

    public static function stm_save_customer_note_meta($post_id, $post)
    {
        $slug = 'product';

        if ($slug != $post->post_type) {
            return;
        }

        if(isset($_POST['remove-date']) && !empty($_POST['remove-date'])) {
            self::deleteEntry($_POST['remove-date']);
        }

        if(isset($_POST['date-pickup']) && isset($_POST['date-drop']) && isset($_POST['date-price'])) {
            for ($q=0;$q<count($_POST['date-pickup']);$q++) {
                if(isset($_POST['date-pickup'][$q]) && isset($_POST['date-drop'][$q]) && isset($_POST['date-price'][$q]) &&
                    !empty($_POST['date-pickup'][$q]) && !empty($_POST['date-drop'][$q]) && !empty($_POST['date-price'][$q])) {
                    self::addPeriodIntoDB($post->ID, wp_slash($_POST['date-pickup'][$q]), wp_slash($_POST['date-drop'][$q]), $_POST['date-price'][$q]);
                }
            }
        }
    }

    /*get price for date period*/
    public static function getVariationPriceView ($carId) {
        $table_name = self::$wpdbObj->prefix. "rental_price_date";

        $orderCookieData = stm_get_rental_order_fields_values();

        $varId = $carId;
        $startDate = (isset($orderCookieData['calc_pickup_date']) && !empty($orderCookieData['calc_pickup_date'])) ? strtotime($orderCookieData['calc_pickup_date']) : '' ;
        $endDate = (isset($orderCookieData['calc_return_date']) && !empty($orderCookieData['calc_return_date'])) ? strtotime($orderCookieData['calc_return_date']) : '' ;
        $orderDays = (isset($orderCookieData['order_days']) && !empty($orderCookieData['order_days'])) ? $orderCookieData['order_days'] : '' ;

        if(!empty($startDate) && !empty($endDate)) {
            $request = "SELECT * FROM {$table_name}
          WHERE
          (variation_id = {$varId}) AND
          (starttime BETWEEN {$startDate} AND {$endDate} OR
          endtime BETWEEN {$startDate} AND {$endDate} OR
          (starttime <= {$startDate} AND endtime >= {$endDate})) ORDER BY id ASC";

            $response = self::$wpdbObj->get_results($request);

            return $response;
        }

        return '';
    }

    public static function priceForDateView() {

        $periods = self::getPeriods();
        ?>
        <div class="rental-price-date-wrap">
            <ul class="rental-price-date-list">
                <?php
                if(!empty($periods)) :
                    foreach ($periods as $k => $val) : ?>
                        <li>
                            <div class="repeat-number"><?php echo esc_html($k+1);?></div>
                            <table>
                                <tr>
                                    <td>
                                        <?php echo esc_html__('Start Date', 'motors'); ?>
                                    </td>
                                    <td>
                                        <input type="text" class="date-pickup" value="<?php echo date('Y/m/d H:i', $val->starttime); ?>" name="date-pickup[]" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo esc_html__('End Date', 'motors'); ?>
                                    </td>
                                    <td>
                                        <input type="text" class="date-drop" value="<?php echo date('Y/m/d H:i', $val->endtime); ?>" name="date-drop[]" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Price
                                    </td>
                                    <td>
                                        <input type="text" value="<?php echo esc_attr($val->price); ?>" name="date-price[]" />
                                    </td>
                                </tr>
                            </table>
                            <div class="btn-wrap">
                                <button class="remove-fields button-secondary" data-remove="<?php echo esc_attr($val->id); ?>">Remove</button>
                            </div>
                        </li>
                    <?php
                    endforeach;
                else:
                    ?>
                    <li>
                        <div class="repeat-number">1</div>
                        <table>
                            <tr>
                                <td>
                                    <?php echo esc_html__('Start Date', 'motors'); ?>
                                </td>
                                <td>
                                    <input type="text" class="date-pickup" name="date-pickup[]" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php echo esc_html__('End Date', 'motors'); ?>
                                </td>
                                <td>
                                    <input type="text" class="date-drop" name="date-drop[]" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Price
                                </td>
                                <td>
                                    <input type="text" name="date-price[]" />
                                </td>
                            </tr>
                        </table>
                        <div class="btn-wrap">
                            <button class="remove-fields button-secondary">Remove</button>
                        </div>
                    </li>
                <?php
                endif;
                ?>
                <li>
                    <button class="repeat-fields button-primary button-large">Add</button>
                </li>
            </ul>
            <input type="hidden" name="remove-date" />
        </div>
        <?php
    }
}

new PriceForDatePeriod();
