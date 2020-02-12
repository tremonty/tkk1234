<?php
$user = wp_get_current_user();
$user_fields = stm_get_user_custom_fields( $user->ID );
$link = add_query_arg(array('page_admin' => 'settings'), stm_get_author_link(''));
?>

<div class="stm-user-private">
	<div class="container">
		<div class="row">

			<div class="col-md-3 col-sm-3 hidden-sm hidden-xs stm-sticky-user-sidebar">
				<div class="stm-user-private-sidebar">

					<div class="clearfix stm-user-top">

						<div class="stm-user-avatar">
							<a href="<?php echo esc_url($link); ?>" class="stm-image-avatar image <?php echo esc_attr(empty($user_fields['image']) ? 'hide-photo' : 'hide-empty'); ?>">
								<img class="img-responsive img-avatar" src="<?php echo (!empty(get_user_meta($user->ID, 'wsl_current_user_image', true))) ? esc_url(get_user_meta($user->ID, 'wsl_current_user_image', true)) : esc_url($user_fields['image']); ?>" />
								<div class="stm-empty-avatar-icon"><i class="fa fa-camera"></i></div>
							</a>
						</div>

						<div class="stm-user-profile-information">
							<a href="<?php echo esc_url($link); ?>" class="title heading-font"><?php echo esc_attr(stm_display_user_name($user->ID)); ?></a>
							<div class="title-sub"><?php esc_html_e('Private Seller', 'motors'); ?></div>
							<?php if(!empty($user_fields['socials'])): ?>
								<div class="socials clearfix">
									<?php foreach($user_fields['socials'] as $social_key => $social): ?>
										<a href="<?php echo esc_url($social); ?>">
											<?php
												if($social_key == 'youtube') {
													$social_key = 'youtube-play';
												}
											?>
											<i class="fa fa-<?php echo esc_attr($social_key); ?>"></i>
										</a>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>

					</div>

					<?php if(!stm_pricing_enabled()): ?>
						<div class="stm-became-dealer">
							<a href="<?php echo esc_url(add_query_arg(array('become_dealer' => 1), stm_get_author_link(''))); ?>" class="button stm-dp-in"><?php esc_html_e('Become a dealer', 'motors'); ?></a>
						</div>
					<?php endif; ?>

					<?php get_template_part( 'partials/user/private/navigation' ) ?>

					<?php if(!empty($user_fields['phone'])): ?>
						<div class="stm-dealer-phone">
							<i class="stm-service-icon-phone"></i>
							<div class="phone-label heading-font"><?php esc_html_e('Seller Contact Phone', 'motors'); ?></div>
							<div class="phone"><?php echo esc_attr($user_fields['phone']); ?></div>
						</div>
					<?php endif; ?>

					<div class="stm-dealer-mail">
						<i class="fa fa-envelope-o"></i>
						<div class="mail-label heading-font"><?php esc_html_e('Seller Email', 'motors'); ?></div>
						<div class="mail"><a href="mailto:<?php echo esc_attr($user->data->user_email); ?>">
								<?php echo esc_attr($user->data->user_email); ?>
						</a></div>
					</div>


					<?php if(stm_pricing_enabled()): ?>
						<?php $stm_user_active_subscriptions = stm_user_active_subscriptions(); ?>
						<div class="stm-user-current-plan-info heading-font">
							<?php if(!empty($stm_user_active_subscriptions)): ?>
								<?php
									$day_left = false;
									$date_expires = strtotime($stm_user_active_subscriptions['expires']);
									$date_now = time();
									$date_diff = ($date_expires - $date_now) / (60*60*24);

									if($date_diff < 1) {
										$day_left = true;
									}
								?>


								<div class="sub-title"><?php esc_html_e('Current Plan', 'motors'); ?></div>
								<div class="stm-plan-name"><?php echo stm_do_lmth($stm_user_active_subscriptions['plan_name']); ?></div>
								<div class="sub-title"><?php esc_html_e('Subscription renewal', 'motors'); ?></div>
								<?php if($day_left): ?>
									<div class="days-left stm-start-countdown"></div>

									<script type="text/javascript">
										jQuery(document).ready(function(){
											var $ = jQuery;
											$(".stm-start-countdown")
												.countdown("<?php echo stm_do_lmth($stm_user_active_subscriptions['expires']); ?>", function (event) {
													$(this).text(
														event.strftime('%H:%M:%S')
													);
												});
										})
									</script>

								<?php else: ?>
									<div class="days-left"><?php echo date('m.d.Y', $date_expires); ?></div>
								<?php endif; ?>
								<?php $stm_pricing_link = stm_pricing_link();
								if(!empty($stm_pricing_link)): ?>
									<div class="stm-plan-renew">
										<a href="<?php echo esc_url($stm_pricing_link); ?>" class="button stm-dp-in"><?php esc_html_e('Get new plan', 'motors'); ?></a>
									</div>
								<?php endif; ?>
							<?php else: ?>
								<div class="sub-title"><?php esc_html_e('Current Plan', 'motors'); ?></div>
								<div class="stm-plan-name stm-free-plan"><?php esc_html_e('Free', 'motors'); ?></div>
								<?php $stm_pricing_link = stm_pricing_link();
								if(!empty($stm_pricing_link)): ?>
									<div class="stm-plan-renew">
										<a href="<?php echo esc_url($stm_pricing_link); ?>" class="button stm-dp-in"><?php esc_html_e('Upgrade plan', 'motors'); ?></a>
									</div>
								<?php endif; ?>
							<?php endif; ?>
						</div>
					<?php else: ?>
						<div class="show-my-profile">
							<a href="<?php echo esc_url(stm_get_author_link('myself-view')); ?>" target="_blank"><i class="fa fa-external-link"></i><?php esc_html_e('Show my Public Profile', 'motors'); ?></a>
						</div>
					<?php endif; ?>

				</div>
			</div>

			<div class="col-md-9 col-sm-12">

				<?php get_template_part( 'partials/user/private/main' ) ?>

			</div>

		</div>
	</div>
</div>