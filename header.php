<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WorkScout
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
    <style>
        #logo{margin-top:5px}header#main-header.transparent{background:rgba(0,0,0,0.5)}.menu ul li.menu-item-246 a{background:#3bc0ff}.menu ul li.menu-item-246 a:hover{background:rgba(59,192,254,0.85)}.menu ul > li.menu-item-246 > a{color:#fff}.border-line-w{margin:auto;width:100%}.border-left:before{content:'';position:relative;width:46%;height:2px;border-top:1px solid #e0e0e0;float:left;top:13px}.border-right:before{content:'';position:relative;width:47.5%;height:2px;border-top:1px solid #e0e0e0;float:right;top:13px}.border-center{padding:0 5px;color:#afafaf}#titlebar span.res-profile-link a,#titlebar span.res-file-link a,.res-profile-link i,.res-file-link i,.website i,.company-info span a.website{color:#26ae61;font-weight:700}.resume-table strong:before{background:#26b2fc}#titlebar span a:hover,.website:hover{text-decoration:underline}.search-container .chosen-container .chosen-results li.level-0{font-weight:700}.home-search-widget .categories-boxes-container{width:100%;left:0}.home-search-widget .category-small-box{margin:10px 0 0;width:calc(37.8%);width:calc(100% * (1/2) - 45px)}.home-search-widget .category-small-box:nth-child(even){margin-left:10px}.new-layout.job_listings > li a span.job-type{position:relative;font-size:12px;line-height:16px;padding:2px 4px;top:8px;right:0}.job_listing span.full-time{color:#186fc9!important;border:1px solid #186fc9!important;background-color:#f1f7fc!important}.job_listing span.part-time{color:#f1630d!important;border:1px solid #f1630d!important;background-color:#fef6f0!important}.job_listing span.temporary{color:#e12335!important;border:1px solid #e12335!important;background-color:#fdf2f3!important}.listing-icons li:first-child,.ws-meta-company-name{font-weight:700}.ln-icon-Management{font-weight:400}.listing-date{background-color:#f5f5f5}.new-layout.job_listings > li:hover a{border-left-color:rgba(38,174,97,0.7)}.resume-titlebar i,.job-spotlight i,.resume-spotlight i,.map-box a i,.job_listings > li a i,.job-list > li a i,.resumes li a i,.listing-icons li,body .job-spotlight span,.category-small-box h4,#titlebar span a,#titlebar span{color:#5a5a5a}.job_listings .ln-icon-Money-2{display:none}.text-center{text-align:center}.mbs{margin-bottom:8px}.fieldset-allow_facebook .input-checkbox{position:relative;top:10px}.company-h3{border-left:4px solid rgba(38,174,97,0.7);background-color:#fafafa;padding-left:20px}.chosen-select,.job-manager-category-dropdown{height:48px}#search_category{height:60px}#submit-resume-form fieldset,.submit-page fieldset,.submit-page .form{margin-bottom:20px}#submit-resume-form{padding:0 9%}@media (max-width: 991px){.home-search-widget .category-small-box{width:100%}.home-search-widget .category-small-box:nth-child(even){margin-left:0}.new-layout.job_listings > li a{padding:15px}.new-layout .job_listing .listing-title{padding:0}.listing-logo{position:absolute}.listing-title h4{padding-left:55px;margin-top:0}.new-layout.job_listings > li a span.job-type{font-size:11px;padding:2px 0}.type-page ul.listing-icons,.listing-icons{margin-top:28px}}@media (max-width: 736px){.search-container h2{font-size:32px}.new-layout .job_listing .listing-title h4{font-size:16px;min-height:54px}.new-layout.job_listings > li a span.job-type{line-height:16px;max-width:80px;top:0;width:100%;display:inline;font-size:12px;padding:2px 8px}.type-page ul.listing-icons,.listing-icons{margin-top:0}}

    </style>
</head>
<?php $layout = Kirki::get_option( 'workscout','pp_body_style','fullwidth' ); ?>
<body <?php body_class($layout); ?>>
<div id="wrapper">

