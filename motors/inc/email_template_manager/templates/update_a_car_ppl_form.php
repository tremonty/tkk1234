<?php
$val = (get_option('update_a_car_ppl_template', '') != '') ? stripslashes(get_option('update_a_car_ppl_template', '')) :
    '<table>
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

$subject = (get_option('update_a_car_ppl_subject', '') != '') ? get_option('update_a_car_ppl_subject', '') : 'Car Updated';
?>
<div class="etm-single-form">
    <h3>Update A Car Template</h3>
    <input type="text" name="update_a_car_ppl_subject" value="<?php echo esc_html($subject); ?>" class="full_width" />
    <div class="lr-wrap">
        <div class="left">
            <?php
            $sc_arg = array(
                'textarea_rows' => apply_filters( 'etm-aac-sce-row', 10 ),
                'wpautop' => true,
                'media_buttons' => apply_filters( 'etm-aac-sce-media_buttons', false ),
                'tinymce' => apply_filters( 'etm-aac-sce-tinymce', true ),
            );

            wp_editor( $val, 'update_a_car_ppl_template', $sc_arg );
            ?>
        </div>
        <div class="right">
            <h4>Shortcodes</h4>
            <ul>
                <?php
                foreach (getTemplateShortcodes('updateACarPPL') as $k => $val) {
                    echo "<li id='{$k}'><input type='text' value='{$val}' class='auto_select' /></li>";
                }
                ?>
            </ul>
        </div>
    </div>
</div>
