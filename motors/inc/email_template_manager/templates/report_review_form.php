<?php
$val = (get_option('report_review_template', '') != '') ? stripslashes(get_option('report_review_template', '')) :
    '<table>
        <tr>
            <td colspan="2">Review with id: "[report_id]" was reported</td>
        </tr>
        <tr>
            <td>Review content</td>
            <td>[review_content]</td>
        </tr>
    </table>';

$subject = (get_option('report_review_subject', '') != '') ? get_option('report_review_subject', '') : 'Report review';
?>
<div class="etm-single-form">
    <h3>Report Review Template</h3>
    <input type="text" name="report_review_subject" value="<?php echo esc_html($subject);?>" class="full_width" />
    <div class="lr-wrap">
        <div class="left">
            <?php
            $sc_arg = array(
                'textarea_rows' => apply_filters( 'etm-aac-sce-row', 10 ),
                'wpautop' => true,
                'media_buttons' => apply_filters( 'etm-aac-sce-media_buttons', false ),
                'tinymce' => apply_filters( 'etm-aac-sce-tinymce', true ),
            );

            wp_editor( $val, 'report_review_template', $sc_arg );
            ?>
        </div>
        <div class="right">
            <h4>Shortcodes</h4>
            <ul>
                <?php
                foreach (getTemplateShortcodes('reportReview') as $k => $val) {
                    echo "<li id='{$k}'><input type='text' value='{$val}' class='auto_select' /></li>";
                }
                ?>
            </ul>
        </div>
    </div>
</div>
