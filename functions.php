<?php 
add_action( 'wp_enqueue_scripts', 'workscout_enqueue_styles' );
function workscout_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css',array('workscout-base','workscout-responsive','workscout-font-awesome') );

}

add_action( 'wp_enqueue_scripts', 'jobph_enqueue_styles', 50 );

function jobph_enqueue_styles(){
	wp_enqueue_style( 'custom-style', get_stylesheet_directory_uri(). '/css/custom-style.css', array(), '2.2' );
}

function remove_parent_theme_features() {
   	
}
add_action( 'after_setup_theme', 'remove_parent_theme_features', 10 );


function workscout_login_form_fields() {

	ob_start(); ?>
	<div class="entry-header">
		<h3 class="headline margin-bottom-20"><?php esc_html_e('Login','workscout'); ?></h3>
	</div>
	<?php  $loginpage = Kirki::get_option( 'workscout', 'pp_login_workscout_page' );  ?>

	<?php
	// show any error messages after form submission
	workscout_show_error_messages(); ?>

	<form id="workscout_login_form test"  class="workscout_form" action="" method="post">
		<p class="status"></p>
		<fieldset>
			<p>
				<label for="workscout_user_Login"><?php _e('Username','workscout'); ?>
					<i class="ln ln-icon-Male"></i><input name="workscout_user_login" id="workscout_user_login" class="required" type="text"/>
				</label>
			</p>
			<p>
				<label for="workscout_user_pass"><?php _e('Password','workscout'); ?>
					<i class="ln ln-icon-Lock-2"></i><input name="workscout_user_pass" id="workscout_user_pass" class="required" type="password"/>
				</label>
			</p>
			<p>
				<input type="hidden" id="workscout_login_nonce" name="workscout_login_nonce" value="<?php echo wp_create_nonce('workscout-login-nonce'); ?>"/>
				<input type="hidden" name="workscout_login_check" value="1"/>
				<?php  wp_nonce_field( 'ajax-login-nonce', 'security' );  ?>
				<input id="workscout_login_submit" type="submit" value="<?php esc_attr_e('Login','workscout'); ?>"/>
			</p>
			<p><?php esc_html_e('Don\'t have an account?','workscout'); ?> <a href="<?php echo get_permalink($loginpage); ?>?action=register"><?php esc_html_e('Sign up now','workscout'); ?></a>!</p>
			<p><a href="<?php echo wp_lostpassword_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e('Forgot Password?','workscout'); ?>"><?php esc_html_e('Forgot Password?','workscout'); ?></a></p>

		</fieldset>
	</form>
	<?php
	return ob_get_clean();
}

/**
 * Output job_application_meta
 * @param  object $application
 */
function job_application_meta( $application ) {
	if ( 'job_application' === $application->post_type ) {
		$meta    = get_post_custom( $application->ID );
		$hasmeta = false;
		if ( $meta ) {
			foreach ( $meta as $key => $value ) {
				if ( strpos( $key, '_' ) === 0 ) {
					continue;
				}
				if ( ! $hasmeta ) {
					echo '<dl class="job-application-meta test">';
				}
				$hasmeta = true;
				echo '<dt>' . esc_html( $key ) . '</dt>';
				echo '<dd>' . make_clickable( esc_html( strip_tags( $value[0] ) ) ) . '</dd>';
			}
			if ( $hasmeta ) {
				echo '</dl>';
			}
		}
	}
}

/**
 * Output get_job_application_phone
 * @param  object $application_id
 * @return string
 */
function get_job_application_phone( $application_id ) {
	return get_post_meta( $application_id, '_candidate_phone', true );
}
/**
 * Output add_phone_application_form_fields
 * @param  int $resume_id
 * @return array
 */
function add_phone_application_form_fields( $resume_id ) {
	$phone_number = get_job_application_phone( $resume_id );

	return array(
		'label'       => __( 'Phone number', 'wp-job-manager-applications' ),
        'type'        => 'text',
        'required'    => true,
        'value'       => $phone_number,
        'placeholder' => 'Your contact number',
        'priority'    => 2,
        'rules'       => array( 'phone_number' )
	);
}

function add_phone_field($option){
    $phone = array(
	    'candidate_phone' => array(
		    'label'       => __( 'Phone number', 'wp-job-manager-applications' ),
		    'type'        => 'text',
		    'required'    => true,
		    'value'       => '',
		    'placeholder' => 'Your contact number',
		    'priority'    => 2,
		    'rules'       => array( 'phone_number' )
	    ),
    );
    $option = array_merge( $phone, $option );
    return $option;
}
add_filter( 'job_application_form_fields', 'add_phone_field', 50 );

