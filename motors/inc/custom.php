<?php
// Add svg support
function stm_svg_mime( $mimes )
{
    $mimes['ico'] = 'image/icon';
    $mimes['svg'] = 'image/svg+xml';
    $mimes['xml'] = 'application/xml';

    return $mimes;
}

add_filter( 'upload_mimes', 'stm_svg_mime', 100 );

if ( stm_is_auto_parts() ) {
    add_filter( 'stm_hb_icons_set', 'stm_motors_icons_set', 100, 1 );
    add_filter( 'stm_hb_elements', 'stm_motors_hb_elements', 100 );
}

function stm_motors_hb_elements( $elements )
{
    $elements[] = array(
        'label' => 'Icon Box 2',
        'type' => 'text',
        'icon' => 'icon-magnifier',
        'view_template' => 'icon-box-2',
        'settings_template' => 'hb_templates/modals/icon-box-2'
    );

    $elements[] = array(
        'label' => 'Cart 2',
        'type' => 'text',
        'icon' => 'icon-cart',
        'view_template' => 'cart-2',
        'settings_template' => 'hb_templates/modals/cart-2'
    );

    $elements[] = array(
        'label' => 'Text Link',
        'type' => 'text',
        'icon' => 'icon-cart',
        'view_template' => 'text-link',
        'settings_template' => 'hb_templates/modals/text-link'
    );

    $elements[] = array(
        'label' => 'Multi Currency Select',
        'type' => 'text',
        'icon' => 'icon-cart',
        'view_template' => 'multi-currency',
        'settings_template' => 'hb_templates/modals/multi-currency'
    );
    return $elements;
}

function stm_motors_icons_set( $icons )
{
    global $wp_filesystem;

    if ( empty( $wp_filesystem ) ) {
        require_once ABSPATH . '/wp-admin/includes/file.php';
        WP_Filesystem();
    }

    $json_file = get_theme_file_path() . '/assets/fonts/auto-parts-font/selection.json';
    $custom_icons_json = json_decode( $wp_filesystem->get_contents( $json_file ), true );
    $custom_icons = array();

    if ( !empty( $custom_icons_json ) ) {
        $set_name = $custom_icons_json['metadata']['name'];
        $set_prefix = $custom_icons_json['preferences']['fontPref']['prefix'];
        foreach ( $custom_icons_json['icons'] as $icon ) {
            $custom_icons[] = $set_prefix . $icon['properties']['name'];
        }

        if ( !empty( $custom_icons ) ) {
            $icons['stm-icon'] = $custom_icons;
        }
    }

    return $icons;
}

function stm_change_l()
{
    if ( isset( $_GET['change_lay'] ) ) {
        update_option( 'stm_motors_chosen_template', $_GET['change_lay'] );
    }
}

stm_change_l();

// Comments
if ( !function_exists( 'stm_comment' ) ) {
    function stm_comment( $comment, $args, $depth )
    {
        $GLOBALS['comment'] = $comment;
        extract( $args, EXTR_SKIP );

        if ( 'div' == $args['style'] ) {
            $tag = 'div ';
            $add_below = 'comment';
        } else {
            $tag = 'li ';
            $add_below = 'div-comment';
        }
        ?>
        <<?php echo esc_attr( $tag ) ?><?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
        <?php if ( 'div' != $args['style'] ) { ?>
        <div id="div-comment-<?php comment_ID() ?>" class="comment-body clearfix">
    <?php } ?>
        <?php if ( $args['avatar_size'] != 0 ) { ?>
        <div class="comment-avatar">
            <?php echo get_avatar( $comment, 80 ); ?>
        </div>
    <?php } ?>
        <div class="comment-info-wrapper">
            <div class="comment-info">
                <div class="clearfix">
                    <div class="comment-author pull-left"><span
                                class="h5"><?php echo get_comment_author_link(); ?></span></div>
                    <div class="comment-meta commentmetadata pull-right">
                        <a class="comment-date"
                           href="<?php echo esc_url( htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ); ?>">
                            <?php printf( __( '%1$s', 'motors' ), get_comment_date() ); ?>
                        </a>
                        <span class="comment-meta-data-unit">
							<?php comment_reply_link( array_merge( $args, array(
                                'reply_text' => __( '<span class="comment-divider">/</span><i class="fa fa-reply"></i> Reply', 'motors' ),
                                'add_below' => $add_below,
                                'depth' => $depth,
                                'max_depth' => $args['max_depth']
                            ) ) ); ?>
						</span>
                        <span class="comment-meta-data-unit">
							<?php edit_comment_link( __( '<span class="comment-divider">/</span><i class="fa fa-pencil-square-o"></i> Edit', 'motors' ), '  ', '' ); ?>
						</span>
                    </div>
                </div>
                <?php if ( $comment->comment_approved == '0' ) { ?>
                    <em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'motors' ); ?></em>
                <?php } ?>
            </div>
            <div class="comment-text">
                <?php comment_text(); ?>
            </div>
        </div>

        <?php if ( 'div' != $args['style'] ) { ?>
        </div>
    <?php } ?>
        <?php
    }
}


add_filter( 'comment_form_default_fields', 'stm_bootstrap3_comment_form_fields' );

if ( !function_exists( 'stm_bootstrap3_comment_form_fields' ) ) {
    function stm_bootstrap3_comment_form_fields( $fields )
    {
        $commenter = wp_get_current_commenter();
        $req = get_option( 'require_name_email' );
        $aria_req = ( $req ? " aria-required='true'" : '' );
        $html5 = current_theme_supports( 'html5', 'comment-form' ) ? 1 : 0;
        $fields = array(
            'author' => '<div class="row stm-row-comments">
							<div class="col-md-4 col-sm-4 col-xs-12">
								<div class="form-group comment-form-author">
			            			<input placeholder="' . sprintf( esc_attr__( 'Name %s', 'motors' ), ( $req ? '*' : '' ) ) . '" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' />
		                        </div>
		                    </div>',
            'email' => '<div class="col-md-4 col-sm-4 col-xs-12">
							<div class="form-group comment-form-email">
								<input placeholder="' . sprintf( esc_attr__( 'E-mail %s', 'motors' ), ( $req ? '*' : '' ) ) . '" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' />
							</div>
						</div>',
            'url' => '<div class="col-md-4 col-sm-4 col-xs-12">
						<div class="form-group comment-form-url">
							<input placeholder="' . esc_attr__( 'Website', 'motors' ) . '" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" />
						</div>
					</div></div>'
        );

        return $fields;
    }
}

add_filter( 'comment_form_defaults', 'stm_bootstrap3_comment_form' );

if ( !function_exists( 'stm_bootstrap3_comment_form' ) ) {
    function stm_bootstrap3_comment_form( $args )
    {
        $args['comment_field'] = '<div class="form-group comment-form-comment">
			 <textarea placeholder="' . esc_attr__( 'Message *', 'motors' ) . '" name="comment" rows="9" aria-required="true"></textarea>
	    </div>';

        return $args;
    }
}

if ( !function_exists( 'stm_body_class' ) ) {
    function stm_body_class( $classes )
    {
        $macintosh = ( !empty( apply_filters( 'stm_get_global_server_val', "HTTP_USER_AGENT" ) ) ) ? strpos( apply_filters( 'stm_get_global_server_val', "HTTP_USER_AGENT" ), 'Macintosh' ) ? true : false : false;
        global $wp_customize;

        if ( $macintosh ) {
            $classes[] = 'stm-macintosh';
        }

        $boxed = get_theme_mod( 'site_boxed', false );
        $bg_image = get_theme_mod( 'bg_image', false );

        if ( $boxed ) {
            $classes[] = 'stm-boxed';
            if ( $bg_image ) {
                $classes[] = $bg_image;
            }
        }

        $frontend_customizer = get_theme_mod( 'frontend_customizer', false );
        if ( $frontend_customizer ) {
            $classes[] = 'stm_frontend_customizer';
        }

        // Layout class
        $layout = stm_get_current_layout();


        if ( empty( $layout ) ) {
            $class = "";
            switch ( get_current_blog_id() ) {
                case 1:
                    $class = 'car_dealer';
                    break;
                case 2:
                    $class = 'listing';
                    break;
                case 4:
                    $class = 'boats';
                    break;
                case 5:
                    $class = 'motorcycle';
                    break;
                case 7:
                    $class = 'car_rental';
                    break;
                case 8:
                    $class = 'car_magazine';
                    break;
                case 9:
                    $class = 'car_dealer_two';
                    break;
                case 10:
                    $class = 'listing_two';
                    break;
                case 11:
                    $class = 'listing_three';
                    break;
            }
            $layout = $class;
        }

        if ( ( $layout == 'car_magazine' && is_singular( 'post' ) && !is_page_template( 'single-interview.php' ) ) || ( $layout == 'car_magazine' && is_category() ) ) {
            $classes[] = 'no_margin';
        }

        if ( $layout == 'car_dealer_two' ) {
            global $wp_query;

            $inventoryClass = ( is_singular( array( stm_listings_post_type() ) ) ||
                is_post_type_archive( stm_listings_post_type() ) ||
                ( $wp_query->post->ID == get_theme_mod( 'listing_archive', '' ) ) ) ? ' inventory-' . get_theme_mod( 'inventory_layout', 'dark' ) : '';


            $classes[] = 'no_margin' . $inventoryClass;

            $show_title_box = get_post_meta( get_the_ID(), 'title', true );

            if ( get_option( 'page_on_front' ) != get_the_ID() && get_option( 'page_for_posts' ) != get_the_ID() && $show_title_box == 'hide' ) {
                $classes[] = 'title-box-hide';
            }

            if ( is_singular( array( stm_listings_post_type() ) ) && $show_title_box != 'hide' ) {
                $classes[] = 'single-listing-title-box-show';
            }
        }

        $classes[] = 'stm-template-' . $layout;

        if ( is_singular( stm_listings_post_type() ) ) {
            global $post;
            $has_id = get_post_meta( $post->ID, 'automanager_id', true );
            if ( !empty( $has_id ) ) {
                $classes[] = 'automanager-listing-page';
            }
        }

        if ( !is_user_logged_in() ) {
            $classes[] = 'stm-user-not-logged-in';
        }


        if ( !empty( apply_filters( 'stm_get_global_server_val', 'HTTP_USER_AGENT' ) ) ) {
            $agent = apply_filters( 'stm_get_global_server_val', 'HTTP_USER_AGENT' );
            if ( strlen( strstr( $agent, 'Firefox' ) ) > 0 ) {
                $classes[] = 'stm-firefox';
            }
        }

        if ( isset( $wp_customize ) ) {
            $classes[] = 'stm-customize-page';
            $classes[] = 'stm-customize-layout-' . $layout;
        }

        if ( stm_is_boats() ) {
            global $post;
            if ( !empty( $post->ID ) ) {
                $transparent = get_post_meta( $post->ID, 'transparent_header', true );
                if ( !empty( $transparent ) and $transparent == 'on' ) {
                    $transparent = 'stm-boats-transparent';
                } else {
                    $transparent = 'stm-boats-default';
                }
                $classes[] = $transparent;
            }
        }

        if ( stm_is_listing() ) {
            $fixed_header = get_theme_mod( 'header_sticky', true );
            if ( !$fixed_header ) {
                $classes[] = 'header-listing-mobile-unfixed';
            }
        }

        if ( !get_theme_mod( 'header_compare_show', true ) ) {
            $classes[] = 'header_remove_compare';
        }

        if ( !get_theme_mod( 'header_cart_show', true ) ) {
            $classes[] = 'header_remove_cart';
        }

        if ( stm_is_rental() and is_cart() ) {
            $classes[] = 'woocommerce';
        }

        if ( stm_is_rental() and is_page( get_theme_mod( 'rental_datepick', false ) ) ) {
            $classes[] = 'stm-template-rental-daypicker-page';
        }

        if ( stm_is_auto_parts() && get_theme_mod( 'enable_preloader', false ) ) {

            $classes[] = 'stm-preloader';
        }

        if( stm_is_aircrafts() ){
            global $wp_query;

            if(!empty($wp_query->post) && $wp_query->post->ID == get_theme_mod( 'listing_archive', '' )) $classes[] = 'stm-inventory-page';
        }

        return $classes;
    }
}

add_filter( 'body_class', 'stm_body_class' );

add_filter( 'language_attributes', 'stm_preloader_html_class' );

function stm_preloader_html_class( $output )
{
    $enable_preloader = get_theme_mod( 'enable_preloader', false );

    $preloader_class = '';

    if ( $enable_preloader ) {
        $preloader_class = ' class="stm-site-preloader"';
        if ( stm_is_rental() ) {
            $preloader_class = ' class="stm-site-preloader stm-site-preloader-anim"';

            if ( get_option( 'woocommerce_myaccount_page_id' ) == get_the_ID() && is_user_logged_in() ) {
                $preloader_class = "";
            }
        }
    }

    return $output . $preloader_class;
}

if ( !function_exists( 'stm_print_styles' ) ) {
    function stm_print_styles()
    {
        $site_css = get_theme_mod( 'custom_css' );
        if ( $site_css ) {
            $site_css = preg_replace( '/\s+/', ' ', $site_css );
        }
        wp_add_inline_style( 'stm-theme-style', $site_css );
    }
}

add_action( 'wp_enqueue_scripts', 'stm_print_styles' );

//Hex to rgba
if ( !function_exists( 'stm_hex2rgb' ) ) {
    function stm_hex2rgb( $colour )
    {
        if ( !empty( $colour[0] ) && $colour[0] == '#' ) {
            $colour = substr( $colour, 1 );
        }
        if ( strlen( $colour ) == 6 ) {
            list( $r, $g, $b ) = array( $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5] );
        } elseif ( strlen( $colour ) == 3 ) {
            list( $r, $g, $b ) = array( $colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2] );
        } else {
            return false;
        }
        $r = hexdec( $r );
        $g = hexdec( $g );
        $b = hexdec( $b );

        return $r . ',' . $g . ',' . $b;
    }
}


//Limit content by chars
if ( !function_exists( 'stm_limit_content' ) ) {
    function stm_limit_content( $limit )
    {
        $content = explode( ' ', get_the_content(), $limit );
        if ( count( $content ) >= $limit ) {
            array_pop( $content );
            $content = implode( " ", $content ) . '...';
        } else {
            $content = implode( " ", $content );
        }
        $content = preg_replace( '/\[.+\]/', '', $content );
        $content = apply_filters( 'the_content', $content );
        $content = str_replace( ']]>', ']]&gt;', $content );

        return $content;
    }
}

//Get socials
if ( !function_exists( 'stm_get_header_socials' ) ) {
    function stm_get_header_socials( $socials_pos = 'header_socials_enable' )
    {
        $socials_array = array();

        $header_socials_enable = get_theme_mod( $socials_pos );
        $header_socials_enable = explode( ',', $header_socials_enable );

        $socials = get_theme_mod( 'socials_link' );
        $socials_values = array();
        if ( !empty( $socials ) ) {
            parse_str( $socials, $socials_values );
        }

        if ( $header_socials_enable ) {
            foreach ( $header_socials_enable as $social ) {
                if ( !empty( $socials_values[$social] ) ) {
                    $socials_array[$social] = $socials_values[$social];
                }
            }
        }

        return $socials_array;
    }
}

//Sidebar layout
if ( !function_exists( 'stm_sidebar_layout_mode' ) ) {
    function stm_sidebar_layout_mode( $position = 'left', $sidebar_id = false )
    {
        $content_before = $content_after = $sidebar_before = $sidebar_after = $show_title = $default_row = $default_col = '';

        if ( get_post_type() == 'post' ) {
            if ( !empty( $_GET['show-title-box'] ) and $_GET['show-title-box'] == 'hide' ) {
                $blog_archive_id = get_option( 'page_for_posts' );
                if ( !empty( $blog_archive_id ) ) {

                    $get_the_title = get_the_title( $blog_archive_id );

                    if ( !empty( $get_the_title ) ) {
                        $show_title = '<h2 class="stm-blog-main-title">' . $get_the_title . '</h2>';
                    }
                }
            }
        }

        if ( !$sidebar_id ) {
            $content_before .= '<div class="col-md-12">';

            $content_after .= '</div>';

            $default_row = 3;
            $default_col = 'col-md-4 col-sm-4 col-xs-12';
        } else {
            if ( $position == 'right' ) {
                $content_before .= '<div class="col-md-9 col-sm-12 col-xs-12"><div class="sidebar-margin-top clearfix"></div>';
                $sidebar_before .= '<div class="col-md-3 hidden-sm hidden-xs">';

                $sidebar_after .= '</div>';
                $content_after .= '</div>';
            } elseif ( $position == 'left' ) {
                $content_before .= '<div class="col-md-9 col-md-push-3 col-sm-12"><div class="sidebar-margin-top clearfix"></div>';
                $sidebar_before .= '<div class="col-md-3 col-md-pull-9 hidden-sm hidden-xs">';

                $sidebar_after .= '</div>';
                $content_after .= '</div>';
            }
            $default_row = 2;
            $default_col = 'col-md-6 col-sm-6 col-xs-12';
        }

        $return = array();
        $return['content_before'] = $content_before;
        $return['content_after'] = $content_after;
        $return['sidebar_before'] = $sidebar_before;
        $return['sidebar_after'] = $sidebar_after;
        $return['show_title'] = $show_title;
        $return['default_row'] = $default_row;
        $return['default_col'] = $default_col;

        return $return;
    }
}

//Add empty gravatar
function stm_default_avatar( $avatar_defaults )
{
    $stm_avatar = get_template_directory_uri() . '/assets/images/gravataricon.png';
    $avatar_defaults[$stm_avatar] = esc_html__( 'Motors Theme Default', 'motors' );

    return $avatar_defaults;
}

add_filter( 'avatar_defaults', 'stm_default_avatar' );

//Crop title
if ( !function_exists( 'stm_trim_title' ) ) {
    function stm_trim_title( $number = 35, $after = '...' )
    {
        $response = '';
        $response = esc_attr( trim( preg_replace( '/\s+/', ' ', mb_substr( get_the_title(), 0, $number ) ) ) );
        if ( mb_strlen( get_the_title() ) > $number ) {
            $response .= esc_attr( $after );
        }

        return $response;
    }
}

//Get link
if ( !function_exists( 'stm_listings_user_defined_filter_page' ) ) {
    function stm_listings_user_defined_filter_page()
    {
        return apply_filters( 'stm_listings_inventory_page_id', get_theme_mod( 'listing_archive', false ) );
    }
}

if ( !function_exists( 'stm_get_listing_archive_link' ) ) {
    function stm_get_listing_archive_link()
    {
        $listing_link = stm_listings_user_defined_filter_page();

        if ( !empty( $listing_link ) ) {
            $listing_link = get_permalink( $listing_link );
        } else {
            $listing_link = get_post_type_archive_link( stm_listings_post_type() );
        }

        return $listing_link;
    }
}

//After crop chars
if ( !function_exists( 'stm_excerpt_more_new' ) ) {
    function stm_excerpt_more_new( $more )
    {
        return '...';
    }

    add_filter( 'excerpt_more', 'stm_excerpt_more_new' );
}

