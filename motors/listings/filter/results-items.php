<?php if ( have_posts() ):

    $view_type = sanitize_file_name( stm_listings_input( 'view_type', get_theme_mod( "listing_view_type", "list" ) ) );

    $template = 'partials/listing-cars/listing-aircrafts-' . $view_type;

    while ( have_posts() ): the_post();
        get_template_part( $template );
    endwhile;

endif; ?>