add_filter( 'submit_job_form_login_required_message', 'custom_text_message_job_form', 20 );

function custom_text_message_job_form(){

	$html_output = '<a href="/sign-up/?type=employer">Sign up</a> as Employer</a> to post new job or <a href="/my-account/">Sign in</a> to get started!';
    return $html_output;
}

add_action( 'init', 'custom_remove_workscout_hooks', 20 );

function custom_remove_workscout_hooks(){
    //remove register_form line 822 @workscout/inc/wp-job-manager.php

	remove_action( 'register_form', 'workscout_register_form' );

}

/* modify register with role output */
add_action( 'register_form', 'custom_workscout_register_form' );
function custom_workscout_register_form() {
    global $account_type;

	$role_status  = Kirki::get_option( 'workscout','pp_singup_role_status', false);
	$role_revert  = Kirki::get_option( 'workscout','pp_singup_role_revert', false);
	$register_type = ( isset($_GET['type']) ) ? $_GET['type'] : $account_type;

	if(!$role_status) {
		global $wp_roles;
		echo '<label for="user_email">'.esc_html__('Account type','workscout').'</label>';
		echo '<select name="role" class="input chosen-select">';
		switch($register_type){
            case 'applicant':
	            echo '<option value="candidate">'.esc_html__("Applicant","workscout").'</option>';
                break;
            case 'employer':
	            echo '<option value="employer">'.esc_html__("Employer","workscout").'</option>';
                break;
            default:
	            if($role_revert){
		            echo '<option value="candidate">'.esc_html__("Applicant","workscout").'</option>';
	            }
	            echo '<option value="employer">'.esc_html__("Employer","workscout").'</option>';
	            if(!$role_revert){
		            echo '<option value="candidate">'.esc_html__("Applicant","workscout").'</option>';
	            }
                break;
        }

		echo '</select>';
	}
}

/**
 * workscout old registration form inline
 * shortcode: add_shortcode('workscout_register_form', 'workscout_registration_form');
 * file: workscout/inc/registration
 */
function workscout_registration_form_fields() {

	ob_start();
	$register_type = $_GET['type'];
    $register_heading = ( $register_type == 'employer' ) ? 'Employer\'s Registration' : 'Register';
	?>
    <div class="entry-header">
        <h3 class="headline margin-bottom-20"><?php esc_html_e( $register_heading,'workscout'); ?></h3>
    </div>

	<?php
	// show any error messages after form submission
	workscout_show_error_messages(); ?>

    <form id="workscout_registration_form" class="workscout_form <?php echo $register_type ?>-signup" action="" method="POST">
        <p class="status"></p>
        <fieldset>
            <p>
                <label for="workscout_user_login"><?php _e('Username','workscout'); ?>
                    <i class="ln ln-icon-Male"></i><input name="workscout_user_login" id="workscout_user_login" class="required" type="text"/>
                </label>
            </p>
            <p>
                <label for="workscout_user_email"><?php _e('Email','workscout'); ?>
                    <i class="ln ln-icon-Mail"></i><input name="workscout_user_email" id="workscout_user_email" class="required" type="email"/>
                </label>
            </p>
			<?php
			$role_status  = Kirki::get_option( 'workscout','pp_singup_role_status', false);
			$role_revert  = Kirki::get_option( 'workscout','pp_singup_role_revert', false);
			if(!$role_status) {?>
                <p>
					<?php
					echo '<label for="workscout_user_role">'.esc_html__('I\'m looking..','workscout').'</label>';
					echo '<select name="workscout_user_role" id="workscout_user_role" class="input chosen-select">';
					if( $role_revert && $register_type != 'employer' ) {
						echo '<option value="candidate">'.esc_html__(".. for a job","workscout").'</option>';
					}
					echo '<option value="employer">'.esc_html__("..to hire","workscout").'</option>';
					if( !$role_revert && $register_type != 'employer' ) {
						echo '<option value="candidate">'.esc_html__(".. for a job","workscout").'</option>';
					}
					echo '</select>';
					?>
                </p>
			<?php } ?>
			<?php if( function_exists( 'gglcptch_display' ) ) { echo gglcptch_display(); } ; ?>
            <p style="display:none">
                <label for="confirm_email"><?php esc_html_e('Please leave this field empty','workscout'); ?></label>
                <input type="text" name="confirm_email" id="confirm_email" class="input" value="">
            </p>
            <p>
                <input type="hidden" name="workscout_register_nonce" value="<?php echo wp_create_nonce('workscout-register-nonce'); ?>"/>
                <input type="hidden" name="workscout_register_check" value="1"/>
				<?php  wp_nonce_field( 'ajax-register-nonce', 'security' );  ?>
                <input type="submit" value="<?php _e('Register Your Account','workscout'); ?>"/>
            </p>
        </fieldset>
    </form>
	<?php
	return ob_get_clean();
}

