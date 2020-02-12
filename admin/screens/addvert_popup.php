<?php
if(!get_option('showed_addv_popup', false)) {
    add_action( 'admin_head', 'stm_addvert_popup_ss' );
    add_action( 'admin_footer', 'stm_show_addvert_popup' );
}

function stm_addvert_popup_ss()
{
    ?>
    <style>
        .stm-addvert-popup {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            justify-content: center;
            align-items: flex-start;
            height: 100vh;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            padding-top: 120px;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999999;
        }

        .stm-addvert-popup .stm-addvert-img-wrap {
            display: block;
            position: relative;
            width: 830px;
            margin-left: -200px;
        }

        .stm-addvert-popup .stm-addvert-img-wrap a {
            text-decoration: none;
        }

        .stm-addvert-popup .stm-addvert-img-wrap img {
            display: block;
            width: 100%;
            object-fit: contain;
            position: relative;
            transform: translateZ(0);
        }

        .stm-addvert-img-wrap .stm-addvert-close {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            background: #3253f9;
            border-radius: 50%;
            position: absolute;
            top: 51px;
            right: 3px;
        }

        .stm-addvert-close .dashicons-no-alt {
            cursor: pointer;
            font-size: 24px;
            color: #ffffff;
            margin: 0;
            padding: 0;
            width: 24px;
            height: 24px;
        }
    </style>
    <?php
}

function stm_show_addvert_popup()
{
    ?>
    <div class="stm-addvert-popup">
        <div class="stm-addvert-img-wrap">
            <a href="https://stylemixthemes.com/plugins/motors-car-dealership-classified-listings-mobile-app-for-android-ios/" target="_blank">
                <img src="http://motors.stylemixthemes.com/landing-app/img/app_banner.png"/>
            </a>
            <div class="stm-addvert-close">
                <span class="dashicons dashicons-no-alt"></span>
            </div>
        </div>
    </div>
    <script>
        jQuery('.stm-addvert-close').on('click', function () {
            jQuery.ajax({
                url: ajaxurl,
                dataType: 'json',
                context: this,
                data: {
                    'action': 'close_addv_popup',
                    'security': closeAddvPopup
                },
                beforeSend: function () {
                    jQuery('.stm-addvert-popup').hide();
                },
                success: function (data) {
                }
            })
        });
    </script>
    <?php
}

add_action( 'wp_ajax_close_addv_popup', 'close_addv_popup' );
function close_addv_popup()
{
    check_ajax_referer( 'motors_add_popup', 'security' );
    wp_send_json( array( 'code' => 200, 'responce' => update_option('showed_addv_popup', true) ) );
    exit;
}
