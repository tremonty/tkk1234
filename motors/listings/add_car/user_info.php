<?php
$user = stm_get_user_custom_fields( $user_id );

if ( is_wp_error( $user ) ) {
	return;
}
$dealer = stm_get_user_role( $user['user_id'] );
if ( $dealer ) :
	$ratings = stm_get_dealer_marks( $user_id ); ?>

	<div class="stm-add-a-car-user">
		<div class="clearfix">
			<div class="left-info left-dealer-info">
				<div class="stm-dealer-image-custom-view">
					<?php if ( ! empty( $user['logo'] ) ): ?>
						<img src="<?php echo esc_url( $user['logo'] ); ?>"/>
					<?php else: ?>
						<img src="<?php stm_get_dealer_logo_placeholder(); ?>"/>
					<?php endif; ?>
				</div>
				<h4><?php stm_display_user_name( $user['user_id'], $user_login, $f_name, $l_name ); ?></h4>

				<?php if ( ! empty( $ratings['average'] ) ): ?>
					<div class="stm-star-rating">
						<div class="inner">
							<div class="stm-star-rating-upper" style="width:<?php echo esc_attr( $ratings['average_width'] ); ?>"></div>
							<div class="stm-star-rating-lower"></div>
						</div>
						<div class="heading-font"><?php echo stm_do_lmth($ratings['average']); ?></div>
					</div>
				<?php endif; ?>

			</div>

			<div class="right-info">

				<a target="_blank" href="<?php echo esc_url( add_query_arg( array( 'view-myself' => 1 ), get_author_posts_url( $user_id ) ) ); ?>">
					<i class="fa fa-external-link"></i><?php esc_html_e( 'Show my Public Profile', 'motors' ); ?>
				</a>

				<div class="stm_logout">
					<a href="#"><?php esc_html_e( 'Log out', 'motors' ); ?></a>
					<?php esc_html_e( 'to choose a different account', 'motors' ); ?>
				</div>

			</div>

		</div>
	</div>

<?php else: ?>

	<div class="stm-add-a-car-user">
		<div class="clearfix">
			<div class="left-info">
				<div class="avatar">
					<?php if ( ! empty( $user['image'] ) ): ?>
						<img src="<?php echo esc_url( $user['image'] ); ?>"/>
					<?php else: ?>
						<i class="stm-service-icon-user"></i>
					<?php endif; ?>
				</div>
				<div class="user-info">
					<h4><?php stm_display_user_name( $user['user_id'], $user_login, $f_name, $l_name ); ?></h4>
					<div class="stm-label"><?php esc_html_e( 'Private Seller', 'motors' ); ?></div>
				</div>
			</div>

			<div class="right-info">
				<a target="_blank" href="<?php echo esc_url( add_query_arg( array( 'view-myself' => 1 ), get_author_posts_url( $user_id ) ) ); ?>">
					<i class="fa fa-external-link"></i><?php esc_html_e( 'Show my Public Profile', 'motors' ); ?>
				</a>
				<div class="stm_logout">
					<a href="#"><?php esc_html_e( 'Log out', 'motors' ); ?></a>
					<?php esc_html_e( 'to choose a different account', 'motors' ); ?>
				</div>
			</div>
		</div>
	</div>
<?php endif;