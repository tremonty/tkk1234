<?php
$price = get_post_meta( get_the_ID(), 'price', true );
$sale_price = get_post_meta( get_the_ID(), 'sale_price', true );
$car_price_form_label = get_post_meta( get_the_ID(), 'car_price_form_label', true );

if ( empty( $price ) and !empty( $sale_price ) ) {
    $price = $sale_price;
}

if ( !empty( $price ) and !empty( $sale_price ) ) {
    $price = $sale_price;
}

$asSold = get_post_meta( get_the_ID(), 'car_mark_as_sold', true );

?>
<div class="stm-listing-single-price-title heading-font clearfix">
    <?php if ( !$asSold ): ?>
        <?php if ( !empty( $car_price_form_label ) ): ?>
            <div class="price"><?php echo esc_attr( $car_price_form_label ); ?></div>
        <?php else: ?>
            <?php if ( !empty( $price ) ): ?>
                <div class="price"><?php echo stm_listing_price_view( $price ); ?></div>
            <?php endif; ?>
        <?php endif; ?>
    <?php else : ?>
        <div class="price"><?php echo esc_html__( 'Sold', 'motors' ); ?></div>
    <?php endif; ?>
    <div class="stm-single-title-wrap">
        <h1 class="title">
            <?php echo stm_generate_title_from_slugs( get_the_ID(), get_theme_mod( 'show_generated_title_as_label', false ) ); ?>
        </h1>
        <?php if ( get_theme_mod( 'show_added_date', false ) ) : ?>
            <span class="normal_font">
                <i class="fa fa-clock-o"></i>
                <?php printf( esc_html__( 'ADDED: %s', 'motors' ), get_the_modified_date( 'F d, Y' ) ); ?>
            </span>
        <?php endif; ?>
    </div>
</div>