<header <?php workscout_header_class(); ?> id="main-header">
<div class="container">
	<div class="sixteen columns">
	
		<!-- Logo -->
		<div id="logo">
			 <?php
                
                $logo = Kirki::get_option( 'workscout', 'pp_logo_upload', '' ); 
                $logo_retina = Kirki::get_option( 'workscout', 'pp_retina_logo_upload', '' ); 
                if( is_singular() ) {
                	$header_image = get_post_meta($post->ID, 'pp_job_header_bg', TRUE); 
                	if( !empty($header_image) ) {
                		$transparent_status = get_post_meta($post->ID, 'pp_transparent_header', TRUE); 	

                		if($transparent_status == 'on'){
                			$logo_transparent = Kirki::get_option( 'workscout','pp_transparent_logo_upload');

							$logo =(!empty($logo_transparent)) ? $logo_transparent : $logo ;	
                		}
                	}
                }
                if( is_page_template( 'template-home.php' ) ) {

					if(Kirki::get_option( 'workscout','pp_transparent_header')) {
						$logo_transparent = Kirki::get_option( 'workscout','pp_transparent_logo_upload');
						$logo =(!empty($logo_transparent)) ? $logo_transparent : $logo ;
					}
				}        
				if( is_page_template( 'template-home-resumes.php' ) ) {

					if(Kirki::get_option( 'workscout','pp_resume_home_transparent_header')) {
						$logo_transparent = Kirki::get_option( 'workscout','pp_transparent_logo_upload');
						$logo =(!empty($logo_transparent)) ? $logo_transparent : $logo ;
					}
				}


                if($logo) {
                    if(is_front_page()){ ?>
                        <span style="display: none;"><img src="<?php echo esc_url($logo); ?>" ></span>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home"><img src="<?php echo esc_url($logo); ?>" data-rjs="<?php echo esc_url($logo_retina); ?>" alt="<?php esc_attr(bloginfo('name')); ?>"/></a>
                    <?php } else { ?>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php echo esc_url($logo); ?>" data-rjs="<?php echo esc_url($logo_retina); ?>" alt="<?php esc_attr(bloginfo('name')); ?>"/></a>
                    <?php }
                } else {
                    if(is_front_page()) { ?>
                    <h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                    <?php } else { ?>
                    <h2><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h2>
                    <?php }
                }
                ?>
                <?php if(get_theme_mod('workscout_tagline_switch','hide') == 'show') { ?><div id="blogdesc"><?php bloginfo( 'description' ); ?></div><?php } ?>
		</div>

		<!-- Menu -->
	
		<nav id="navigation" class="menu">

			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'responsive','container' => false ) ); 
			
			$minicart_status = Kirki::get_option( 'workscout', 'pp_minicart_in_header', false );
			if(Kirki::get_option( 'workscout', 'pp_login_form_status', true ) ) { 
				
					$login_system = Kirki::get_option( 'workscout', 'pp_login_form_system' );
//					var_dump($login_system);
					switch ($login_system) {
						case 'custom':
							get_template_part('template-parts/login-custom');
							break;

						case 'woocommerce':
							get_template_part('template-parts/login-woocommerce');
							break;

						case 'um':
							get_template_part('template-parts/login-um');
							break;					

						case 'workscout':
							get_template_part('template-parts/login-workscout');
							break;
						
						default:
							# code...
							break;
					}
			
			
			} 
			
			?>

		</nav>

		<!-- Navigation -->
		<div id="mobile-navigation">
			<a href="#menu" class="menu-trigger"><i class="fa fa-reorder"></i><?php esc_html_e('Menu','workscout'); ?></a>
		</div>

	</div>
</div>
</header>
<div class="clearfix"></div>
<?php if(isset($_GET['success']) && $_GET['success'] == 1 )  { ?>
	 <script type="text/javascript">
        jQuery(document).ready(function ($) {
    	
		    	$.magnificPopup.open({
				  items: {
				    src: '<div id="singup-dialog" class="small-dialog zoom-anim-dialog apply-popup">'+
		                	'<div class="small-dialog-headline"><h2><?php esc_html_e("Success!","workscout"); ?></h2></div>'+
		                	'<div class="small-dialog-content"><p class="margin-reset"><?php esc_html_e("You have successfully registered and logged in. Thank you!","workscout"); ?></p></div>'+
		        		'</div>', // can be a HTML string, jQuery object, or CSS selector
				    type: 'inline'
				  }
				});
	    	});
    </script>
<?php } ?>