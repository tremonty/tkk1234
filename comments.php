<?php
if ( post_password_required() ) {
	return;
}
?>

 <div class="container navirelatedposts">
    <h2 class="widgettitle">Related Posts</h2>
      <div class="row">
       <?php 
	   $args = array(  
        'post_type' => 'post',
		'posts_per_page' => 4,
		'post_status' => 'publish',
		'post__not_in' => array($post->ID),
		'orderby' => 'rand'
   );

   $loop = new WP_Query( $args );
     
   while ( $loop->have_posts() ) : $loop->the_post();
      ?>
      
      	<div class="col-md-3 col-sm-3 col-xs-12">
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
	
 ?>
    </div>
	  </div>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) { ?>
		<h4 class="comments-title">
			<?php comments_number(); ?>
		</h4>

		<ul class="comment-list list-unstyled">
			<?php
				wp_list_comments( array(
					'style'       => 'ul',
					'short_ping'  => true,
					'avatar_size' => 80,
					'callback'    => 'stm_comment'
				) );
			?>
		</ul>
		<div class="clearfix"></div>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { ?>
			<nav class="navigation comment-navigation" role="navigation">
				<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'motors' ); ?></h2>
				<div class="nav-links">
					<?php
					if ( $prev_link = get_previous_comments_link( __( 'Older Comments', 'motors' ) ) ) {
						printf( '<div class="nav-previous">%s</div>', $prev_link );
					}
					if ( $next_link = get_next_comments_link( __( 'Newer Comments', 'motors' ) ) ) {
						printf( '<div class="nav-next">%s</div>', $next_link );
					}
					?>
				</div>
			</nav>
		<?php } ?>

	<?php } ?>

	<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) { ?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'motors' ); ?></p>
	<?php } ?>

	<?php comment_form( array(
		'comment_notes_before' => '',
		'comment_notes_after' => ''
	) ); ?>

</div>