<?php get_header();?>
	<?php
    if(!stm_is_magazine()) {
        get_template_part('partials/page_bg');
        get_template_part('partials/title_box');
    } else {
        get_template_part('partials/magazine/content/breadcrumbs');
    }
	?>
	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="stm-single-post">
			<div class="container">
				<?php if ( have_posts() ) :
					while ( have_posts() ) : the_post();
						if(!stm_is_magazine()) {
							get_template_part('partials/blog/content');
						} else {
							get_template_part('partials/magazine/main');
						}
					endwhile;
				endif; ?>
			</div>
		</div>
	</div>
<?php get_footer();?>