<?php if ( !stm_is_auto_parts() ) : ?>
    <?php if ( stm_is_rental() ): ?>
        <?php get_template_part( 'partials/rental/reservation', 'archive' ); ?>
    <?php else: ?>
        <?php get_header(); ?>
        <?php
        $sp_sidebar_id = get_theme_mod( 'shop_sidebar', 768 );
        $sp_sidebar_position = get_theme_mod( 'shop_sidebar_position', 'left' );

        if ( !empty( $sp_sidebar_id ) ) {
            $sp_sidebar = get_post( $sp_sidebar_id );
        }

        $stm_sidebar_layout_mode = stm_sidebar_layout_mode( $sp_sidebar_position, $sp_sidebar_id );
        ?>

        <?php get_template_part( 'partials/title_box' ); ?>

        <div class="container">
            <div class="row">

                <?php echo stm_do_lmth($stm_sidebar_layout_mode['content_before']); ?>
                <?php
                if ( have_posts() ) {
                    woocommerce_content();
                }
                ?>
                <?php echo stm_do_lmth($stm_sidebar_layout_mode['content_after']); ?>

                <?php echo stm_do_lmth($stm_sidebar_layout_mode['sidebar_before']); ?>
                <div class="stm-shop-sidebar-area">
                    <?php
                    if ( !empty( $sp_sidebar_id ) && !empty( $sp_sidebar->post_content ) ) {
                        echo apply_filters( 'the_content', $sp_sidebar->post_content );
                    }
                    ?>
                </div>
                <?php echo stm_do_lmth($stm_sidebar_layout_mode['sidebar_after']); ?>

            </div> <!--row-->
        </div> <!--container-->


        <?php get_footer(); ?>
    <?php endif; ?>
<?php
else :
    do_action('stm_wcmap_single_product_view');
endif;
?>
