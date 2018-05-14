<?php
/**
 * Access denied message when attempting to browse resumes.
 *
 * This template can be overridden by copying it to yourtheme/wp-job-manager-resumes/access-denied-browse-resumes.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     WP Job Manager - Resume Manager
 * @category    Template
 * @version     1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<p class="job-manager-error">You need to <a href="/my-account/">log in</a> as employer to access this page or if you still don't have an employer account, please <a href="/sign-up/?type=employer">sign up here</a> to get started!</p>
