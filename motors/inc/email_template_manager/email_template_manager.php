<?php

function addEmailTemplateMenu () {
    $title =  esc_html__('Email Template Manager', 'motors');

    add_submenu_page( 'tools.php', $title, $title, 'administrator', 'email-templaet-manager', 'emailTemplateView' );
}
add_action('admin_menu', 'addEmailTemplateMenu');

function emailTemplateView() {
    get_template_part('inc/email_template_manager/main');
}

function getDefaultSubject($templateName) {
    $test_drive = 'Request Test Drive [car]';

    $request_price = 'Request car price [car]';

    $trade_offer = 'Trade offer [car]';

    $trade_in = 'Car Trade In';

    $sell_a_car = 'Sell a car';

    $add_a_car = 'Car Added';

    $pay_per_listing = 'New Pay Per Listing';

    $report_review = 'Report Review';

    $password_recovery = 'Password recovery';

    $request_for_a_dealer = 'Request for becoming a dealer';

    $welcome = 'Welcome';

    $new_user = 'New user';

    return ${''.$templateName};
}

function getDefaultTemplate($templateName) {
    $test_drive = '<table>
        <tr>
            <td>Name - </td>
            <td>[name]</td>
        </tr>
        <tr>
            <td>Email - </td>
            <td>[email]</td>
        </tr>
        <tr>
            <td>Phone - </td>
            <td>[phone]</td>
        </tr>
        <tr>
            <td>Date - </td>
            <td>[best_time]</td>
        </tr>
    </table>';

    $request_price = '<table>
        <tr>
            <td>Name - </td>
            <td>[name]</td>
        </tr>
        <tr>
            <td>Email - </td>
            <td>[email]</td>
        </tr>
        <tr>
            <td>Phone - </td>
            <td>[phone]</td>
        </tr>
    </table>';

    $trade_offer = '<table>
        <tr>
            <td>Name - </td>
            <td>[name]</td>
        </tr>
        <tr>
            <td>Email - </td>
            <td>[email]</td>
        </tr>
        <tr>
            <td>Phone - </td>
            <td>[phone]</td>
        </tr>
        <tr>
            <td>Trade Offer - </td>
            <td>[price]</td>
        </tr>
    </table>';

    $trade_in = '<table>
        <tr>
            <td>First name - </td>
            <td>[first_name]</td>
        </tr>
        <tr>
            <td>Last Name - </td>
            <td>[last_name]</td>
        </tr>
        <tr>
            <td>Email - </td>
            <td>[email]</td>
        </tr>
        <tr>
            <td>Phone - </td>
            <td>[phone]</td>
        </tr>
        <tr>
            <td>Car - </td>
            <td>[car]</td>
        </tr>
        <tr>
            <td>Make - </td>
            <td>[make]</td>
        </tr>
        <tr>
            <td>Model - </td>
            <td>[model]</td>
        </tr>
        <tr>
            <td>Year - </td>
            <td>[stm_year]</td>
        </tr>
        <tr>
            <td>Transmission - </td>
            <td>[transmission]</td>
        </tr>
        <tr>
            <td>Mileage - </td>
            <td>[mileage]</td>
        </tr>
        <tr>
            <td>Vin - </td>
            <td>[vin]</td>
        </tr>
        <tr>
            <td>Exterior color</td>
            <td>[exterior_color]</td>
        </tr>
        <tr>
            <td>Interior color</td>
            <td>[interior_color]</td>
        </tr>
        <tr>
            <td>Exterior condition</td>
            <td>[exterior_condition]</td>
        </tr>
        <tr>
            <td>Interior condition</td>
            <td>[interior_condition]</td>
        </tr>
        <tr>
            <td>Owner</td>
            <td>[owner]</td>
        </tr>
        <tr>
            <td>Accident</td>
            <td>[accident]</td>
        </tr>
        <tr>
            <td>Comments</td>
            <td>[comments]</td>
        </tr>
    </table>';

    $add_a_car = '<table>
        <tr>
            <td>User Added car.</td>
            <td></td>
        </tr>
        <tr>
            <td>User id - </td>
            <td>[user_id]</td>
        </tr>
        <tr>
            <td>Car ID - </td>
            <td>[car_id]</td>
        </tr>
    </table>';

    $update_a_car_ppl = '<table>
        <tr>
            <td>User Updated car.</td>
            <td></td>
        </tr>
        <tr>
            <td>User id - </td>
            <td>[user_id]</td>
        </tr>
        <tr>
            <td>Car ID - </td>
            <td>[car_id]</td>
        </tr>
        <tr>
            <td>Revision Link - </td>
            <td>[revision_link]</td>
        </tr>
    </table>';

    $pay_per_listing = '<table>
        <tr>
            <td>New Pay Per Listing. Order id - </td>
            <td>[order_id]</td>
        </tr>
        <tr>
            <td>Order status - </td>
            <td>[order_status]</td>
        </tr>
        <tr>
            <td>User - </td>
            <td>[first_name] [last_name] [email]</td>
        </tr>
        <tr>
            <td>Car Title - </td>
            <td>[listing_title]</td>
        </tr>
        <tr>
            <td>Car Id - </td>
            <td>[car_id]</td>
        </tr>
    </table>';

    $report_review = '<table>
        <tr>
            <td colspan="2">Review with id: "[report_id]" was reported</td>
        </tr>
        <tr>
            <td>Report content</td>
            <td>[review_content]</td>
        </tr>
    </table>';

    $password_recovery = '<table>
        <tr>
            <td>Please, follow the link, to set new password:</td>
            <td>[password_content]</td>
        </tr>
    </table>';

    $request_for_a_dealer = '<table>
        <tr>
            <td>User Login:</td>
            <td>[user_login]</td>
        </tr>
    </table>';

    $welcome = '<table>
        <tr>
            <td>Congratulations! You have been registered in our website with a username: </td>
            <td>[user_login]</td>
        </tr>
    </table>';

    $new_user = '<table>
        <tr>
            <td>New user Registered. Nickname: </td>
            <td>[user_login]</td>
        </tr>
    </table>';

    return ${''.$templateName};
}

