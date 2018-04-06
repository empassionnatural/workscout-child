<?php 
add_action( 'wp_enqueue_scripts', 'workscout_enqueue_styles' );
function workscout_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css',array('workscout-base','workscout-responsive','workscout-font-awesome') );

}

add_action( 'wp_enqueue_scripts', 'jobph_enqueue_styles', 50 );

function jobph_enqueue_styles(){
	wp_enqueue_style( 'custom-style', get_stylesheet_directory_uri(). '/css/custom-style.css' );
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