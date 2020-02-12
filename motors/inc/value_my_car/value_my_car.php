<?php
function adminEnqueueScriptsStyles () {
    wp_enqueue_style( 'stm-admin-vmc-style', get_template_directory_uri() . '/inc/value_my_car/assets/css/admin-vmc-style.css', null, round(0,10000), 'all' );
    wp_enqueue_script( 'stm-admin-vmc-script', get_stylesheet_directory_uri() . '/inc/value_my_car/assets/js/admin-vmc.js', 'jquery', round(0,10000));
}

add_action( 'admin_enqueue_scripts', 'adminEnqueueScriptsStyles' );

function enqueueScriptStyles () {
    $directoryStylesheet = get_template_directory_uri();

    wp_enqueue_style( 'stm-vmc', $directoryStylesheet . '/inc/value_my_car/assets/css/vmc-style.css', null, '1.0', 'all' );
    wp_enqueue_script( 'stm-vmc-script', $directoryStylesheet . '/inc/value_my_car/assets/js/vmc.js', 'jquery', '1.0', true );
}

if ( ! is_admin() ) {
    add_action( 'wp_enqueue_scripts', 'enqueueScriptStyles' );
}

function addVMCMenu () {
    $title =  esc_html__('Value My Car', 'motors');

    add_menu_page( $title, $title, 'administrator', 'value-my-car', 'vmcTemplateView' , '', 50);
}
add_action('admin_menu', 'addVMCMenu');

function vmcTemplateView() {
    get_template_part('inc/value_my_car/admin-page');
}

function stm_ajax_value_my_car () {

    check_ajax_referer('stm_ajax_value_my_car', 'security');

    $responce = array();

    if(isset($_POST['motors-gdpr-agree']) && $_POST['motors-gdpr-agree'] == 'agree') {
        //email, phone,make, model, year, mileage, vin, photos
        if (!empty($_POST['make']) && !empty($_POST['model']) && !empty($_POST['email']) && !empty($_POST['phone'])) {

            $opt = stm_get_value_my_car_options();
            $postTitle = '';
            $postContent = '<table>';

            foreach ($_POST as $k => $val) {
                if (!empty($val) && $k != 'action') {
                    if ($k == 'make') {
                        $postTitle .= $val;
                    } elseif ($k == 'model') {
                        $postTitle .= ' ' . $val;
                    } else {
                        $postContent .= '<tr><td><b>' . array_search($k, $opt) . '</b> </td><td> - ' . $val . '</td></tr>';
                    }
                }
            }

            $postContent .= "</table>";

            $args = array(
                'post_author' => 1,
                'post_title' => $postTitle,
                'post_content' => $postContent,
                'post_status' => 'pending',
                'post_type' => 'car_value'

            );

            $postId = wp_insert_post($args);

            if (!empty($_POST['email'])) update_post_meta($postId, 'vmc_email', $_POST['email']);
            if (!empty($_POST['phone'])) update_post_meta($postId, 'vmc_phone', $_POST['phone']);

            if ($postId != 0 && count($_FILES) > 0) {
                $responce['status'] = 'success';
                $responce['msg'] = esc_html__('Thanks for your request, we will contact you as soon as we review you car.', 'motors');
            } else {
                $responce['status'] = 'error';
                $responce['msg'] = esc_html__('Error', 'motors');
            }
        } else {
            $responce['status'] = 'error';
            $responce['msg'] = esc_html__('Please enter required fields', 'motors');
        }
    } else {
        $gdpr = get_option('stm_gdpr_compliance', '');
        $ppLink = ($gdpr['stmgdpr_privacy'][0]['privacy_page'] != 0) ? get_the_permalink($gdpr['stmgdpr_privacy'][0]['privacy_page']) : '';
        $ppLinkText = (!empty($gdpr) && !empty($gdpr['stmgdpr_privacy'][0]['link_text'])) ? $gdpr['stmgdpr_privacy'][0]['link_text'] : '';
        $mess = sprintf(__("Providing consent to our <a href='%s'>%s</a> is necessary in order to use our services and products.", 'motors'), $ppLink, $ppLinkText);

        $responce['status'] = 'error';
        $responce['msg'] = $mess;
    }

    wp_send_json($responce);
    exit;
}