if ( !function_exists( 'stm_custom_pagination' ) ) {
    function stm_custom_pagination()
    {

        global $wp_query;
        $show_pagination = true;
        if ( !empty( $wp_query->found_posts ) and !empty( $wp_query->query_vars['posts_per_page'] ) ) {
            if ( $wp_query->found_posts <= $wp_query->query_vars['posts_per_page'] ) {
                $show_pagination = false;
            }
        }
        if ( $show_pagination ): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="stm-blog-pagination">
                        <?php if ( get_previous_posts_link() ) { ?>
                            <div class="stm-prev-next stm-prev-btn">
                                <?php previous_posts_link( '<i class="fa fa-angle-left"></i>' ); ?>
                            </div>
                        <?php } else { ?>
                            <div class="stm-prev-next stm-prev-btn disabled"><i class="fa fa-angle-left"></i></div>
                        <?php }

                        echo paginate_links( array(
                            'type' => 'list',
                            'prev_next' => false
                        ) );

                        if ( get_next_posts_link() ) { ?>
                            <div class="stm-prev-next stm-next-btn">
                                <?php next_posts_link( '<i class="fa fa-angle-right"></i>' ); ?>
                            </div>
                        <?php } else { ?>
                            <div class="stm-prev-next stm-next-btn disabled"><i class="fa fa-angle-right"></i></div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php endif;
    }
}

if ( !function_exists( 'stm_custom_prev_next' ) ) {
    function stm_custom_prev_next( $post_id )
    {
        global $post;

        $oldGlobal = $post;
        $post = get_post( $post_id );

        $next_post = get_next_post();
        $prev_post = get_previous_post();

        $post = $oldGlobal;

        $prevNextPosts = array();

        if ( !empty( $prev_post ) ) $prevNextPosts['prev'] = $prev_post;
        if ( !empty( $next_post ) ) $prevNextPosts['next'] = $next_post;

        return $prevNextPosts;
    }
}

// STM Updater
if ( !function_exists( 'stm_updater' ) ) {
    function stm_updater()
    {

        $envato_username = get_theme_mod( 'envato_username' );
        $envato_api_key = get_theme_mod( 'envato_api' );

        if ( !empty( $envato_username ) && !empty( $envato_api_key ) ) {
            $envato_username = trim( $envato_username );
            $envato_api_key = trim( $envato_api_key );
            if ( !empty( $envato_username ) && !empty( $envato_api_key ) ) {
                load_template( get_template_directory() . '/inc/updater/envato-theme-update.php' );

                if ( class_exists( 'Envato_Theme_Updater' ) ) {
                    Envato_Theme_Updater::init( $envato_username, $envato_api_key, 'StylemixThemes' );
                }
            }
        }
    }

    add_action( 'after_setup_theme', 'stm_updater' );
}

function stm_setup_listing_options()
{
    $stm_listings = array(
        1 => array(
            'single_name' => 'Condition',
            'plural_name' => 'Conditions',
            'slug' => 'condition',
            'font' => '',
            'numeric' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => false,
            'use_on_map_page' => true,
            'use_on_car_filter' => true,
            'use_on_car_modern_filter' => true,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
        ),
        2 => array(
            'single_name' => 'Body',
            'plural_name' => 'Bodies',
            'slug' => 'body',
            'font' => '',
            'numeric' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => true,
            'use_on_map_page' => false,
            'use_on_car_filter' => true,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
        ),
        3 => array(
            'single_name' => 'Make',
            'plural_name' => 'Makes',
            'slug' => 'make',
            'font' => '',
            'numeric' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => false,
            'use_on_map_page' => true,
            'use_on_car_filter' => true,
            'use_on_car_modern_filter' => true,
            'use_on_car_modern_filter_view_images' => true,
            'use_on_car_filter_links' => false,
        ),
        5 => array(
            'single_name' => 'Model',
            'plural_name' => 'Models',
            'slug' => 'serie',
            'font' => '',
            'numeric' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => false,
            'use_on_map_page' => true,
            'use_on_car_filter' => true,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
        ),
        6 => array(
            'single_name' => 'Mileage',
            'plural_name' => 'Mileages',
            'slug' => 'mileage',
            'font' => 'stm-icon-road',
            'numeric' => true,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => true,
            'use_on_car_archive_listing_page' => true,
            'use_on_single_car_page' => true,
            'use_on_map_page' => true,
            'use_on_car_filter' => false,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
        ),
        7 => array(
            'single_name' => 'Fuel type',
            'plural_name' => 'Fuel types',
            'slug' => 'fuel',
            'font' => 'stm-icon-fuel',
            'numeric' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => true,
            'use_on_single_car_page' => true,
            'use_on_map_page' => false,
            'use_on_car_filter' => false,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
        ),
        8 => array(
            'single_name' => 'Engine',
            'plural_name' => 'Engines',
            'slug' => 'engine',
            'font' => 'stm-icon-engine_fill',
            'numeric' => true,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => true,
            'use_on_single_car_page' => true,
            'use_on_map_page' => true,
            'use_on_car_filter' => false,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
        ),
        9 => array(
            'single_name' => 'Year',
            'plural_name' => 'Years',
            'slug' => 'ca-year',
            'font' => 'stm-icon-road',
            'numeric' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => true,
            'use_on_map_page' => true,
            'use_on_car_filter' => true,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
        ),
        10 => array(
            'single_name' => 'Price',
            'plural_name' => 'Prices',
            'slug' => 'price',
            'font' => '',
            'numeric' => true,
            'use_on_single_listing_page' => true,
            'use_on_car_listing_page' => true,
            'use_on_car_archive_listing_page' => true,
            'use_on_single_car_page' => true,
            'use_on_map_page' => true,
            'use_on_car_filter' => true,
            'use_on_car_modern_filter' => true,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
        ),
        11 => array(
            'single_name' => 'Fuel consumption',
            'plural_name' => 'Fuel consumptions',
            'slug' => 'fuel-consumption',
            'font' => 'stm-icon-fuel',
            'numeric' => true,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => true,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => false,
            'use_on_map_page' => false,
            'use_on_car_filter' => false,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
        ),
        12 => array(
            'single_name' => 'Transmission',
            'plural_name' => 'Transmission',
            'slug' => 'transmission',
            'font' => 'stm-icon-transmission_fill',
            'numeric' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => true,
            'use_on_car_archive_listing_page' => true,
            'use_on_single_car_page' => true,
            'use_on_map_page' => true,
            'use_on_car_filter' => true,
            'use_on_car_modern_filter' => true,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
        ),
        13 => array(
            'single_name' => 'Drive',
            'plural_name' => 'Drives',
            'slug' => 'drive',
            'font' => 'stm-icon-drive_2',
            'numeric' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => true,
            'use_on_single_car_page' => true,
            'use_on_map_page' => false,
            'use_on_car_filter' => false,
            'use_on_car_modern_filter' => true,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
        ),
        14 => array(
            'single_name' => 'Fuel economy',
            'plural_name' => 'Fuel economy',
            'slug' => 'fuel-economy',
            'font' => '',
            'numeric' => true,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => true,
            'use_on_map_page' => false,
            'use_on_car_filter' => false,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
        ),
        15 => array(
            'single_name' => 'Exterior Color',
            'plural_name' => 'Exterior Colors',
            'slug' => 'exterior-color',
            'font' => '',
            'numeric' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => true,
            'use_on_map_page' => false,
            'use_on_car_filter' => true,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
        ),
        16 => array(
            'single_name' => 'Interior Color',
            'plural_name' => 'Interior Colors',
            'slug' => 'interior-color',
            'font' => '',
            'numeric' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => true,
            'use_on_map_page' => false,
            'use_on_car_filter' => true,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
        ),
    );
    if ( !get_option( 'stm_vehicle_listing_options' ) ) {
        update_option( 'stm_vehicle_listing_options', $stm_listings );
    }
}

add_action( 'after_switch_theme', 'stm_setup_listing_options' );
add_action( 'load-themes.php', 'stm_setup_listing_options' );

// After import hook and add menu, home page. slider, blog page
if ( !function_exists( 'stm_importer_done_function' ) ) {
    function stm_importer_done_function( $layout )
    {
        global $wp_filesystem;

        if ( stm_is_auto_parts() ) {
            if ( class_exists( 'ClassWCMAPSearchFilter' ) ) {
                $query = new WP_Query( array( 'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => -1 ) );

                foreach ( $query->posts as $prod ) {
                    do_action( 'stm_after_import_woocommerce_update_product', $prod->ID );
                }

                wp_reset_postdata();
            }

            if ( class_exists( 'YITH_WCWL_Install' ) ) {
                $wshlst = YITH_WCWL_Install::get_instance();
                $wshlst->init();
            }
        }

        if ( empty( $wp_filesystem ) ) {
            require_once ABSPATH . '/wp-admin/includes/file.php';
            WP_Filesystem();
        }

        $fxml = get_temp_dir() . $layout . '.xml';
        $fzip = get_temp_dir() . $layout . '.zip';
        if ( file_exists( $fxml ) ) @unlink( $fxml );
        if ( file_exists( $fzip ) ) @unlink( $fzip );
    }
}

add_action( 'stm_importer_done', 'stm_importer_done_function', 10, 1 );

if ( !function_exists( 'stm_upload_user_file' ) ) {
    function stm_upload_user_file( $file = array() )
    {

        require_once( ABSPATH . 'wp-admin/includes/admin.php' );

        if ( !function_exists( 'wp_handle_upload' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
        }

        $file_return = wp_handle_upload( $file, array( 'test_form' => false ) );

        if ( isset( $file_return['error'] ) || isset( $file_return['upload_error_handler'] ) ) {
            return false;
        } else {
            $filename = $file_return['file'];
            $attachment = array(
                'post_mime_type' => $file_return['type'],
                'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
                'post_content' => '',
                'post_status' => 'inherit',
                'guid' => $file_return['url']
            );

            $attachment_id = wp_insert_attachment( $attachment, $file_return['file'] );
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            $attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
            wp_update_attachment_metadata( $attachment_id, $attachment_data );
            if ( 0 < intval( $attachment_id ) ) {
                return $attachment_id;
            }
        }

        return false;
    }
}


// Price delimeter
if ( !function_exists( 'stm_listing_price_view' ) ) {
    function stm_listing_price_view( $price )
    {
        if ( $price !== '' ) {
            $price_label = stm_get_price_currency();
            $price_label_position = get_theme_mod( 'price_currency_position', 'left' );
            $price_delimeter = get_theme_mod( 'price_delimeter', ' ' );

            if ( strpos( $price, '<' ) !== false || strpos( $price, '>' ) !== false ) {
                $priceConvert = number_format( getConverPrice( filter_var( $price, FILTER_SANITIZE_NUMBER_INT ) ), 0, '', $price_delimeter );
            } elseif ( strpos( $price, '-' ) !== false ) {
                $priceArr = explode( '-', $price );
                $priceConvert = number_format( getConverPrice( $priceArr[0] ), 0, '', $price_delimeter ) . '-' . number_format( getConverPrice( $priceArr[1] ), 0, '', $price_delimeter );
            } else {
                $priceConvert = number_format( getConverPrice( $price ), 0, '', $price_delimeter );
            }

            if ( $price_label_position == 'left' ) {

                $response = $price_label . $priceConvert;

                if ( strpos( $price, '<' ) !== false ) {
                    $response = '&lt; ' . $price_label . $priceConvert;
                } elseif ( strpos( $price, '>' ) !== false ) {
                    $response = '&gt; ' . $price_label . $priceConvert;
                }
            } else {
                $response = $priceConvert . $price_label;

                if ( strpos( $price, '<' ) !== false ) {
                    $response = '&lt; ' . $priceConvert . $price_label;
                } elseif ( strpos( $price, '>' ) !== false ) {
                    $response = '&gt; ' . $priceConvert . $price_label;
                }
            }

            return apply_filters( 'stm_filter_price_view', $response );
        }
    }
}

if ( !function_exists( 'stm_get_price_currency' ) ) {
    /**
     * Get price currency
     */
    function stm_get_price_currency()
    {
        $currency = get_theme_mod( 'price_currency', '$' );
        if ( isset( $_COOKIE["stm_current_currency"] ) ) {
            $cookie = explode( "-", $_COOKIE["stm_current_currency"] );
            $currency = $cookie[0];
        }
        return $currency;
    }
}

if ( !function_exists( 'stm_get_current_layout' ) ) {
    function stm_get_current_layout()
    {
        $layout = get_option( 'stm_motors_chosen_template' );

        if ( empty( $layout ) ) {
            $layout = 'car_dealer';
        }

        return $layout;
    }


}

if ( !function_exists( 'stm_dev_print_array' ) ) {
    function stm_dev_print_array( $array )
    {
        echo '<pre>';
        print_r( $array );
        echo '</pre>';
    }
}

if ( !function_exists( 'stm_enable_location' ) ) {
    function stm_enable_location()
    {
        $enable_location = get_theme_mod( 'enable_location', true );

        return $enable_location;
    }
}

if ( !function_exists( 'stm_distance_measure_unit' ) ) {
    function stm_distance_measure_unit()
    {
        $distance_measure = get_theme_mod( 'distance_measure_unit', 'miles' );
        $distance_affix = esc_html__( 'mi', 'motors' );

        if ( $distance_measure == 'kilometers' ) {
            $distance_affix = esc_html__( 'km', 'motors' );
        }

        return $distance_affix;
    }
}

if ( !function_exists( 'stm_calculate_distance_between_two_points' ) ) {
    function stm_calculate_distance_between_two_points( $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo )
    {
        $distance_measure = get_theme_mod( 'distance_measure_unit', 'miles' );

        $latitudeFrom = esc_attr( floatval( $latitudeFrom ) );
        $longitudeFrom = esc_attr( floatval( $longitudeFrom ) );

        $distance_affix = stm_distance_measure_unit();

        $theta = $longitudeFrom - $longitudeTo;
        $dist = sin( deg2rad( $latitudeFrom ) ) * sin( deg2rad( $latitudeTo ) ) + cos( deg2rad( $latitudeFrom ) ) * cos( deg2rad( $latitudeTo ) ) * cos( deg2rad( $theta ) );
        $dist = acos( $dist );
        $dist = rad2deg( $dist );
        $dist = $dist * 60 * 1.515;
        if ( $distance_measure != 'kilometers' ) {
            $dist = $dist / 1.609344;
        }

        return round( $dist, 1 ) . ' ' . $distance_affix;
    }
}


//Location Filter hook
if ( !function_exists( 'stm_edit_join_posts' ) ) {
    function stm_edit_join_posts( $join_paged_statement )
    {

        global $wpdb;
        $table_prefix = $wpdb->prefix;


        $join_paged_statement .= " INNER JOIN " . $table_prefix . "postmeta stm_lat_prefix ON (" . $table_prefix . "posts.ID = stm_lat_prefix.post_id AND stm_lat_prefix.meta_key = 'stm_lat_car_admin')";
        $join_paged_statement .= " INNER JOIN " . $table_prefix . "postmeta stm_lng_prefix ON (" . $table_prefix . "posts.ID = stm_lng_prefix.post_id AND stm_lng_prefix.meta_key = 'stm_lng_car_admin') ";

        return $join_paged_statement;

        do_action( 'stm_me_edit_join_posts' );
    }
}

if ( !function_exists( 'stm_show_filter_by_location' ) ) {
    function stm_show_filter_by_location( $orderby )
    {

        $lat_from = esc_attr( floatval( $_GET['stm_lat'] ) );
        $lng_from = esc_attr( floatval( $_GET['stm_lng'] ) );


        $orderby = "(6378.137 * ACOS(COS(RADIANS(stm_lat_prefix.meta_value))*COS(RADIANS(" . $lat_from . "))*COS(RADIANS(stm_lng_prefix.meta_value)-RADIANS(" . $lng_from . "))+SIN(RADIANS(stm_lat_prefix.meta_value))*SIN(RADIANS(" . $lat_from . "))))*1.3 ASC";

        return apply_filters( 'stm_listings_clauses_filter', $orderby );

        do_action( 'stm_me_posts_orderby' );
    }
}

if ( !function_exists( 'stm_location_validates' ) ) {
    function stm_location_validates()
    {
        if ( isset( $_GET['stm_lng'] ) and isset( $_GET['stm_lat'] ) and !empty( $_GET['ca_location'] ) ) {
            return true;
        } else {
            return false;
        }
    }
}

if ( !function_exists( 'stm_modify_query_location' ) ) {
    function stm_modify_query_location()
    {
        if ( stm_location_validates() ) {
            add_filter( 'posts_join_paged', 'stm_edit_join_posts' );
            add_filter( 'posts_orderby', 'stm_show_filter_by_location' );
        }
    }
}


if ( !function_exists( 'stm_generate_title_from_slugs' ) ) {
    function stm_generate_title_from_slugs( $post_id, $show_labels = false )
    {
        $title_from = get_theme_mod( 'listing_directory_title_frontend', '' );

        $title_return = '';
        if ( get_post_type( $post_id ) == 'listings' ) {
            if ( !empty( $title_from ) and stm_is_listing() || stm_is_car_dealer() || stm_is_dealer_two() || stm_is_aircrafts()) {
                $title = stm_replace_curly_brackets( $title_from );
                $title_counter = 0;

                if ( !empty( $title ) ) {
                    foreach ( $title as $title_part ) {
                        $title_counter++;
                        if ( $title_counter == 1 ) {
                            if ( $show_labels ) {
                                $title_return .= '<div class="labels">';
                            }
                        }

                        $term = wp_get_post_terms( $post_id, strtolower( $title_part ), array( 'orderby' => 'none' ) );
                        if ( !empty( $term ) and !is_wp_error( $term ) ) {
                            if ( !empty( $term[0] ) ) {
                                if ( !empty( $term[0]->name ) ) {
                                    if ( $title_counter == 1 ) {
                                        $title_return .= $term[0]->name;
                                    } else {
                                        $title_return .= ' ' . $term[0]->name;
                                    }
                                } else {
                                    $number_affix = get_post_meta( $post_id, strtolower( $title_part ), true );
                                    if ( !empty( $number_affix ) ) {
                                        $title_return .= ' ' . $number_affix . ' ';
                                    }
                                }
                            }
                        } else {
                            $number_affix = get_post_meta( $post_id, strtolower( $title_part ), true );
                            if ( !empty( $number_affix ) ) {
                                $title_return .= ' ' . $number_affix . ' ';
                            }
                        }
                        if ( $show_labels and $title_counter == 2 ) {
                            $title_return .= '</div>';
                        }
                    }
                }
            } elseif ( !empty( $title_from ) and stm_is_boats() ) {
                $title = stm_replace_curly_brackets( $title_from );

                if ( !empty( $title ) ) {
                    foreach ( $title as $title_part ) {
                        $value = get_post_meta( $post_id, $title_part, true );
                        if ( !empty( $value ) ) {
                            $cat = get_term_by( 'slug', $value, $title_part );
                            if ( !is_wp_error( $cat ) and !empty( $cat->name ) ) {
                                $title_return .= $cat->name . ' ';
                            } else {
                                $title_return .= $value . ' ';
                            }
                        }
                    }
                }
            } elseif ( !empty( $title_from ) and stm_is_motorcycle() ) {
                $title = stm_replace_curly_brackets( $title_from );

                $title_counter = 0;

                if ( !empty( $title ) ) {
                    foreach ( $title as $title_part ) {
                        $value = get_post_meta( $post_id, $title_part, true );
                        $title_counter++;

                        if ( !empty( $value ) ) {
                            $cat = get_term_by( 'slug', $value, $title_part );
                            if ( !is_wp_error( $cat ) and !empty( $cat->name ) ) {
                                if ( $title_counter == 1 and $show_labels ) {
                                    $title_return .= '<span class="stm-label-title">';
                                }
                                $title_return .= $cat->name . ' ';
                                if ( $title_counter == 1 and $show_labels ) {
                                    $title_return .= '</span>';
                                }
                            } else {
                                if ( $title_counter == 1 and $show_labels ) {
                                    $title_return .= '<span class="stm-label-title">';
                                }
                                $title_return .= $value . ' ';
                                if ( $title_counter == 1 and $show_labels ) {
                                    $title_return .= '</span>';
                                }
                            }
                        }
                    }
                }
            } elseif ( !empty( $title_from ) && stm_is_listing_three() ) {
                $title = stm_replace_curly_brackets( $title_from );
                $title_counter = 0;

                if ( !empty( $title ) ) {
                    foreach ( $title as $title_part ) {
                        $title_counter++;
                        if ( $title_counter == 1 ) {
                            if ( $show_labels ) {
                                $title_return .= '<div class="labels">';
                            }
                        }

                        $term = wp_get_post_terms( $post_id, strtolower( $title_part ), array( 'orderby' => 'none' ) );
                        if ( !empty( $term ) and !is_wp_error( $term ) ) {
                            if ( !empty( $term[0] ) ) {
                                if ( !empty( $term[0]->name ) ) {
                                    if ( $title_counter == 1 ) {
                                        $title_return .= $term[0]->name;
                                    } else {
                                        $title_return .= ' ' . $term[0]->name;
                                    }
                                } else {
                                    $number_affix = get_post_meta( $post_id, strtolower( $title_part ), true );
                                    if ( !empty( $number_affix ) ) {
                                        $title_return .= ' ' . $number_affix . ' ';
                                    }
                                }
                            }
                        } else {
                            $number_affix = get_post_meta( $post_id, strtolower( $title_part ), true );
                            if ( !empty( $number_affix ) ) {
                                $title_return .= ' ' . $number_affix . ' ';
                            }
                        }
                        if ( $show_labels and $title_counter == 2 ) {
                            $title_return .= '</div>';
                        }
                    }
                }
            }
        }

        if ( empty( $title_return ) ) {
            $title_return = get_the_title( $post_id );
        }

        return $title_return;
    }
}

if ( !function_exists( 'stm_replace_curly_brackets' ) ) {
    function stm_replace_curly_brackets( $string )
    {
        $matches = array();
        preg_match_all( '/{(.*?)}/', $string, $matches );

        return $matches[1];
    }
}

if ( !function_exists( 'stm_check_if_car_imported' ) ) {
    function stm_check_if_car_imported( $id )
    {
        $return = false;
        if ( !empty( $id ) ) {
            $has_id = get_post_meta( $id, 'automanager_id', true );
            if ( !empty( $has_id ) ) {
                $return = true;
            } else {
                $return = false;
            }
        }

        return $return;
    }
}

if ( !function_exists( 'stm_get_car_medias' ) ) {
    function stm_get_car_medias( $post_id )
    {
        if ( !empty( $post_id ) ) {

            $image_limit = '';

            if ( stm_pricing_enabled() ) {
                $user_added = get_post_meta( $post_id, 'stm_car_user', true );
                if ( !empty( $user_added ) ) {
                    $limits = stm_get_post_limits( $user_added );
                    $image_limit = $limits['images'];
                }
            }
            $car_media = array();

            //Photo
            $car_photos = array();
            $car_gallery = get_post_meta( $post_id, 'gallery', true );
            $car_videos_posters = get_post_meta( $post_id, 'gallery_videos_posters', true );

            if ( has_post_thumbnail( $post_id ) ) {
                $car_photos[] = wp_get_attachment_url( get_post_thumbnail_id( $post_id ) );
            }

            if ( !empty( $car_gallery ) ) {
                $i = 0;
                foreach ( $car_gallery as $car_gallery_image ) {
                    if ( empty( $image_limit ) ) {
                        if ( wp_get_attachment_url( $car_gallery_image ) ) {
                            $car_photos[] = wp_get_attachment_url( $car_gallery_image );
                        }
                    } else {
                        $i++;
                        if ( $i < $image_limit ) {
                            if ( wp_get_attachment_url( $car_gallery_image ) ) {
                                $car_photos[] = wp_get_attachment_url( $car_gallery_image );
                            }
                        }
                    }
                }
            }

            $car_media['car_photos'] = $car_photos;
            $car_media['car_photos_count'] = count( $car_photos );

            //Video
            $car_video = array();
            $car_video_main = get_post_meta( $post_id, 'gallery_video', true );
            $car_videos = get_post_meta( $post_id, 'gallery_videos', true );


            if ( !empty( $car_video_main ) ) {
                $car_video[] = $car_video_main;
            }

            if ( !empty( $car_videos ) ) {
                foreach ( $car_videos as $car_video_single ) {
                    if ( !empty( $car_video_single ) ) {
                        $car_video[] = $car_video_single;
                    }
                }
            }

            $car_media['car_videos'] = $car_video;
            $car_media['car_videos_posters'] = $car_videos_posters;
            $car_media['car_videos_count'] = count( $car_video );

            return $car_media;
        }
    }
}

function stm_similar_cars()
{
    $tax_query = array();
    $taxes = get_theme_mod( 'stm_similar_query', '' );
    $query = array(
        'post_type' => stm_listings_post_type(),
        'post_status' => 'publish',
        'posts_per_page' => '3',
        'post__not_in' => array( get_the_ID() ),
    );

    if ( !empty( $taxes ) ) {
        $taxes = array_filter( array_map( 'trim', explode( ',', $taxes ) ) );
        $attributes = stm_listings_attributes( array( 'key_by' => 'slug' ) );

        foreach ( $taxes as $tax ) {
            if ( !isset( $attributes[$tax] ) || !empty( $attributes[$tax]['numeric'] ) ) {
                continue;
            }

            $terms = get_the_terms( get_the_ID(), $tax );
            if ( !is_array( $terms ) ) {
                continue;
            }

            $tax_query[] = array(
                'taxonomy' => esc_attr( $tax ),
                'field' => 'slug',
                'terms' => wp_list_pluck( $terms, 'slug' )
            );
        }
    }

    if ( !empty( $tax_query ) ) {
        $query['tax_query'] = array( 'relation' => 'OR' ) + $tax_query;
    }

    return new WP_Query( apply_filters( 'stm_similar_cars_query', $query ) );
}


if ( !function_exists( 'stm_get_footer_terms' ) ) {
    function stm_get_footer_terms()
    {
        $taxonomies = stm_get_footer_taxonomies();
        $terms = array();
        $terms_slugs = array();
        $tax_slug = array();
        $tax_names = array();
        $input_placeholder = esc_html__( 'Enter', 'motors' );


        $response = array();

        if ( !empty( $taxonomies ) ) {
            foreach ( $taxonomies as $tax_key => $taxonomy ) {
                if ( !empty( $taxonomy['slug'] ) ) {
                    if ( $tax_key < 2 ) {
                        $tax_names[] = __( $taxonomy['single_name'], 'motors' );
                    }
                    $tmp_terms = get_terms( $taxonomy['slug'] );
                    foreach ( $tmp_terms as $tmp_term ) {
                        if ( !empty( $tmp_term->name ) ) {
                            $terms[] = $tmp_term->name;
                            $terms_slugs[] = $tmp_term->slug;
                            $tax_slug[] = $taxonomy['slug'];
                        }
                    }
                }
            }
        }

        $input_placeholder .= ' ' . implode( ' ' . esc_html__( 'or', 'motors' ) . ' ', $tax_names );

        $response['names'] = $terms;
        $response['slugs'] = $terms_slugs;
        $response['tax'] = $tax_slug;
        $response['placeholder'] = $input_placeholder;

        return $response;
    }
}

if ( !function_exists( 'stm_get_author_link' ) ) {
    function stm_get_author_link( $id = 'register' )
    {

        if ( $id == 'register' ) {
            $login_page = get_theme_mod( 'login_page', 1718 );

            $login_page = stm_motors_wpml_is_page( $login_page );

            $link = get_permalink( $login_page );
        } else {
            if ( empty( $id ) or $id == 'myself-view' ) {
                $user = wp_get_current_user();
                if ( !is_wp_error( $user ) ) {
                    $link = get_author_posts_url( $user->data->ID );
                    if ( $id == 'myself-view' ) {
                        $link = add_query_arg( array( 'view-myself' => 1 ), $link );
                    }
                } else {
                    $link = '';
                }
            } else {
                $link = get_author_posts_url( $id );
            }

        }

        return $link;
    }
}


if ( !function_exists( 'stm_get_user_role' ) ) {
    function stm_get_user_role( $user_id = null )
    {
        $user_data = get_userdata( $user_id ? $user_id : get_current_user_id() );

        return !empty( $user_data ) && in_array( 'stm_dealer', $user_data->roles );
    }
}

if ( !function_exists( 'stm_get_user_custom_fields' ) ) {
    function stm_get_user_custom_fields( $user_id )
    {
        $response = array();

        if ( empty( $user_id ) ) {
            $user_current = wp_get_current_user();
            $user_id = $user_current->ID;
        }

        //Phone
        $user_phone = '';
        $user_phone = get_the_author_meta( 'stm_phone', $user_id );

        $user_mail = '';
        $user_mail = get_the_author_meta( 'email', $user_id );

        $user_show_mail = '';
        $user_show_mail = get_the_author_meta( 'stm_show_email', $user_id );

        $user_name = '';
        $user_name = get_the_author_meta( 'first_name', $user_id );

        $user_last_name = '';
        $user_last_name = get_the_author_meta( 'last_name', $user_id );

        //Image
        $user_image = '';
        $user_image = get_the_author_meta( 'stm_user_avatar', $user_id );


        //Socials
        $socials = array( 'facebook', 'twitter', 'linkedin', 'youtube' );
        $user_socials = array();
        foreach ( $socials as $social ) {
            $user_soc = get_the_author_meta( 'stm_user_' . $social, $user_id );
            if ( !empty( $user_soc ) ) {
                $user_socials[$social] = $user_soc;
            }
        }

        $response['user_id'] = $user_id;
        $response['phone'] = $user_phone;
        $response['image'] = $user_image;
        $response['name'] = $user_name;
        $response['last_name'] = $user_last_name;
        $response['socials'] = $user_socials;
        $response['email'] = $user_mail;
        $response['show_mail'] = $user_show_mail;

        /*Dealer fields*/
        $logo = '';
        $logo = get_the_author_meta( 'stm_dealer_logo', $user_id );

        $dealer_image = '';
        $dealer_image = get_the_author_meta( 'stm_dealer_image', $user_id );

        $license = '';
        $license = get_the_author_meta( 'stm_company_license', $user_id );

        $website = '';
        $website = get_the_author_meta( 'stm_website_url', $user_id );

        $location = '';
        $location = get_the_author_meta( 'stm_dealer_location', $user_id );

        $location_lat = '';
        $location_lat = get_the_author_meta( 'stm_dealer_location_lat', $user_id );

        $location_lng = '';
        $location_lng = get_the_author_meta( 'stm_dealer_location_lng', $user_id );

        $stm_company_name = '';
        $stm_company_name = get_the_author_meta( 'stm_company_name', $user_id );

        $stm_company_license = '';
        $stm_company_license = get_the_author_meta( 'stm_company_license', $user_id );

        $stm_message_to_user = '';
        $stm_message_to_user = get_the_author_meta( 'stm_message_to_user', $user_id );

        $stm_sales_hours = '';
        $stm_sales_hours = get_the_author_meta( 'stm_sales_hours', $user_id );

        $stm_seller_notes = '';
        $stm_seller_notes = get_the_author_meta( 'stm_seller_notes', $user_id );

        $stm_payment_status = '';
        $stm_payment_status = get_the_author_meta( 'stm_payment_status', $user_id );


        $response['logo'] = $logo;
        $response['dealer_image'] = $dealer_image;
        $response['license'] = $license;
        $response['website'] = $website;
        $response['location'] = $location;
        $response['location_lat'] = $location_lat;
        $response['location_lng'] = $location_lng;
        $response['stm_company_name'] = $stm_company_name;
        $response['stm_company_license'] = $stm_company_license;
        $response['stm_message_to_user'] = $stm_message_to_user;
        $response['stm_sales_hours'] = $stm_sales_hours;
        $response['stm_seller_notes'] = $stm_seller_notes;
        $response['stm_payment_status'] = $stm_payment_status;


        return $response;


    }
}

if ( !function_exists( 'stm_account_navigation' ) ) {
    function stm_account_navigation()
    {
        $nav = array(
            'inventory' => array(
                'label' => esc_html__( 'My Inventory', 'motors' ),
                'url' => stm_get_author_link( '' ),
                'icon' => 'stm-service-icon-inventory',
            ),
            'favourite' => array(
                'label' => esc_html__( 'My Favorites', 'motors' ),
                'url' => add_query_arg( array( 'page' => 'favourite' ), stm_get_author_link( '' ) ),
                'icon' => 'stm-service-icon-star-o',
            ),
            'settings' => array(
                'label' => esc_html__( 'Profile Settings', 'motors' ),
                'url' => add_query_arg( array( 'page' => 'settings' ), stm_get_author_link( '' ) ),
                'icon' => 'fa fa-cog',
            ),
        );

        return apply_filters( 'stm_account_navigation', $nav );
    }
}

if ( !function_exists( 'stm_account_current_page' ) ) {
    function stm_account_current_page()
    {
        $page = 'inventory';

        if ( isset( $_GET['page'] ) ) {
            $page = sanitize_text_field($_GET['page']);
        }

        if ( !empty( $_GET['my_favourites'] ) and $_GET['my_favourites'] ) {
            $page = 'favourite';
        }

        if ( !empty( $_GET['my_settings'] ) and $_GET['my_settings'] ) {
            $page = 'settings';
        }

        if ( !empty( $_GET['become_dealer'] ) and $_GET['become_dealer'] ) {
            $page = 'become-dealer';
        }

        return apply_filters( 'stm_account_current_page', $page );
    }
}

function stm_send_cf7_message_to_user( $wpcf )
{

    if ( !empty( $_POST['stm_changed_recepient'] ) ) {

        $mail = $wpcf->prop( 'mail' );

        $mail_to = get_the_author_meta( 'email', intval( $_POST['stm_changed_recepient'] ) );

        if ( !empty( $mail_to ) ) {
            $mail['recipient'] = sanitize_email( $mail_to );
            $wpcf->set_properties( array( 'mail' => $mail ) );
        }

    }

    return $wpcf;
}

add_action( "wpcf7_before_send_mail", "stm_send_cf7_message_to_user", 8, 1 );

function stm_single_car_counter()
{
    if ( is_singular( stm_listings_post_type() ) || is_singular( 'post' ) ) {
        //Views
        $cookies = '';

        if ( empty( $_COOKIE['stm_car_watched'] ) ) {
            $cookies = get_the_ID();
            setcookie( 'stm_car_watched', $cookies, time() + ( 86400 * 30 ), '/' );
            stm_increase_rating( get_the_ID() );
        }

        if ( !empty( $_COOKIE['stm_car_watched'] ) ) {
            $cookies = $_COOKIE['stm_car_watched'];
            $cookies = explode( ',', $cookies );

            if ( !in_array( get_the_ID(), $cookies ) ) {
                $cookies[] = get_the_ID();

                $cookies = implode( ',', $cookies );

                stm_increase_rating( get_the_ID() );
                setcookie( 'stm_car_watched', $cookies, time() + ( 86400 * 30 ), '/' );
            }
        }

        if ( !empty( $_COOKIE['stm_car_watched'] ) ) {
            $watched = explode( ',', $_COOKIE['stm_car_watched'] );
        }
    }
}

function stm_increase_rating( $post_id )
{
    $current_rating = intval( get_post_meta( $post_id, 'stm_car_views', true ) );
    if ( empty( $current_rating ) ) {
        update_post_meta( $post_id, 'stm_car_views', 1 );
    } else {
        $current_rating = $current_rating + 1;
        update_post_meta( $post_id, 'stm_car_views', $current_rating );
    }
}

add_action( 'wp', 'stm_single_car_counter', 10, 1 );

if ( !function_exists( 'stm_force_favourites' ) ) {
    function stm_force_favourites( $user_id )
    {

        $user_exist_fav = get_the_author_meta( 'stm_user_favourites', $user_id );
        if ( !empty( $user_exist_fav ) ) {
            $user_exist_fav = explode( ',', $user_exist_fav );
        } else {
            $user_exist_fav = array();
        }

        if ( !empty( $_COOKIE['stm_car_favourites'] ) ) {
            $cookie_fav = explode( ',', $_COOKIE['stm_car_favourites'] );
            setcookie( 'stm_car_favourites', '', time() - 3600, '/' );
        } else {
            $cookie_fav = array();
        }

        if ( !empty( $user_exist_fav ) or !empty( $cookie_fav ) ) {
            $new_fav = implode( ',', array_unique( array_merge( $user_exist_fav, $cookie_fav ) ) );
            if ( !empty( $new_fav ) ) {
                update_user_meta( $user_id, 'stm_user_favourites', $new_fav );
            }
        }
    }
}

if ( !function_exists( 'stm_edit_delete_user_car' ) ) {
    function stm_edit_delete_user_car()
    {

        $demo = stm_is_site_demo_mode();
        if ( !$demo ) {

            if ( isset( $_GET['stm_unmark_as_sold_car'] ) ) {
                delete_post_meta( $_GET['stm_unmark_as_sold_car'], 'car_mark_as_sold', 'on' );
            } elseif ( isset( $_GET['stm_mark_as_sold_car'] ) ) {
                update_post_meta( $_GET['stm_mark_as_sold_car'], 'car_mark_as_sold', 'on' );
            }

            if ( isset( $_GET['stm_make_featured'] ) ) {
                if ( class_exists( "WooCommerce" ) && get_theme_mod( 'dealer_payments_for_featured_listing', false ) ) {
                    $featuredListingPrice = get_theme_mod( 'featured_listing_price', 0 );
                    update_post_meta( $_GET['stm_make_featured'], '_price', $featuredListingPrice );
                    update_post_meta( $_GET['stm_make_featured'], 'car_make_featured', 'on' );

                    $checkoutUrl = wc_get_checkout_url() . '?add-to-cart=' . $_GET['stm_make_featured'];

                    wp_safe_redirect( $checkoutUrl );
                    die();
                }
            }

            if ( !empty( $_GET['stm_disable_user_car'] ) ) {
                $car = intval( $_GET['stm_disable_user_car'] );

                $author = get_post_meta( $car, 'stm_car_user', true );
                $user = wp_get_current_user();

                if ( intval( $author ) == intval( $user->ID ) ) {
                    $status = get_post_status( $car );
                    if ( $status == 'publish' ) {
                        $disabled_car = array(
                            'ID' => $car,
                            'post_status' => 'draft'
                        );

                        wp_update_post( $disabled_car );
                    }
                }
            }

            if ( !empty( $_GET['stm_enable_user_car'] ) ) {
                $car = intval( $_GET['stm_enable_user_car'] );

                $author = get_post_meta( $car, 'stm_car_user', true );
                $user = wp_get_current_user();

                if ( intval( $author ) == intval( $user->ID ) ) {
                    $status = get_post_status( $car );
                    if ( $status == 'draft' ) {
                        $disabled_car = array(
                            'ID' => $car,
                            'post_status' => 'publish'
                        );

                        $can_update = true;

                        if ( stm_pricing_enabled() ) {

                            $user_limits = stm_get_post_limits( $user->ID );
                            if ( !$user_limits['posts'] ) {
                                $can_update = false;
                            }
                        }
                        if ( $can_update ) {
                            wp_update_post( $disabled_car );
                        } else {
                            add_action( 'wp_enqueue_scripts', 'stm_user_out_of_limit' );
                            function stm_user_out_of_limit()
                            {
                                $field_limit = 'jQuery(document).ready(function(){';
                                $field_limit .= 'jQuery(".stm-no-available-adds-overlay, .stm-no-available-adds").removeClass("hidden");';
                                $field_limit .= 'jQuery(".stm-no-available-adds-overlay").on("click", function(){';
                                $field_limit .= 'jQuery(".stm-no-available-adds-overlay, .stm-no-available-adds").addClass("hidden")';
                                $field_limit .= '});';
                                $field_limit .= '});';
                                wp_add_inline_script( 'stm-theme-scripts', $field_limit );
                            }
                        }
                    }
                }
            }

            if ( !empty( $_GET['stm_move_trash_car'] ) ) {
                $car = intval( $_GET['stm_move_trash_car'] );

                $author = get_post_meta( $car, 'stm_car_user', true );
                $user = wp_get_current_user();

                if ( intval( $author ) == intval( $user->ID ) ) {
                    if ( get_post_status( $car ) == 'draft' or get_post_status( $car ) == 'pending' ) {

                        wp_trash_post( $car, false );

                    }
                }
            }
        }
    }
}

add_action( 'wp', 'stm_edit_delete_user_car' );

if ( !function_exists( 'stm_filter_display_name' ) ) {
    function stm_filter_display_name( $display_name, $user_id, $user_login = '', $f_name = '', $l_name = '' )
    {
        $user = get_userdata( $user_id );

        if ( empty( $user_login ) ) {
            $login = $user->data->user_login;
        } else {
            $login = $user_login;
        }
        if ( empty( $f_name ) ) {
            $first_name = get_the_author_meta( 'first_name', $user_id );
        } else {
            $first_name = $f_name;
        }

        if ( empty( $l_name ) ) {
            $last_name = get_the_author_meta( 'last_name', $user_id );
        } else {
            $last_name = $l_name;
        }

        $display_name = $login;

        if ( !empty( $first_name ) ) {
            $display_name = $first_name;
        }

        if ( !empty( $first_name ) and !empty( $last_name ) ) {
            $display_name .= ' ' . $last_name;
        }

        if ( empty( $first_name ) and !empty( $last_name ) ) {
            $display_name = $last_name;
        }

        if ( in_array( 'stm_dealer', $user->roles ) ) {
            $company_name = get_the_author_meta( 'stm_company_name', $user_id );
            if ( !empty( $company_name ) ) {
                return ( $company_name );
            } else {
                return ( $display_name );
            }
        } else {
            return ( $display_name );
        }
    }

    add_filter( 'stm_filter_display_user_name', 'stm_filter_display_name', 20, 5 );
}

if ( !function_exists( 'stm_get_add_page_url' ) ) {
    function stm_get_add_page_url( $edit = '', $post_id = '' )
    {
        $page_id = get_theme_mod( 'user_add_car_page', 1755 );
        $page_link = '';

        if ( !empty( $page_id ) ) {
            $page_id = stm_motors_wpml_is_page( $page_id );

            $page_link = get_permalink( $page_id );
        }


        if ( $edit == 'edit' and !empty( $post_id ) ) {
            return esc_url( add_query_arg( array( 'edit_car' => '1', 'item_id' => intval( $post_id ) ), $page_link ) );
        } else {
            return esc_url( $page_link );
        }
    }
}


//Add car helpers

if ( !function_exists( 'stm_get_dealer_marks' ) ) {
    function stm_get_dealer_marks( $dealer_id = '' )
    {
        if ( !empty( $dealer_id ) ) {
            $args = array(
                'post_type' => 'dealer_review',
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'meta_query' => array(
                    array(
                        'key' => 'stm_review_added_on',
                        'value' => intval( $dealer_id ),
                        'compare' => '='
                    )
                )
            );

            $query = new WP_Query( $args );

            $ratings = array(
                'average' => 0,
                'rate1' => 0,
                'rate1_label' => get_theme_mod( 'dealer_rate_1', esc_html__( 'Customer Service', 'motors' ) ),
                'rate2' => 0,
                'rate2_label' => get_theme_mod( 'dealer_rate_2', esc_html__( 'Buying Process', 'motors' ) ),
                'rate3' => 0,
                'rate3_label' => get_theme_mod( 'dealer_rate_3', esc_html__( 'Overall Experience', 'motors' ) ),
                'likes' => 0,
                'dislikes' => 0,
                'count' => 0
            );

            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) {
                    $query->the_post();
                    $rate1 = get_post_meta( get_the_id(), 'stm_rate_1', true );
                    $rate2 = get_post_meta( get_the_id(), 'stm_rate_2', true );
                    $rate3 = get_post_meta( get_the_id(), 'stm_rate_3', true );
                    $stm_recommended = get_post_meta( get_the_id(), 'stm_recommended', true );

                    if ( !empty( $rate1 ) ) {
                        $ratings['rate1'] = intval( $ratings['rate1'] ) + intval( $rate1 );
                    }
                    if ( !empty( $rate2 ) ) {
                        $ratings['rate2'] = intval( $ratings['rate2'] ) + intval( $rate2 );
                    }
                    if ( !empty( $rate1 ) ) {
                        $ratings['rate3'] = intval( $ratings['rate3'] ) + intval( $rate3 );
                    }

                    if ( $stm_recommended == 'yes' ) {
                        $ratings['likes']++;
                    }

                    if ( $stm_recommended == 'no' ) {
                        $ratings['dislikes']++;
                    }
                }
                $total = $query->found_posts;
                $ratings['count'] = $total;

                $average_num = 0;

                if ( empty( $ratings['rate1_label'] ) ) {
                    $ratings['rate1'] = 0;
                } else {
                    $ratings['rate1'] = round( $ratings['rate1'] / $ratings['count'], 1 );

                    $ratings['rate1_width'] = ( ( $ratings['rate1'] * 100 ) / 5 ) . '%';

                    $ratings['average'] = $ratings['average'] + $ratings['rate1'];

                    $average_num++;
                }

                if ( empty( $ratings['rate2_label'] ) ) {
                    $ratings['rate2'] = 0;
                } else {
                    $ratings['rate2'] = round( $ratings['rate2'] / $ratings['count'], 1 );

                    $ratings['rate2_width'] = ( ( $ratings['rate2'] * 100 ) / 5 ) . '%';

                    $ratings['average'] = $ratings['average'] + $ratings['rate2'];

                    $average_num++;
                }

                if ( empty( $ratings['rate3_label'] ) ) {
                    $ratings['rate3'] = 0;
                } else {
                    $ratings['rate3'] = round( $ratings['rate3'] / $ratings['count'], 1 );

                    $ratings['rate3_width'] = ( ( $ratings['rate3'] * 100 ) / 5 ) . '%';

                    $ratings['average'] = $ratings['average'] + $ratings['rate3'];

                    $average_num++;
                }

                $ratings['average'] = number_format( round( $ratings['average'] / $average_num, 1 ), '1', '.', '' );
                $ratings['average_width'] = ( ( $ratings['average'] * 100 ) / 5 ) . '%';

                if ( empty( $ratings['rate1_label'] ) and empty( $ratings['rate2_label'] ) and empty( $ratings['rate3_label'] ) ) {
                    $ratings['average'] = 0;
                }

                wp_reset_postdata();
            }


            return $ratings;
        }
    }
}

if ( !function_exists( 'stm_dealer_gmap' ) ) {
    function stm_dealer_gmap( $lat, $lng )
    {
        ?>

        <div id="stm-dealer-gmap"></div>

        <script>
            jQuery(document).ready(function ($) {
                google.maps.event.addDomListener(window, 'load', init);

                var center, map;

                function init() {
                    center = new google.maps.LatLng(<?php echo esc_js( $lat ); ?>, <?php echo esc_js( $lng ); ?>);
                    var mapOptions = {
                        zoom: 15,
                        center: center,
                        fullscreenControl: true,
                        scrollwheel: false
                    };
                    var mapElement = document.getElementById('stm-dealer-gmap');
                    map = new google.maps.Map(mapElement, mapOptions);
                    var marker = new google.maps.Marker({
                        position: center,
                        icon: '<?php echo get_template_directory_uri(); ?>/assets/images/stm-map-marker-green.png',
                        map: map
                    });
                }

                $(window).resize(function () {
                    if (typeof map != 'undefined' && typeof center != 'undefined') {
                        setTimeout(function () {
                            map.setCenter(center);
                        }, 1000);
                    }
                })
            });
        </script>

        <?php
    }
}

if ( !function_exists( 'stm_get_dealer_reviews' ) ) {
    function stm_get_dealer_reviews( $dealer_id = '', $per_page = 6, $offset = 0 )
    {
        if ( !empty( $dealer_id ) ) {
            $args = array(
                'post_type' => 'dealer_review',
                'posts_per_page' => intval( $per_page ),
                'offset' => intval( $offset ),
                'post_status' => 'publish',
                'meta_query' => array(
                    array(
                        'key' => 'stm_review_added_on',
                        'value' => intval( $dealer_id ),
                        'compare' => '='
                    )
                )
            );

            $query = new WP_Query( $args );

            return $query;
        }
    }
}

if ( !function_exists( 'stm_get_user_reviews' ) ) {
    function stm_get_user_reviews( $dealer_id = '', $dealer_id_from = '' )
    {
        if ( !empty( $dealer_id ) and !empty( $dealer_id_from ) ) {
            $args = array(
                'post_type' => 'dealer_review',
                'post_status' => 'publish',
                'meta_query' => array(
                    array(
                        'key' => 'stm_review_added_by',
                        'value' => intval( $dealer_id ),
                        'compare' => '='
                    ),
                    array(
                        'key' => 'stm_review_added_on',
                        'value' => intval( $dealer_id_from ),
                        'compare' => '='
                    )
                )
            );

            $query = new WP_Query( $args );

            return $query;
        }
    }
}

if ( !function_exists( 'stm_get_dealer_logo_placeholder' ) ) {
    function stm_get_dealer_logo_placeholder()
    {
        echo esc_url( get_template_directory_uri() . '/assets/images/empty_dealer_logo.png' );
    }
}

if ( !function_exists( 'stm_filter_post_limits' ) ) {
    function stm_filter_post_limits( $restrictions, $user_id, $type )
    {
        $user_id = intval( $user_id );

        $restrictions = array(
            'premoderation' => get_theme_mod( 'user_premoderation', true ),
            'posts_allowed' => intval( get_theme_mod( 'user_post_limit', '3' ) ),
            'posts' => intval( get_theme_mod( 'user_post_limit', '3' ) ),
            'images' => intval( get_theme_mod( 'user_post_images_limit', '5' ) ),
            'role' => 'user'
        );

        if ( !empty( $user_id ) ) {

            $dealer = stm_get_user_role( $user_id );

            if ( $dealer ) {
                $restrictions['posts_allowed'] = intval( get_theme_mod( 'dealer_post_limit', '50' ) );
                $restrictions['premoderation'] = get_theme_mod( 'dealer_premoderation', false );
                $restrictions['images'] = intval( get_theme_mod( 'dealer_post_images_limit', '10' ) );
                $restrictions['role'] = 'dealer';
            }

            if ( stm_pricing_enabled() ) {
                $current_quota = stm_user_active_subscriptions( false, $user_id );
                if ( !empty( $current_quota['post_limit'] ) and !empty( $current_quota['image_limit'] ) ) {
                    $restrictions['posts_allowed'] = intval( $current_quota['post_limit'] );
                    $restrictions['images'] = intval( $current_quota['image_limit'] );
                }
            }

            /*IF is admin, set all */
            if ( user_can( $user_id, 'manage_options' ) ) {
                $restrictions['premoderation'] = false;
                $restrictions['posts_allowed'] = '9999';
                $restrictions['images'] = '9999';
                $restrictions['role'] = 'user';
            }
        }

        $restrictions = apply_filters( 'stm_user_restrictions', $restrictions, $user_id );

        if ( !empty( $user_id ) ) {
            $created_posts = 0;
            /*Due to users wish*/
//            if (stm_pricing_enabled()) {
//                $post_status = 'publish';
//            }

            $query = new WP_Query( array(
                'post_type' => stm_listings_post_type(),
                'post_status' => ( !empty( $type ) ) ? 'publish' : array( 'publish', 'pending', 'draft' ),
                'posts_per_page' => 1,
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'stm_car_user',
                        'value' => $user_id,
                        'compare' => '='
                    ),
                    array(
                        'key' => 'pay_per_listing',
                        'compare' => 'NOT EXISTS',
                        'value' => ''
                    )
                )
            ) );

            $restrictions['posts'] = max( 0, intval( $restrictions['posts_allowed'] ) - intval( $query->found_posts ) );
        }

        return $restrictions;
    }

    add_filter( 'stm_filter_user_restrictions', 'stm_filter_post_limits', 10, 3 );
}


