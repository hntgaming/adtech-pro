<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package HTG
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php
/**
 * WordPress function to load custom scripts after body.
 *
 * Introduced in WordPress 5.2.0
 *
 * @since HTG 1.2.6
 */
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}
?>

<?php do_action( 'HTG_before_site' ); ?>

<div id="page" class="site HTG-wrapper">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'adtech-pro' ); ?></a>

	<?php do_action( 'HTG_before_header' ); ?>

	<header id="masthead" class="site-header" role="banner">

		<?php if ( true == get_theme_mod( 'display_topbar', 'true' ) ) : ?>

			<?php if ( has_nav_menu( 'menu-2' ) ) : ?>
				<div class="hm-topnavbutton">
					<div class="hm-nwrap">
						<?php 
							// Mobile Menu Button Label: Top Menu
							$HTG_top_menu_btn_text = get_theme_mod( 'HTG_top_menu_btn_text', esc_html__( 'Top Menu', 'adtech-pro') );
						?>
						<a href="#" class="navbutton" id="top-nav-button">
							<?php 
								if( isset( $HTG_top_menu_btn_text ) && !empty( $HTG_top_menu_btn_text ) ) {
									echo '<span class="top-nav-btn-lbl">' .  esc_html( $HTG_top_menu_btn_text ) . '</span>'; 
								}
							?>
						</a>
					</div>	
				</div>
				<div class="responsive-topnav"></div>					
			<?php endif; ?>

			<div class="hm-top-bar">
				<div class="hm-container">
					
					<?php if ( true == get_theme_mod( 'show_topbar_date', 'true' ) ) : ?>
						<div class="hm-date"><?php echo date_i18n( get_option( 'date_format' ) ); ?></div>
					<?php endif; ?>

					<?php if ( has_nav_menu( 'menu-2' ) ) : ?>
						<div id="top-navigation" class="top-navigation">
							<?php wp_nav_menu( array( 'theme_location' => 'menu-2', 'menu_id' => 'top-menu' ) ); ?>					
						</div>		
					<?php endif; ?>

					<?php get_template_part( 'template-parts/menu', 'social' ); ?>

				</div><!-- .hm-container -->
			</div><!-- .hm-top-bar -->

		<?php endif; ?>

		<?php if ( get_theme_mod( 'header_image_position', 'after-site-title' ) == 'before-site-title' ) { HTG_header_image(); } ?>

		<div class="header-main-area <?php HTG_header_bg_image_class(); ?>">
			<div class="hm-container">
			<div class="site-branding">
				<div class="site-branding-content">
					<div class="hm-logo">
						<?php HTG_the_custom_logo(); ?>
					</div><!-- .hm-logo -->

					<div class="hm-site-title">
						<?php
						if ( is_front_page() || is_home() ) : ?>
							<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
						<?php else : ?>
							<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
						<?php
						endif;

						$description = get_bloginfo( 'description', 'display' );
						if ( $description || is_customize_preview() ) : ?>
							<p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
						<?php
						endif; ?>
					</div><!-- .hm-site-title -->
				</div><!-- .site-branding-content -->
			</div><!-- .site-branding -->

			<?php do_action( 'HTG_after_site_logo' ); ?>

			<?php 
				if ( is_active_sidebar( 'sidebar-header' ) ) {
					echo '<div class="hm-header-sidebar">';
					dynamic_sidebar( 'sidebar-header' );
					echo '</div>';
				} 
			?>
			</div><!-- .hm-container -->
		</div><!-- .header-main-area -->

		<?php if ( get_theme_mod( 'header_image_position', 'after-site-title' ) == 'after-site-title' ) { HTG_header_image(); } ?>

		<div class="hm-nav-container">
			<nav id="site-navigation" class="main-navigation" role="navigation">
				<div class="hm-container">
					<?php wp_nav_menu( array( 'theme_location' => 'menu-1', 'menu_id' => 'primary-menu' ) ); ?>
					
					<?php if ( true == get_theme_mod( 'show_nav_search', true ) ) : ?>
						<button class="hm-search-button-icon" aria-label="<?php esc_attr_e( 'Open search', 'adtech-pro' ); ?>"></button>
						<div class="hm-search-box-container">
							<div class="hm-search-box">
								<?php get_search_form(); ?>
							</div><!-- th-search-box -->
						</div><!-- .th-search-box-container -->
					<?php endif; ?>
				</div><!-- .hm-container -->
			</nav><!-- #site-navigation -->
			<div class="hm-nwrap">
				<?php 
					// Mobile Menu Button Label: Main Menu
					$HTG_main_menu_btn_text = get_theme_mod( 'HTG_main_menu_btn_text', esc_html__( 'Main Menu', 'adtech-pro') );
				?>
				<a href="#" class="navbutton" id="main-nav-button">
					<?php 
						if( isset( $HTG_main_menu_btn_text ) && !empty( $HTG_main_menu_btn_text ) ) {
							echo '<span class="main-nav-btn-lbl">' . esc_html( $HTG_main_menu_btn_text ) . '</span>'; 
						}
					?>
				</a>
			</div>
			<div class="responsive-mainnav"></div>
		</div><!-- .hm-nav-container -->

		<?php if ( get_theme_mod( 'header_image_position', 'after-site-title' ) == 'after-main-nav' ) { HTG_header_image(); } ?>

		<?php 
		// Ad Slot removed (not policy-safe: too close to navigation)
		// Use header_top slot instead
		?>

	</header><!-- #masthead -->

	<?php do_action( 'HTG_after_header' ); ?>

	<div id="content" class="site-content">
		<div class="hm-container">
		
		<?php 
		// Display breadcrumbs
		HTG_breadcrumbs();
		?>
		