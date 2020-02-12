<?php
/**
 * Created by PhpStorm.
 * User: NDA
 * Date: 19.12.2017
 * Time: 14:10
 */

$logo_main = get_theme_mod('logo', get_template_directory_uri() . '/assets/images/tmp/magazine-logo/logo.svg');

$fixed_header = get_theme_mod('header_sticky', true);
if(!empty($fixed_header) and $fixed_header) {
	$fixed_header_class = 'header-magazine-fixed';
} else {
	$fixed_header_class = 'header-magazine-unfixed';
}

if(empty($_COOKIE['compare_ids'])) {
	$_COOKIE['compare_ids'] = array();
}

$compare_page = get_theme_mod( 'compare_page', 156 );

$header_bg = get_theme_mod('header_listing_layout_image_bg');

$rgba = (is_front_page()) ? stm_hex2rgb(get_theme_mod('header_bg_color', '#293742')) . ',0.7' : stm_hex2rgb(get_theme_mod('header_bg_color', '#293742'));



$header_style = 'style="background-color: rgba(' . $rgba . ')";';
?>

<div class="header-magazine <?php echo esc_attr($fixed_header_class); ?>" <?php echo stm_do_lmth($header_style);?>>

	<div class="magazine-header-bg" <?php if(!empty($header_bg)): ?>style="background-image: url('<?php echo esc_url($header_bg); ?>')"<?php endif; ?>></div>
	<div class="container header-inner-content">
		<!--Logo-->
		<div class="magazine-logo-main" style="margin-top: <?php echo get_theme_mod( 'menu_top_margin', '0' ); ?>px;">
			<?php if(empty($logo_main)): ?>
				<a class="blogname" href="<?php echo esc_url(home_url('/')); ?>" title="<?php _e('Home', 'motors'); ?>">
					<h1><?php echo esc_attr(get_bloginfo('name')) ?></h1>
				</a>
			<?php else: ?>
				<a class="bloglogo" href="<?php echo esc_url(home_url('/')); ?>">
					<img
						src="<?php echo esc_url( $logo_main ); ?>"
						style="width: <?php echo get_theme_mod( 'logo_width', '112' ); ?>px;"
						title="<?php esc_attr_e('Home', 'motors'); ?>"
						alt="<?php esc_attr_e('Logo', 'motors'); ?>"
					/>
				</a>
			<?php endif; ?>
		</div>

		<div class="magazine-service-right clearfix">

			<div class="magazine-right-actions clearfix">
                <div class="magazine-menu-mobile-wrapper">
                    <div class="stm-menu-trigger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    <div class="stm-opened-menu-magazine">
                        <ul class="magazine-menu-mobile header-menu heading-font visible-xs visible-sm clearfix">
                            <?php
                            wp_nav_menu( array(
                                    'menu'              => 'primary',
                                    'theme_location'    => 'primary',
                                    'depth'             => 3,
                                    'container'         => false,
                                    'menu_class'        => 'service-header-menu clearfix',
                                    'items_wrap'        => '%3$s',
                                    'fallback_cb' => false
                                )
                            );
                            ?>

                            <?php if(!empty($compare_page) && get_theme_mod('header_compare_show', true)): ?>
                                <li class="stm_compare_mobile"><a href="<?php echo esc_url(get_the_permalink($compare_page)); ?>"><?php _e('Compare', 'motors'); ?></a></li>
                            <?php endif; ?>
                        </ul>
                        <?php //get_template_part('partials/top', 'bar'); ?>
                    </div>
                </div>
				<!--Socials-->
				<?php $socials = stm_get_header_socials('header_socials_enable'); ?>

				<!-- Header top bar Socials -->
				<?php if(!empty($socials)): ?>
					<div class="pull-right">
						<div class="header-main-socs">
							<ul class="clearfix">
								<?php foreach ( $socials as $key => $val ): ?>
									<li>
										<a href="<?php echo esc_url($val) ?>" target="_blank">
											<i class="fa fa-<?php echo esc_attr($key); ?>"></i>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
				<?php endif; ?>
				<?php if(!empty($compare_page) && get_theme_mod('header_compare_show', true)): ?>
					<div class="pull-right">
						<a class="lOffer-compare heading-font"
							href="<?php echo esc_url(get_the_permalink($compare_page)); ?>"
							title="<?php esc_attr_e('Watch compared', 'motors'); ?>">
							<?php echo esc_html__('Compare', 'motors'); ?>
							<i class="stm-icon-speedometr2"></i>
							<span class="list-badge"><span class="stm-current-cars-in-compare"><?php if(!empty($_COOKIE['compare_ids']) and count($_COOKIE['compare_ids'])){ echo esc_attr(count($_COOKIE['compare_ids'])); } ?></span></span>
						</a>
					</div>
				<?php endif; ?>
			</div>

			<ul class="magazine-menu header-menu clearfix" style="margin-top: <?php echo (get_theme_mod( 'menu_top_margin', '17' ) + 1); ?>px;">
				<?php
				wp_nav_menu( array(
						'menu'              => 'primary',
						'theme_location'    => 'primary',
						'depth'             => 3,
						'container'         => false,
						'menu_class'        => 'service-header-menu clearfix',
						'items_wrap'        => '%3$s',
						'fallback_cb' => false
					)
				);
				?>
			</ul>
		</div>
	</div>
</div>