if ( !function_exists( 'stm_delete_media' ) ) {
    function stm_delete_media( $media_id )
    {
        $current_user = wp_get_current_user();
        $media_id = intval( $media_id );
        if ( !empty( $current_user->ID ) ) {
            $current_user_id = $current_user->ID;

            $args = array(
                'author' => intval( $current_user_id ),
                'post_status' => 'any',
                'post__in' => array( $media_id ),
                'post_type' => 'attachment'
            );

            $query = new WP_Query( $args );

            if ( $query->found_posts == 1 ) {
                wp_delete_attachment( $media_id, true );
            }
        }
    }
}

if ( !function_exists( 'stm_data_binding' ) ) {
    function stm_data_binding( $allowAll = false )
    {
        $attributes = stm_get_car_parent_exist();

        $bind_tax = array();
        $depends = array();
        foreach ( $attributes as $attr ) {

            $parent = $attr['listing_taxonomy_parent'];
            $slug = $attr['slug'];

            $depends[] = array( 'parent' => $parent, 'dep' => $slug );


            if ( !isset( $bind_tax[$parent] ) ) {
                $bind_tax[$parent] = array();
            }

            $bind_tax[$slug] = array(
                'dependency' => $parent,
                'allowAll' => $allowAll,
                'options' => [],
            );

            /** @var WP_Term $term */

            foreach ( stm_get_category_by_slug_all( $slug ) as $term ) {
                $deps = array_values( array_filter( (array)get_term_meta( $term->term_id, 'stm_parent' ) ) );

                $bind_tax[$slug]['options'][] = array(
                    'value' => $term->slug,
                    'label' => $term->name,
                    'count' => $term->count,
                    'deps' => $deps,
                );
            }
        }

        $sortDeps = array();

        for ( $q = 0; $q < count( $depends ); $q++ ) {
            if ( $q == 0 ) {
                $sortDeps[] = $depends[$q]['parent'];
                $sortDeps[] = $depends[$q]['dep'];
            } else {
                if ( in_array( $depends[$q]['dep'], $sortDeps ) ) {
                    array_splice( $sortDeps, array_search( $depends[$q]['dep'], $sortDeps ), 0, $depends[$q]['parent'] );
                } elseif ( in_array( $depends[$q]['parent'], $sortDeps ) ) {
                    array_splice( $sortDeps, array_search( $depends[$q]['parent'], $sortDeps ) + 1, 0, $depends[$q]['dep'] );
                } elseif ( !in_array( $depends[$q]['parent'], $sortDeps ) ) {
                    array_splice( $sortDeps, 0, 0, $depends[$q]['parent'] );
                    array_splice( $sortDeps, count( $sortDeps ), 0, $depends[$q]['dep'] );
                }
            }
        }

        $newBindTax = array();

        foreach ( $sortDeps as $val ) {
            $newBindTax[$val] = $bind_tax[$val];
        }

        return apply_filters( 'stm_data_binding', $newBindTax );
    }
}

