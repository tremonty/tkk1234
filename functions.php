<?php
if ( is_admin() ) {
    require_once get_template_directory() . '/admin/admin.php';
    /* Phone Number Patch */
    require_once( get_template_directory() . '/inc/autoload.php' );
}

define( 'STM_TEMPLATE_URI', get_template_directory_uri() );
define( 'STM_TEMPLATE_DIR', get_template_directory() );
define( 'STM_THEME_SLUG', 'stm' );
define( 'STM_INC_PATH', get_template_directory() . '/inc' );
define( 'STM_CUSTOMIZER_PATH', get_template_directory() . '/inc/customizer' );
define( 'STM_CUSTOMIZER_URI', get_template_directory_uri() . '/inc/customizer' );


//	Include path
$inc_path = get_template_directory() . '/inc';

//	Widgets path
$widgets_path = get_template_directory() . '/inc/widgets';

// Theme setups
require_once STM_CUSTOMIZER_PATH . '/customizer.class.php';

// Custom code and theme main setups
require_once( $inc_path . '/setup.php' );

// Enqueue scripts and styles for theme
require_once( $inc_path . '/scripts_styles.php' );

// Multiple Currency
require_once( $inc_path . '/multiple_currencies.php' );

// Custom code for any outputs modifying
require_once( $inc_path . '/custom.php' );

// Required plugins for theme
require_once( $inc_path . '/tgm/tgm-plugin-registration.php' );

// Visual composer custom modules
if ( defined( 'WPB_VC_VERSION' ) ) {
    require_once( $inc_path . '/visual_composer.php' );
}

// Custom code for any outputs modifying with ajax relation
require_once( $inc_path . '/stm-ajax.php' );

// Custom code for filter output
//require_once( $inc_path . '/listing-filter.php' );
require_once( $inc_path . '/user-filter.php' );

//User
if ( is_listing() ) {
    require_once( $inc_path . '/user-extra.php' );
}

require_once( $inc_path . '/stm_single_dealer.php' );

//email template manager
require_once( $inc_path . '/email_template_manager/email_template_manager.php' );

//value my car
if ( is_listing( array( 'listing_two', 'listing_three' ) ) ) require_once( $inc_path . '/value_my_car/value_my_car.php' );

// Custom code for woocommerce modifying
if ( class_exists( 'WooCommerce' ) ) {
    require_once( $inc_path . '/woocommerce_setups.php' );
    if ( stm_is_rental() ) {
        require_once( $inc_path . '/woocommerce_setups_rental.php' );
    }

    if ( ( get_theme_mod( 'dealer_pay_per_listing', false ) || get_theme_mod( 'dealer_payments_for_featured_listing', false ) ) && is_listing() ) {
        require_once $inc_path . '/perpay.php';
    }
}

if ( class_exists( '\\STM_GDPR\\STM_GDPR' ) ) {
    if ( stm_is_use_plugin( 'stm-gdpr-compliance/stm-gdpr-compliance.php' ) ) {
        require_once( $inc_path . '/motors-gdpr.php' );
    }
}

if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Advertisment Area',
		'menu_title'	=> 'Advertisment Area',
		'menu_slug' 	=> 'ad-area',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
	
}

/**
 * Add a sidebar.
 */
