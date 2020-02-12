<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

stm_motors_enqueue_scripts_styles( 'stm_inventory_with_filter' );

$css_class = ( !empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

$filterBy = explode( ',', $atts['filter_all'] );

$isPageInventory = (get_the_ID() == get_theme_mod( 'listing_archive', '' )) ? true : false;

?>
<div class="row stm_inventory_with_filter-wrap <?php if($isPageInventory) echo 'is_page_inventory'; ?>">
    <div class="col-md-3 col-sm-12 classic-filter-row sidebar-sm-mg-bt">
        <?php
        $data = array_filter( (array)get_option( 'stm_vehicle_listing_options' ) );
        $filter = array();

        foreach ( $data as $key => $_data ) {
            foreach ( $filterBy as $_val ) {
                if ( array_key_exists( 'slug', $_data ) && $_data['slug'] == $_val ) {
                    $filter['filters'][$_data['slug']] = $_data;
                }
            }
        }

        $_terms = get_terms( array(
            'taxonomy' => $filterBy,
            'hide_empty' => (get_theme_mod('hide_empty_category', false)) ? true : false,
            'update_term_meta_cache' => false,
        ) );

        $terms = array();

        foreach ( $_terms as $_term ) {
            if ( !empty( $_term ) ) $terms[$_term->taxonomy][$_term->slug] = $_term;
        }

        $filter['options'] = $terms;
        $selected_options = array();
        ?>
        <form action="<?php echo stm_listings_current_url() ?>" method="get" data-trigger="filter">
            <?php

            foreach ( $filter['filters'] as $checkbox ) {
                if ( $checkbox['slug'] != 'price' ) {

                    $listing_rows_numbers_default_expanded = 'false';
                    if ( isset( $checkbox['listing_rows_numbers_default_expanded'] ) AND $checkbox['listing_rows_numbers_default_expanded'] == 'open' ) {
                        $listing_rows_numbers_default_expanded = 'true';
                    }

                    if ( !empty( $_GET[$checkbox['slug']] ) ) {
                        $val = ( is_array( $_GET[$checkbox['slug']] ) ) ? $_GET[$checkbox['slug']][0] : $_GET[$checkbox['slug']];

                        $selected_options = sanitize_text_field( $val );
                        if ( !is_array( $selected_options ) ) {
                            $selected_options = array( '0' => $selected_options );
                        }
                    }

                    if ( !empty( $checkbox['enable_checkbox_button'] ) and $checkbox['enable_checkbox_button'] == 1 ) {
                        $stm_checkbox_ajax_button = 'stm-ajax-checkbox-button';
                    } else {
                        $stm_checkbox_ajax_button = 'stm-ajax-checkbox-instant';
                    }
                    ?>

                    <?php
                    $terms_args = array(
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'hide_empty' => false,
                        'fields' => 'all',
                        'pad_counts' => true,
                    );
                    ?>
                    <div class="stm-accordion-single-unit stm-listing-directory-checkboxes <?php echo esc_attr( $stm_checkbox_ajax_button ) ?>">
                        <a class="title <?php echo ( esc_attr( $listing_rows_numbers_default_expanded ) == 'false' ) ? 'collapsed' : '' ?> "
                           data-toggle="collapse" href="#accordion-<?php echo esc_attr( $checkbox['slug'] ); ?>"
                           aria-expanded="<?php echo esc_attr( $listing_rows_numbers_default_expanded ); ?>">
                            <h5><?php esc_html_e( $checkbox['single_name'], 'motors' ); ?></h5>
                            <span class="minus"></span>
                        </a>
                        <div class="stm-accordion-content">
                            <div class="collapse content <?php echo ( esc_attr( $listing_rows_numbers_default_expanded ) == 'true' ) ? 'in' : '' ?>"
                                 id="accordion-<?php echo esc_attr( $checkbox['slug'] ); ?>">
                                <div class="stm-accordion-content-wrapper stm-accordion-content-padded">
                                    <div class="stm-accordion-inner">
                                        <?php
                                        $terms = get_terms( $checkbox['slug'], $terms_args );

                                        if ( !empty( $terms ) ) {
                                            foreach ( $terms as $term ) {
                                                ?>
                                                <label class="stm-option-label <?php if ( in_array( $term->slug, $selected_options ) ): ?>checked<?php endif; ?>"
                                                       data-taxonomy="stm-iwf-<?php echo esc_attr( $term->taxonomy ); ?>">
                                                    <input type="checkbox"
                                                           name="<?php echo esc_attr( $checkbox['slug'] ) ?>[]"
                                                           value="<?php echo esc_attr( $term->slug ); ?>"
                                                           <?php if ( in_array( $term->slug, $selected_options ) ): ?>checked<?php endif; ?>/>
                                                    <span class="heading-font"><?php echo esc_html( $term->name ); ?>
                                                        <span
                                                                class="count"
                                                                data-slug="stm-iwf-<?php echo esc_attr( $term->slug ); ?>">(<?php echo esc_html( $term->count ); ?>)</span></span>
                                                </label>
                                            <?php }
                                        }

                                        if ( !empty( $checkbox['enable_checkbox_button'] ) and $checkbox['enable_checkbox_button'] == 1 ): ?>
                                            <div class="clearfix"></div>
                                            <div class="stm-checkbox-submit">
                                                <a class="button"
                                                   href="#"><?php echo esc_html_e( 'Apply', 'motors' ); ?></a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                } else {
                    if(!empty($filter['options']) and !empty($filter['options']['price'])) {
                        stm_listings_load_template( 'filter/types/price', array(
                            'taxonomy' => 'price',
                            'options'  => $filter['options']['price']
                        ) );
                    }
                }
            }
            ?>
            <input type="hidden" id="stm_view_type" name="view_type" value="<?php echo esc_attr(stm_listings_input( 'view_type', get_theme_mod( "listing_view_type", "list" ) )); ?>"/>
            <input type="hidden" name="navigation_type" value="<?php echo esc_attr($navigation);?>" />
            <input type="hidden" name="posts_per_page" value="<?php echo esc_attr($posts_per_page);?>" />
            <input type="hidden" name="sort_order" value="<?php echo esc_attr( stm_listings_input( 'sort_order' ) ); ?>"/>
        </form>
    </div>

    <div class="col-md-9 col-sm-12">

        <div class="stm-ajax-row">
            <div class="stm-action-wrap">
                <?php if($isPageInventory) : ?>
                    <div class="showing heading-font">
                    <?php printf(__('<b>Showing <span class="ac-showing">%s</span> jets</b> from <span class="ac-total">%s</span>', 'motors'), $posts_per_page, 0); ?>
                    </div>
                <?php else:?>
                    <h2><?php echo esc_html( $inventory_title ); ?></h2>
                <?php endif;?>
                <?php stm_listings_load_template( 'filter/actions' ); ?>
            </div>
            <div id="listings-result">
                <?php stm_listings_load_results( array( 'posts_per_page' => $posts_per_page ), null, $navigation ); ?>
            </div>
        </div>

    </div> <!--col-md-9-->
</div>