if ( !function_exists( 'stm_is_site_demo_mode' ) ) {
    function stm_is_site_demo_mode()
    {

        $site_demo_mode = get_theme_mod( 'site_demo_mode', false );

        return $site_demo_mode;
    }
}


if ( !function_exists( 'stm_payment_enabled' ) ) {
    function stm_payment_enabled()
    {
        $paypal_options = array(
            'enabled' => false
        );

        $paypal_email = get_theme_mod( 'paypal_email', '' );
        $paypal_currency = get_theme_mod( 'paypal_currency', 'USD' );
        $paypal_mode = get_theme_mod( 'paypal_mode', 'sandbox' );
        $membership_cost = get_theme_mod( 'membership_cost', '' );


        if ( !empty( $paypal_email ) and !empty( $paypal_currency ) and !empty( $paypal_mode ) and !empty( $membership_cost ) ) {
            $paypal_options['enabled'] = true;
        }

        $paypal_options['email'] = $paypal_email;
        $paypal_options['currency'] = $paypal_currency;
        $paypal_options['mode'] = $paypal_mode;
        $paypal_options['price'] = $membership_cost;


        return $paypal_options;
    }
}

if ( !function_exists( 'stm_generatePayment' ) ) {

    function stm_generatePayment()
    {

        $user = wp_get_current_user();

        if ( !empty( $user->ID ) ) {

            $user_id = $user->ID;

            $return['result'] = true;

            $base = 'https://' . stm_paypal_url() . '/cgi-bin/webscr';

            $return_url = add_query_arg( array( 'become_dealer' => 1 ), stm_get_author_link( $user_id ) );

            $url_args = array(
                'cmd' => '_xclick',
                'business' => get_theme_mod( 'paypal_email', '' ),
                'item_name' => $user->data->user_login,
                'item_number' => $user_id,
                'amount' => get_theme_mod( 'membership_cost', '' ),
                'no_shipping' => '1',
                'no_note' => '1',
                'currency_code' => get_theme_mod( 'paypal_currency', 'USD' ),
                'bn' => 'PP%2dBuyNowBF',
                'charset' => 'UTF%2d8',
                'invoice' => $user_id,
                'return' => $return_url,
                'rm' => '2',
                'notify_url' => home_url()
            );

            $return = add_query_arg( $url_args, $base );
        }

        return $return;

    }
}

if ( !function_exists( 'stm_get_dealer_list_page' ) ) {
    function stm_get_dealer_list_page()
    {
        $dealer_list_page = get_theme_mod( 'dealer_list_page', 2173 );

        $dealer_list_page = stm_motors_wpml_is_page( $dealer_list_page );

        $link = get_permalink( $dealer_list_page );

        return $link;
    }
}

function stm_motors_pa( $arr )
{
    echo '<pre>';
    print_r( $arr );
    echo '</pre>';
}