function getTemplateShortcodes($templateName) {
    $testDrive = array (
        'car' => '[car]',
        'f_name' => '[name]',
        'email' => '[email]',
        'phone' => '[phone]',
        'best_time' => '[best_time]',
    );

    $requestPrice = array (
        'car' => '[car]',
        'f_name' => '[name]',
        'email' => '[email]',
        'phone' => '[phone]',
    );

    $tradeOffer = array (
        'car' => '[car]',
        'f_name' => '[name]',
        'email' => '[email]',
        'phone' => '[phone]',
        'price' => '[price]',
    );

    $tradeIn = array (
        'car' => '[car]',
        'first_name' => '[first_name]',
        'last_name' => '[last_name]',
        'email' => '[email]',
        'phone' => '[phone]',
        'make' => '[make]',
        'model' => '[model]',
        'stm_year' => '[stm_year]',
        'transmission' => '[transmission]',
        'mileage' => '[mileage]',
        'vin' => '[vin]',
        'exterior_color' => '[exterior_color]',
        'interior_color' => '[interior_color]',
        'owner' => '[owner]',
        'exterior_condition' => '[exterior_condition]',
        'interior_condition' => '[interior_condition]',
        'accident' => '[accident]',
        'comments' => '[comments]',
        'video_url' => '[video_url]',
        'image_urls' => '[image_urls]',
    );

    $addACar = array (
        'user_id' => '[user_id]',
        'car_id' => '[car_id]',
    );

    $updateACarPPL = array (
        'user_id' => '[user_id]',
        'car_id' => '[car_id]',
        'revision_link' => '[revision_link]',
    );

    $perPayListing = array (
        'first_name' => '[first_name]',
        'last_name' => '[last_name]',
        'email' => '[email]',
        'order_id' => '[order_id]',
        'order_status' => '[order_status]',
        'listing_title' => '[listing_title]',
        'car_id' => '[car_id]'
    );

    $reportReview = array (
        'report_id' => '[report_id]',
        'review_content' => '[review_content]',
    );

    $passwordRecovery = array (
        'password_content' => '[password_content]',
    );

    $requestForADealer = array (
        'user_login' => '[user_login]',
    );

    $welcome = array (
        'user_login' => '[user_login]',
    );

    $valueMyCar = array (
        'car' => '[car]',
        'email' => '[email]',
        'price' => '[price]'
    );

    $newUser = array (
        'user_login' => '[user_login]',
    );

    return ${''.$templateName};
}

function updateTemplates () {
    $opt = array ('add_a_car_', 'update_a_car_ppl_', 'trade_in_', 'trade_offer_', 'request_price_', 'test_drive_', 'update_a_car_', 'report_review_', 'password_recovery_', 'request_for_a_dealer_', 'welcome_', 'new_user_', 'pay_per_listing_', 'value_my_car_');

    foreach ($opt as $key) {
        update_option($key . 'template', $_POST[$key . 'template']);
        update_option($key . 'subject', $_POST[$key . 'subject']);
        if($key == 'trade_in_') {
            update_option('sell_a_car_subject', $_POST['sell_a_car_subject']);
        } elseif ($key == 'value_my_car_') {
            update_option('value_my_car_reject_subject', $_POST['value_my_car_reject_subject']);
            update_option('value_my_car_reject_template', $_POST['value_my_car_reject_template']);
        }
    }
}

if(isset($_POST['update_email_templates'])) {
    updateTemplates();
}

function generateSubjectView($subjectName, $args) {
    $template = stripslashes(get_option($subjectName . '_subject', getDefaultSubject($subjectName)));

    if($template != '') {
        foreach ($args as $k => $val) {
            $template = str_replace("[{$k}]", $val, $template);
        }

        return $template;
    }

    return '';
}

function generateTemplateView($templateName, $args) {
    $template = stripslashes(get_option($templateName . '_template', getDefaultTemplate($templateName)));

    if($template != '') {
        foreach ($args as $k => $val) {
            $template = str_replace("[{$k}]", $val, $template);
        }

        return $template;
    }

    return '';
}