function wpdocs_theme_slug_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Magazine Sidebar', 'motors' ),
        'id'            => 'magazine',
        'description'   => __( 'Magazine detail page Sidebar', 'motors' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widgettitle text-center">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'wpdocs_theme_slug_widgets_init' );



function joki_count_views($postID) {
    $post_meta = 'joki_post_views_count';
    $count = get_post_meta($postID, $post_meta, true);
    if($count == '') {
        $count = 0;
        delete_post_meta($postID, $post_meta);
        add_post_meta($postID, $post_meta, '0');
    }
    else {
        $count++;
        update_post_meta($postID, $post_meta, $count);
    }
}


function joki_track_views ($post_id) {
    if ( !is_single() ) { return; }
    if ( empty ( $postId) ) {
        global $post;
        $postId = $post->ID;
    }
    joki_count_views($postId);
}
add_action( 'wp_head', 'joki_track_views');


remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10);








 function trendinglisting() {
 
	ob_start();
	    $args = array(  
        'post_type' => 'listings',
        'post_status' => 'publish',
        'posts_per_page' => 3,
		'tax_query' => array(
		  array(
			'taxonomy' => 'category_type',
			'field' => 'slug',
			'terms' => 'trending'
		  )
		)
    );

    $loop = new WP_Query( $args );
	
    while ( $loop->have_posts() ) : $loop->the_post(); 
        ?>
        
        <div
	class="car-listing-row col-md-12 col-sm-12 col-xs-12 col-xxs-12 stm-isotope-listing-item stm_moto_single_grid_item all <?php print_r(implode(' ', $classes)); ?>"
	data-price="<?php echo esc_attr($data_price) ?>"
	data-date="<?php echo get_the_date('Ymdhi') ?>"
	data-mileage="<?php echo esc_attr($data_mileage); ?>"
	>
	<a href="<?php echo esc_url(get_the_permalink()); ?>" class="rmv_txt_drctn">
		<div class="image minheightcontrol">
			<?php if(!empty($special_car) and $special_car == 'on'): ?>
				<div class="special-label special-label-small h6" <?php echo esc_attr($badge_style); ?>>
					<?php stm_dynamic_string_translation_e('Special Badge Text', $badge_text ); ?>
				</div>
			<?php endif; ?>
			<?php if(has_post_thumbnail()): ?>
				<?php
				$img_placeholder = $img = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'stm-img-350-205');
				?>
				<img
					data-original="<?php echo esc_url($img[0]); ?>"
					src="<?php echo esc_url(get_template_directory_uri().'/assets/images/'.$placeholder_path); ?>"
					class="lazy img-responsive"
				    alt="<?php echo stm_generate_title_from_slugs(get_the_id()); ?>"
					/>
			<?php else: ?>
				<img
					src="<?php echo esc_url(get_template_directory_uri().'/assets/images/'.$placeholder_path); ?>"
					class="img-responsive"
					alt="<?php esc_attr_e('Placeholder', 'motors'); ?>"
					/>
			<?php endif; ?>
			<div class="stm_moto_hover_unit">
				<!--Compare-->
				<?php if(!empty($show_compare) and $show_compare): ?>
					<div
						class="stm-listing-compare heading-font stm-compare-directory-new"
						data-id="<?php echo esc_attr(get_the_id()); ?>"
						data-title="<?php echo stm_generate_title_from_slugs(get_the_id(),false); ?>"
						>
						<i class="stm-service-icon-compare-new"></i>
						<?php esc_html_e('Compare', 'motors'); ?>
					</div>
				<?php endif; ?>
				<?php stm_get_boats_image_hover(get_the_ID()); ?>
				<div class="heading-font">
					<?php if(empty($car_price_form_label)): ?>
						<?php if(!empty($price) and !empty($sale_price) and $price != $sale_price):?>
							<div class="price discounted-price">
								<div class="regular-price"><?php echo esc_attr(stm_listing_price_view($price)); ?></div>
								<div class="sale-price"><?php echo esc_attr(stm_listing_price_view($sale_price)); ?></div>
							</div>
						<?php elseif(!empty($price)): ?>
							<div class="price">
								<div class="normal-price"><?php echo esc_attr(stm_listing_price_view($price)); ?></div>
							</div>
						<?php endif; ?>
					<?php else: ?>
						<div class="price">
							<div class="normal-price"><?php echo esc_attr($car_price_form_label); ?></div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div class="listing-car-item-meta">
			<div class="car-meta-top heading-font clearfix">
				<div class="car-title">
					<?php echo stm_generate_title_from_slugs(get_the_id(), true); ?>
				</div>
			</div>

			<?php $labels = stm_get_car_listings(); ?>
			<?php if(!empty($labels)): ?>
				<div class="car-meta-bottom">
					<ul>
						<?php foreach($labels as $label): ?>
							<?php $label_meta = get_post_meta(get_the_id(),$label['slug'],true); ?>
							<?php if($label_meta !== '' and $label['slug'] != 'price'): ?>
								<li>
									<?php if(!empty($label['font'])): ?>
										<i class="<?php echo esc_attr($label['font']) ?>"></i>
									<?php endif; ?>

									<span class="stm_label">
										<?php stm_dynamic_string_translation_e('Label Name', $label['single_name']);?>:
									</span>
									
									<?php if(!empty($label['numeric']) and $label['numeric']): ?>
										<span><?php echo esc_attr($label_meta); ?></span>
									<?php else: ?>
										
										<?php 
											$data_meta_array = explode(',',$label_meta);
											$datas = array();
											
											if(!empty($data_meta_array)){
												foreach($data_meta_array as $data_meta_single) {
													$data_meta = get_term_by('slug', $data_meta_single, $label['slug']);
													if(!empty($data_meta->name)) {
														$datas[] = esc_attr($data_meta->name);
													}
												}
											}
										?>

										<?php if(!empty($datas)): ?>
											
											<?php 
												if(count($datas) > 1) { ?>
													
													<span 
														class="stm-tooltip-link" 
														data-toggle="tooltip"
														data-placement="bottom"
														title="<?php echo esc_attr(implode(', ', $datas)); ?>">
														<?php echo stm_do_lmth($datas[0]).'<span class="stm-dots dots-aligned">...</span>'; ?>
													</span>

												<?php } else { ?>
													<span><?php echo implode(', ', $datas); ?></span>
												<?php }
											?>
										<?php endif; ?>
										
									<?php endif; ?>

									<?php if(!empty($label['number_field_affix'])): ?>
										<span><?php stm_dynamic_string_translation_e('Number Field Affix', $label['number_field_affix']); ?></span>
									<?php endif; ?>
								</li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

		</div>
	</a>