//Add user custom color styles
if ( !function_exists( 'stm_print_styles_color' ) ) {
    function stm_print_styles_color()
    {
        $css = "";
        $css_listing = '';
        $css_magazine = '';

        $layout = stm_get_current_layout();
        $site_color_style = get_theme_mod( 'site_style' );

        $predefined_colors = array(
            'dealer' => array(
                'site_style_blue' => array(
                    'primary' => '#7c9fda',
                    'secondary' => '#dd8411'
                ),
                'site_style_light_blue' => array(
                    'primary' => '#2ea6b8',
                    'secondary' => '#2ea6b8'
                ),
                'site_style_orange' => array(
                    'primary' => '#58ba3a',
                    'secondary' => '#58ba3a'
                ),
                'site_style_red' => array(
                    'primary' => '#e41515',
                    'secondary' => '#e41515'
                ),
                'site_style_yellow' => array(
                    'primary' => '#ecbf24',
                    'secondary' => '#22b7d2'
                ),
            ),
            'listing_four' => array(
                'site_style_blue' => array(
                    'primary' => '#7c9fda',
                    'secondary' => '#dd8411'
                ),
                'site_style_light_blue' => array(
                    'primary' => '#2ea6b8',
                    'secondary' => '#2ea6b8'
                ),
                'site_style_orange' => array(
                    'primary' => '#58ba3a',
                    'secondary' => '#58ba3a'
                ),
                'site_style_red' => array(
                    'primary' => '#e41515',
                    'secondary' => '#e41515'
                ),
                'site_style_yellow' => array(
                    'primary' => '#ecbf24',
                    'secondary' => '#22b7d2'
                ),
            ),
            'classified' => array(
                'site_style_blue' => array(
                    'primary' => '#7c9fda', /*light blue*/
                    'secondary' => '#7c9fda',
                    'primary_listing' => '#7c9fda',
                    'secondary_listing' => '#121e24', /*Dark one*/
                ),
                'site_style_light_blue' => array(
                    'primary' => '#2ea6b8',
                    'secondary' => '#2ea6b8',
                    'primary_listing' => '#2ea6b8',
                    'secondary_listing' => '#1d2428'
                ),
                'site_style_orange' => array(
                    'primary' => '#2d8611',
                    'secondary' => '#2d8611',
                    'primary_listing' => '#2d8611',
                    'secondary_listing' => '#202a30'
                ),
                'site_style_red' => array(
                    'primary' => '#e41515',
                    'secondary' => '#e41515',
                    'primary_listing' => '#e41515',
                    'secondary_listing' => '#333'
                ),
                'site_style_yellow' => array(
                    'primary' => '#ecbf24',
                    'secondary' => '#22b7d2',
                    'primary_listing' => '#ecbf24',
                    'secondary_listing' => '#333'
                ),
            ),
            'boats' => array(
                'site_style_blue' => array(
                    'primary' => '#31a3c6',
                    'secondary' => '#ffa07a',
                    'third' => '#211133',
                ),
                'site_style_light_blue' => array(
                    'primary' => '#31a3c6',
                    'secondary' => '#21d99b',
                    'third' => '#004015',
                ),
                'site_style_orange' => array(
                    'primary' => '#31a3c6',
                    'secondary' => '#58ba3a',
                    'third' => '#102d40',
                ),
                'site_style_red' => array(
                    'primary' => '#31a3c6',
                    'secondary' => '#e41515',
                    'third' => '#232628',
                )
            ),
            'magazine' => array(
                'site_style_blue' => array(
                    'primary' => '#18ca3e',
                    'secondary' => '#3c98ff',
                    'third' => '#ff1b1b',
                ),
            ),
            'dealer_two' => array(
                'site_style_blue' => array(
                    'primary' => '#4971ff',
                    'secondary' => '#ffb129',
                    'third' => '#3350b8',
                    'four' => '#ffb100'
                ),
            ),
            'listing_two' => array(
                'site_style_blue' => array(
                    'primary' => '#269aff',
                    'secondary' => '#1e7bcc',
                    'third' => '#2289e2',
                    'four' => '#2289e2'
                ),
            ),
            'listing_three' => array(
                'site_style_blue' => array(
                    'primary' => '#ff9500',
                    'secondary' => '#cc7700',
                    'third' => '#2289e2',
                    'four' => '#2289e2'
                ),
            ),
            'auto_parts' => array(
                'site_style_blue' => array(
                    'primary' => '#cc6119',
                    'secondary' => '#6c98e1',
                    'third' => '#cc6119',
                    'four' => '#cc6119'
                ),
            ),
            'aircrafts' => array(
                'site_style_blue' => array(
                    'primary' => '#6c98e1',
                    'secondary' => '#cc6119',
                    'third' => '#4c94fa',
                    'four' => '#ff9420'
                ),
            ),

        );

        if ( $site_color_style != 'site_style_default' ) {

            $colors_differences = false;
            $colors_arr = array();

            global $wp_filesystem;

            if ( empty( $wp_filesystem ) ) {
                require_once ABSPATH . '/wp-admin/includes/file.php';
                WP_Filesystem();
            }

            $theme_path = get_template_directory_uri() . '/assets/';

            /*Motorcycle*/
            if ( $layout == 'motorcycle' ) {
                $custom_style_css = $wp_filesystem->get_contents( get_template_directory() . '/assets/css/motorcycle/app.css' );
                $base_color = get_theme_mod( 'site_style_base_color', '#df1d1d' );
                $secondary_color = get_theme_mod( 'site_style_secondary_color', '#2f3c40' );

                $colors_arr[] = $base_color;
                $colors_arr[] = $secondary_color;

                $custom_style_css = str_replace(
                    array(
                        '#df1d1d', //1
                        '#2f3c40', //2
                        '#243136', //3
                        '#1d282c', //4
                        '#272e36', //5
                        '#27829e',
                        '#1b92a8',
                        '36,49,54',
                        '36, 49, 54',
                        '../../',
                        '#b11313',
                        '#d11717',
                        '#b01b1c'
                    ),
                    array(
                        $base_color, //1
                        $secondary_color, //2
                        $secondary_color, //3
                        'rgba(' . stm_hex2rgb( $secondary_color ) . ', 0.8)', //4
                        $secondary_color, //5
                        'rgba(' . stm_hex2rgb( $base_color ) . ', 0.75)',
                        'rgba(' . stm_hex2rgb( $secondary_color ) . ', 0.8)',
                        stm_hex2rgb( $secondary_color ),
                        stm_hex2rgb( $secondary_color ),
                        $theme_path,
                        'rgba(' . stm_hex2rgb( $base_color ) . ', 0.75)',
                        'rgba(' . stm_hex2rgb( $base_color ) . ', 0.75)',
                        $base_color, //1
                    ),
                    $custom_style_css
                );
                $css .= $custom_style_css;
            } else {

                if ( $layout !== 'boats' ) {

                    /*Rental*/
                    $custom_style_css = $wp_filesystem->get_contents( get_template_directory() . '/assets/css/rental/app.css' );
                    $base_color = get_theme_mod( 'site_style_base_color', '#f0c540' );
                    $secondary_color = get_theme_mod( 'site_style_secondary_color', '#2a4045' );

                    $colors_arr[] = $base_color;
                    $colors_arr[] = $secondary_color;

                    $custom_style_css = str_replace(
                        array(
                            '#f0c540',
                            '#2a4045',
                            '../../'
                        ),
                        array(
                            $base_color,
                            $secondary_color,
                            $theme_path,
                        ),
                        $custom_style_css
                    );
                    $css .= $custom_style_css;


                    /*Dealer*/
                    $custom_style_css = $wp_filesystem->get_contents( get_template_directory() . '/assets/css/app.css' );

                    if ( $site_color_style == 'site_style_custom' ) {
                        $base_color = get_theme_mod( 'site_style_base_color', '#183650' );
                        $secondary_color = get_theme_mod( 'site_style_secondary_color', '#34ccff' );
                    } else {
                        $base_color = $predefined_colors['dealer'][$site_color_style]['primary'];
                        $secondary_color = $predefined_colors['dealer'][$site_color_style]['secondary'];
                    }


                    $colors_arr[] = $base_color;
                    $colors_arr[] = $secondary_color;

                    $custom_style_css = str_replace(
                        array(
                            '#cc6119',
                            '#6c98e1',
                            '#567ab4',
                            '#6c98e1',
                            '#1b92a8',
                            '204, 97, 25',
                            '#ecbf24',
                            '#22b7d2',
                            '../'
                        ),
                        array(
                            $base_color,
                            $secondary_color,
                            'rgba(' . stm_hex2rgb( $secondary_color ) . ', 0.75)',
                            'rgba(' . stm_hex2rgb( $secondary_color ) . ', 0.75)',
                            'rgba(' . stm_hex2rgb( $secondary_color ) . ', 0.8)',
                            stm_hex2rgb( $base_color ),
                            $base_color,
                            $secondary_color,
                            $theme_path,
                        ),
                        $custom_style_css
                    );
                    $css .= $custom_style_css;

                    /*Listing Four*/
                    if(stm_is_listing_four()) {
                        $custom_style_css = $wp_filesystem->get_contents( get_template_directory() . '/assets/css/app.css' );
                        $custom_style_css .= $wp_filesystem->get_contents( get_template_directory() . '/assets/css/listing_four/app.css' );

                        if ( $site_color_style == 'site_style_custom' ) {
                            $base_color = get_theme_mod( 'site_style_base_color', '#183650' );
                            $secondary_color = get_theme_mod( 'site_style_secondary_color', '#34ccff' );
                        } else {
                            $base_color = $predefined_colors['dealer'][$site_color_style]['primary'];
                            $secondary_color = $predefined_colors['dealer'][$site_color_style]['secondary'];
                        }


                        $colors_arr[] = $base_color;
                        $colors_arr[] = $secondary_color;

                        $custom_style_css = str_replace(
                            array(
                                '#cc6119',
                                '#6c98e1',
                                '#567ab4',
                                '#6c98e1',
                                '#1b92a8',
                                '204, 97, 25',
                                '#ecbf24',
                                '#22b7d2',
                                '../'
                            ),
                            array(
                                $base_color,
                                $secondary_color,
                                'rgba(' . stm_hex2rgb( $secondary_color ) . ', 0.75)',
                                'rgba(' . stm_hex2rgb( $secondary_color ) . ', 0.75)',
                                'rgba(' . stm_hex2rgb( $secondary_color ) . ', 0.8)',
                                stm_hex2rgb( $base_color ),
                                $base_color,
                                $secondary_color,
                                $theme_path,
                            ),
                            $custom_style_css
                        );
                        $css = $custom_style_css;
                    }

                    /*Listing*/
                    $custom_style_css = $wp_filesystem->get_contents( get_template_directory() . '/assets/css/listing/app.css' );

                    if ( $site_color_style == 'site_style_custom' ) {
                        $base_color_listing = get_theme_mod( 'site_style_base_color_listing', '#1bc744' );
                        $secondary_color_listing = get_theme_mod( 'site_style_secondary_color_listing', '#153e4d' );
                    } else {
                        $base_color = $predefined_colors['classified'][$site_color_style]['primary'];
                        $secondary_color = $predefined_colors['classified'][$site_color_style]['secondary'];
                        $base_color_listing = $predefined_colors['classified'][$site_color_style]['primary_listing'];
                        $secondary_color_listing = $predefined_colors['classified'][$site_color_style]['secondary_listing'];
                    }

                    $colors_arr[] = $base_color_listing;
                    $colors_arr[] = $secondary_color_listing;

                    $custom_style_css = str_replace(
                        array(
                            '#1bc744',
                            '#153e4d',
                            '#169f36',
                            '#4e90cc',
                            '51,51,51,0.9',
                            '../../',
                            '#32cd57',
                            '#19b33e',
                            '#609bd1',
                            '#4782b8',
                            '27, 199, 68',
                            '#11323e',
                            '#133340'
                        ),
                        array(
                            $base_color_listing,
                            $secondary_color_listing,
                            'rgba(' . stm_hex2rgb( $base_color_listing ) . ', 0.75)',
                            $base_color,
                            stm_hex2rgb( $secondary_color_listing ) . ',0.8',
                            $theme_path,
                            'rgba(' . stm_hex2rgb( $base_color_listing ) . ', 1)',
                            'rgba(' . stm_hex2rgb( $base_color_listing ) . ', 0.8)',
                            'rgba(' . stm_hex2rgb( $secondary_color_listing ) . ', 1)',
                            'rgba(' . stm_hex2rgb( $secondary_color_listing ) . ', 0.8)',
                            stm_hex2rgb( $base_color_listing ),
                            'rgba(' . stm_hex2rgb( $secondary_color_listing ) . ', 0.8)',
                            'rgba(' . stm_hex2rgb( $secondary_color_listing ) . ', 1)',
                        ),
                        $custom_style_css
                    );
                    $css_listing .= $custom_style_css;

                    if ( stm_is_listing() ) {
                        $css .= $css_listing;
                    }

                    /*Magazine*/
                    if ( stm_is_magazine() ) {
                        $l = 'magazine';

                        $custom_style_css = $wp_filesystem->get_contents( get_template_directory() . '/assets/css/app.css' );
                        $custom_style_css .= $wp_filesystem->get_contents( get_template_directory() . '/assets/css/' . $l . '/app.css' );

                        if ( $site_color_style == 'site_style_custom' ) {
                            $base_color = get_theme_mod( 'site_style_base_color_listing', $predefined_colors[$l]['site_style_blue']['primary'] );
                            $secondary_color = get_theme_mod( 'site_style_secondary_color_listing', $predefined_colors[$l]['site_style_blue']['secondary'] );
                        } else {
                            $base_color = $predefined_colors[$l]['site_style_blue']['primary'];
                            $secondary_color = $predefined_colors[$l]['site_style_blue']['secondary'];
                        }

                        $colors_arr[] = $base_color;
                        $colors_arr[] = $secondary_color;

                        $custom_style_css = str_replace(
                            array(
                                '#cc6119',
                                '#6c98e1',
                                $predefined_colors[$l]['site_style_blue']['primary'],
                                $predefined_colors[$l]['site_style_blue']['secondary'],
                                '../../',
                                '../', ),
                            array(
                                $base_color,
                                $secondary_color,
                                $base_color,
                                $secondary_color,
                                $theme_path,
                                $theme_path,
                            ),
                            $custom_style_css
                        );

                        $css_magazine .= $custom_style_css;
                        $css = $css_magazine;
                    }

                    /*Dealler Two*/
                    if ( stm_is_dealer_two() || is_listing( array( 'listing_two', 'listing_three' ) ) ) {
                        $l = ( get_option( 'stm_motors_chosen_template' ) == 'car_dealer_two' ) ? 'dealer_two' : get_option( 'stm_motors_chosen_template' );


                        $custom_style_css = $wp_filesystem->get_contents( get_template_directory() . '/assets/css/app.css' );
                        if ( is_listing( array( 'listing_two', 'listing_three' ) ) ) $custom_style_css .= $wp_filesystem->get_contents( get_template_directory() . '/assets/css/listing/app.css' );

                        $custom_style_css .= $wp_filesystem->get_contents( get_template_directory() . '/assets/css/' . $l . '/app.css' );
                        $custom_style_css .= $wp_filesystem->get_contents( get_template_directory() . '/assets/css/vc_template_styles/stm_car_leasing.css' );

                        if ( $site_color_style == 'site_style_custom' ) {
                            $base_color = get_theme_mod( 'site_style_base_color', $predefined_colors[$l]['site_style_blue']['primary'] );
                            $secondary_color = get_theme_mod( 'site_style_secondary_color', $predefined_colors[$l]['site_style_blue']['secondary'] );
                            $third_color = get_theme_mod( 'site_style_base_color_listing', $predefined_colors[$l]['site_style_blue']['third'] );
                            $four_color = get_theme_mod( 'site_style_secondary_color_listing', $predefined_colors[$l]['site_style_blue']['four'] );
                        } else {
                            $base_color = $predefined_colors[$l]['site_style_blue']['primary'];
                            $secondary_color = $predefined_colors[$l]['site_style_blue']['secondary'];
                            $third_color = $predefined_colors[$l]['site_style_blue']['third'];
                            $four_color = $predefined_colors[$l]['site_style_blue']['four'];
                        }

                        $custom_style_css = str_replace(
                            array(
                                '#6c98e1',
                                '#cc6119',
                                '#1bc744',
                                '#169f36',
                                '#567ab4',
                                '#3c98ff',
                                '#18ca3e',
                                '27, 199, 68, 0.85',
                                $predefined_colors[$l]['site_style_blue']['primary'],
                                $predefined_colors[$l]['site_style_blue']['secondary'],
                                $predefined_colors[$l]['site_style_blue']['third'],
                                $predefined_colors[$l]['site_style_blue']['four'],
                                '../../',
                                '../',
                            ),
                            array(
                                $base_color,
                                $secondary_color,
                                $base_color,
                                $secondary_color,
                                $secondary_color,
                                $base_color,
                                $secondary_color,
                                stm_hex2rgb( $secondary_color ) . ',0.8',
                                $base_color,
                                $secondary_color,
                                $third_color,
                                $four_color,
                                $theme_path,
                                $theme_path,
                            ),
                            $custom_style_css
                        );

                        $css_magazine = $custom_style_css;
                        $css = $css_magazine;
                    }

                    /*Auto Parts*/
                    if ( stm_is_auto_parts() ) {
                        $l = 'auto_parts';

                        $custom_style_css = $wp_filesystem->get_contents( get_template_directory() . '/assets/css/auto-parts/app.css' );

                        $base_color = ( $site_color_style == 'site_style_custom' ) ? get_theme_mod( 'site_style_base_color', $predefined_colors[$l]['site_style_blue']['primary'] ) : $predefined_colors[$l]['site_style_blue']['primary'];
                        $secondary_color = ( $site_color_style == 'site_style_custom' ) ? get_theme_mod( 'site_style_secondary_color', $predefined_colors[$l]['site_style_blue']['secondary'] ) : $predefined_colors[$l]['site_style_blue']['secondary'];
                        $third_color = ( $site_color_style == 'site_style_custom' ) ? get_theme_mod( 'site_style_base_color_listing', $predefined_colors[$l]['site_style_blue']['third'] ) : $predefined_colors[$l]['site_style_blue']['third'];
                        $four_color = ( $site_color_style == 'site_style_custom' ) ? get_theme_mod( 'site_style_secondary_color_listing', $predefined_colors[$l]['site_style_blue']['four'] ) : $predefined_colors[$l]['site_style_blue']['four'];

                        $custom_style_css = str_replace(
                            array(
                                '#cc6119',
                                '#6c98e1',
                                '#1bc744',
                                '#169f36',
                                '#567ab4',
                                '#3c98ff',
                                '#18ca3e',
                                '27, 199, 68, 0.85',
                                $predefined_colors[$l]['site_style_blue']['primary'],
                                $predefined_colors[$l]['site_style_blue']['secondary'],
                                $predefined_colors[$l]['site_style_blue']['third'],
                                $predefined_colors[$l]['site_style_blue']['four'],
                                '../../',
                                '../',
                            ),
                            array(
                                $base_color,
                                $secondary_color,
                                $base_color,
                                $secondary_color,
                                $secondary_color,
                                $base_color,
                                $secondary_color,
                                stm_hex2rgb( $secondary_color ) . ',0.8',
                                $base_color,
                                $secondary_color,
                                $third_color,
                                $four_color,
                                $theme_path,
                                $theme_path,
                            ),
                            $custom_style_css
                        );

                        $css = $custom_style_css;
                    }

                    /*Aircrafts*/
                    if ( stm_is_aircrafts() ) {
                        $l = 'aircrafts';

                        $custom_style_css = $wp_filesystem->get_contents( get_template_directory() . '/assets/css/app.css' );
                        $custom_style_css .= $wp_filesystem->get_contents( get_template_directory() . '/assets/css/app-aircrafts.css' );

                        $base_color = ( $site_color_style == 'site_style_custom' ) ? get_theme_mod( 'site_style_base_color', $predefined_colors[$l]['site_style_blue']['primary'] ) : $predefined_colors[$l]['site_style_blue']['primary'];
                        $secondary_color = ( $site_color_style == 'site_style_custom' ) ? get_theme_mod( 'site_style_secondary_color', $predefined_colors[$l]['site_style_blue']['secondary'] ) : $predefined_colors[$l]['site_style_blue']['secondary'];
                        $third_color = ( $site_color_style == 'site_style_custom' ) ? get_theme_mod( 'site_style_base_color_listing', $predefined_colors[$l]['site_style_blue']['third'] ) : $predefined_colors[$l]['site_style_blue']['third'];
                        $four_color = ( $site_color_style == 'site_style_custom' ) ? get_theme_mod( 'site_style_secondary_color_listing', $predefined_colors[$l]['site_style_blue']['four'] ) : $predefined_colors[$l]['site_style_blue']['four'];

                        $custom_style_css = str_replace(
                            array(
                                '#6c98e1',
                                '#cc6119',
                                '#1bc744',
                                '#169f36',
                                '#567ab4',
                                '#3c98ff',
                                '#18ca3e',
                                '27, 199, 68, 0.85',
                                $predefined_colors[$l]['site_style_blue']['primary'],
                                $predefined_colors[$l]['site_style_blue']['secondary'],
                                $predefined_colors[$l]['site_style_blue']['third'],
                                $predefined_colors[$l]['site_style_blue']['four'],
                                '../../',
                                '../',
                            ),
                            array(
                                $base_color,
                                $secondary_color,
                                $base_color,
                                $secondary_color,
                                $third_color,
                                $base_color,
                                $secondary_color,
                                stm_hex2rgb( $secondary_color ) . ',0.8',
                                $base_color,
                                $secondary_color,
                                $third_color,
                                $four_color,
                                $theme_path,
                                $theme_path,
                            ),
                            $custom_style_css
                        );

                        $css = $custom_style_css;
                    }
                } else {
                    /*Boats*/
                    $custom_style_css = $wp_filesystem->get_contents( get_template_directory() . '/assets/css/boats/app.css' );

                    if ( $site_color_style == 'site_style_custom' ) {
                        $base_color = get_theme_mod( 'site_style_base_color', '#31a3c6' );
                        $secondary_color = get_theme_mod( 'site_style_secondary_color', '#ceac61' );
                        $third_color = get_theme_mod( 'site_style_base_color_listing', '#002568' );
                    } else {
                        $base_color = $predefined_colors['boats'][$site_color_style]['primary'];
                        $secondary_color = $predefined_colors['boats'][$site_color_style]['secondary'];
                        $third_color = $predefined_colors['boats'][$site_color_style]['third'];
                    }

                    $colors_arr[] = $base_color;
                    $colors_arr[] = $secondary_color;
                    $colors_arr[] = $third_color;

                    $custom_style_css = str_replace(
                        array(
                            '#31a3c6',
                            '#ceac61',
                            '#002568',
                            '#27829e',
                            '#1b92a8',
                            '204, 97, 25',
                            '../../',
                        ),
                        array(
                            $base_color,
                            $secondary_color,
                            $third_color,
                            'rgba(' . stm_hex2rgb( $base_color ) . ', 0.75)',
                            'rgba(' . stm_hex2rgb( $secondary_color ) . ', 0.8)',
                            stm_hex2rgb( $base_color ),
                            $theme_path,
                        ),
                        $custom_style_css
                    );
                    $css .= $custom_style_css;
                }
            }

            $upload_dir = wp_upload_dir();

            if ( !$wp_filesystem->is_dir( $upload_dir['basedir'] . '/stm_uploads' ) ) {
                do_action( 'stm_create_dir' );
            }

            if ( $custom_style_css ) {
                $css_to_filter = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
                $css_to_filter = str_replace( array(
                    "\r\n",
                    "\r",
                    "\n",
                    "\t",
                    '  ',
                    '    ',
                    '    '
                ), '', $css_to_filter );

                $custom_style_file = $upload_dir['basedir'] . '/stm_uploads/skin-custom.css';

                $wp_filesystem->put_contents( $custom_style_file, $css_to_filter, FS_CHMOD_FILE );

                $current_style = get_option( 'stm_custom_style', '4' );
                update_option( 'stm_custom_style', $current_style + 1 );
            }
        }
    }
}

