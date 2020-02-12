
	<div id="penci-demobar" class="penci-main-demobar hide-demos-bar ">
		<div class="style-toggle">Open</div>
            <div id="penci-demobar-container">
               <div class="sidepanelmain"> 
               <?php if( get_field('side_panel_description','option') ) { ?>
               <div class="descsec">
               	<div class="latestmagbtn">
                	<a href="<?php echo get_site_url(); ?>/magazines">Latest Magazines</a>
                </div>
               	<p><?php the_field('side_panel_description','option') ?></p>
               </div>
               <?php } ?>
							<?php 
                            
                            
                                 $args = array(  
                                  'post_type' => 'post',
                                  'posts_per_page' => 1,
                                  'post_status' => 'publish',
                                  'orderby' => 'date',
            						'order'   => 'DESC',
                                  'tax_query' => array(
                                        array(
                                          'taxonomy' => 'magazines',
                                          'field' => 'slug',
                                          'terms' => 'magazine-archive',
                                    
                                        )
                                      )
                             );
                         
                             $loop = new WP_Query( $args );
                              
                             while ( $loop->have_posts() ) : $loop->the_post(); ?>
							 <div class="sidepaneldiv">
							 	<a href="<?php echo get_post_permalink( $post->ID ); ?>"> <img class="magzinethumb" src="<?php echo the_post_thumbnail_url();?>" /></a>
                                <div class="preview-img penci-lazy" style="background-image: url(<?php echo the_post_thumbnail_url();?>);"></div>
                           	 </div>      
                                 
								 
							<?php
                              endwhile;
                          
                             wp_reset_postdata();
                              
                           ?>
                           </div>
            </div>
     </div>


</div> <!--main-->
</div> <!--wrapper-->
<?php do_action('stm_pre_footer'); ?>
<?php if ( !is_404() and !is_page_template( 'coming-soon.php' ) ) { ?>
    <footer id="footer">
        <?php get_template_part( 'partials/footer/footer' ); ?>
        <?php get_template_part( 'partials/footer/copyright' ); ?>
        <?php get_template_part( 'partials/global-alerts' ); ?>
        <!-- Searchform -->
        <?php get_template_part( 'partials/modals/searchform' ); ?>
    </footer>
<?php } elseif ( is_page_template( 'coming-soon.php' ) ) {
    get_template_part( 'partials/footer/footer-coming-soon' );
}; ?>

<?php
if ( get_theme_mod( 'frontend_customizer' ) ) {
    get_template_part( 'partials/frontend_customizer' );
}
?>

<?php wp_footer(); ?>


<?php if( get_field('video_url','option') ) { ?>
<?php 
		
$videoserver = get_field('video_url','option');

if (strpos($videoserver,'youtube') == true) {		
$breakdown = get_field('video_url','option');
$tmp = explode('=', $breakdown);
$videoid = end($tmp);
// echo $videoid; //youtube video id
// echo "<br>";
$breakdown = explode('/', $breakdown);
$breakdown = array_filter($breakdown);
$breakdown = array_merge($breakdown, array()); //reset keys
// echo $url = $breakdown[1]; //youtube url
} 

elseif(strpos($videoserver,'vimeo') == true) {	
$breakdown = get_field('video_url','option');
$tmp = explode('/', $breakdown);
$videoid = end($tmp);
// echo $videoid; // video id
// echo "<br>";
$breakdown = explode('/', $breakdown);
$breakdown = array_filter($breakdown);
$breakdown = array_merge($breakdown, array()); //reset keys
// echo $url = $breakdown[1]; //video url
}	
?>
<?php } ?>

<?php 
if (strpos($videoserver,'youtube') == true) {
?>
<script>
jQuery(document).ready(function(){
	
jQuery('<div style="position: fixed;z-index: -99999999;width: 100%;height: 100%;top: 0;"><iframe width="100%" height="100%" src="https://www.youtube.com/embed/<?php echo $videoid ?>" frameborder="0" autoplay="1" ></iframe></div>').insertBefore(".dakh_search-close");

});
</script>

<?php } 

elseif(strpos($videoserver,'vimeo') == true){?>

<script>
jQuery(document).ready(function(){
	
jQuery('<div style="position: fixed;z-index: -99999999;width: 100%;height: 100%;top: 0;"><iframe src="https://player.vimeo.com/video/<?php echo $videoid ?>?autoplay=1&loop=1&title=0&byline=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe></div>').insertBefore(".dakh_search-close");

});
</script>


<script src="https://player.vimeo.com/api/player.js"></script>

<?php }


?>


<?php
if ( !stm_is_auto_parts() ) :
    if ( is_singular( stm_listings_post_type() ) ) {
        if ( get_theme_mod( 'show_calculator', true ) ) get_template_part( 'partials/modals/car-calculator' );
        if ( get_theme_mod( 'show_offer_price', false ) ) get_template_part( 'partials/modals/trade-offer' );
        if ( get_theme_mod( 'show_trade_in', stm_is_motorcycle() ) ) get_template_part( 'partials/modals/trade-in' );
    }

    if(stm_is_motorcycle()) {
        if ( get_theme_mod( 'show_calculator', true ) ) get_template_part( 'partials/modals/car-calculator' );
        if ( get_theme_mod( 'show_offer_price', true ) ) get_template_part( 'partials/modals/trade-offer' );
    }

    if ( get_theme_mod( 'show_test_drive', true ) ) get_template_part( 'partials/modals/test-drive' );
    get_template_part( 'partials/modals/get-car-price' );

    $show_compare = ( is_single( get_the_ID() ) ) ? get_theme_mod( 'show_listing_compare', true ) : get_theme_mod( 'show_compare', true );
    if ( $show_compare ) get_template_part( 'partials/single-car/single-car-compare-modal' );

    if ( stm_is_rental() ) {
        get_template_part( 'partials/modals/rental-notification-choose-another-class' );
        echo '<div class="stm-rental-overlay"></div>';
    }

    if ( stm_pricing_enabled() ) {
        get_template_part( 'partials/modals/limit_exceeded' );
        get_template_part( 'partials/modals/subscription_ended' );
    }
    ?>
    <?php if ( is_listing( array( 'listing_two', 'listing_three' ) ) ) : ?>
        <div class="notification-wrapper">
            <div class="notification-wrap">
                <div class="message-container">
                    <span class="message"></span>
                </div>
                <div class="btn-container">
                    <button class="notification-close">
                        <?php echo esc_html__( 'Close', 'motors' ); ?>
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="modal_content"></div>
<?php endif; ?>



<script>
jQuery(".style-toggle").click(function(){
	jQuery(this).html() == "Open" ? jQuery(this).html('Close') : jQuery(this).html('Open');
  jQuery(".penci-main-demobar").toggleClass("hide-demos-bar");
});


</script>

</body>
</html>