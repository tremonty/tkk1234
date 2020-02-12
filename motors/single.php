<?php get_header();?>
<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/lightbox/css/lightbox.min.css">
	<?php if ( is_singular( 'magazines' ) ) { 

	?>
   
   <div class="navisinglediv">
   
        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="stm-single-post navi">
                <div class="container">
                
                <div class="row">
                    <div class="col-sm-9">
                    <h3 class="megzinetop text-center">Issue: NO. <?php  echo types_render_field( "issue-no" ); ?>  <?php  echo types_render_field( "year" ); ?></h3>
                   
                    
                   <?php /*?> <div class="">
						<?php //the_terms( $post->ID, 'magazine-year' );
                        	echo strip_tags(get_the_term_list( $post->ID, 'magazine-year' ));
						?>
                    </div><?php */?>
                        <div class="row">
                            <div class="col-sm-6" style="">
                                <img class="magzinesingleimg" src="<?php echo the_post_thumbnail_url();?>" />
                                 <?php
                                      $post_id = $post->ID; //get your post_id
                                      $field = get_post_meta($post_id, 'wpcf-upload-pdf', true); //get the post_meta
									  $field2 = get_post_meta($post_id, 'wpcf-upload-pdf2', true); //get the post_meta
                                     ?>
                                    <div class="overlaybtns">
                                      <div class="btnsdiv"><a class="onlinebtn" href="<?php echo $field;?>" target="_blank">Read Online</a></div>
                                      <div class="btnsdiv"><a class="downldbtn" href="<?php echo $field2;?>" download="<?php  the_title();?>"  target="_blank">Free Download</a></div>
                                      
                                     </div>
                            </div>
                            <div class="col-sm-6" style="">
                              <?php /*?> <h3 class="magtitle"><?php  the_title();?></h3><?php */?>
                               <?php /*?><p class="metdata">Issue: NO. <?php  echo types_render_field( "issue-no" ); ?></p>
                               <p class="metdata">Time: <?php  echo types_render_field( "time" ); ?></p>
                               <p class="metdata">Pages: <?php  echo types_render_field( "pages" ); ?></p>    
                               <p class="metdata">Year: <?php  echo types_render_field( "year" ); ?></p> <?php 
                               <p class="metdata">Year: <?php  echo types_render_field( "sanpshot-1-image" ); ?></p> */?> 
                               
                               
                               <div class="row sanprow">
                                   <div class="col-sm-5">
                                   <div class="sanpimg">
                                     <a href="<?php echo $hoverImg = types_render_field("sanpshot-1-image", array("raw" => "true" ) );?>" data-lightbox="sanpshot">
                                      <?php  echo types_render_field( "sanpshot-1-image" ); ?>
                                     </a>
                                    </div>             
                                      
                                    </div>
                                    
                                     <div class="col-sm-7">
                                      <div class="sanpcat"><?php 
										// the_terms( $post->ID, 'magazine-category' );
											echo strip_tags(get_the_term_list( $post->ID, 'magazine-category' ));
										?>
									  </div>
                                      <div class="sanptitle">
                                      	<?php  echo types_render_field( "sanpshot-1-title" ); ?>
                                      </div>
                                        <div class="sanpyear">
                                             <?php  echo types_render_field( "year" ); ?>
                                        </div>
                                    
                                     </div>
                               </div>
                               
                               
                               <div class="row mb-2 sanprow">
                                   <div class="col-sm-5">
                                   <div class="sanpimg">
                                     <a href="<?php echo $hoverImg = types_render_field("sanpshot-2-image", array("raw" => "true" ) );?>" data-lightbox="sanpshot">
                                      <?php  echo types_render_field( "sanpshot-2-image" ); ?>
                                     </a>
                                    </div>             
                                     <?php
                                      $post_id = $post->ID; //get your post_id
                                      $field = get_post_meta($post_id, 'wpcf-upload-pdf', true); //get the post_meta
                                     ?>
                                    
                                      <?php /*?><div class="btnsdiv"><a class="onlinebtn" href="<?php echo $field;?>" target="_blank">Read Online</a></div>
                                      <div class="btnsdiv"><a class="downldbtn" href="<?php echo $field;?>" download="<?php  the_title();?>"  target="_blank">Free Download</a></div><?php */?>
                                    </div>
                                    
                                     <div class="col-sm-7">
                                      <div class="sanpcat"><?php 
										// the_terms( $post->ID, 'magazine-category' );
											echo strip_tags(get_the_term_list( $post->ID, 'magazine-category' ));
										?>
									  </div>
                                      <div class="sanptitle">
                                      	<?php  echo types_render_field( "sanpshot-2-title" ); ?>
                                      </div>
                                        <div class="sanpyear">
                                             <?php  echo types_render_field( "year" ); ?>
                                        </div>
                                    
                                     </div>
                               </div>
                               
                               <div class="row mb-2 "sanprow>
                                   <div class="col-sm-5">
                                   <div class="sanpimg">
                                     <a href="<?php echo $hoverImg = types_render_field("sanpshot-3-image", array("raw" => "true" ) );?>" data-lightbox="sanpshot">
                                      <?php  echo types_render_field( "sanpshot-3-image" ); ?>
                                     </a>
                                     </div>          
                                     <?php
                                      $post_id = $post->ID; //get your post_id
                                      $field = get_post_meta($post_id, 'wpcf-upload-pdf', true); //get the post_meta
                                     ?>
                                    
                                      <?php /*?><div class="btnsdiv"><a class="onlinebtn" href="<?php echo $field;?>" target="_blank">Read Online</a></div>
                                      <div class="btnsdiv"><a class="downldbtn" href="<?php echo $field;?>" download="<?php  the_title();?>"  target="_blank">Free Download</a></div><?php */?>
                                    </div>
                                    
                                     <div class="col-sm-7">
                                      <div class="sanpcat"><?php 
										// the_terms( $post->ID, 'magazine-category' );
											echo strip_tags(get_the_term_list( $post->ID, 'magazine-category' ));
										?>
									  </div>
                                      <div class="sanptitle">
                                      	<?php  echo types_render_field( "sanpshot-3-title" ); ?>
                                      </div>
                                        <div class="sanpyear">
                                             <?php  echo types_render_field( "year" ); ?>
                                        </div>
                                    
                                     </div>
                               </div>
                               
                               
                               
                            </div>
                            <?php if( get_field('Magazine_Banner','option') ) { ?>
                            <div class="naveedahmad">
                                    <a target="_blank" href="<?php the_field('url','option') ?>"><img src="<?php the_field('Magazine_Banner','option') ?>" /></a>
                                </div>
                            <?php } ?>
                            
                            
                            
                            <div class="relatedlisting">
                            <h3 class="text-center text-uppercase">more issues</h3>
                            <div class="row isotope"> 
							<?php 
                                 $args = array(  
                                  'post_type' => 'magazines',
                                  'posts_per_page' => -1,
                                  'post_status' => 'publish',
                                  'post__not_in' => array($post->ID),
                                  'orderby' => 'rand'
                             );
                         
                             $loop = new WP_Query( $args );
                              
                             while ( $loop->have_posts() ) : $loop->the_post(); ?>
							 <div data-category='transition' class="element-item transition col-md-2 col-sm-6 sommrgin <?php 
							$term_list = wp_get_post_terms( get_the_ID(), 'magazine-year', array( 'fields' => 'ids' ) );
								echo $pureid = implode(" ", $term_list );
							  ?>">
							 	<a href="<?php echo get_post_permalink( $post->ID ); ?>"> <img class="magzinethumb" src="<?php echo the_post_thumbnail_url();?>" /></a>
                             	<a class="relatedmagtitle" href="<?php echo get_post_permalink( $post->ID ); ?>">  <?php  echo types_render_field( "year" ); ?></a>
                           </div>      
                                 
								 
							<?php
                              endwhile;
                          
                             wp_reset_postdata();
                              
                           ?>
                           </div>
                           
                           
                           
                            </div>
                            
                            
                        </div>
                    </div>
                    
                    
                    
                    
                    
                    
                    <div class="col-sm-3">
                          <?php if ( is_active_sidebar( 'magazine' ) ) {?>
                            <div class="magazinesidebar">
                                <?php dynamic_sidebar( 'magazine' ); ?>
                            </div>
                          <?php } ?>
                        </div>
                 </div>   
                </div>
              </div>
            </div>
   </div>
   
   
   
   
<?php /*?>   <?php 
	   $args = array(  
        'post_type' => 'magazines',
		'posts_per_page' => 5,
		'post_status' => 'publish',
		'post__not_in' => array($post->ID),
		'orderby' => 'rand'
   );

   $loop = new WP_Query( $args );
     
   while ( $loop->have_posts() ) : $loop->the_post();
       print the_title();
   	endwhile;

   wp_reset_postdata();
	
 ?><?php */?>
	<?php } 
	
	else{
	
    if(!stm_is_magazine()) {
        get_template_part('partials/page_bg');
        get_template_part('partials/title_box');
    } else {
        get_template_part('partials/magazine/content/breadcrumbs');
    }
	?>
	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="stm-single-post navi">
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
 
   <?php  } ?>
   
   
    
