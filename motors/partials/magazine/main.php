<?php
$sidebar_id = get_theme_mod('sidebar_blog', 'default');
$sidebar_position = get_theme_mod('sidebar_position', 'left');

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

?>

<div class="row">
    <?php echo stm_do_lmth($stm_sidebar_layout_mode['content_before']); ?>

    <!--Post thumbnail-->
    <?php if ( has_post_thumbnail() ): ?>
        <div class="post-thumbnail">
            <?php the_post_thumbnail( 'stm-img-1110-577', array( 'class' => 'img-responsive' ) ); ?>
        </div>
    <?php endif; ?>

    <?php  get_template_part('partials/magazine/content/top-content-standart'); ?>

    <?php
    wp_link_pages( array(
        'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'motors' ) . '</span>',
        'after'       => '</div>',
        'link_before' => '<span>',
        'link_after'  => '</span>',
        'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'motors' ) . ' </span>%',
        'separator'   => '<span class="screen-reader-text">, </span>',
    ) );
    ?>

    <div class="blog-meta-bottom">
        <div class="clearfix">
            <div class="left">
                <!--Tags-->
                <?php if( $tags = wp_get_post_tags( get_the_ID() ) ){ ?>
                    <div class="post-tags">
                        <span class="h6"><?php esc_html_e( 'Tags', 'motors' ); ?></span>
                        <span class="post-tag">
                            <?php echo get_the_tag_list('', '', ''); ?>
                        </span>
                    </div>
                <?php } ?>
            </div>

            <div class="right">
                <div class="stm-shareble stm-single-car-link">
                    <a
                        href="#"
                        class="car-action-unit stm-share"
                        title="<?php esc_attr_e('Share this', 'motors'); ?>"
                        download>
                        <i class="stm-icon-share"></i>
                        <?php esc_html_e('Share this', 'motors'); ?>
                    </a>
                    <?php if(function_exists( 'ADDTOANY_SHARE_SAVE_KIT' ) && ! get_post_meta( get_the_ID(), 'sharing_disabled', true )): ?>
                        <div class="stm-a2a-popup">
                            <?php echo do_shortcode('[addtoany url="'.get_the_permalink(get_the_ID()).'" title="'.get_the_title(get_the_ID()).'"]'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!--Author info-->
    <?php if ( get_the_author_meta('description') ) : ?>
        <div class="stm-author-box clearfix author-info-wrap">
            <div class="author-image">
                <?php echo get_avatar( get_the_author_meta( 'email' ), 86 ); ?>
            </div>
            <div class="author-content">
                <h6><?php esc_html_e( 'Author:', 'motors' ); ?></h6>
                <h4><?php the_author_meta('nickname'); ?></h4>
                <div class="author-description"><?php echo get_the_author_meta( 'description' ); ?></div>
            </div>
        </div>
    <?php endif; ?>

    <!--Previous Next Post Pagination-->
    <?php get_template_part('partials/magazine/content/previous_next_pagination'); ?>




    <!--Comments-->
    <?php if ( comments_open() || get_comments_number() ) { ?>
        <div class="stm_post_comments cmnt2">
            <?php comments_template(); ?>
        </div>
    <?php } ?>

    <?php echo stm_do_lmth($stm_sidebar_layout_mode['content_after']); ?>


    <!--Sidebar-->
    <?php
    if($sidebar_id == 'default') {
        echo stm_do_lmth($stm_sidebar_layout_mode['sidebar_before']);
        get_sidebar();
        echo stm_do_lmth($stm_sidebar_layout_mode['sidebar_after']);
    }else if(!empty($sidebar_id)) {
        echo stm_do_lmth($stm_sidebar_layout_mode['sidebar_before']);
        echo apply_filters( 'the_content' , $blog_sidebar->post_content);
        echo stm_do_lmth($stm_sidebar_layout_mode['sidebar_after']); ?>
        <style type="text/css">
            <?php echo get_post_meta( $sidebar_id, '_wpb_shortcodes_custom_css', true ); ?>
        </style>
    <?php }
    ?>
</div>