add_action( 'customize_save_after', 'stm_print_styles_color' );

if ( !function_exists( 'stm_boats_styles' ) ) {
    function stm_boats_styles()
    {
        $front_css = '';

        if ( stm_is_boats() ) {
            $header_bg_color = get_theme_mod( 'header_bg_color', '#002568' );
            $top_bar_bg_color = get_theme_mod( 'top_bar_bg_color', '#002568' );

            $front_css .= '
				#stm-boats-header #top-bar:after {
					background-color: ' . $top_bar_bg_color . ';
				}
				#stm-boats-header #header:after {
					background-color: ' . $header_bg_color . ';
				}
			';
        }

        if ( stm_is_motorcycle() or stm_is_rental() ) {
            $header_bg_color = get_theme_mod( 'header_bg_color', '#002568' );


            $front_css .= '
				.stm_motorcycle-header {
					background-color: ' . $header_bg_color . ';
				}
			';

            $defColor = ( stm_is_rental() ) ? '#eeeeee' : '#0e1315';

            $site_bg = get_theme_mod( 'site_bg_color', $defColor );

            $front_css .= '
				#wrapper {
					background-color: ' . $site_bg . ' !important;
				}
				.stm-single-car-page:before,
				.stm-simple-parallax .stm-simple-parallax-gradient:before {
					background: -moz-linear-gradient(left, rgba(' . stm_hex2rgb( $site_bg ) . ',1) 0%, rgba(' . stm_hex2rgb( $site_bg ) . ',0) 100%);
					background: -webkit-linear-gradient(left, rgba(' . stm_hex2rgb( $site_bg ) . ',1) 0%,rgba(' . stm_hex2rgb( $site_bg ) . ',0) 100%);
					background: linear-gradient(to right, rgba(' . stm_hex2rgb( $site_bg ) . ',1) 0%,rgba(' . stm_hex2rgb( $site_bg ) . ',0) 100%);
					filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#0e1315\', endColorstr=\'#000e1315\',GradientType=1 ); /* IE6-9 */
				}
				.stm-single-car-page:after,
				.stm-simple-parallax .stm-simple-parallax-gradient:after {
					background: -moz-linear-gradient(left, rgba(' . stm_hex2rgb( $site_bg ) . ',0) 0%, rgba(' . stm_hex2rgb( $site_bg ) . ',1) 99%, rgba(' . stm_hex2rgb( $site_bg ) . ',1) 100%);
					background: -webkit-linear-gradient(left, rgba(' . stm_hex2rgb( $site_bg ) . ',0) 0%,rgba(' . stm_hex2rgb( $site_bg ) . ',1) 99%,rgba(' . stm_hex2rgb( $site_bg ) . ',1) 100%);
					background: linear-gradient(to right, rgba(' . stm_hex2rgb( $site_bg ) . ',0) 0%,rgba(' . stm_hex2rgb( $site_bg ) . ',1) 99%,rgba(' . stm_hex2rgb( $site_bg ) . ',1) 100%);
					filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#000e1315\', endColorstr=\'#0e1315\',GradientType=1 );
				}
			';

            $stm_single_car_page = get_theme_mod( 'stm_single_car_page' );

            if ( !empty( $stm_single_car_page ) ) {
                $front_css .= '
				.stm-single-car-page {
					background-image: url(" ' . $stm_single_car_page . ' ");
				}
			';
            }
            wp_add_inline_style( 'stm-theme-style', $front_css );
        }

        if ( stm_is_dealer_two() ) {
            $stm_single_car_page = get_theme_mod( 'stm_single_car_page' );

            if ( !empty( $stm_single_car_page ) && ( is_singular( array( stm_listings_post_type() ) ) || is_post_type_archive( stm_listings_post_type() ) || ( get_the_ID() == get_theme_mod( 'listing_archive', '' ) ) ) ) {
                $front_css = '
				#main {
					background-image: url(" ' . $stm_single_car_page . ' ");
                    background-repeat: no-repeat;					
				}
			';
                wp_add_inline_style( 'stm-theme-style', $front_css );
            }
        }

        if ( stm_is_aircrafts() ) {
            $header_bg_color = get_theme_mod( 'header_bg_color', '#002568' );
            $top_bar_bg_color = get_theme_mod( 'top_bar_bg_color', '#002568' );

            $front_css .= '
				#wrapper #top-bar {
					background-color: ' . $top_bar_bg_color . ' !important;
				}
				#wrapper #header {
					background-color: ' . $header_bg_color . ' !important;
				}
			';
        }

        if ( get_theme_mod( 'site_style', 'site_style_default' ) == 'site_style_default' ) {
            wp_add_inline_style( 'stm-theme-style', $front_css );
        }
    }
}

add_action( 'wp_enqueue_scripts', 'stm_boats_styles' );

if ( !function_exists( 'stm_get_boats_image_hover' ) ) {
    function stm_get_boats_image_hover( $id )
    {
        $car_media = stm_get_car_medias( $id );

        $dynamicClassPhoto = 'stm-car-photos-' . $id . '-' . rand();
        $dynamicClassVideo = 'stm-car-videos-' . $id . '-' . rand();

        echo '<div class="boats-image-unit">';
        if ( !empty( $car_media['car_photos_count'] ) ): ?>
            <div class="stm-listing-photos-unit stm-car-photos-<?php echo get_the_id(); ?> <?php echo esc_attr( $dynamicClassPhoto ); ?>">
                <i class="stm-boats-icon-camera"></i>
                <span><?php echo esc_html( $car_media['car_photos_count'] ); ?></span>
            </div>

            <script>
                jQuery(document).ready(function () {

                    jQuery(".<?php echo esc_attr( $dynamicClassPhoto ); ?>").on('click', function (e) {
                        e.preventDefault();
                        jQuery(this).lightGallery({
                            dynamic: true,
                            dynamicEl: [
                                <?php foreach($car_media['car_photos'] as $car_photo): ?>
                                {
                                    src: "<?php echo esc_url( $car_photo ); ?>"
                                },
                                <?php endforeach; ?>
                            ],
                            download: false,
                            mode: 'lg-fade',
                        })

                    });
                });

            </script>
        <?php endif; ?>
        <?php if ( !empty( $car_media['car_videos_count'] ) ): ?>
        <div class="stm-listing-videos-unit stm-car-videos-<?php echo get_the_id(); ?> <?php echo esc_attr( $dynamicClassVideo ); ?>">
            <i class="stm-boats-icon-movie"></i>
            <span><?php echo esc_html( $car_media['car_videos_count'] ); ?></span>
        </div>

        <script>
            jQuery(document).ready(function () {

                jQuery(".<?php echo esc_attr( $dynamicClassVideo ); ?>").on('click', function (e) {
                    e.preventDefault();
                    jQuery(this).lightGallery({
                        dynamic: true,
                        iframe: true,
                        dynamicEl: [
                            <?php foreach($car_media['car_videos'] as $car_video): ?>
                            {
                                src: "<?php echo esc_url($car_video); ?>"
                            },
                            <?php endforeach; ?>
                        ],
                        download: false,
                        mode: 'lg-fade',
                    })

                }); //click
            }); //ready

        </script>
    <?php endif;
        echo '</div>';
    }
}

if ( !function_exists( 'stm_get_boats_comapre' ) ) {
    function stm_get_boats_compare( $id )
    {
        if ( !empty( $show_compare ) and $show_compare ): ?>
            <div
                    class="stm-listing-compare stm-compare-directory-new"
                    data-id="<?php echo esc_attr( get_the_id() ); ?>"
                    data-title="<?php echo stm_generate_title_from_slugs( get_the_id(), false ); ?>"
                    data-toggle="tooltip" data-placement="left"
                    title="<?php echo esc_attr__( 'Add to compare', 'motors' ); ?>"
            >
                <i class="stm-service-icon-compare-new"></i>
            </div>
        <?php endif;
    }
}

function stm_display_script_sort( $tax_info )
{
    ?>case '<?php echo esc_attr( $tax_info['slug'] . '_low' ); ?>':
    <?php
    $slug = sanitize_title( str_replace( '-', '_', $tax_info['slug'] ) );
    $sort_asc = 'true';
    $sort_desc = 'false';
    if ( !empty( $tax_info['numeric'] ) and $tax_info['numeric'] ) {
        $sort_asc = 'false';
        $sort_desc = 'true';
    }
    ?>
    $container.isotope({
    getSortData: {
    <?php echo esc_attr( $slug ); ?>: function( itemElem ) {
    <?php if ( !empty( $tax_info['numeric'] ) and $tax_info['numeric'] ): ?>
    var <?php echo esc_attr( $slug ); ?> = $(itemElem).data('<?php echo esc_attr( $tax_info['slug'] ); ?>');
    if(typeof(<?php echo esc_attr( $slug ); ?>) == 'undefined') {
    <?php echo esc_attr( $slug ); ?> = '0';
    }
    return parseFloat(<?php echo esc_attr( $slug ); ?>);
<?php else: ?>
    var <?php echo esc_attr( $slug ); ?> = $(itemElem).data('<?php echo esc_attr( $tax_info['slug'] ); ?>');
    if(typeof(<?php echo esc_attr( $slug ); ?>) == 'undefined') {
    <?php echo esc_attr( $slug ); ?> = 'n/a';
    }
    return <?php echo esc_attr( $slug ); ?>;
<?php endif; ?>

    }
    },
    sortBy: '<?php echo esc_attr( $slug ) ?>',
    sortAscending: <?php echo esc_attr( $sort_asc ); ?>
    });
    break
    case '<?php echo esc_attr( $tax_info['slug'] . '_high' ); ?>':
    $container.isotope({
    getSortData: {
    <?php echo esc_attr( $slug ); ?>: function( itemElem ) {
    <?php if ( !empty( $tax_info['numeric'] ) and $tax_info['numeric'] ): ?>
    var <?php echo esc_attr( $slug ); ?> = $(itemElem).data('<?php echo esc_attr( $tax_info['slug'] ); ?>');
    if(typeof(<?php echo esc_attr( $slug ); ?>) == 'undefined') {
    <?php echo esc_attr( $slug ); ?> = '0';
    }
    return parseFloat(<?php echo esc_attr( $slug ); ?>);
<?php else: ?>
    var <?php echo esc_attr( $slug ); ?> = $(itemElem).data('<?php echo esc_attr( $tax_info['slug'] ); ?>');
    if(typeof(<?php echo esc_attr( $slug ); ?>) == 'undefined') {
    <?php echo esc_attr( $slug ); ?> = 'n/a';
    }
    return <?php echo esc_attr( $slug ); ?>;
<?php endif; ?>

    }
    },
    sortBy: '<?php echo esc_attr( $tax_info['slug'] ); ?>',
    sortAscending: <?php echo esc_attr( $sort_desc ); ?>
    });
    break
    <?php
}

if ( !function_exists( 'stm_theme_add_body_class' ) ) {
    function stm_theme_add_body_class( $classes )
    {
        return "$classes stm-template-" . stm_get_current_layout();
    }
}

add_filter( 'stm_listings_admin_body_class', 'stm_theme_add_body_class' );

if ( !function_exists( 'stm_display_wpml_switcher' ) ) {
    function stm_display_wpml_switcher( $langs = array() )
    {
        if ( !empty( apply_filters( 'stm_get_global_server_val', 'HTTP_HOST' ) ) ) {
            $server_uri = apply_filters( 'stm_get_global_server_val', 'HTTP_HOST' );
            if ( $server_uri == 'motors.stm' or $server_uri == 'motors.stylemixthemes.com' ) {
                $langs = array(
                    'en' => array(
                        'active' => 1,
                        'url' => '#',
                        'native_name' => esc_html__( 'English', 'motors' )
                    ),
                    'fr' => array(
                        'active' => 0,
                        'url' => '#',
                        'native_name' => esc_html__( 'Français', 'motors' )
                    ),
                );

                $lang_name = esc_html__( 'English', 'motors' );
            }
        }

        if ( !empty( $langs ) ): ?>
            <!--LANGS-->
            <?php
            if ( count( $langs ) > 1 ) {
                $langs_exist = 'dropdown_toggle';
            } else {
                $langs_exist = 'no_other_langs';
            }
            if ( defined( 'ICL_LANGUAGE_NAME' ) ) {
                $lang_name = ICL_LANGUAGE_NAME;
            }
            ?>
            <div class="pull-left language-switcher-unit">
                <div
                        class="stm_current_language <?php echo esc_attr( $langs_exist ); ?>" <?php if ( count( $langs ) > 1 ) { ?> id="lang_dropdown" data-toggle="dropdown" <?php } ?>><?php echo esc_attr( $lang_name ); ?><?php if ( count( $langs ) > 1 ) { ?>
                        <i class="fa fa-angle-down"></i><?php } ?></div>
                <?php if ( count( $langs ) > 1 ): ?>
                    <ul class="dropdown-menu lang_dropdown_menu" role="menu" aria-labelledby="lang_dropdown">
                        <?php foreach ( $langs as $lang ): ?>
                            <?php if ( !$lang['active'] ): ?>
                                <li role="presentation"><a role="menuitem" tabindex="-1"
                                                           href="<?php echo esc_url( $lang['url'] ); ?>"><?php echo esc_attr( $lang['native_name'] ); ?></a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endif;
    }
}

if ( !function_exists( 'stm_listing_filter_get_selects' ) ) {
    function stm_listing_filter_get_selects( $select_strings, $tab_name = '', $words = array(), $show_amount = 'yes', $isSlide = false )
    {
        if ( !empty( $select_strings ) ) {
            $select_strings = explode( ',', $select_strings );

            if ( !empty( $select_strings ) ) {
                $i = 0;
                $output = '';
                $output .= '<div class="row">';
                foreach ( $select_strings as $select_string ) {

                    if ( $isSlide && $i == 4 && count( $select_strings ) > 4 ) $output .= '<div class="stm-slide-content">';

                    //col-md dynamic
                    $colMd = ( count( $select_strings ) < 3 ) ? 'col-md-6' : 'col-md-3';

                    $output .= '<div class="' . $colMd . ' col-sm-6 col-xs-12 stm-select-col">';
                    //if price
                    if ( $select_string == 'price' ) {
                        $args = array(
                            'orderby' => 'name',
                            'order' => 'ASC',
                            'hide_empty' => false,
                            'fields' => 'all',
                        );

                        $prices = array();

                        $terms = get_terms( 'price', $args );

                        if ( !empty( $terms ) ) {
                            foreach ( $terms as $term ) {
                                $prices[] = intval( $term->name );
                            }
                            sort( $prices );
                        }

                        $number_string = '';

                        if ( !empty( $words['number_prefix'] ) ) {
                            $number_string .= $words['number_prefix'] . ' ';
                        } else {
                            $number_string = esc_html__( 'Max', 'motors' ) . ' ';
                        }

                        $number_string .= stm_dynamic_string_translation('Select Text', stm_get_name_by_slug( $select_string ));

                        if ( !empty( $words['number_affix'] ) ) {
                            $number_string .= ' ' . $words['number_affix'];
                        }

                        $output .= '<select class="stm-filter-ajax-disabled-field" name="max_price" data-class="stm_select_overflowed">';
                        $output .= '<option value="">' . $number_string . '</option>';
                        if ( !empty( $terms ) ) {
                            foreach ( $prices as $price ) {
                                $output .= '<option value="' . esc_attr( $price ) . '">' . stm_listing_price_view( $price ) . '</option>';
                            }
                        }
                        $output .= '</select>';
                    } else {
                        $taxonomy_info = stm_get_taxonomies_with_type( $select_string );

                        //If numeric
                        if ( !empty( $taxonomy_info['numeric'] ) and $taxonomy_info['numeric'] ) {
                            $args = array(
                                'orderby' => 'name',
                                'order' => 'ASC',
                                'hide_empty' => false,
                                'fields' => 'all',
                            );
                            $numbers = array();

                            $terms = get_terms( $select_string, $args );

                            if ( !empty( $terms ) ) {
                                foreach ( $terms as $term ) {
                                    $numbers[] = intval( $term->name );
                                }
                            }
                            sort( $numbers );

                            if ( !empty( $numbers ) ) {
                                // name="max_' . $select_string . '"
                                $output .= '<select name="' . $select_string . '" data-class="stm_select_overflowed" data-sel-type="' . esc_attr( $select_string ) . '">';
                                $output .= '<option value="">' . stm_dynamic_string_translation('Option text', stm_get_name_by_slug( $select_string )) . '</option>';
                                foreach ( $numbers as $number_key => $number_value ) {
                                    if ( $number_key == 0 ) {
                                        $output .= '<option value="' . sprintf( '< %s', esc_attr( $number_value ) ) . '">< ' . $number_value . '</option>';
                                    } elseif ( count( $numbers ) - 1 == $number_key ) {
                                        $output .= '<option value="' . sprintf( '> %s', esc_attr( $number_value ) ) . '">> ' . $number_value . '</option>';
                                    } else {
                                        $option_value = $numbers[( $number_key - 1 )] . '-' . $number_value;
                                        $option_name = $numbers[( $number_key - 1 )] . '-' . $number_value;
                                        $output .= '<option value="' . esc_attr( $option_value ) . '"> ' . $option_name . '</option>';
                                    }
                                }
                                $output .= '<input type="hidden" name="min_' . $select_string . '"/>';
                                $output .= '<input type="hidden" name="max_' . $select_string . '"/>';
                                $output .= '</select>';
                            }
                            //other default values
                        } else {
                            if ( $select_string == 'location' ) {
                                $output .= '<div class="stm-location-search-unit">';
                                $output .= '<input type="text" placeholder="' . esc_attr__( 'Enter a location', 'motors' ) . '" class="stm_listing_filter_text stm_listing_search_location" id="stm-car-location-' . $tab_name . '" name="ca_location" />';
                                $output .= '<input type="hidden" name="stm_lat"/>';
                                $output .= '<input type="hidden" name="stm_lng"/>';
                                $output .= '</div>';
                            } else {
                                if ( !empty( $taxonomy_info['listing_taxonomy_parent'] ) ) {
                                    $terms = [];
                                } else {
                                    $terms = stm_get_category_by_slug_all( $select_string );
                                }

                                $select_main = '';
                                if ( !empty( $words['select_prefix'] ) ) {
                                    $select_main .= $words['select_prefix'] . ' ';
                                } else {
                                    $select_main .= esc_html__( "Choose", "motors" ) . ' ';
                                }

                                $select_main .= stm_dynamic_string_translation('Option select text', stm_get_name_by_slug( $select_string ));

                                if ( !empty( $words['select_affix'] ) ) {
                                    $select_main .= ' ' . $words['select_affix'];
                                }

                                $output .= '<div class="stm-ajax-reloadable">';
                                $output .= '<select name="' . esc_attr( $select_string ) . '" data-class="stm_select_overflowed">';
                                $output .= '<option value="">' . $select_main . '</option>';
                                if ( !empty( $terms ) ) {
                                    foreach ( $terms as $term ) {
                                        if ( $show_amount == 'yes' ) {
                                            $output .= '<option value="' . esc_attr( $term->slug ) . '">' . $term->name . ' (' . $term->count . ') </option>';
                                        } else {
                                            $output .= '<option value="' . esc_attr( $term->slug ) . '">' . $term->name . ' </option>';
                                        }
                                    }
                                }
                                $output .= '</select>';
                                $output .= '</div>';
                            }
                        }
                    }
                    $output .= '</div>';
                    if ( $isSlide && $i == count( $select_strings ) - 1 && count( $select_strings ) > 4 ) $output .= '</div>';
                    $i++;
                }
                $output .= '</div>';

                if ( !empty( $output ) ) {
                    echo stm_do_lmth( $output );
                }
            }
        }
    }
}

function stm_pricing_enabled()
{
    $enabled = get_theme_mod( 'enable_plans', false );
    if ( $enabled and class_exists( 'Subscriptio' ) ) {
        $enabled = true;
    } else {
        $enabled = false;
    }

    return ( $enabled );
}

function stm_pricing_link()
{
    $pricing_link = get_theme_mod( 'pricing_link', '' );
    if ( !empty( $pricing_link ) ) {
        $pricing_link = stm_motors_wpml_is_page( $pricing_link );
    }

    return get_permalink( $pricing_link );
}

