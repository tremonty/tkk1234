<?php
	//Get sidebar settings


	$sidebar_id = get_theme_mod('sidebar', 'default');
	$sidebar_position = get_theme_mod('sidebar_position', 'right');

	if(!empty($_GET['sidebar-position']) and $_GET['sidebar-position'] == 'left') {
		$sidebar_position = 'left';
	}

	if(!empty($_GET['sidebar-position']) and $_GET['sidebar-position'] == 'right') {
		$sidebar_position = 'right';
	}

	if(!empty($_GET['sidebar-position']) and $_GET['sidebar-position'] == 'none') {
		$sidebar_id = false;
	}

	$view_type = get_theme_mod('view_type', 'grid');

	if(!empty($_GET['view-type']) and $_GET['view-type'] == 'grid') {
		$view_type = 'grid';
	}

	if(!empty($_GET['view-type']) and $_GET['view-type'] == 'list') {
		$view_type = 'list';
	}

	if( !empty($sidebar_id) ) {
		$blog_sidebar = get_post( $sidebar_id );
	}

	if(!is_numeric($sidebar_id) && ($sidebar_id == 'no_sidebar' || !is_active_sidebar($sidebar_id))) {
	    $sidebar_id = false;
    }

    if(is_numeric($sidebar_id) && empty($blog_sidebar->post_content)) {
	    $sidebar_id = false;
    }

	$stm_sidebar_layout_mode = stm_sidebar_layout_mode($sidebar_position, $sidebar_id);

    $tpl = '';
    if($view_type == 'grid') {
        $tpl = (!stm_is_magazine()) ? 'partials/blog/content-grid' : 'partials/blog/content-grid-magazine';
    } else {
        $tpl = (!stm_is_magazine()) ? 'partials/blog/content-list' : 'partials/blog/content-list-magazine';
    }
?>

<?php get_header(); ?>

<?php
if (is_post_type_archive( 'magazines' )) {?>


<div class="entry-header center small_title_box" style="">
	    <div class="container">
            <div class="entry-title">
                <h2 class="h1" style=""> Magazine Archive</h2>
	       </div>
	    </div>
    </div>


	<div class="stm-archives archivenavi stm-view-type-<?php echo esc_attr($view_type); ?>">
		<div class="container">
            <div class="row <?php if(stm_is_magazine()) echo 'sb-' . $sidebar_position; ?>">
				<?php if(have_posts()): ?>
					<?php echo stm_do_lmth($stm_sidebar_layout_mode['content_before']); ?>
						<?php echo stm_do_lmth($stm_sidebar_layout_mode['show_title']); ?>
                        <?php
                        if(stm_is_magazine()) :
                            get_template_part('partials/magazine/content/title_box_magazine_archive');
                        endif;
                        ?>

						<?php if($view_type == 'grid'): ?>
						<div class="row row-<?php echo esc_attr($stm_sidebar_layout_mode['default_row']); ?>">
						<?php endif; ?>

						<?php while(have_posts()): the_post(); ?>
                            <?php get_template_part($tpl, 'loop'); ?>
						<?php endwhile; ?>

						<?php if($view_type == 'grid'): ?>
						</div>
						<?php endif; ?>

						<!--Pagination-->
						<?php stm_custom_pagination(); ?>

					<?php echo stm_do_lmth($stm_sidebar_layout_mode['content_after']); ?>

					<!--Sidebar-->
					<?php
						if($sidebar_id == 'default') {
							echo stm_do_lmth($stm_sidebar_layout_mode['sidebar_before']);
							get_sidebar();
							echo stm_do_lmth($stm_sidebar_layout_mode['sidebar_after']);
						} else if(!empty($sidebar_id)) {
							echo stm_do_lmth($stm_sidebar_layout_mode['sidebar_before']);
							echo apply_filters( 'the_content' , $blog_sidebar->post_content);
							echo stm_do_lmth($stm_sidebar_layout_mode['sidebar_after']); ?>
							<style type="text/css">
								<?php echo get_post_meta( $sidebar_id, '_wpb_shortcodes_custom_css', true ); ?>
							</style>
					<?php }
					?>
				<?php else: ?>
					<div class="col-md-12">
						<h3 class="text-transform nothing found"><?php esc_html_e('No Results', 'motors'); ?></h3>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php }


 else { ?>


	<?php (!stm_is_magazine()) ? get_template_part('partials/title_box') : get_template_part('partials/magazine/content/breadcrumbs'); ?>


	<div class="stm-archives archivenavi stm-view-type-<?php echo esc_attr($view_type); ?>">
		<div class="container">
            <div class="row <?php if(stm_is_magazine()) echo 'sb-' . $sidebar_position; ?>">
				<?php if(have_posts()): ?>
					<?php echo stm_do_lmth($stm_sidebar_layout_mode['content_before']); ?>
						<?php echo stm_do_lmth($stm_sidebar_layout_mode['show_title']); ?>
                        <?php
                        if(stm_is_magazine()) :
                            get_template_part('partials/magazine/content/title_box_magazine_archive');
                        endif;
                        ?>

						<?php if($view_type == 'grid'): ?>
						<div class="row row-<?php echo esc_attr($stm_sidebar_layout_mode['default_row']); ?>">
						<?php endif; ?>

						<?php while(have_posts()): the_post(); ?>
                            <?php get_template_part($tpl, 'loop'); ?>
						<?php endwhile; ?>

						<?php if($view_type == 'grid'): ?>
						</div>
						<?php endif; ?>

						<!--Pagination-->
						<?php stm_custom_pagination(); ?>

					<?php echo stm_do_lmth($stm_sidebar_layout_mode['content_after']); ?>

					<!--Sidebar-->
					<?php
						if($sidebar_id == 'default') {
							echo stm_do_lmth($stm_sidebar_layout_mode['sidebar_before']);
							get_sidebar();
							echo stm_do_lmth($stm_sidebar_layout_mode['sidebar_after']);
						} else if(!empty($sidebar_id)) {
							echo stm_do_lmth($stm_sidebar_layout_mode['sidebar_before']);
							echo apply_filters( 'the_content' , $blog_sidebar->post_content);
							echo stm_do_lmth($stm_sidebar_layout_mode['sidebar_after']); ?>
							<style type="text/css">
								<?php echo get_post_meta( $sidebar_id, '_wpb_shortcodes_custom_css', true ); ?>
							</style>
					<?php }
					?>
				<?php else: ?>
					<div class="col-md-12">
						<h3 class="text-transform nothing found"><?php esc_html_e('No Results', 'motors'); ?></h3>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
    
<?php } ?>
<?php get_footer(); ?>