function uploadVMCPhotos($files, $parentId) {
    $files_approved = array();

    $_FILES = $files;

    foreach ($_FILES['files']['name'] as $f => $name) {
        $tmp_name = $_FILES['files']['tmp_name'][ $f ];
        $error = $_FILES['files']['error'][ $f ];
        $type = $_FILES['files']['type'][ $f ];
        $files_approved[$f] = compact('name', 'tmp_name', 'type', 'error');
    }

    require_once(ABSPATH . 'wp-admin/includes/image.php');

    $attachments_ids = array();

    foreach ($files_approved as $f => $file) {
        $uploaded = wp_handle_upload($file, array('test_form' => FALSE, 'action' => 'stm_ajax_add_a_car_media'));

        if ($uploaded['error']) {
            $response['errors'][ $file['name'] ] = $uploaded;
            continue;
        }

        $filetype = wp_check_filetype(basename($uploaded['file']), null);

        // Insert attachment to the database
        $attach_id = wp_insert_attachment(array(
            'guid' => $uploaded['url'],
            'post_mime_type' => $filetype['type'],
            'post_title' => preg_replace('/\.[^.]+$/', '', basename($uploaded['file'])),
            'post_content' => '',
            'post_status' => 'inherit',
        ), $uploaded['file'], $parentId);

        if($f == 0) {
            set_post_thumbnail($parentId, $attach_id);
        }

        $attachments_ids[$f] = $attach_id;
    }

    update_post_meta($parentId, 'vmc_gallery', $attachments_ids);

    do_action( 'stm_vmc_gallery_saved', $parentId, $attachments_ids );

    return esc_html__('Thanks for your request, we will contact you as soon as we review you car.', 'motors');
}

add_action('wp_ajax_stm_ajax_value_my_car', 'stm_ajax_value_my_car');
add_action('wp_ajax_nopriv_stm_ajax_value_my_car', 'stm_ajax_value_my_car');

function stm_ajax_get_file_size () {

    check_ajax_referer('stm_ajax_get_file_size', 'security');

    echo stm_get_filesize($_FILES['photo']['tmp_name']);
    exit;
}

add_action('wp_ajax_stm_ajax_get_file_size', 'stm_ajax_get_file_size');
add_action('wp_ajax_nopriv_stm_ajax_get_file_size', 'stm_ajax_get_file_size');

function stm_get_filesize($file) {
    $bytes = filesize($file);
    return $bytes;
}

function vmc_send_mess($postId, $status) {
    $userEmail = get_post_meta($postId, '');
}

function stm_ajax_set_vmc_status() {

    check_ajax_referer('stm_ajax_set_vmc_status', 'security');

    if($_POST['status'] == 'declined') {
        wp_trash_post($_POST['post_id']);
    }

    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
    $wp_email = 'wordpress@' . preg_replace('#^www\.#', '', strtolower(apply_filters('stm_get_global_server_val', 'SERVER_NAME')));
    $headers[] = 'From: ' . $blogname . ' <' . $wp_email . '>' . "\r\n";

    $args = array (
        'car' => $_POST['vmc-car'],
        'email' => $_POST['vmc-email']
    );

    $subject = generateSubjectView('value_my_car_reject', $args);
    $body = generateTemplateView('value_my_car_reject', $args);

    do_action('stm_wp_mail', $_POST['vmc-email'], $subject, nl2br($body), $headers);

    update_post_meta($_POST['post_id'], 'vmc_status', $_POST['status']);
    exit;
}

add_action('wp_ajax_stm_ajax_set_vmc_status', 'stm_ajax_set_vmc_status');
add_action('wp_ajax_nopriv_stm_ajax_set_vmc_status', 'stm_ajax_set_vmc_status');

function stm_ajax_send_vmc_reply() {

    check_ajax_referer('stm_ajax_send_vmc_reply', 'security');
    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
    $wp_email = 'wordpress@' . preg_replace('#^www\.#', '', strtolower(apply_filters('stm_get_global_server_val', 'SERVER_NAME')));
    $headers[] = 'From: ' . $blogname . ' <' . $wp_email . '>' . "\r\n";

    $args = array (
        'car' => $_POST['vmc-car'],
        'email' => $_POST['vmc-email'],
        'price' => $_POST['vmc-price']
    );

    $subject = generateSubjectView('value_my_car', $args);
    $body = generateTemplateView('value_my_car', $args);

    do_action('stm_wp_mail', $_POST['vmc-email'], $subject, nl2br($body), $headers);

    $response['message'] = esc_html__( 'Reply was sent', 'motors' );
    wp_send_json($response);
}

add_action('wp_ajax_stm_ajax_send_vmc_reply', 'stm_ajax_send_vmc_reply');
add_action('wp_ajax_nopriv_stm_ajax_send_vmc_reply', 'stm_ajax_send_vmc_reply');