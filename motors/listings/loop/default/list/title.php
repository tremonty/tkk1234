<div class="title heading-font">
	<a href="<?php the_permalink() ?>" class="rmv_txt_drctn">
		<?php
        if(stm_is_aircrafts()) {
            echo stm_generate_title_from_slugs(get_the_id(), true);
        } else {
            the_title();
        }
        ?>
	</a>
</div>