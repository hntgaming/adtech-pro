/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */

( function() {

	const navigationIds = ['site-navigation', 'top-navigation'];

	navigationIds.forEach(initNavigation);

	function initNavigation(navigationId) {

		const siteNavigation = document.getElementById(navigationId);

		// Return early if the navigation doesn't exist.
		if ( ! siteNavigation ) {
			return;
		}
	
		const menu = siteNavigation.getElementsByTagName( 'ul' )[ 0 ];
	
		// If menu is empty return early.
		if ( 'undefined' === typeof menu ) {
			return;
		}
	
		if ( ! menu.classList.contains( 'nav-menu' ) ) {
			menu.classList.add( 'nav-menu' );
		}
	
		// Get all the link elements within the menu.
		const links = menu.getElementsByTagName( 'a' );
	
		// Toggle focus each time a menu link is focused or blurred.
		for ( const link of links ) {
			link.addEventListener( 'focus', toggleFocus, true );
			link.addEventListener( 'blur', toggleFocus, true );
		}
	
		/**
		 * Sets or removes .focus class on an element.
		 */
		function toggleFocus(event) {
			if ( event.type === 'focus' || event.type === 'blur' ) {
				let self = this;
				// Move up through the ancestors of the current link until we hit .nav-menu.
				while ( self && self.classList && ! self.classList.contains( 'nav-menu' ) ) {
					// On li elements toggle the class .focus.
					if ( 'li' === self.tagName.toLowerCase() ) {
						self.classList.toggle( 'focus' );
					}
					self = self.parentNode;
				}
			}
		}
	}
}() );

jQuery(document).ready(function(){

	// Main Navigation Mobile Toggle
	var mobMainNav = jQuery('.responsive-mainnav'),
		mainNavUl = mobMainNav.find('ul#primary-menu'),
		mNavWrapper = jQuery('<div class="hm-nwrap"></div>');

	mNavWrapper.appendTo(mobMainNav);
	jQuery('#site-navigation ul:first-child').clone().appendTo(mNavWrapper);

	var mainNavButton = jQuery('#main-nav-button');
	mainNavButton.attr('aria-expanded', 'false');

	mainNavButton.on( "click", function(event){
		event.preventDefault();
		var isOpen = mobMainNav.is(':visible');
		jQuery(this).attr('aria-expanded', isOpen ? 'false' : 'true');
		mobMainNav.slideToggle(200);
		mainNavUl.show();
	});

	// Top Navigation Mobile Toggle
	var mobTopNav = jQuery('.responsive-topnav'),
		topNavUl = mobTopNav.find('ul#top-menu'),
		tNavWrapper = jQuery('<div class="hm-nwrap"></div>');

	tNavWrapper.appendTo(mobTopNav);
	jQuery('#top-navigation ul:first-child').clone().appendTo(tNavWrapper);

	var topNavButton = jQuery('#top-nav-button');
	topNavButton.attr('aria-expanded', 'false');

	topNavButton.on( "click", function(event){
		event.preventDefault();
		var isOpen = mobTopNav.is(':visible');
		jQuery(this).attr('aria-expanded', isOpen ? 'false' : 'true');
		mobTopNav.slideToggle(200);
		topNavUl.show();
	});
	
});