//Filters from new plugin
if ( !function_exists( 'stm_filter_add_links' ) ) {
    function stm_filter_add_links( $taxes )
    {

        /*Filter links*/
        $filter_links = stm_get_car_filter_links();
        if ( !empty( $filter_links ) and !empty( $taxes ) ) {
            foreach ( $filter_links as $key => $tax ) {
                if ( !array_key_exists( $key, $taxes ) ) {
                    $taxes[] = $key;
                }
            }
        }

        /*Filter checkboxes*/
        $filter_checkboxes = stm_get_car_filter_checkboxes();
        if ( !empty( $filter_checkboxes ) and !empty( $taxes ) ) {
            foreach ( $filter_checkboxes as $key => $tax ) {
                if ( !array_key_exists( $key, $taxes ) ) {
                    $taxes[] = $key;
                }
            }
        }


        return $taxes;
    }

    //add_filter('stm_listings_filter_taxonomies', 'stm_filter_add_links');
}

if ( !function_exists( 'stm_listings_filter_classified_title' ) ) {
    function stm_listings_filter_classified_title( $params )
    {

        $title_default = get_theme_mod( 'listing_directory_title_default', esc_html__( 'Cars for sale', 'motors' ) );
        $title_generated_postfix = get_theme_mod( 'listing_directory_title_generated_affix', esc_html__( ' for sale', 'motors' ) );

        $title_response = '';

        $titles_args = stm_get_filter_title();
        $title_generated_counter = 0;
        foreach ( $titles_args as $title_arg ) {
            if ( !empty( $_GET[$title_arg['slug']] ) ) {
                $title_generated_counter++;
                if ( !is_array( $_GET[$title_arg['slug']] ) ) {
                    $category = get_term_by( 'slug', $_GET[$title_arg['slug']], $title_arg['slug'] );
                    if ( !empty( $category ) and !is_wp_error( $category ) ) {
                        $title_response .= ' ' . $category->name;
                    }
                }
            }
        }

        if ( empty( $title_response ) ) {
            $title_response = $title_default;
        } else {
            if ( $title_generated_counter == 1 ) {
                $title_response .= ' ' . strtolower( $title_default );
            } else {
                $title_response .= $title_generated_postfix;
            }
        }

        $params['listing_title'] = $title_response;

        return $params;
    }

    if ( stm_is_listing() or stm_is_motorcycle() || stm_is_dealer_two() or stm_is_listing_two() ) {
        add_filter( 'stm_listings_filter', 'stm_listings_filter_classified_title' );
    }
}

function stm_listing_pre_get_vehicles( $query_vars )
{
    if ( !empty( $_GET['featured_top'] ) ) {
        if ( stm_is_listing() ) {
            $query_vars['meta_query'] = array(
                array(
                    'key' => 'special_car',
                    'value' => 'on',
                    'compare' => '='
                ),
                $query_vars['meta_query']
            );
        } else {
            $query_vars['meta_query'][] = array(
                'key' => 'special_car',
                'value' => 'on',
                'compare' => '='
            );
        }
    }

    if ( !empty( $_GET['sale_cars'] ) ) {
        if ( is_listing() ) {
            $query_vars['meta_query'] = array(
                array(
                    'key' => 'sale_price',
                    'value' => '',
                    'compare' => '!='
                ),
                array(
                    'key' => 'car_price_form',
                    'value' => '',
                    'compare' => '=='
                ),
                $query_vars['meta_query']
            );
        } else {
            $query_vars['meta_query'] = array(
                array(
                    'key' => 'sale_price',
                    'value' => '',
                    'compare' => '!='
                ),
                array(
                    'key' => 'car_price_form',
                    'value' => '',
                    'compare' => '=='
                )
            );
        }
    }

    if ( !is_admin() ) {
        $posts_per_page = intval( stm_listings_input( 'posts_per_page' ) );

        if ( empty( $posts_per_page ) ) {
            $posts_per_page = get_theme_mod( 'listing_grid_choice', 9 );
        }

        $query_vars['posts_per_page'] = intval( $posts_per_page );

        if ( !empty( $_GET['stm-footer-search-name'] ) ) {
            $query_vars['s'] = sanitize_text_field($_GET['stm-footer-search-name']);
        }

        if ( !empty( $_GET['stm_features'] ) ) {
            $features = array();
            foreach ( $_GET['stm_features'] as $feature ) {
                $features[] = sanitize_title( $feature );
            }

            $query_vars['tax_query'][] = array(
                'relation' => 'OR',
                array(
                    'taxonomy' => 'stm_additional_features',
                    'field' => 'slug',
                    'terms' => $features
                )
            );
        }
    }

    return $query_vars;
}

add_filter( 'stm_listings_build_query_args', 'stm_listing_pre_get_vehicles', 20 );

if ( !function_exists( 'stm_get_sidebar_position' ) ) {
    function stm_get_sidebar_position()
    {
        $listing_filter_position = get_theme_mod( 'listing_filter_position', 'left' );
        if ( !empty( $_GET['filter_position'] ) and $_GET['filter_position'] == 'right' ) {
            $listing_filter_position = 'right';
        }

        $sidebar_pos_classes = '';
        $content_pos_classes = '';

        if ( $listing_filter_position == 'right' ) {
            $sidebar_pos_classes = 'col-md-push-9 col-sm-push-0';
            $content_pos_classes = 'col-md-pull-3 col-sm-pull-0';
        }

        $position = array(
            'sidebar' => $sidebar_pos_classes,
            'content' => $content_pos_classes
        );

        return $position;
    }
}

//Media upload limit
if ( !function_exists( 'stm_filter_media_upload_size' ) ) {
    function stm_filter_media_upload_size( $size )
    {
        $size = get_theme_mod( 'user_image_size_limit', '4000' ) * 1024;

        return $size;
    }

    add_filter( 'stm_listing_media_upload_size', 'stm_filter_media_upload_size' );
}

/**
 * Listings post type identifier
 *
 * @return string
 */
if ( !function_exists( 'stm_listings_post_type' ) ) {
    function stm_listings_post_type()
    {
        return apply_filters( 'stm_listings_post_type', 'listings' );
    }
}

if ( !function_exists( 'stm_display_user_name' ) ) {
    /**
     * User display name
     *
     * @param $user_id
     * @param string $user_login
     * @param string $f_name
     * @param string $l_name
     */
    function stm_display_user_name( $user_id, $user_login = '', $f_name = '', $l_name = '' )
    {
        $user = get_userdata( $user_id );

        if ( empty( $user_login ) ) {
            $login = $user->data->user_login;
        } else {
            $login = $user_login;
        }
        if ( empty( $f_name ) ) {
            $first_name = get_the_author_meta( 'first_name', $user_id );
        } else {
            $first_name = $f_name;
        }

        if ( empty( $l_name ) ) {
            $last_name = get_the_author_meta( 'last_name', $user_id );
        } else {
            $last_name = $l_name;
        }

        $display_name = $login;

        if ( !empty( $first_name ) ) {
            $display_name = $first_name;
        }

        if ( !empty( $first_name ) and !empty( $last_name ) ) {
            $display_name .= ' ' . $last_name;
        }

        if ( empty( $first_name ) and !empty( $last_name ) ) {
            $display_name = $last_name;
        }


        echo apply_filters( 'stm_filter_display_user_name', $display_name, $user_id, $user_login, $f_name, $l_name );

    }
}

if ( !function_exists( 'stm_theme_clauses_filter' ) ) {

    function stm_theme_clauses_filter( $clauses )
    {
        $radius = get_theme_mod( 'distance_search', '' );
        if ( isset( $_GET["max_search_radius"] ) ) $radius = sanitize_text_field($_GET["max_search_radius"]);

        if ( !empty( $radius ) ) {
            global $wpdb;
            if ( trim( $clauses['groupby'] ) == '' ) {
                $clauses['groupby'] = $wpdb->posts . '.ID';
            }

            $distance = floatval( $radius );
            $clauses['groupby'] .= " HAVING stm_distance <= $distance";
        }

        return $clauses;
    }

    add_filter( 'stm_listings_clauses_filter', 'stm_theme_clauses_filter' );
}

function stm_theme_image_sizes_js( $response, $attachment, $meta )
{

    $size_array = array( 'stm-img-796-466', 'stm-img-350-205' );

    foreach ( $size_array as $size ):

        if ( isset( $meta['sizes'][$size] ) ) {
            $attachment_url = wp_get_attachment_url( $attachment->ID );
            $base_url = str_replace( wp_basename( $attachment_url ), '', $attachment_url );
            $size_meta = $meta['sizes'][$size];

            $response['sizes'][$size] = array(
                'height' => $size_meta['height'],
                'width' => $size_meta['width'],
                'url' => $base_url . $size_meta['file'],
                'orientation' => $size_meta['height'] > $size_meta['width'] ? 'portrait' : 'landscape',
            );
        }

    endforeach;

    return $response;
}

add_filter( 'wp_prepare_attachment_for_js', 'stm_theme_image_sizes_js', 10, 3 );

if ( !function_exists( 'stm_listings_archive_inventory_page_id' ) ) {
    function stm_listings_archive_inventory_page_id( $id )
    {
        if ( $id ) {
            /*Polylang*/
            if ( function_exists( 'pll_current_language' ) ) {
                $id = pll_current_language();
            }

            if ( class_exists( 'PMXI_Plugin' ) ) {
                $id = stm_motors_wpml_binding( $id, 'page' );
            }
        }
        return $id;
    }

    add_filter( 'stm_listings_inventory_page_id', 'stm_listings_archive_inventory_page_id' );
}

function stm_motors_wpml_binding( $id, $type )
{
    return apply_filters( 'wpml_object_id', $id, $type );
}

function stm_motors_wpml_is_page( $pageId )
{
    if ( class_exists( 'PMXI_Plugin' ) ) {
        $id = stm_motors_wpml_binding( $pageId, 'page' );
        if ( is_page( $id ) ) {
            return $id;
        }
    }

    return $pageId;
}

function stm_verify_motors_theme( $v )
{
    return true;
}

add_filter( 'stm_listing_is_motors_theme', 'stm_verify_motors_theme', 100 );

function stm_woo_shop_page_id()
{
    return apply_filters( 'stm_woo_shop_page_id', get_option( 'woocommerce_shop_page_id' ) );
}

function stm_woo_shop_page_url()
{
    return apply_filters( 'stm_woo_shop_page_url', get_permalink( stm_woo_shop_page_id() ) );
}

function stm_woo_shop_checkout_id()
{
    return apply_filters( 'woocommerce_checkout_page_id', get_option( 'woocommerce_checkout_page_id' ) );
}


function stm_woo_shop_checkout_url()
{
    return apply_filters( 'stm_woo_shop_page_url', get_permalink( stm_woo_shop_checkout_id() ) );
}

add_action( 'wp_loaded', 'stm_pmxi_disable_rich_editor' );
function stm_pmxi_disable_rich_editor()
{
    if ( is_admin() ) {
        if ( !empty( $_GET['page'] ) ) {
            if ( $_GET['page'] == 'pmxi-admin-manage' or $_GET['page'] == 'pmxi-admin-import' ) {
                add_filter( 'user_can_richedit', '__return_false', 50 );
            }
        }
    }
}

/*WPML duplicate*/
add_action( 'icl_make_duplicate', 'stm_duplicate_wpml_post', 1, 4 );
add_action( 'icl_make_duplicate', 'stm_duplicate_wpml_post_update_additional_features', 1, 4 );
add_action( 'edit_term', 'stm_save_additional_features', 1, 2 );

function stm_duplicate_wpml_post( $master_post_id, $lang, $post_array, $id )
{

    $post_id = $master_post_id;
    $taxonomies = array();

    $filter_options = get_option( 'stm_vehicle_listing_options' );

    foreach ( $filter_options as $filter_option ) {
        if ( $filter_option['numeric'] ) {
            continue;
        }

        $slug = $filter_option['slug'];

        $terms = wp_get_post_terms( $post_id, $slug );

        if ( !empty( $terms ) and !is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                if ( empty( $taxonomies[$slug] ) ) {
                    $taxonomies[$slug] = array();
                }

                $term_id = $term->term_id;
                $binded_id = stm_motors_wpml_binding( $term_id, $slug );
                $binded_term = get_term( $binded_id, $slug );

                if ( !empty( $binded_term ) and !is_wp_error( $binded_term ) ) {
                    $taxonomies[$slug][] = $binded_term->slug;
                }
            }
        }
    }

    if ( !empty( $taxonomies ) ) {
        foreach ( $taxonomies as $meta_key => $meta_value ) {
            update_post_meta( $id, $meta_key, implode( ',', $meta_value ) );
        }
    }
}

function stm_duplicate_wpml_post_update_additional_features( $master_post_id, $lang, $post_array, $id )
{

    $post_id = $master_post_id;
    $taxonomies = array();

    $slug = "stm_additional_features";

    $terms = wp_get_post_terms( $post_id, $slug );

    if ( !empty( $terms ) and !is_wp_error( $terms ) ) {
        foreach ( $terms as $term ) {
            if ( empty( $taxonomies[$slug] ) ) {
                $taxonomies[$slug] = array();
            }

            $term_id = $term->term_id;
            $binded_id = stm_motors_wpml_binding( $term_id, $slug );
            $binded_term = get_term( $binded_id, $slug );

            if ( !empty( $binded_term ) and !is_wp_error( $binded_term ) ) {
                $taxonomies[$slug][] = $binded_term->name;
            }
        }
    }

    if ( !empty( $taxonomies ) ) {
        delete_post_meta( $id, 'additional_features', '' );
        foreach ( $taxonomies as $meta_key => $meta_value ) {
            update_post_meta( $id, 'additional_features', implode( ',', $meta_value ) );
        }
    }
}

function stm_save_additional_features( $args, $args2 )
{

    $termObj = get_term_by( "term_taxonomy_id", $args );

    if ( $termObj->taxonomy == "stm_additional_features" ) {

        $args = array(
            'post_type' => 'listings',
            'post_status' => 'publish',
            'tax_query' => array(
                array(
                    'taxonomy' => $termObj->taxonomy,
                    'field' => 'slug',
                    'terms' => $termObj->slug
                )
            )
        );
        $postslist = get_posts( $args );

        foreach ( $postslist as $k => $post ) {
            $post_id = $post->ID;

            $taxonomies = array();

            $slug = "stm_additional_features";

            $terms = wp_get_post_terms( $post_id, $slug );
            if ( !empty( $terms ) and !is_wp_error( $terms ) ) {
                foreach ( $terms as $key => $term ) {
                    if ( empty( $taxonomies[$slug] ) ) {
                        $taxonomies[$slug] = array();
                    }

                    $taxonomies[$slug][] = $term->name;
                }
            }


            if ( !empty( $taxonomies ) ) {
                delete_post_meta( $post_id, 'additional_features', '' );
                update_post_meta( $post_id, 'additional_features', implode( ',', $taxonomies[$slug] ) );
            }
        }
    }
}

add_filter( 'stm_listings_default_search_inventory', 'stm_enable_listing_search_name' );

function stm_enable_listing_search_name()
{
    return ( get_theme_mod( 'enable_search', false ) );
}

function stm_disableDisplayAddToAny()
{
    $new_options['display_in_posts_on_front_page'] = '-1';
    $new_options['display_in_posts_on_archive_pages'] = '-1';
    $new_options['display_in_excerpts'] = '-1';
    $new_options['display_in_posts'] = '-1';
    $new_options['display_in_pages'] = '-1';
    $new_options['display_in_attachments'] = '-1';
    $new_options['display_in_feed'] = '-1';

    $custom_post_types = array_values( get_post_types( array( 'public' => true, '_builtin' => false ), 'objects' ) );
    foreach ( $custom_post_types as $custom_post_type_obj ) {
        $placement_name = $custom_post_type_obj->name;
        $new_options['display_in_cpt_' . $placement_name] = '-1';
    }

    $existing_options = get_option( 'addtoany_options' );

    // Merge $new_options into $existing_options to retain AddToAny options from all other screens/tabs
    if ( $existing_options ) {
        $new_options = array_merge( $existing_options, $new_options );
    }

    update_option( 'addtoany_options', $new_options );
}

if ( function_exists( "A2A_SHARE_SAVE_options_page" ) ) {
    add_action( "stm_importer_done", "stm_disableDisplayAddToAny", 100 );
}

add_filter( 'get_avatar', 'stm_cyb_get_avatar', 10, 5 );
function stm_cyb_get_avatar( $avatar = '', $id_or_email, $size = 96, $default = '', $alt = '' )
{

    if ( isset( $id_or_email->user_id ) ) {
        $user = stm_get_user_custom_fields( $id_or_email->user_id );
        // Replace $avatar with your own image element, for example
        if ( $user['image'] != "" ) {
            $avatar = "<img alt='{$alt}' src='{$user['image']}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
        }
    }
    return $avatar;
}

function stm_motors_create_nonce () {
    $adminPatchPrice    = wp_create_nonce( 'stm_admin_patch_price' );//
    $adminPatchLocation = wp_create_nonce( 'stm_admin_patch_location' );//
    $adminPatchCatImg   = wp_create_nonce( 'stm_admin_patch_cat_image' );//
    $installPlugin      = wp_create_nonce( 'motors_install_plugin' );//
    $closeAddvPopup     = wp_create_nonce( 'motors_add_popup' );//
    ?>
    <script>
        var adminPatchPrice    = '<?php echo esc_js( $adminPatchPrice );?>';
        var adminPatchLocation = '<?php echo esc_js( $adminPatchLocation );?>';
        var adminPatchCatImg   = '<?php echo esc_js( $adminPatchCatImg );?>';
        var installPlugin      = '<?php echo esc_js( $installPlugin );?>';
        var closeAddvPopup      = '<?php echo esc_js( $closeAddvPopup );?>';
    </script>
    <?php

}

add_action('admin_head', 'stm_motors_create_nonce');

