<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

wp_enqueue_script( 'bootstrap-js', get_template_directory_uri() . '/inc/email_template_manager/assets/js/bootstrap.js', array('jquery'), STM_THEME_VERSION, true );
wp_enqueue_script( 'tab-bootstrap-js', get_template_directory_uri() . '/inc/email_template_manager/assets/js/tab.js', array('jquery'), STM_THEME_VERSION, true );
wp_enqueue_script( 'etm-js', get_template_directory_uri() . '/inc/email_template_manager/assets/js/etm.js', array('jquery'), STM_THEME_VERSION, true );
wp_enqueue_style( 'bootstrap-styles', get_template_directory_uri() . '/inc/email_template_manager/assets/css/bootstrap.css', null, STM_THEME_VERSION, 'all' );


$templatePart = 'inc/email_template_manager/templates';
?>

<div class="stm-etm-wrap">
    <h2><?php echo esc_html__('Email Template Manager', 'motors'); ?></h2>
    <form action="" method="post">
        <div class="tabs-wrap">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link active" id="v-pills-welcome-tab" data-toggle="pill" href="#v-pills-welcome" role="tab" aria-controls="v-pills-welcome" aria-selected="true">Welcome</a>
                <a class="nav-link" id="v-pills-new_user-tab" data-toggle="pill" href="#v-pills-new_user" role="tab" aria-controls="v-pills-new_user" aria-selected="false">New User</a>
                <a class="nav-link" id="v-pills-password_recovery-tab" data-toggle="pill" href="#v-pills-password_recovery" role="tab" aria-controls="v-pills-password_recovery" aria-selected="false">Password Recovery</a>
                <a class="nav-link" id="v-pills-request_for_a_dealer-tab" data-toggle="pill" href="#v-pills-request_for_a_dealer" role="tab" aria-controls="v-pills-request_for_a_dealer" aria-selected="false">Request For a Dealer</a>
                <a class="nav-link" id="v-pills-test_drive-tab" data-toggle="pill" href="#v-pills-test_drive" role="tab" aria-controls="v-pills-test_drive" aria-selected="false">Test Drive</a>
                <a class="nav-link" id="v-pills-request_price-tab" data-toggle="pill" href="#v-pills-request_price" role="tab" aria-controls="v-pills-request_price" aria-selected="false">Request Price</a>
                <a class="nav-link" id="v-pills-trade_in-tab" data-toggle="pill" href="#v-pills-trade_in" role="tab" aria-controls="v-pills-trade_in" aria-selected="false">Trade In</a>
                <a class="nav-link" id="v-pills-trade_offer-tab" data-toggle="pill" href="#v-pills-trade_offer" role="tab" aria-controls="v-pills-trade_offer" aria-selected="false">Trade Offer</a>
                <a class="nav-link" id="v-pills-add_a_car-tab" data-toggle="pill" href="#v-pills-add_a_car" role="tab" aria-controls="v-pills-add_a_car" aria-selected="false">Add a Car</a>
                <a class="nav-link" id="v-pills-update_a_car-tab" data-toggle="pill" href="#v-pills-update_a_car" role="tab" aria-controls="v-pills-update_a_car" aria-selected="false">Update a Car</a>
                <a class="nav-link" id="v-pills-update_a_car_ppl-tab" data-toggle="pill" href="#v-pills-update_a_car_ppl" role="tab" aria-controls="v-pills-update_a_car_ppl" aria-selected="false">Update a Pay Per Listing</a>
                <a class="nav-link" id="v-pills-report_review-tab" data-toggle="pill" href="#v-pills-report_review" role="tab" aria-controls="v-pills-report_review" aria-selected="false">Report Review</a>
                <a class="nav-link" id="v-pills-pay_per_listing-tab" data-toggle="pill" href="#v-pills-pay_per_listing" role="tab" aria-controls="v-pills-pay_per_listing" aria-selected="false">Pay Per Listing</a>
                <a class="nav-link" id="v-pills-value_my_car-tab" data-toggle="pill" href="#v-pills-value_my_car" role="tab" aria-controls="v-pills-value_my_car" aria-selected="false">Value My Car</a>
            </div>
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="v-pills-welcome" role="tabpanel" aria-labelledby="v-pills-home-tab">
                    <?php get_template_part($templatePart . '/welcome_form'); ?>
                </div>
                <div class="tab-pane fade" id="v-pills-new_user" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                    <?php get_template_part($templatePart . '/new_user_form'); ?>
                </div>
                <div class="tab-pane fade" id="v-pills-password_recovery" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                    <?php get_template_part($templatePart . '/password_recovery_form'); ?>
                </div>
                <div class="tab-pane fade" id="v-pills-request_for_a_dealer" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                    <?php get_template_part($templatePart . '/request_for_a_dealer_form'); ?>
                </div>
                <div class="tab-pane fade" id="v-pills-test_drive" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                    <?php get_template_part($templatePart . '/test_drive_form'); ?>
                </div>
                <div class="tab-pane fade" id="v-pills-request_price" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                    <?php get_template_part($templatePart . '/request_price_form'); ?>
                </div>
                <div class="tab-pane fade" id="v-pills-trade_in" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                    <?php get_template_part($templatePart . '/trade_in_form'); ?>
                </div>
                <div class="tab-pane fade" id="v-pills-trade_offer" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                    <?php get_template_part($templatePart . '/trade_offer_form'); ?>
                </div>
                <div class="tab-pane fade" id="v-pills-add_a_car" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                    <?php get_template_part($templatePart . '/add_a_car_form'); ?>
                </div>
                <div class="tab-pane fade" id="v-pills-update_a_car" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                    <?php get_template_part($templatePart . '/update_a_car_form'); ?>
                </div>
                <div class="tab-pane fade" id="v-pills-update_a_car_ppl" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                    <?php get_template_part($templatePart . '/update_a_car_ppl_form'); ?>
                </div>
                <div class="tab-pane fade" id="v-pills-report_review" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                    <?php get_template_part($templatePart . '/report_review_form'); ?>
                </div>
                <div class="tab-pane fade" id="v-pills-pay_per_listing" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                    <?php get_template_part($templatePart . '/pay_per_listing_form'); ?>
                </div>
                <div class="tab-pane fade" id="v-pills-value_my_car" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                    <?php get_template_part($templatePart . '/value_my_car_form'); ?>
                </div>
            </div>
        </div>
        <input type="hidden" name="update_email_templates" value="true" />
        <input type="submit" class="button-primary" value="Save changes" />
    </form>
</div>
