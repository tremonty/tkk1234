<?php
$val = (get_option('request_for_a_dealer_template', '') != '') ? stripslashes(get_option('request_for_a_dealer_template', '')) :
    '<table>
        <tr>
            <td>User Login:</td>
            <td>[user_login]</td>
        </tr>
    </table>';

$subject = (get_option('request_for_a_dealer_subject', '') != '') ? get_option('request_for_a_dealer_subject', '') : 'Request for a dealer';
?>
<div class="etm-single-form">
    <h3>Request For A Dealer Template</h3>
    <input type="text" name="request_for_a_dealer_subject" value="<?php echo esc_html($subject);?>" class="full_width" />
    <div class="lr-wrap">
        <div class="left">
            <?php
            $sc_arg = array(
                'textarea_rows' => apply_filters( 'etm-aac-sce-row', 10 ),
                'wpautop' => true,
                'media_buttons' => apply_filters( 'etm-aac-sce-media_buttons', false ),
                'tinymce' => apply_filters( 'etm-aac-sce-tinymce', true ),
            );

            wp_editor( $val, 'request_for_a_dealer_template', $sc_arg );
            ?>
        </div>
        <div class="right">
            <h4>Shortcodes</h4>
            <ul>
                <?php
                foreach (getTemplateShortcodes('requestForADealer') as $k => $val) {
                    echo "<li id='{$k}'><input type='text' value='{$val}' class='auto_select' /></li>";
                }
                ?>
            </ul>
        </div>
    </div>
</div>
