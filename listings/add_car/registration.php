<div class="stm-user-registration-unit">
	<div class="clearfix stm_register_title">
		<h3><?php esc_html_e('Sign Up', 'motors'); ?></h3>
		<div class="stm_login_me"><?php esc_html_e('Already Registered? Members','motors'); ?>
			<a href="#"><?php esc_html_e('Login Here','motors'); ?></a>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3 col-sm-3 col-md-push-9 col-sm-push-9 col-xs-push-0">
			<?php if (stm_is_listing() && defined('WORDPRESS_SOCIAL_LOGIN_ABS_PATH')): ?>
				<div class="stm-social-login-wrap">
					<?php do_action( 'wordpress_social_login' ); ?>
				</div>
			<?php endif; ?>
			<div class="heading-font stm-title"><?php echo esc_attr($stm_title_user); ?></div>
			<div class="stm-text"><?php echo esc_attr($stm_text_user); ?></div>
		</div>

		<div class="col-md-9 col-sm-9 col-md-pull-3 col-sm-pull-3 col-xs-pull-0">
			<div class="stm-login-register-form">
				<div class="stm-register-form">
					<form method="post">
						<input type="hidden" name="redirect" value="disable">

						<div class="row form-group">
							<div class="col-md-6">
								<h4><?php esc_html_e('First Name', 'motors'); ?></h4>
								<input class="user_validated_field" type="text" name="stm_user_first_name" placeholder="<?php esc_attr_e('Enter First name', 'motors') ?>"/>
							</div>
							<div class="col-md-6">
								<h4><?php esc_html_e('Last Name', 'motors'); ?></h4>
								<input class="user_validated_field" type="text" name="stm_user_last_name" placeholder="<?php esc_attr_e('Enter Last name', 'motors') ?>"/>
							</div>
						</div>

						<div class="row form-group">
							<div class="col-md-6">
								<h4><?php esc_html_e('Phone number', 'motors'); ?></h4>
								<input class="user_validated_field" type="tel" name="stm_user_phone" placeholder="<?php esc_attr_e('Enter Phone', 'motors') ?>"/>
							</div>
							<div class="col-md-6">
								<h4><?php esc_html_e('Email *', 'motors'); ?></h4>
								<input class="user_validated_field" type="email" name="stm_user_mail" placeholder="<?php esc_attr_e('Enter E-mail', 'motors') ?>"/>
							</div>
						</div>

						<div class="row form-group">
							<div class="col-md-6">
								<h4><?php esc_html_e('Login *', 'motors'); ?></h4>
								<input class="user_validated_field" type="text" name="stm_nickname" placeholder="<?php esc_attr_e('Enter Login', 'motors') ?>"/>
							</div>
							<div class="col-md-6">
								<h4><?php esc_html_e('Password *', 'motors'); ?></h4>
								<div class="stm-show-password">
									<i class="fa fa-eye-slash"></i>
									<input class="user_validated_field" type="password" name="stm_user_password"  placeholder="<?php esc_attr_e('Enter Password', 'motors') ?>"/>
								</div>
							</div>
						</div>

						<div class="form-group form-checker">
							<label>
								<input type="checkbox" name="stm_accept_terms" />
								<span>
								<?php esc_html_e('I accept the terms of the', 'motors'); ?>
									<?php if(!empty($link) and !empty($link['url'])): ?>
										<a href="<?php echo esc_url($link['url']); ?>"><?php esc_html_e($link['title'], 'motors') ?></a>
									<?php endif; ?>
							</span>
							</label>
						</div>

						<div class="form-group form-group-submit">
							<?php
							$has_captcha = '';
							$recaptcha_enabled = get_theme_mod('enable_recaptcha',0);
							$recaptcha_public_key = get_theme_mod('recaptcha_public_key');
							$recaptcha_secret_key = get_theme_mod('recaptcha_secret_key');
							if (!empty($recaptcha_enabled) and $recaptcha_enabled and !empty($recaptcha_public_key) and !empty($recaptcha_secret_key)): ?>
								<script type="text/javascript" src="https://www.google.com/recaptcha/api.js"></script>
								<div class="g-recaptcha" data-sitekey="<?php echo esc_attr($recaptcha_public_key); ?>" data-size="normal"></div>
								<?php $has_captcha = 'cptch_nbld'; ?>
							<?php endif; ?>
							<input class="<?php echo esc_attr($has_captcha); ?>" type="submit" value="<?php esc_html_e('Sign up now!', 'motors'); ?>" disabled/>
							<span class="stm-listing-loader"><i class="stm-icon-load1"></i></span>
						</div>

						<div class="stm-validation-message"></div>

					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function () {
		var $ = jQuery;
		$('.stm-show-password .fa').mousedown(function () {
			$(this).closest('.stm-show-password').find('input').attr('type', 'text');
			$(this).addClass('fa-eye');
			$(this).removeClass('fa-eye-slash');
		});

		$(document).mouseup(function () {
			$('.stm-show-password').find('input').attr('type', 'password');
			$('.stm-show-password .fa').addClass('fa-eye-slash');
			$('.stm-show-password .fa').removeClass('fa-eye');
		});

        $("body").on('touchstart', '.stm-show-password .fa', function () {
            $(this).closest('.stm-show-password').find('input').attr('type', 'text');
            $(this).addClass('fa-eye');
            $(this).removeClass('fa-eye-slash');
        });
	});
</script>