</div>
        <?php
   
    endwhile;

    wp_reset_postdata();
$contentofshape = ob_get_contents();
ob_clean();
return $contentofshape;	
 }
 
 add_shortcode( 'trending_list', 'trendinglisting' );
 
 
 
 
 
 function featuredposts() {
 
	ob_start();
	    $args = array(  
        'post_type' => 'post',
        'post_status' => 'publish',
		'category_name' => 'featured',
        'posts_per_page' => 3,
    );

    $loop = new WP_Query( $args );
	
    while ( $loop->have_posts() ) : $loop->the_post(); 
        $featured_img = wp_get_attachment_image_src( $loop->ID );
		?>
        <div class="col-md-4 col-sm-4 col-xs-12">
    <div class="animateup post-grid-single-unit <?php if ( is_sticky( get_the_id() ) ) {
        echo 'sticky-wrap';
    } ?>">
        <?php if ( has_post_thumbnail() ): ?>
            <div class="image">
                <a href="<?php the_permalink() ?>">
                    <!--Video Format-->
                    <?php if ( get_post_format( get_the_ID() ) == 'video' ): ?>
                        <div class="video-preview">
                            <i class="fa fa-film"></i><?php esc_html_e( 'Video', 'motors' ); ?>
                        </div>
                    <?php endif; ?>
                    <!--Sticky Post-->
                    <?php if ( is_sticky( get_the_id() ) ): ?>
                        <div class="sticky-post heading-font"><?php esc_html_e( 'Sticky Post', 'motors' ); ?></div>
                    <?php endif; ?>
                    <?php
                    if ( $stm_sidebar_layout_mode['default_row'] == 2 ) {
                        the_post_thumbnail( 'stm-img-398-206', array( 'class' => 'img-responsive' ) );
                    } else {
                        the_post_thumbnail( $imgSize, array( 'class' => 'img-responsive' ) );
                    }
                    ?>
                </a>
            </div>
        <?php else: ?>
            <?php if ( is_sticky( get_the_id() ) ): ?>
                <div class="sticky-post blog-post-no-image heading-font"><?php esc_html_e( 'Sticky', 'motors' ); ?></div>
            <?php endif; ?>
        <?php endif; ?>
        <div class="content">
            <?php if ( empty( $title ) ): ?>
            <a href="<?php the_permalink() ?>">
                <?php endif; ?>
                <div class="title-relative">
                    <?php if ( !empty( $title ) ): ?>
                    <a href="<?php the_permalink() ?>">
                        <?php endif; ?>
                        <?php $title = stm_trim_title( 85, '...' ); ?>
                        <?php if ( !empty( $title ) ): ?>
                            <h4 class="title"><?php echo esc_attr( $title ); ?></h4>
                        <?php endif; ?>
                        <?php if ( !empty( $title ) ): ?>
                    </a>
                <?php endif; ?>
                </div>
                <?php if ( empty( $title ) ): ?>
            </a>
        <?php endif; ?>
            <?php if ( $blog_show_excerpt ): ?>
                <div class="blog-posts-excerpt">
                    <?php the_excerpt(); ?>
                    <div>
                        <a href="<?php the_permalink(); ?>"><?php esc_html_e( 'Continue reading', 'motors' ); ?></a>
                    </div>
                </div>
            <?php endif; ?>
            <?php /*?><div class="post-meta-bottom">
                <div class="blog-meta-unit">
                    <i class="stm-icon-date"></i>
                    <span><?php echo get_the_date(); ?></span>
                </div>
                <div class="blog-meta-unit comments">
                    <a href="<?php comments_link(); ?>" class="post_comments">
                        <i class="stm-icon-message"></i> <?php comments_number(); ?>
                    </a>
                </div>
            </div><?php */?>
        </div>
    </div>
