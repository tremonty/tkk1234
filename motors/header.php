<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php wp_head(); ?>
	
	<?php
	
	if(get_theme_mod('logo_font_family', '') != "" || get_theme_mod('logo_font_size', '') != "" || get_theme_mod('logo_color', '') != "") {
		echo "<style>";
		echo ".blogname h1{";
		if ( get_theme_mod( 'logo_font_family', '' ) != "" ) {
			echo "font-family: " . get_theme_mod( 'logo_font_family', '' ) . " !important; ";
		}
		if ( get_theme_mod( 'logo_font_size', '' ) != "" ) {
			echo "font-size: " . get_theme_mod( 'logo_font_size', '' ) . "px !important; ";
		}
		if ( get_theme_mod( 'logo_color', '' ) != "" ) {
			echo "color: " . get_theme_mod( 'logo_color', '' ) . " !important;";
		}
		echo "}";
		echo "</style>";
	}
	?>
</head>

<?php
	$body_custom_image = get_theme_mod('custom_bg_image');
	$boats_header_layout = get_theme_mod('boats_header_layout', 'boats');
	$motorcycle_header_layout = get_theme_mod('motorcycle_header_layout', 'motorcycle');
	$top_bar_layout = '';
	if(stm_is_boats() || stm_is_dealer_two()) {
		$top_bar_layout = '-boats';
	}

?>

<body <?php body_class(); ?> <?php if(!empty($body_custom_image)): ?> style="background-image: url('<?php echo esc_url($body_custom_image); ?>')" <?php endif; ?> ontouchstart="">
	<?php do_action('motors_before_header'); ?>
	<div id="wrapper">
        <?php if(!stm_is_auto_parts()) { ?>
            <?php if(stm_is_boats() || stm_is_dealer_two()): ?>
                <div id="stm-boats-header">
            <?php endif; ?>

                <?php if(!is_404() and !is_page_template('coming-soon.php')){ ?>
                    <?php get_template_part('partials/top', 'bar' . $top_bar_layout); ?>
                    <div id="header">
                        <?php
                            if(is_listing(array('listing', 'listing_two', 'listing_three'))) {
                                get_template_part( 'partials/header/header-listing' );
                            } elseif(stm_get_current_layout() == 'boats' and $boats_header_layout == 'boats') {
                                get_template_part( 'partials/header/header-boats' );
                            } elseif(stm_is_motorcycle() and $motorcycle_header_layout == 'motorcycle') {
                                get_template_part( 'partials/header/header-motorcycle' );
                            } elseif(stm_is_rental()) {
                                get_template_part( 'partials/header/header-rental' );
                            } elseif(stm_is_magazine()) {
                                get_template_part( 'partials/header/header-magazine' );
                            } elseif(stm_is_dealer_two()) {
                                get_template_part( 'partials/header/header-dealer-two' );
                            } elseif(stm_is_aircrafts()) {
                                get_template_part( 'partials/header/header-aircrafts' );
                            } else {
                                get_template_part('partials/header/header');
                                get_template_part('partials/header/header-nav');
                            }
                        ?>
                    </div> <!-- id header -->
                <?php } elseif(is_page_template('coming-soon.php')) {
                    get_template_part('partials/header/header-coming','soon');
                } else {
                    get_template_part('partials/header/header','404');
                }; ?>

            <?php if(stm_is_boats() || stm_is_dealer_two()): ?>
                </div>
                <?php get_template_part('partials/header/header-boats-mobile'); ?>
            <?php endif; ?>
        <?php } else {
            do_action('stm_hb', array('header' => 'stm_hb_settings'));
        } ?>
		<div id="main" <?php if(stm_is_magazine()) echo 'style="margin-top: -80px;"'; ?>>