<?php get_footer();?>



<script src="<?php echo get_stylesheet_directory_uri(); ?>/lightbox/js/lightbox.min.js"></script>


<script>
/*jQuery( document ).ready(function() {
jQuery( ".navirelatedposts" ).insertBefore( jQuery( ".stm_post_comments" ) );
});*/
</script>

<script src='http://npmcdn.com/isotope-layout@2/dist/isotope.pkgd.js'></script>




<script>
// external js: isotope.pkgd.js

$(document).ready(function() {

  // init Isotope
  var $container = $('.isotope').isotope({
    itemSelector: '.element-item',
    layoutMode: 'fitRows',
    getSortData: {
      name: '.name',
      symbol: '.symbol',
      number: '.number parseInt',
      category: '[data-category]',
      weight: function(itemElem) {
        var weight = $(itemElem).find('.weight').text();
        return parseFloat(weight.replace(/[\(\)]/g, ''));
      }
    }
  });

  // filter functions
  var filterFns = {
    // show if number is greater than 50
    numberGreaterThan50: function() {
      var number = $(this).find('.number').text();
      return parseInt(number, 10) > 50;
    },
    // show if name ends with -ium
    ium: function() {
      var name = $(this).find('.name').text();
      return name.match(/ium$/);
    }
  };




$('.filters-select').on( 'change', function() {
  // get filter value from option value
  var filterValue = this.value;
  // use filterFn if matches value
  filterValue = filterFns[ filterValue ] || filterValue;
  $container.isotope({
      filter: filterValue
    });
});





  // bind sort button click
  $('#sorts').on('click', 'button', function() {
    var sortByValue = $(this).attr('data-sort-by');
    $container.isotope({
      sortBy: sortByValue
    });
  });

  // change is-checked class on buttons
  $('.button-group').each(function(i, buttonGroup) {
    var $buttonGroup = $(buttonGroup);
    $buttonGroup.on('click', 'button', function() {
      $buttonGroup.find('.is-checked').removeClass('is-checked');
      $(this).addClass('is-checked');
    });
  });

  //****************************
  // Isotope Load more button
  //****************************
  var initShow = 3; //number of items loaded on init & onclick load more button
  var counter = initShow; //counter for load more button
  var iso = $container.data('isotope'); // get Isotope instance

  loadMore(initShow); //execute function onload

  function loadMore(toShow) {
    $container.find(".hidden").removeClass("hidden");

    var hiddenElems = iso.filteredItems.slice(toShow, iso.filteredItems.length).map(function(item) {
      return item.element;
    });
    $(hiddenElems).addClass('hidden');
    $container.isotope('layout');

    //when no more to load, hide show more button
    if (hiddenElems.length == 0) {
      jQuery("#load-more").hide();
    } else {
      jQuery("#load-more").show();
    };

  }

  //append load more button
  //$container.after('<button id="load-more"> Load More</button>');

  //when load more button clicked
  $(window).scroll(function() {
    if ($(window).scrollTop() == $(document).height() - $(window).height()) {
    if ($('#filters').data('clicked')) {
      //when filter button clicked, set initial value for counter
      counter = initShow;
      $('#filters').data('clicked', false);
    } else {
      counter = counter;
    };

    counter = counter + initShow;

    loadMore(counter);
	}
  });

  //when filter button clicked
  $("#filters").click(function() {
    $(this).data('clicked', true);

    loadMore(initShow);
  });

  
  
});
</script>