</div>
        
        <?php
   
    endwhile;

    wp_reset_postdata();
$contentofshape = ob_get_contents();
ob_clean();
return $contentofshape;	
 }
 
 add_shortcode( 'featured_posts', 'featuredposts' );
 
 
 
 
 
 
 
 
  function magzinesidebarad() {
 
	ob_start();
	      if( get_field('sidebar_ad','option') ) { ?>
                            <div class="naveedahmad">
                                    <a target="_blank" href="<?php the_field('siderbaradurl','option') ?>"><img src="<?php the_field('sidebar_ad','option') ?>" /></a>
                                </div>
                            <?php } 
$contentofshape = ob_get_contents();
ob_clean();
return $contentofshape;	
 }
 
 add_shortcode( 'sidebarad', 'magzinesidebarad' );
 
 

 
 
 add_filter( 'widget_text', 'do_shortcode' );
 
 
 
 
 
   function isotopsfilter() {
 
	ob_start();

$terms = get_terms( 'magazine-year' );
if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
echo '<div id="filters" class="button-group">';
echo '<select class="filters-select">';
echo '<option value="*" data-filter="*">Select Year</option>';
	foreach ( $terms as $term ) {		
		echo '<option value=".'.$term->term_id.'">'. $term->name .'</option>';
    }
 echo '</select>';
echo '</div>';
}
                        
                           
$contentofshape = ob_get_contents();
ob_clean();
return $contentofshape;	
 }
 
 add_shortcode( 'yeartaxnomines', 'isotopsfilter' );
 
 
 
 
 
 
 
  
  function popularmagaizne() {
 
	ob_start();
?>	

<?php
							 
                                 $args = array(  
                                  'post_type' => 'magazines',
                                  'posts_per_page' => 3,
                                  'post_status' => 'publish',
                                  'post__not_in' => array($post->ID),
								  'meta_key' => 'joki_post_views_count',
									'orderby' => 'meta_value_num',
									'order' => 'DESC'
									
                             );
                         
                             $loop = new WP_Query( $args );
                              
                             while ( $loop->have_posts() ) : $loop->the_post(); ?>
							 <div class="mostsubscribed">
							 	<div class="subimgsec">
                                	<a href="<?php echo get_post_permalink( $post->ID ); ?>"> <img class="magzinethumb" src="<?php echo the_post_thumbnail_url();?>" /></a>
                                </div>
                                
                             	<div class="submetasec">
                                <div class="sanpcat"><p><?php  echo strip_tags(get_the_term_list( $post->ID, 'magazine-category' ));?></p></div>
                                <div class="subvategory"><a class="submagtitle" href="<?php echo get_post_permalink( $post->ID ); ?>"><?php  the_title();?></a></div>
                                </div>
                             </div>      
                                 
								 
							<?php
                              endwhile;
                          
                             wp_reset_postdata();
                              
                          ?>
                           
                           <?php
						   
$contentofshape = ob_get_contents();				   
ob_clean();
return $contentofshape;	
 }
 
 add_shortcode( 'popmagazine', 'popularmagaizne' );

//修改后台显示更新的代码

add_filter('pre_site_transient_update_core',    create_function('$a', "return null;")); // 关闭核心提示

add_filter('pre_site_transient_update_plugins', create_function('$a', "return null;")); // 关闭插件提示

add_filter('pre_site_transient_update_themes',  create_function('$a', "return null;")); // 关闭主题提示

remove_action('admin_init', '_maybe_update_plugins'); // 禁止 WordPress 更新插件

remove_action('admin_init', '_maybe_update_core');    // 禁止 WordPress 检查更新

remove_action('admin_init', '_maybe_update_themes');  // 禁止 WordPress 更新主题