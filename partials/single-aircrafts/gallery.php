<?php
//Getting gallery list
$post_id = get_the_ID();

$gallery = get_post_meta( $post_id, 'gallery', true );
$car_media = stm_get_car_medias( $post_id );
$video_preview = get_post_meta( $post_id, 'video_preview', true );
$gallery_video = get_post_meta( $post_id, 'gallery_video', true );

$countImg = 0;
?>

<div class="container">
<?php if ( !has_post_thumbnail() and stm_check_if_car_imported( $post_id ) ): ?>
    <img
            src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/automanager_placeholders/plchldr798automanager.png' ); ?>"
            class="img-responsive"
            alt="<?php esc_attr_e( 'Placeholder', 'motors' ); ?>"
    />
<?php endif; ?>


<div class="stm-car-carousels">
    <div class="stm-big-car-gallery">
        <?php
        if ( has_post_thumbnail() ):
            $full_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );
            $countImg += 1;
            ?>
            <div class="stm-single-image"
                 data-id="big-image-<?php echo esc_attr( get_post_thumbnail_id( $post_id ) ); ?>">
                <a href="<?php echo esc_url( $full_src[0] ); ?>" class="stm_fancybox" rel="stm-car-gallery">
                    <?php the_post_thumbnail( 'stm-img-1110-577', array( 'class' => 'img-responsive' ) ); ?>
                </a>
            </div>
        <?php
        endif;
        if ( !empty( $video_preview ) and !empty( $gallery_video ) ):
            $src = wp_get_attachment_image_src( $video_preview, 'stm-img-1110-577' );
            if ( !empty( $src[0] ) ):
                $countImg += 1;
                ?>
                <div class="stm-single-image video-preview"
                     data-id="big-image-<?php echo esc_attr( $video_preview ); ?>">
                    <a class="fancy-iframe" data-iframe="true" data-src="<?php echo esc_url( $gallery_video ); ?>">
                        <img src="<?php echo esc_url( $src[0] ); ?>" class="img-responsive"
                             alt="<?php esc_attr_e( 'Video preview', 'motors' ); ?>"/>
                    </a>
                    <!--New badge with videos-->
                    <?php if ( !empty( $car_media['car_videos_count'] ) and $car_media['car_videos_count'] > 0 ): ?>
                        <div class="stm-car-medias">
                            <div class="stm-listing-videos-unit stm-car-videos-<?php echo get_the_id(); ?>">
                                <i class="fa fa-film"></i>
                                <span><?php echo esc_html( $car_media['car_videos_count'] ); ?><?php esc_html_e( 'Video', 'motors' ); ?></span>
                            </div>
                        </div>

                        <script>
                            jQuery(document).ready(function () {
                                jQuery(".stm-car-videos-<?php echo get_the_id(); ?>").on('click', function () {
                                    jQuery(this).lightGallery({
                                        dynamic: true,
                                        dynamicEl: [{
                                            src: "<?php echo esc_url( $gallery_video ); ?>"
                                        }
                                        ],
                                        download: false,
                                        mode: 'lg-fade',
                                    })
                                }); //click
                            }); //ready
                        </script>
                    <?php endif; ?>
                </div>
            <?php
            endif;
        endif;
        if ( !empty( $gallery ) ):
            foreach ( $gallery as $gallery_image ):
                $src = wp_get_attachment_image_src( $gallery_image, 'stm-img-796-466' );
                $full_src = wp_get_attachment_image_src( $gallery_image, 'full' );
                if ( !empty( $src[0] ) && $gallery_image != get_post_thumbnail_id( get_the_ID() ) ):
                    $countImg += 1;
                    ?>
                    <div class="stm-single-image" data-id="big-image-<?php echo esc_attr( $gallery_image ); ?>">
                        <a href="<?php echo esc_url( $full_src[0] ); ?>" class="stm_fancybox" rel="stm-car-gallery">
                            <img src="<?php echo esc_url( $src[0] ); ?>"
                                 alt="<?php printf( esc_attr__( '%s full', 'motors' ), get_the_title( $post_id ) ); ?>"/>
                        </a>
                    </div>
                <?php
                endif;
            endforeach;
        endif;

        if ( !empty( $car_media['car_videos_posters'] ) and !empty( $car_media['car_videos'] ) ):
            foreach ( $car_media['car_videos_posters'] as $k => $val ):
                $src = wp_get_attachment_image_src( $val, 'stm-img-350-205' );
                $videoSrc = ( isset( $car_media['car_videos'][$k] ) ) ? $car_media['car_videos'][$k] : '';
                if ( !empty( $src[0] ) ):
                    $countImg += 1;
                    ?>
                    <div class="stm-single-image video-preview" data-id="big-image-<?php echo esc_attr( $val ); ?>">
                        <a class="fancy-iframe" data-iframe="true" data-src="<?php echo esc_url( $videoSrc ); ?>">
                            <img src="<?php echo esc_url( $src[0] ); ?>" class="img-responsive"
                                 alt="<?php esc_attr_e( 'Video preview', 'motors' ); ?>"/>
                        </a>
                        <!--New badge with videos-->
                        <?php if ( !empty( $car_media['car_videos_count'] ) and $car_media['car_videos_count'] > 0 ): ?>
                            <div class="stm-car-medias">
                                <div class="stm-listing-videos-unit stm-car-videos-<?php echo get_the_id(); ?>">
                                    <i class="fa fa-film"></i>
                                    <span><?php echo esc_html( $car_media['car_videos_count'] ); ?><?php esc_html_e( 'Video', 'motors' ); ?></span>
                                </div>
                            </div>

                            <script>
                                jQuery(document).ready(function () {

                                    jQuery(".stm-car-videos-<?php echo get_the_id(); ?>").on('click', function () {
                                        jQuery(this).lightGallery({
                                            dynamic: true,
                                            dynamicEl: [
                                                <?php foreach($car_media['car_videos'] as $car_video): ?>
                                                {
                                                    src: "<?php echo esc_url( $car_video ); ?>"
                                                },
                                                <?php endforeach; ?>
                                            ],
                                            download: false,
                                            mode: 'lg-fade',
                                        })
                                    }); //click
                                }); //ready

                            </script>
                        <?php endif; ?>
                    </div>
                <?php
                endif;
            endforeach;
        endif;
        ?>
    </div>
    <?php get_template_part( 'partials/single-aircrafts/action-bar' ); ?>
    <div class="stm-owl-nav">
        <div class="stm-owl-prev">
            <i class="ac-icon-arrow-left"></i>
        </div>
        <div class="stm-nav-count-wrap">
            <div class="stm-owl-next">
                <i class="ac-icon-arrow-right"></i>
            </div>
            <span class="count-img"><?php printf( esc_html__( '+%s Images', 'motors' ), $countImg ); ?></span>
        </div>
    </div>
</div>
</div>

<?php if($countImg > 1) : ?>
<!--Enable carousel-->
<script>
    jQuery(document).ready(function ($) {
        var big = $('.stm-big-car-gallery');

        var owlRtl = false;
        if ($('body').hasClass('rtl')) {
            owlRtl = true;
        }

        big.owlCarousel({
            rtl: owlRtl,
            items: 1,
            center: true,
            dots: false,
            nav: false,
            margin: 0,
            autoplay: false,
            loop: true,
        });

        $('.stm-owl-next').click(function () {
            big.trigger('next.owl.carousel');
        })

        $('.stm-owl-prev').click(function () {
            big.trigger('prev.owl.carousel', [300]);
        })
    })
</script>
<?php endif; ?>