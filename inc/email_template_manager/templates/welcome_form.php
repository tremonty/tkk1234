<?php
$val = (get_option('welcome_template', '') != '') ? stripslashes(get_option('welcome_template', '')) :
    '<table>
        <tr>
            <td>Congratulations! You have been registered in our website with a username: </td>
            <td>[user_login]</td>
        </tr>
    </table>';

$subject = (get_option('welcome_subject', '') != '') ? get_option('welcome_subject', '') : 'Welcome';
?>
<div class="etm-single-form">
    <h3>Welcome Template</h3>
    <input type="text" name="welcome_subject" value="<?php echo esc_html($subject);?>" class="full_width" />
    <div class="lr-wrap">
        <div class="left">
            <?php
            $sc_arg = array(
                'textarea_rows' => apply_filters( 'etm-aac-sce-row', 10 ),
                'wpautop' => true,
                'media_buttons' => apply_filters( 'etm-aac-sce-media_buttons', false ),
                'tinymce' => apply_filters( 'etm-aac-sce-tinymce', true ),
            );

            wp_editor( $val, 'welcome_template', $sc_arg );
            ?>
        </div>
        <div class="right">
            <h4>Shortcodes</h4>
            <ul>
                <?php
                foreach (getTemplateShortcodes('welcome') as $k => $val) {
                    echo "<li id='{$k}'><input type='text' value='{$val}' class='auto_select' /></li>";
                }
                ?>
            </ul>
        </div>
    </div>
</div>
