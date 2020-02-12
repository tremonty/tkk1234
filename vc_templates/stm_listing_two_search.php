<?php
wp_enqueue_script( 'stm-cascadingdropdown', get_template_directory_uri() . '/assets/js/jquery.cascadingdropdown.js', array( 'jquery' ), STM_THEME_VERSION );

stm_motors_enqueue_scripts_styles('stm_listing_two_search');

$atts = vc_map_get_attributes($this->getShortcode(), $atts);
extract($atts);
$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ' '));

if (isset($atts['items']) && strlen($atts['items']) > 0) {
    $items = vc_param_group_parse_atts($atts['items']);
    if (!is_array($items)) {
        $temp = explode(',', $atts['items']);
        $paramValues = array();
        foreach ($temp as $value) {
            $data = explode('|', $value);
            $newLine = array();
            $newLine['title'] = isset($data[0]) ? $data[0] : 0;
            $newLine['sub_title'] = isset($data[1]) ? $data[1] : '';
            if (isset($data[1]) && preg_match('/^\d{1,3}\%$/', $data[1])) {
                $colorIndex += 1;
                $newLine['title'] = (float)str_replace('%', '', $data[1]);
                $newLine['sub_title'] = isset($data[2]) ? $data[2] : '';
            }
            $paramValues[] = $newLine;
        }
        $atts['items'] = urlencode(json_encode($paramValues));
    }
}

$args = array('post_type' => stm_listings_post_type(), 'post_status' => 'publish', 'posts_per_page' => 1, 'suppress_filters' => 0);

if(is_listing()) {
    $args['meta_query'][] = array(
        'relation' => 'OR',
        array(
            'key' => 'car_mark_as_sold',
            'value' => '',
            'compare'  => 'NOT EXISTS'
        ),
        array(
            'key' => 'car_mark_as_sold',
            'value' => '',
            'compare'  => '='
        )
    );
}

$all = new WP_Query($args);
$all = $all->found_posts;

if(empty($show_amount)) {
    $show_amount = 'no';
}

$words = array();

if (!empty($select_prefix)) {
    $words['select_prefix'] = $select_prefix;
}

if (!empty($select_affix)) {
    $words['select_affix'] = $select_affix;
}

if (!empty($number_prefix)) {
    $words['number_prefix'] = $number_prefix;
}

