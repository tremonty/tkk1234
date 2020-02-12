<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

if ( empty( $number_of_posts ) ) {
	$number_of_posts = 1;
}

$args = array(
	'post_type'      => 'post',
	'posts_per_page' => $number_of_posts,
    'ignore_sticky_posts' => true,
);

$r = new WP_Query( $args );

$use_category_tabs = (isset($use_category_tabs) && $use_category_tabs == 'yes') ? true : false;

if($use_category_tabs) {
    $idObj = get_category_by_slug('slide');
    $catList = get_categories(array('hide_empty' => true, 'exclude' => array($idObj->term_id, 1)));
}

?>

<?php if ( $r->have_posts() ) :?>
	<div class="widget stm_widget_recent_entries">
        <?php if(!$use_category_tabs): ?>
            <?php if(!empty($title)): ?>
                <h4><?php echo esc_attr($title); ?></h4>
            <?php endif; ?>
        <?php else : ?>
            <div class="recent-top">
                <div class="left">
                    <?php if(!empty($title)): ?>
                        <h4><?php echo esc_attr($title); ?></h4>
                    <?php endif; ?>
                </div>
                <div class="right">
                    <ul class="cat-list">
                        <li><span class="heading-font active"><?php echo esc_html__('All News', 'motors'); ?></span></li>
                        <?php foreach($catList as $k => $cat) : ?>
                            <li>
                                <span class="heading-font"><?php echo esc_html($cat->name); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="recent-show-all">
                        <span class="btn-show"></span>
                    </div>
                </div>
            </div>
		<?php endif; ?>
		<?php while ( $r->have_posts() ) : $r->the_post(); ?>
            <?php if(!stm_is_magazine()) : ?>
			<div class="stm-last-post-widget">
				<?php echo wp_trim_words( get_the_excerpt(), 13, '...' ); ?>
				<?php $com_num = get_comments_number( get_the_id() ); ?>
				<?php if ( ! empty( $com_num ) ) { ?>
					<div class="comments-number">
						<a href="<?php echo esc_url(get_comments_link(get_the_ID())); ?>"><i
								class="stm-icon-message"></i><?php echo esc_attr( $com_num ) . ' ' . esc_html__( 'Comment', 'motors' ); ?>
						</a>
					</div>
				<?php } else { ?>
					<div class="comments-number">
						<a href="<?php the_permalink() ?>">
							<i class="stm-icon-message"></i><?php esc_html_e( 'No comments', 'motors' ); ?>
						</a>
					</div>
				<?php }; ?>
			</div>
            <?php else : ?>
                <?php get_template_part('partials/blog/content-list-magazine-loop'); ?>
            <?php endif;?>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
	</div>
<?php endif;