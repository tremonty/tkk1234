<?php
$current = stm_account_current_page();
?>

<div class="stm-user-private-main">

	<?php if ( $current == 'favourite' ): ?>

		<div class="archive-listing-page">
			<?php get_template_part( 'partials/user/private/user-favourite' ); ?>
		</div>

	<?php elseif ( $current == 'settings' ): ?>

		<?php get_template_part( 'partials/user/private/' . ( stm_get_user_role( get_current_user_id() ) ? 'dealer-settings' : 'user-settings' ) ); ?>

	<?php elseif ( $current == 'become-dealer' ): ?>

		<?php get_template_part( 'partials/user/private/become-dealer' ); ?>

	<?php elseif ( $current == 'inventory' ):

		$query = ( function_exists( 'stm_user_listings_query' ) ) ? stm_user_listings_query( get_current_user_id(), 'any' ) : null;
		$queryPPL = ( function_exists( 'stm_user_pay_per_listings_query' ) ) ? stm_user_pay_per_listings_query( get_current_user_id(), 'any' ) : null;
		$tabsActive = ( $query != null && $query->have_posts() && $queryPPL != null && $queryPPL->have_posts() ) ? true : false;

		if ( $query != null && $query->have_posts() || $queryPPL != null && $queryPPL->have_posts() ): ?>
			<?php get_template_part( 'partials/user/private/user', 'inventory' ); ?>
			<div class="archive-listing-page">
				<?php if ( $tabsActive ) : ?>
					<ul class="nav nav-tabs" role="tablist">
						<li class="nav-item active">
							<a href="#pp" class="nav-link active heading-font" id="pp-tab" data-toggle="tab" role="tab" aria-controls="pp" aria-selected="true">
								<?php echo esc_html__( 'Subsciption Listings', 'motors' ); ?>
							</a>
						</li>
						<li class="nav-item">
							<a href="#ppl" class="nav-link heading-font" id="ppl-tab" data-toggle="tab" role="tab" aria-controls="ppl" aria-selected="false">
								<?php echo esc_html__( 'Pay Per Listings', 'motors' ); ?>
							</a>
						</li>
					</ul>
				<?php endif; ?>

				<?php if ( $tabsActive ) : ?>
				<div class="tab-content">
					<div class="tab-pane active" id="pp" role="tabpanel" aria-labelledby="pp-tab">
						<?php endif; ?>

						<?php while ( $query->have_posts() ): $query->the_post(); ?>
							<?php get_template_part( 'partials/listing-cars/listing-list-directory-edit', 'loop' ); ?>
						<?php endwhile; ?>

						<?php if ( $tabsActive ) : ?>
					</div>
					<?php endif; ?>

					<?php if ( $tabsActive ) : ?>
					<div class="tab-pane" id="ppl" role="tabpanel" aria-labelledby="ppl-tab">
						<?php endif; ?>

						<?php
						if ( $queryPPL != null && $queryPPL->have_posts() ):
							while ( $queryPPL->have_posts() ): $queryPPL->the_post();
								?>
								<?php get_template_part( 'partials/listing-cars/listing-list-directory-edit', 'loop' ); ?>
							<?php
							endwhile;
						endif;
						?>

						<?php if ( $tabsActive ) : ?>
					</div>
				</div>
			<?php endif; ?>
			</div>
		<?php else: ?>
			<h4 class="stm-seller-title"><?php esc_html_e( 'No Inventory yet', 'motors' ); ?></h4>
		<?php endif; ?>

	<?php else:

		do_action( 'stm_account_custom_page', $current );

	endif; ?>
</div>