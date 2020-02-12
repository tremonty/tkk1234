<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$css_class = (!empty($css)) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

$sticky = get_option('sticky_posts');
$posts_per_page = (isset($_GET['posts_per_page'])) ? $_GET['posts_per_page'] : 4;

$args = array(
    'post_type' => 'any',
    'post__in' => $sticky,
    'post_status' => 'publish'
);

$r = new WP_Query( $args );

$cats = array();
$catsFilter = array();
foreach ($r->posts as $k => $post) {
    $catObj = get_the_terms($post, 'category');

    if($catObj) {
        if (!$catObj) {
            $catObj = get_the_terms($post, 'review_category');
        } elseif (!$catObj) {
            $catObj = get_the_terms($post, 'event_category');
        }

        if (!is_null($catObj[0]->name)) $catsFilter[$catObj[0]->slug] = $catObj[0]->name;
        $cats[$k]['name'] = $catObj[0]->name;
        $cats[$k]['slug'] = $catObj[0]->slug;
    }
}

$hidenWrap = ($use_adsense == 'yes') ? 3 : 4;
?>
<div id="features_posts_wrap" data-action="&hidenWrap=<?php echo esc_attr($hidenWrap);?>&adsense_position=<?php echo (!empty($adsense_position)) ? $adsense_position : 1?>&use_adsense=<?php echo (!empty($use_adsense)) ? $use_adsense : 'no'?>" class="stm-features-posts-main <?php echo esc_attr($css_class); ?>">
    <div class="features-top">
        <div class="left">
            <h2>
                <?php echo esc_html($features_title); ?>
            </h2>
        </div>
        <div class="right">
            <ul class="cat-list features-cat-list">
                <li class="active" data-slug="all"><span class="heading-font"><?php echo esc_html__('All Features', 'motors'); ?></span></li>
                <?php foreach ($catsFilter as $slug => $name) : ?>
                    <li data-slug="<?php echo esc_html($slug); ?>">
                        <span class="heading-font"><?php echo esc_html($name); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="features-show-all">
                <span class="btn-show"></span>
            </div>
        </div>
    </div>
    <div class="features_posts_wrap">
        <?php
        if($r->have_posts()) {
            $num = 0;
            while($r->have_posts()) {
                $r->the_post();
                if($num == 0) {
                    get_template_part('partials/vc_loop/features_posts_big_loop');
                } else {
                    if($adsense_position == $num && $use_adsense == 'yes') {
                ?>
                    <div class="adsense-200-200">
                        <?php if(!empty($content)) echo stm_do_lmth($content); ?>
                    </div>
                <?php
                    }
                    if($num > $hidenWrap) echo '<div class="features_hiden">';
                        get_template_part('partials/vc_loop/features_posts_small_loop');
                    if($num > $hidenWrap) echo '</div>';
                }

                $num++;
            }
        }
        ?>
    </div>
</div>

<?php wp_reset_postdata(); ?>
