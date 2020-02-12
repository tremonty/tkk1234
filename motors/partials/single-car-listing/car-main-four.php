<div class="row">
    <div class="col-md-9 col-sm-12 col-xs-12">
        <div class="stm-single-car-content">
            <h1 class="title h2"><?php the_title(); ?></h1>

            <!--Actions-->
            <?php get_template_part( 'partials/single-car/car', 'actions' ); ?>

            <!--Gallery-->
            <?php get_template_part( 'partials/single-car/car', 'gallery' ); ?>

            <!--Car Gurus if is style BANNER-->
            <?php if ( strpos( get_theme_mod( "carguru_style", "STYLE1" ), "BANNER" ) !== false ) get_template_part( 'partials/single-car/car', 'gurus' ); ?>

            <?php //CAR DATA
            $data = stm_get_single_car_listings();
            if(!empty($data)):
                ?>
                <div class="stm-car-listing-data-single stm-border-top-unit">
                    <div class="title heading-font"><?php esc_html_e('Car Details','motors'); ?></div>
                </div>

                <?php get_template_part('partials/single-car-listing/car-data'); ?>
            <?php endif; ?>


            <?php
            $features = get_post_meta(get_the_id(), 'additional_features', true);
            if(!empty($features)):
                ?>
                <div class="stm-car-listing-data-single stm-border-top-unit ">
                    <div class="title heading-font"><?php esc_html_e('Features', 'motors'); ?></div>
                </div>
                <?php get_template_part('partials/single-car-listing/car-features'); ?>

            <?php endif; ?>

            <div class="stm-car-listing-data-single stm-border-top-unit">
                <div class="title heading-font"><?php echo esc_html__('Seller Note', 'motors'); ?></div>
            </div>
            <?php the_content(); ?>
        </div>
    </div>

    <div class="col-md-3 col-sm-12 col-xs-12">
        <div class="stm-single-car-side">
            <?php
            if ( is_active_sidebar( 'stm_listing_car' ) ) {
                dynamic_sidebar( 'stm_listing_car' );
            } else {
                /*<!--Prices-->*/
                get_template_part( 'partials/single-car/car', 'price' );

                /*<!--Data-->*/
                get_template_part( 'partials/single-car/car', 'data' );

                /*<!--Rating Review-->*/
                get_template_part( 'partials/single-car/car', 'review_rating' );

                /*<!--MPG-->*/
                get_template_part( 'partials/single-car/car', 'mpg' );

                /*<!--Calculator-->*/
                get_template_part( 'partials/single-car/car', 'calculator' );
            }
            ?>

        </div>
    </div>
</div>