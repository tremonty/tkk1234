<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$css_class = (!empty($css)) ? apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ' ')) : '';

if(!empty($link)) {
	$link = vc_build_link( $link );
}
?>

<div class="stm-login-register-form <?php echo esc_attr($css_class); ?>">
	<?php if(!empty($_GET['user_id']) and !empty($_GET['hash_check'])): ?>
		<?php get_template_part('partials/user/private/password', 'recovery'); ?>
	<?php endif; ?>

    <div class="row">

		<div class="col-md-4">
			<h3><?php esc_html_e('Sign In', 'motors'); ?></h3>
            <?php if(get_theme_mod("site_demo_mode", false)): ?>
            <div style="background: #FFF; padding: 15px; margin-bottom: 15px;">
                <span style="width: 100%;">You can use these credentials for demo testing:</span>

                <div style="display: flex; flex-direction: row; margin-top: 10px;">
                    <span style="width: 40%;">
                        <b>Dealer:</b><br />
                        dealer<br />
                        dealer
                    </span>

                    <span style="width: 40%;">
                        <b>User:</b><br />
                        demo<br />
                        demo
                    </span>
                </div>
            </div>
            <?php endif; ?>
			<div class="stm-login-form">
				<form method="post">
					<?php do_action( 'stm_before_signin_form' ) ?>
                    <div class="form-group">
						<h4><?php esc_html_e('Login or E-mail', 'motors'); ?></h4>
						<input type="text" name="stm_user_login" placeholder="<?php esc_attr_e('Enter login or E-mail', 'motors') ?>"/>
					</div>
					<div class="form-group">
						<h4><?php esc_html_e('Password', 'motors'); ?></h4>
						<input type="password" name="stm_user_password"  placeholder="<?php esc_attr_e('Enter password', 'motors') ?>"/>
					</div>
					<div class="form-group form-checker">
						<label>
							<input type="checkbox" name="stm_remember_me" />
							<span><?php esc_html_e('Remember me', 'motors'); ?></span>
						</label>
						<div class="stm-forgot-password">
							<a href="#">
								<?php esc_html_e('Forgot Password', 'motors'); ?>
							</a>
						</div>
					</div>
                    <?php if(class_exists('PMXI_Plugin')) : ?><input type="hidden" name="current_lang" value="<?php echo ICL_LANGUAGE_CODE; ?>"/><?php endif; ?>
					<input type="submit" value="<?php esc_html_e('Login', 'motors'); ?>"/>
					<span class="stm-listing-loader"><i class="stm-icon-load1"></i></span>
					<div class="stm-validation-message"></div>
                    <?php do_action( 'stm_after_signin_form' ) ?>
				</form>
				<form method="post" class="stm_forgot_password_send">
					<div class="form-group">
						<h4><?php esc_html_e('Login or E-mail', 'motors'); ?></h4>
						<input type="hidden" name="stm_link_send_to" value="<?php echo stm_do_lmth(apply_filters('stm_get_global_server_val', "HTTP_HOST") . apply_filters('stm_get_global_server_val', "REQUEST_URI")); ?>" readonly/>
						<input type="text" name="stm_user_login" placeholder="<?php esc_attr_e('Enter login or E-mail', 'motors') ?>"/>
						<input type="submit" value="<?php esc_attr_e('Send password', 'motors'); ?>"/>
						<span class="stm-listing-loader"><i class="stm-icon-load1"></i></span>
						<div class="stm-validation-message"></div>
					</div>
				</form>
			</div>
			<?php if(is_listing() && defined('WORDPRESS_SOCIAL_LOGIN_ABS_PATH')): ?>
				<div class="stm-social-login-wrap">
					<?php do_action( 'wordpress_social_login' ); ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="col-md-8">
			<h3><?php esc_html_e('Sign Up', 'motors'); ?></h3>
			<div class="stm-register-form">
				<form method="post">
					<?php do_action( 'stm_before_signup_form' ) ?>
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
									<a href="<?php echo esc_url($link['url']); ?>" target="_blank"><?php esc_html_e($link['title'], 'motors') ?></a>
								<?php endif; ?>
							</span>
						</label>
					</div>

					<div class="form-group form-group-submit clearfix">
						<?php
						$has_captcha = '';
						$recaptcha_enabled = get_theme_mod('enable_recaptcha',0);
						$recaptcha_public_key = get_theme_mod('recaptcha_public_key');
						$recaptcha_secret_key = get_theme_mod('recaptcha_secret_key');
						if(!empty($recaptcha_enabled) and $recaptcha_enabled and !empty($recaptcha_public_key) and !empty($recaptcha_secret_key)):
							?>
							<script src="https://www.google.com/recaptcha/api.js"></script>
							<div class="g-recaptcha" data-sitekey="<?php echo esc_attr($recaptcha_public_key); ?>" data-size="normal"></div>
							<?php $has_captcha = 'cptch_nbld'; ?>
						<?php endif; ?>
						<input class="<?php echo esc_attr($has_captcha); ?>" type="submit" value="<?php esc_html_e('Sign up now!', 'motors'); ?>" disabled/>
						<span class="stm-listing-loader"><i class="stm-icon-load1"></i></span>
					</div>

					<div class="stm-validation-message"></div>

					<?php do_action( 'stm_after_signup_form' ) ?>

				</form>
			</div>
		</div>

	</div>
</div>

<script>
	jQuery(document).ready(function(){
		var $= jQuery;

		$('.stm-show-password .fa').mousedown(function(){
			$(this).closest('.stm-show-password').find('input').attr('type', 'text');
			$(this).addClass('fa-eye');
			$(this).removeClass('fa-eye-slash');
		});

		$(document).mouseup(function(){
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