//custom registration shortcode
/**
 * workscout registration
 * function: workscout_registration_form
 * shortcode: add_shortcode('workscout_registration_form', 'workscout_registration_form');
 * file: workscout/inc/registration
 * line: 5
 */
function custom_workscout_registration_form( $atts ) {

	// only show the registration form to non-logged-in members
	if(!is_user_logged_in()) {

		$registration_enabled = get_option('users_can_register');

		//default attributes
		$referer = ( ! empty( $atts['redirect'] ) ) ? $atts['redirect'] : $_SERVER['REQUEST_URI'];
        $type = ( ! empty( $atts['type'] ) ) ? $atts['type'] : '';
		$url_param = array(
		        'job_id' => $atts['job_id'],
		        'type'    => $type,
        );
		$referer = $referer . '?' . http_build_query($url_param, '&amp;');

		$default_atts = shortcode_atts( array(
			'referer' => $referer,
		), $atts );

		//esc_attr( $default_atts['referer'] )

		// only show the registration form if allowed
		if($registration_enabled) { ?>
            <form method="post" class="register workscout_form custom-register">

				<?php do_action( 'woocommerce_register_form_start' ); ?>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

                    <p class="form-row form-row-wide">
                        <label for="reg_username"><?php _e( 'Username', 'workscout' ); ?> <span class="required">*</span>
                            <i class="ln ln-icon-Male"></i>
                            <input type="text" class="input-text" name="username" id="reg_username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
                        </label>
                    </p>
				<?php endif; ?>

                <p class="form-row form-row-wide">
                    <label for="reg_email"><?php _e( 'Email address', 'workscout' ); ?> <span class="required">*</span>
                        <i class="ln ln-icon-Mail"></i><input type="email" placeholder="youremail@domain.com" class="input-text" name="email" id="reg_email" value="<?php if ( ! empty( $_POST['email'] ) ) echo esc_attr( $_POST['email'] ); ?>" />
                    </label>
                </p>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

                    <p class="form-row form-row-wide">
                        <label for="reg_password"><?php _e( 'Password', 'workscout' ); ?> <span class="required">*</span>
                            <i class="ln ln-icon-Lock-2"></i><input type="password" placeholder="Create a strong password" class="input-text" name="password" id="reg_password" />
                        </label>
                    </p>

				<?php endif; ?>

                <!-- Spam Trap -->
                <div style="<?php echo ( ( is_rtl() ) ? 'right' : 'left' ); ?>: -999em; position: absolute;"><label for="trap"><?php _e( 'Anti-spam', 'workscout' ); ?></label><input type="text" name="email_2" id="trap" tabindex="-1" autocomplete="off" /></div>

				<?php do_action( 'woocommerce_register_form' ); ?>
				<?php do_action( 'register_form' ); ?>
                <p></p>
                <p class="register-btn form-row">
					<?php wp_nonce_field( 'woocommerce-register', '_wpnonce', false ); ?>
                    <?php echo '<input type="hidden" name="_wp_http_referer" value="'. esc_url( $default_atts['referer'] ) . '" />'; ?>
                    <input type="submit" class="button" name="register" value="<?php esc_attr_e( 'Register', 'workscout' ); ?>" />
                </p>
                <p class="accept-terms">By clicking "Register" button, you agree to our <a href="/terms-conditions/">Terms of Service</a> and <a href="/privacy-policy/">Privacy Policy</a>. We'll send you an email with job alerts and account related only!</p>
				<?php do_action( 'woocommerce_register_form_end' ); ?>

            </form>
		<?php } else {
			_e('User registration is not enabled','workscout');
		}

	}
}
add_shortcode('custom_workscout_register_form', 'custom_workscout_registration_form');