if (!empty($number_affix)) {
    $words['number_affix'] = $number_affix;
}
?>

    <div class="stm_dynamic_listing_two_filter filter-listing stm-vc-ajax-filter animated fadeIn <?php echo esc_attr($css_class); ?>">
        <!-- Nav tabs -->
        <ul class="stm_dynamic_listing_filter_nav clearfix heading-font" role="tablist">
            <li role="presentation" class="active">
                <a href="#stm_first_tab" aria-controls="stm_first_tab" role="tab" data-toggle="tab">
                    <?php echo esc_attr($first_tab_label); ?>
                </a>
            </li>
            <li role="presentation">
                <a href="#stm_second_tab" aria-controls="stm_second_tab" role="tab" data-toggle="tab">
                    <?php echo esc_attr($second_tab_label); ?>
                </a>
            </li>
            <li role="presentation">
                <a href="#stm_third_tab" aria-controls="stm_third_tab" role="tab" data-toggle="tab">
                    <?php echo esc_attr($third_tab_label); ?>
                </a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="stm_first_tab">
                <form action="<?php echo esc_url(stm_get_listing_archive_link()); ?>" method="GET">
                    <div class="btn-wrap">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12 stm-select-col">
                                <?php if(count(explode(',', $first_tab_tax)) > 4) : ?>
                                    <div class="stm-more-options-wrap" data-tab="first">
                                <span>
                                    <?php echo esc_html__('Advanced search', 'motors'); ?>
                                    <i class="fa fa-angle-down"></i>
                                </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12 stm-select-col"><button type="submit" class="heading-font"><i class="fa fa-search"></i> <?php echo '<span>' . $all . '</span> ' . $search_button_postfix; ?></button></div>
                        </div>
                    </div>
                    <div class="stm-filter-tab-selects stm-filter-tab-selects-first filter stm-vc-ajax-filter">
                        <?php stm_listing_filter_get_selects($first_tab_tax, 'stm_all_listing_tab', $words, $show_amount, true); ?>
                    </div>
                </form>
            </div>
            <div role="tabpanel" class="tab-pane fade in" id="stm_second_tab">
                <form action="<?php echo esc_url(stm_review_archive_link()); ?>" method="GET">
                    <div class="btn-wrap">
                        <button type="submit" class="heading-font"><i class="fa fa-search"></i> <?php echo '<span>' . $all . '</span> ' . $search_button_postfix; ?></button>
                        <?php if(count(explode(',', $second_tab_tax)) > 4) : ?>
                            <div class="stm-more-options-wrap" data-tab="second">
                            <span>
                                <?php echo esc_html__('Advanced search', 'motors'); ?>
                                <i class="fa fa-angle-down"></i>
                            </span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="stm-filter-tab-selects stm-filter-tab-selects-second filter stm-vc-ajax-filter">
                        <?php stm_listing_filter_get_selects($second_tab_tax, 'stm_all_listing_tab', $words, false); ?>
                    </div>
                    <!--<input type="hidden" name="listing_type" value="with_review" />-->
                </form>
            </div>
            <div role="tabpanel" class="tab-pane fade in" id="stm_third_tab">
                <div class="stm-filter-tab-selects stm-filter-tab-selects-third filter stm-vc-ajax-filter">
                    <?php
                        if(!empty($third_tab_tax)) {
                            $html = '<form method="post" name="vmc-form" type="multipart/formdata">';
                            $html .= '<div class="row">';
                            $params = explode(',', $third_tab_tax);
                            $opt = stm_get_value_my_car_options();
                            $i = 0;

                            foreach ($params as $k) {
                                if($i == '4' && count($params) > 4) $html .= '<div class="stm-slide-content clearfix">';

                                if($k == 'photo') {
                                    $html .= '<div class="col-md-3 col-sm-6 col-xs-12 stm-select-col vmc-file-wrap">';
                                    $html .= '<div class="file-wrap"><div class="input-file-holder"><span>' . __("Add Image", 'motors') . '</span><i class="fa fa-plus"></i><input type="file"  name="' . $k . '" /></div><span class="error"></span></div>';
                                } else {
                                    $html .= '<div class="col-md-3 col-sm-6 col-xs-12 stm-select-col">';
                                    $html .= '<input type="text" name="' . $k . '" placeholder="' . array_search($k, $opt) . '" />';

                                }

                                $html .= '</div>';

                                if($i == (count($params) - 1) && count($params) > 4) $html .= '</div>';
                                $i++;
                            }
                            $html .= '</div>';
                            $html .= '</form>';

                            echo stm_do_lmth($html);
                        }
                    ?>
                </div>
                <div class="btn-wrap">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 stm-select-col">
                            <?php if(count(explode(',', $third_tab_tax)) > 4) : ?>
                                <div class="stm-more-options-wrap" data-tab="third">
                            <span>
                                <?php echo esc_html__('More Options', 'motors'); ?>
                                <i class="fa fa-angle-down"></i>
                            </span>
                                </div>
                        <?php endif; ?>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 stm-select-col">
                            <button id="vmc-btn" type="submit" class="heading-font"><?php echo esc_html($third_tab_label); ?><i class="fa fa-spinner"></i></button>
                            <?php
                            if(stm_is_use_plugin('stm-gdpr-compliance/stm-gdpr-compliance.php')) {
                                echo do_shortcode('[motors_gdpr_checkbox]');
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
$bind_tax = stm_data_binding(true);
if (!empty($bind_tax)):
    ?>

    <script>
        jQuery(function ($) {
            var options = <?php echo json_encode( $bind_tax ); ?>, show_amount = <?php echo json_encode( $show_amount != 'no' ) ?>;

            if (show_amount) {
                $.each(options, function (tax, data) {
                    $.each(data.options, function (val, option) {
                        option.label += ' (' + option.count + ')';
                    });
                });
            }

            $('.stm-filter-tab-selects.filter').each(function () {
                new STMCascadingSelect(this, options);
            });

            $("select[data-class='stm_select_overflowed']").on("change", function () {
                var sel = $(this);
                var selValue = sel.val();
                var selType = sel.attr("data-sel-type");
                var min = 'min_' + selType;
                var max = 'max_' + selType;
                if (selValue.includes("<")) {
                    var str = selValue.replace("<", "").trim();
                    $("input[name='" + min + "']").val("");
                    $("input[name='" + max + "']").val(str);
                } else if (selValue.includes("-")) {
                    var strSplit = selValue.split("-");
                    $("input[name='" + min + "']").val(strSplit[0]);
                    $("input[name='" + max + "']").val(strSplit[1]);
                } else {
                    var str = selValue.replace(">", "").trim();
                    $("input[name='" + min + "']").val(str);
                    $("input[name='" + max + "']").val("");
                }
            });
        });
    </script>
<?php endif; ?>