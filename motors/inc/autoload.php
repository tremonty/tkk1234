<?php
$patched = get_transient( 'phone_number_patch' );
if ( $patched != 'passed' ) {
    $header_main_phone = get_theme_mod('header_main_phone');
    $header_secondary_phone_1 = get_theme_mod('header_secondary_phone_1');
    $header_secondary_phone_2 = get_theme_mod('header_secondary_phone_2');

    if ( $header_main_phone == '888-694-5544' ) {
        set_theme_mod('header_main_phone', '878-9671-4455');
    }
    if ( $header_secondary_phone_1 == '888-637-7262' ) {
        set_theme_mod('header_secondary_phone_1', '878-3971-3223');
    }
    if ( $header_secondary_phone_2 == '888-404-7008' ) {
        set_theme_mod('header_secondary_phone_2', '878-0910-0770');
    }

    set_transient( 'phone_number_patch', 'passed' );
}