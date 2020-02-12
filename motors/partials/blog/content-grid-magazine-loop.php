<?php
/**
 * Created by PhpStorm.
 * User: NDA
 * Date: 03.01.2018
 * Time: 10:38
 */

$id = get_the_ID();
$category = stm_motors_get_terms_array($id, 'category', 'name', false);
$date = get_the_date('d M', $id);
?>
<div class="col-md-6 col-sm-6 col-xs-12">
    <a href="<?php the_permalink(); ?>"
       title="<?php the_title(); ?>"
        <?php echo esc_attr(post_class('stm_magazine_single_grid no_deco')); ?>>
        <div class="magazine-grid-img">
            <?php the_post_thumbnail('stm-img-398-206');?>
        </div>
        <div class="stm-magazine-loop-data">
            <?php if(isset($category[0])): ?>
                <div class="magazine-category heading-font">
                    <?php echo stm_do_lmth($category[0]);?>
                </div>
            <?php endif; ?>
            <div class="news-meta-wrap">
                <h3 class="ttc"><?php the_title(); ?></h3>
                <div class="left">
                    <?php if(!empty($date)): ?>
                        <div class="magazine-loop-Date">
                            <i class="icon-ico_mag_reviews"></i>
                            <div><?php echo esc_attr($date); ?></div>
                        </div>
                    <?php endif; ?>

                </div>
                <div class="right">

                </div>
            </div>
        </div>
    </a>
</div>