function stmCurrentUrl()
{
    $locale = explode( '_', get_locale() );

    $filterRemoveHidden = wp_create_nonce( 'stm_ajax_filter_remove_hidden' );//
    $addToCompare       = wp_create_nonce( 'stm_ajax_add_to_compare' );//
    $addTestDrive       = wp_create_nonce( 'stm_ajax_add_test_drive' );//
    $addTradeOffer      = wp_create_nonce( 'stm_ajax_add_trade_offer' );//
    $loadMoreCars       = wp_create_nonce( 'stm_ajax_load_more_cars' );//
    $getCarPrice        = wp_create_nonce( 'stm_ajax_get_car_price' );//
    $addToFav           = wp_create_nonce( 'stm_ajax_add_to_favourites' );//
    $getFav             = wp_create_nonce( 'stm_ajax_get_favourites' );//
    $dealerLoadCars     = wp_create_nonce( 'stm_ajax_dealer_load_cars' );//
    $dealerLoadReviews  = wp_create_nonce( 'stm_ajax_dealer_load_reviews' );//
    $submitReview       = wp_create_nonce( 'stm_submit_review' );//
    $restorePassword    = wp_create_nonce( 'stm_restore_password' );//
    $reportReview       = wp_create_nonce( 'stm_report_review' );//
    $loadDealersList    = wp_create_nonce( 'stm_load_dealers_list' );//
    $addACar            = wp_create_nonce( 'stm_add_a_car' );//
    $getCarsInvtMap     = wp_create_nonce( 'stm_ajax_get_cars_for_inventory_map' );//
    $sellerPhone        = wp_create_nonce( 'stm_ajax_get_seller_phone' );//
    $rentalCheckCar     = wp_create_nonce( 'stm_ajax_rental_check_car_in_current_office' );//
    $availableCarDate   = wp_create_nonce( 'stm_ajax_check_is_available_car_date' );//

    $stickyPostsMagazn  = wp_create_nonce( 'stm_ajax_sticky_posts_magazine' );//
    $getEvents          = wp_create_nonce( 'stm_ajax_get_events' );//
    $testDriveModal     = wp_create_nonce( 'stm_ajax_get_test_drive_modal' );
    $clearData          = wp_create_nonce( 'stm_ajax_clear_data' );//
    $invNoFilter        = wp_create_nonce( 'stm_ajax_inventory_no_filter' );//
    $valueMyCar         = wp_create_nonce( 'stm_ajax_value_my_car' );//
    $getFileSize        = wp_create_nonce( 'stm_ajax_get_file_size' );//
    ?>
    <script>
        var currentAjaxUrl = '<?php echo get_the_permalink( get_the_ID() );?>';
        var resetAllTxt = '<?php echo esc_html__( "Reset All", "motors" ); ?>';
        var prevText = '<?php echo esc_html__( "Previous", "motors" ); ?>';
        var nextText = '<?php echo esc_html__( "Next", "motors" ); ?>';
        var is_rental = '<?php echo stm_is_rental(); ?>';
        var file_type = '<?php echo esc_html__( 'file type noimg', 'motors' );?>';
        var file_size = '<?php echo esc_html__( 'file size big', 'motors' );?>';
        var max_img_quant = '<?php echo esc_html__( 'max imgs 3', 'motors' );?>';
        var currentLocale = '<?php echo stm_do_lmth( $locale[0] ); ?>';


        var filterRemoveHidden = '<?php echo esc_js( $filterRemoveHidden );?>';
        var addToCompare       = '<?php echo esc_js( $addToCompare );?>';
        var addTestDrive       = '<?php echo esc_js( $addTestDrive );?>';
        var addTradeOffer      = '<?php echo esc_js( $addTradeOffer );?>';
        var loadMoreCars       = '<?php echo esc_js( $loadMoreCars );?>';
        var getCarPrice        = '<?php echo esc_js( $getCarPrice );?>';
        var addToFav           = '<?php echo esc_js( $addToFav );?>';
        var getFav             = '<?php echo esc_js( $getFav );?>';
        var dealerLoadCars     = '<?php echo esc_js( $dealerLoadCars );?>';
        var dealerLoadReviews  = '<?php echo esc_js( $dealerLoadReviews );?>';
        var submitReview       = '<?php echo esc_js( $submitReview );?>';
        var restorePassword    = '<?php echo esc_js( $restorePassword );?>';
        var reportReview       = '<?php echo esc_js( $reportReview );?>';
        var loadDealersList    = '<?php echo esc_js( $loadDealersList );?>';
        var addACar            = '<?php echo esc_js( $addACar );?>';
        var getCarsInvtMap     = '<?php echo esc_js( $getCarsInvtMap );?>';
        var sellerPhone        = '<?php echo esc_js( $sellerPhone );?>';
        var rentalCheckCar     = '<?php echo esc_js( $rentalCheckCar );?>';
        var availableCarDate   = '<?php echo esc_js( $availableCarDate );?>';
        var stickyPostsMagazn  = '<?php echo esc_js( $stickyPostsMagazn );?>';
        var getEvents          = '<?php echo esc_js( $getEvents );?>';
        var testDriveModal     = '<?php echo esc_js( $testDriveModal );?>';
        var clearData          = '<?php echo esc_js( $clearData );?>';
        var invNoFilter        = '<?php echo esc_js( $invNoFilter );?>';
        var valueMyCar         = '<?php echo esc_js( $valueMyCar );?>';
        var getFileSize        = '<?php echo esc_js( $getFileSize );?>';
    </script>
    <?php
}

add_action( 'wp_footer', 'stmCurrentUrl' );

if ( !function_exists( 'stm_get_filter_badges' ) ) {
    function stm_get_filter_badges()
    {
        $attributes = stm_listings_filter_terms();

        $filter_badges = array();
        foreach ( $attributes as $attribute => $terms ) {
            /*Text field*/
            $options = stm_get_all_by_slug( $attribute );

            /*Field affix like mi, km or another defined by user*/
            $affix = '';
            if ( !empty( $options['number_field_affix'] ) ) {
                $affix = stm_dynamic_string_translation('Affix text', $options['number_field_affix']);
            }

            /*Slider badge*/
            if ( !empty( $options['slider'] ) and $options['slider'] ) {
                if ( isset( $_GET['max_' . $attribute] ) and !empty( $_GET['max_' . $attribute] ) and $_GET['min_' . $attribute] !== '' ) {
                    reset( $terms );
                    $start_value = key( $terms );
                    end( $terms );
                    $end_value = key( $terms );

                    if ( $attribute == 'price' ) {
                        $value = stm_listing_price_view( stm_listings_input( 'min_' . $attribute, $start_value ) ) . ' - ' . stm_listing_price_view( stm_listings_input( 'max_' . $attribute, $end_value ) );
                    } else {
                        $value = stm_listings_input( 'min_' . $attribute, $start_value ) . ' - ' . stm_listings_input( 'max_' . $attribute, $end_value ) . ' ' . $affix;
                    }

                    $filter_badges[$attribute] = array(
                        'slug' => $attribute,
                        'name' => stm_get_name_by_slug( $attribute ),
                        'type' => 'slider',
                        'value' => $value,
                        'origin' => array( 'min_' . $attribute, 'max_' . $attribute ),
                    );

                    $filter_badges[$attribute]['url'] = stm_get_filter_badge_url( $filter_badges[$attribute] );
                }
                /*Badge of number field*/
            } elseif ( !empty( $options['numeric'] ) and $options['numeric'] ) {
                if ( !empty( $_GET[$attribute] ) ) {
                    $filter_badges[$attribute] = array(
                        'slug' => $attribute,
                        'name' => stm_get_name_by_slug( $attribute ),
                        'value' => $_GET[$attribute] . ' ' . $affix,
                        'type' => 'number',
                        'origin' => array( $attribute )
                    );

                    $filter_badges[$attribute]['url'] = stm_get_filter_badge_url( $filter_badges[$attribute] );
                }
                /*Badge of text field*/
            } else {
                if ( !empty( $_GET[$attribute] ) ) {

                    $txt = '';
                    if ( is_array( $_GET[$attribute] ) ) {
                        foreach ( $_GET[$attribute] as $k => $val ) {
                            $txt .= $terms[$val]->name;
                            $txt .= ( $k != count( $_GET[$attribute] ) - 1 ) ? ', ' : '';
                        }
                    } else {
                        $txt = $terms[$_GET[$attribute]]->name;
                    }


                    $filter_badges[$attribute] = array(
                        'slug' => $attribute,
                        'name' => stm_get_name_by_slug( $attribute ),
                        'value' => $txt,
                        'origin' => array( $attribute ),
                        'type' => 'select',
                    );

                    $filter_badges[$attribute]['url'] = stm_get_filter_badge_url( $filter_badges[$attribute] );
                }
            }
        }

        return apply_filters( 'stm_get_filter_badges', $filter_badges );
    }
}


if ( !function_exists( 'stm_get_filter_badge_url' ) ) {
    function stm_get_filter_badge_url( $badge_info )
    {
        $remove_args = $badge_info['origin'];
        $remove_args[] = 'ajax_action';

        return apply_filters( 'stm_get_filter_badge_url', remove_query_arg( $remove_args ), $badge_info, $remove_args );
    }
}

function stm_wsl_new_register_redirect_url( $user_id, $provider, $hybridauth_user_profile, $redirect_to )
{
    if ( $user_id != null ) {
        do_action( 'wsl_clear_user_php_session' );
        wp_safe_redirect( get_author_posts_url( $user_id ) );
        die();
    }
}

add_action( "wsl_hook_process_login_before_wp_safe_redirect", "stm_wsl_new_register_redirect_url", 100, 4 );

function stm_updateListingsStatus( $subs, $old_status, $new_status )
{
    if ( $new_status == 'active' ) {

        $args = array(
            'post_type' => stm_listings_post_type(),
            'post_status' => 'any',
            'posts_per_page' => -1,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'stm_car_user',
                    'value' => $subs->user_id,
                    'compare' => '='
                ),
                array(
                    'key' => 'pay_per_listing',
                    'compare' => 'NOT EXISTS',
                    'value' => ''
                )
            ),
            'order' => 'DESC',
            'orderby' => 'ID'
        );

        $query = new WP_Query( $args );
        wp_reset_postdata();

        $post_limit = stm_user_active_subscriptions( false, $subs->user_id );
        $post_limit = $post_limit['post_limit'];

        $posts = $query->posts;


        foreach ( $posts as $k => $val ) {
            if ( $val->post_status == 'publish' ) wp_update_post( array( 'ID' => $val->ID, 'post_status' => 'draft' ) );
        }

        foreach ( array_slice( $posts, 0, $post_limit ) as $k => $val ) {
            wp_update_post( array( 'ID' => $val->ID, 'post_status' => 'publish' ) );
        }
    }
}

add_action( "subscriptio_status_changed", "stm_updateListingsStatus", 100, 3 );

function stm_get_formated_date( $date, $format )
{
    $datetime1 = new DateTime( $date );
    return date( $format, strtotime( 'now', $datetime1->getTimestamp() ) );
}

function stm_motors_get_formatted_date( $unix, $custom_format = '' )
{
    $format = ( !empty( $custom_format ) ) ? $custom_format : get_option( 'date_format' );
    return ( date_i18n( $format, $unix ) );
}

function stm_motors_get_terms_array( $id, $taxonomy, $filter, $link = false, $args = '' )
{
    $terms = wp_get_post_terms( $id, $taxonomy );
    if ( !is_wp_error( $terms ) and !empty( $terms ) ) {
        if ( $link ) {
            $links = array();
            if ( !empty( $args ) ) $args = stm_motors_array_as_string( $args );
            foreach ( $terms as $term ) {
                $url = get_term_link( $term );
                $links[] = "<a {$args} href='{$url}' title='{$term->name}'>{$term->name}</a>";
            }
            $terms = $links;
        } else {
            $terms = wp_list_pluck( $terms, $filter );
        }
    } else {
        $terms = array();
    }

    return apply_filters( 'stm_motors_get_terms_array', $terms );
}

function stm_motors_array_as_string( $arr )
{
    $r = implode( ' ', array_map( 'stm_motors_array_map', $arr, array_keys( $arr ) ) );

    return $r;
}

function stm_motors_array_map( $v, $k )
{
    return $k . '="' . $v . '"';
}

/*TO DO DELETE DEPRECATED*/
if ( !function_exists( 'motors_get_formatted_date' ) ) {
    function motors_get_formatted_date( $unix, $custom_format = '' )
    {
        $format = ( !empty( $custom_format ) ) ? $custom_format : get_option( 'date_format' );
        return ( date_i18n( $format, $unix ) );
    }
}

if ( !function_exists( 'motors_get_terms_array' ) ) {
    function motors_get_terms_array( $id, $taxonomy, $filter, $link = false, $args = '' )
    {
        $terms = wp_get_post_terms( $id, $taxonomy );
        if ( !is_wp_error( $terms ) and !empty( $terms ) ) {
            if ( $link ) {
                $links = array();
                if ( !empty( $args ) ) $args = motors_array_as_string( $args );
                foreach ( $terms as $term ) {
                    $url = get_term_link( $term );
                    $links[] = "<a {$args} href='{$url}' title='{$term->name}'>{$term->name}</a>";
                }
                $terms = $links;
            } else {
                $terms = wp_list_pluck( $terms, $filter );
            }
        } else {
            $terms = array();
        }

        return apply_filters( 'motors_get_terms_array', $terms );
    }
}

if ( !function_exists( 'motors_array_as_string' ) ) {
    function motors_array_as_string( $arr )
    {
        $r = implode( ' ', array_map( 'motors_array_map', $arr, array_keys( $arr ) ) );

        return $r;
    }
}

if ( !function_exists( 'motors_array_map' ) ) {
    function motors_array_map( $v, $k )
    {
        return $k . '="' . $v . '"';
    }
}
/*TO DO DELETE DEPRECATED*/

add_action( 'admin_enqueue_scripts', 'stm_sticky_admin_enqueue_scripts' );
function stm_sticky_admin_enqueue_scripts()
{

    $screen = get_current_screen();

    // Only continue if this is an edit screen for a custom post type
    if ( !in_array( $screen->base, array( 'post', 'edit' ) ) || in_array( $screen->post_type, array( 'post', 'page' ) ) )
        return;

    // Editing an individual custom post
    if ( $screen->base == 'post' ) {
        $is_sticky = is_sticky();
        $js_vars = array(
            'screen' => 'post',
            'is_sticky' => $is_sticky ? 1 : 0,
            'checked_attribute' => checked( $is_sticky, true, false ),
            'label_text' => __( 'Stick this post to the front page', 'motors' ),
            'sticky_visibility_text' => __( 'Public, Sticky', 'motors' )
        );

        // Browsing custom posts
    } else {
        global $wpdb;

        $sticky_posts = implode( ', ', array_map( 'absint', ( array )get_option( 'sticky_posts' ) ) );
        $sticky_count = $sticky_posts
            ? $wpdb->get_var( $wpdb->prepare( "SELECT COUNT( 1 ) FROM $wpdb->posts WHERE post_type = %s AND post_status NOT IN ('trash', 'auto-draft') AND ID IN ($sticky_posts)", $screen->post_type ) )
            : 0;

        $js_vars = array(
            'screen' => 'edit',
            'post_type' => $screen->post_type,
            'status_label_text' => __( 'Status', 'motors' ),
            'label_text' => __( 'Make this post sticky', 'motors' ),
            'sticky_text' => __( 'Sticky', 'motors' ),
            'sticky_count' => $sticky_count
        );
    }

    wp_enqueue_script(
        'sscpt-admin',
        get_template_directory_uri() . '/assets/admin/js/admin-sticky.min.js',
        array( 'jquery' )
    );
    wp_localize_script( 'sscpt-admin', 'sscpt', $js_vars );

}

add_filter( 'manage_' . stm_listings_post_type() . '_posts_columns', 'stm_set_listing_stock_number_column' );
function stm_set_listing_stock_number_column( $columns )
{
    $columns = array_slice( $columns, 0, sizeof( $columns ) - 1, true ) +
        array( "stock_number" => __( 'Stock Number', 'motors' ) ) +
        array_slice( $columns, sizeof( $columns ) - 1, 1, true );
    return $columns;
}

// Add the data to the custom columns for the book post type:
add_action( 'manage_' . stm_listings_post_type() . '_posts_custom_column', 'stm_custom_stock_number_column', 10, 2 );
function stm_custom_stock_number_column( $column, $post_id )
{
    switch ( $column ) {

        case 'stock_number' :
            $stockNum = get_post_meta( $post_id, 'stock_number', true );
            echo stm_do_lmth( $stockNum );
            break;

    }
}

//enable per pay listing
function stm_enablePPL()
{
    return get_theme_mod( 'dealer_pay_per_listing', false );
}

if ( !function_exists( 'enablePPL' ) ) {
    function enablePPL()
    {
        return get_theme_mod( 'dealer_pay_per_listing', false );
    }
}

function stm_empty_cart( $passed, $product_id, $quantity, $variation_id = '', $variations = '' )
{
    WC()->cart->empty_cart();
    return $passed;
}

if ( is_listing() ) add_filter( 'woocommerce_add_to_cart_validation', 'stm_empty_cart', 10, 5 );

if ( !function_exists( 'stm_addition_fields' ) ) {
    function stm_add_a_car_addition_fields( $get_params = false, $histories = '', $post_id = '' )
    {
        stm_listings_load_template( 'add_car/step_1_additional_fields', array( 'histories' => $histories, 'post_id' => $post_id ) );
    }
}

if ( !function_exists( 'stm_add_a_car_features' ) ) {
    function stm_add_a_car_features( $user_features, $get_params = false, $post_id = '' )
    {
        stm_listings_load_template( 'add_car/step_2_items', array( 'items' => $user_features, 'id' => $post_id ) );
    }
}

if ( !function_exists( 'stm_add_a_car_registration' ) ) {
    function stm_add_a_car_registration( $user_title = '', $user_text = '', $link = array() )
    {
        stm_listings_load_template( 'add_car/registration.php', array( 'stm_title_user' => $user_title, 'stm_text_user' => $user_text, 'link' => $link ) );
    }
}

if ( !function_exists( 'stm_add_a_car_user_info_theme' ) ) {
    function stm_add_a_car_user_info_theme( $user_login = '', $f_name = '', $l_name = '', $user_id = '' )
    {
        stm_listings_load_template( 'add_car/user_info.php', array( 'user_login' => '', 'f_name' => '', 'l_name' => '', 'user_id' => $user_id ) );
    }
}

function stm_get_value_my_car_options()
{
    $stm_value_my_car_options = array(
        esc_html__( 'Email', 'motors' ) => 'email',
        esc_html__( 'Phone', 'motors' ) => 'phone',
        esc_html__( 'Make', 'motors' ) => 'make',
        esc_html__( 'Model', 'motors' ) => 'model',
        esc_html__( 'Year', 'motors' ) => 'year',
        esc_html__( 'Mileage', 'motors' ) => 'mileage',
        esc_html__( 'VIN', 'motors' ) => 'vin',
        esc_html__( 'Photo', 'motors' ) => 'photo',
    );

    return $stm_value_my_car_options;
}

if ( has_filter( 'wp_get_attachment_image_src', 'stm_get_thumbnail_filter' ) == false AND function_exists( 'stm_get_thumbnail' ) ) {
    add_filter( 'wp_get_attachment_image_src', 'stm_get_thumbnail_filter', 100, 4 );
    function stm_get_thumbnail_filter( $image, $attachment_id, $size = 'thumbnail', $icon = false )
    {
        return stm_get_thumbnail( $attachment_id, $size, $icon = false );
    }
}

// Select sorting options
if ( !function_exists( 'stm_get_stm_select_sorting_options' ) ) {
    function stm_get_stm_select_sorting_options()
    {
        return array(
            'date_high' => esc_html__( 'Date: newest first', 'motors' ),
            'date_low' => esc_html__( 'Date: oldest first', 'motors' ),
            'price_low' => esc_html__( 'Price: lower first', 'motors' ),
            'price_high' => esc_html__( 'Price: highest first', 'motors' ),
            'mileage_low' => esc_html__( 'Mileage: lowest first', 'motors' ),
            'mileage_high' => esc_html__( 'Mileage: highest first', 'motors' )
        );
    }
}

if ( !function_exists( 'stm_get_stm_select_sorting_options_for_select2' ) ) {
    function stm_get_stm_select_sorting_options_for_select2()
    {
        $data = [];
        foreach ( stm_get_stm_select_sorting_options() as $key => $value ) {
            $data[] = array( 'id' => $key, 'text' => $value );
        }
        return $data;
    }
}

if ( !function_exists( 'stm_sort_distance_nearby' ) ) {
    function stm_sort_distance_nearby()
    {

        $ca_location = stm_listings_input( 'ca_location', null );
        $stm_lat = stm_listings_input( 'stm_lat', null );
        $stm_lng = stm_listings_input( 'stm_lng', null );

        if ( $ca_location and $stm_lat and $stm_lng )
            return true;

        return false;
    }
}

function stm_do_lmth( $txt )
{
    return apply_filters( 'stm_string_manipulate', $txt );
}

function stm_motors_sanitize_text_field($text)
{
 return apply_filters('stm_motors_sanitize_text_field', $text);
}

function stm_motors_is_unit_test_mod()
{
    return ( !stm_is_use_plugin( 'stm-post-type/stm-post-type.php' ) ) ? true : false;
}

if ( !stm_motors_is_unit_test_mod() ) {
    add_filter( 'use_block_editor_for_post', '__return_false' );
}

if(!function_exists('stm_motors_getGlobalWPDB')) {
    function stm_motors_getGlobalWPDB()
    {
        global $wpdb;
        return $wpdb;
    }
}

if(!function_exists('stm_motors_getGlobalPageNow')) {
    function stm_motors_getGlobalPageNow()
    {
        global $pagenow;
        return $pagenow;
    }
}

function stm_dynamic_string_translation_e ($desc, $string) {
    do_action( 'wpml_register_single_string', 'motors', $desc, $string );
    echo apply_filters('wpml_translate_single_string', $string, 'motors', $desc );
}

function stm_dynamic_string_translation ($desc, $string) {
    do_action( 'wpml_register_single_string', 'motors', $desc, $string );
    return apply_filters('wpml_translate_single_string', $string, 'motors', $desc );
}

function stm_get_plchdr($layout) {
    if ( file_exists( get_theme_file_path( '/assets/images/' . $layout . '_plchldr.png' ) )) {
        return get_stylesheet_directory_uri() . '/assets/images/' . $layout . '_plchldr.png';
    }

    return '';
}
