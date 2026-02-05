=== H&T AdTech Pro ===

Contributors: H&T GAMING
Requires at least: 5.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 2.5.2
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

H&T AdTech Pro WordPress Theme, Copyright 2025 H&T GAMING
H&T AdTech Pro is distributed under the terms of the GNU GPL

== Description ==

H&T AdTech Pro is an enterprise-grade WordPress theme designed for high-traffic publishers who demand excellence in monetization, performance, and user experience. Built for AdSense, Google Ad Manager, and programmatic advertising with 22 strategic ad slots, real-time analytics dashboard, magazine layouts, interactive engagement tools, and Core Web Vitals optimization (94/100 score). Perfect for publishers like BBC, CNN, and TechCrunch. Features include: policy-compliant ad placements, AJAX post loading, one-click legal pages generator, enhanced author box, reading progress bar, sticky social share, dark mode, and professional analytics. Learn more at https://hntgaming.me

== Installation ==

1. In your admin panel, go to Appearance > Themes and click the Add New button.
2. Click Upload and Choose File, then select the theme's .zip file. Click Install Now.
3. Click Activate to use your new theme right away.
4. Go to Appearance > Customize to customize your theme.

== Frequently Asked Questions ==

= 1. How to create the Magazine Homepage? =

Go to Pages > Add New in the WordPress Dashboard
Give it a name whatever you want. eg : Home.
Then from the page attributes options box select the "Magazine Homepage" for Template.
Then Go to Settings > Reading in the WordPress Dashboard and select the option "a static page" which is under the heading “Front Page Displays”.
Then Select the page that you created from the “Front Page” drop down. eg: Home

= 2. How to add a blog post page when magazine homepage is activated? =

Go to Pages > Add New in the WordPress Dashboard
Give it a name whatever you want. eg : Blog.
Then from the page attributes options box select the Template as Default Template.
Then Go to Settings > Reading in the WordPress Dashboard and select the option “A static page” which is under the heading “Front Page Displays”.
Then Select the page that you created from the “Blog Page” drop down . eg: Blog.

= 3. How to access the enterprise dashboard? =

Go to WordPress Admin → H&T AdTech → Analytics Dashboard
Here you'll find real-time analytics, ad management, and all theme settings.

= 4. Does this theme support any plugins? =

Following are the list of supported plugins.
Contact Form 7
Jetpack
Font Awesome 4 Menus

== Changelog ==

= 2.5.2 =
* Fix: Top navigation dropdown had light background (#ECF0F1) - now uses dark #1a1a1a
* Fix: Tab widget had light background and dark text - now properly styled for dark theme
* Fix: Invisible borders using #1a1f36 changed to visible #00d4aa accent (widget titles, page titles, blockquotes)
* Fix: Light borders (#ededed, #dddddd, #c0c0c0) changed to dark theme compatible colors
* Fix: WooCommerce prices, labels, and links now visible on dark background
* Fix: WooCommerce review avatars, select2 dropdowns, disabled buttons styled for dark theme
* Fix: Thumb swiper background changed from light #ddd to dark #1a1a1a
* Fix: Page links visited color changed from invisible #000000 to visible #e0e0e0
* Improved: Comprehensive CSS audit ensuring uniform styling site-wide
* Improved: Consistent color palette throughout (103 accent uses, 29 background uses)

= 2.5.1 =
* Fix: Post navigation hover styles - Changed from span:hover to a:hover for proper anchor behavior
* Fix: Related posts hover now consistent with rest of site using #00d4aa teal accent
* Fix: Multiple dark theme color fixes - Updated 15+ hover colors from invisible #1a1f36 to visible #00d4aa
* Fix: Comment section dark theme - Updated borders, author colors, and required field indicator
* Fix: Footer widget links, site info links, WooCommerce widget hover colors
* Fix: Widget titles now use white text with teal accent border for dark theme visibility
* Improved: Added smooth 0.2s transitions to all hover effects for polished UX

= 2.5.0 =
* Major: WordPress.org compliance - Fixed text domain inconsistency (657 occurrences updated from 'HTG' to 'adtech-pro')
* Fix: Footer ad slot mismatch - Changed 'footer_top' to 'before_footer' to match Ad Manager settings
* Fix: Dark mode CSS class mismatch - Updated all selectors from 'body.HTG-dark-mode' to 'body.htg-dark-theme'
* Removed: Dead code cleanup - Deleted unused js/dark-mode.js (theme is dark-only, no toggle needed)
* Removed: Legacy FontAwesome v4 font files (~1.08 MB saved) - fontawesome-webfont.*, FontAwesome.otf
* Improved: Dark mode CSS now properly applies dark theme styles
* Improved: Cleaner codebase with removed dead code and unused assets
* Improved: Translation-ready with consistent text domain throughout

= 2.4.8 =
* Fix: Removed purple gradient from table headers
* New: Clean dark header style (#2a2a2a) with teal accent border
* New: Responsive table header sizing for mobile and desktop

= 2.4.7 =
* Fix: Reverted v2.4.6 changes that broke table layout
* Fix: Table header row now properly styled with purple gradient background
* Fix: Header cells (th) now have white text and proper padding

= 2.4.6 =
* Reverted - this version had issues with table layout

= 2.4.5 =
* Fix: Force update check now properly clears all WordPress theme caches
* Fix: Update check redirects to update-core.php to show available updates
* New: Shows version comparison after checking (current vs available)
* Improved: Uses wp_clean_themes_cache() for reliable cache clearing

= 2.4.4 =
* Major: Complete rewrite of responsive table CSS for better mobile experience
* New: "Swipe to see full table" hint banner on mobile
* New: Sticky first column with shadow effect for reference while scrolling
* New: Gradient fade overlay on right side indicating more content
* New: Responsive breakpoints for tablet (992px), mobile (768px), small (480px), extra-small (360px)
* Fix: Table text wrapping and cell sizing for different screen sizes
* Fix: Tables now properly scrollable on all mobile devices
* Improved: Custom scrollbar styling for tables

= 2.4.3 =
* Fix: Improved update detection - reduced cache from 12 hours to 1 hour
* New: "Check for Updates" link in theme actions for instant refresh
* New: Force update check via URL parameter (?htg_force_update_check=1)
* New: Auto-clear WordPress update transient when checking for updates
* Improved: Update notification now appears faster after new releases

= 2.4.2 =
* Fix: Tables now properly responsive on mobile devices - horizontal scroll with sticky first column
* New: Scroll hint indicator on mobile for tables ("← Scroll →")
* New: Styled scrollbar for table containers
* New: Striped table rows and hover effects for better readability
* New: Gradient fade indicator showing more content is available
* Improved: Table cells have proper padding and font sizing on all screen sizes

= 2.4.1 =
* New: Logo auto-resize feature - automatically resizes logos to optimal dimensions (2x for retina)
* New: Logo background removal - optional PNG conversion with solid color background removal
* New: Logo Settings tab in Appearance settings with configurable max width/height
* New: Responsive logo sizing for mobile devices
* Improved: Logo CSS filtering for better display consistency

= 1.4.3 =
* New: Added an option to improve featured image quality by using larger image sizes (Customizer > Blog Options).
* New: Added a filter to modify the related posts query arguments.

= 1.4.2 =
* Added HTG: Popular Posts widget to display popular posts based on the comment count.
* Fixed a CSS issue in the customizer.

= 1.4.1 =
* Removed the loading icon from the customizer.
* Fixed an escaping issue in the aria-label of the search button.

= 1.4.0 =
* Added support for Yoast Breadcrumbs, Breadcrumb NavXT and Rank Math Breadcrumbs.
* Added new hooks - HTG_before_entry_header, HTG_before_main_content.
* Fixed a few accessibility issues.

= 1.3.9 =
* Keyboard navigation support improved.
* Added styles to Post Page navigation.

= 1.3.8 =
* Updated Font Awesome font to 6.5.1 from 4.7
* Updated twitter icon
* Added version to style.css url.
* Changed slider image displaying method. ( Previousely displayed as background-image )
* Added loading="lazy" for offscreen slider images to improve speed.
* Updated swiper library.

= 1.3.7 =
* Fix: Deprecated PHP issues. 
* New: Added aria-label, title and alt attributes to slides on the Featured Slider.

= 1.3.6 =
* New: Added an option to enter the number of days to display popular posts on HTG: Popular, Recent, Tags, Comments widget.

= 1.3.5 =
* Changed slider js library to Swiper from flexslider jquery library.
* Added font-display for the default "Lato" font.
* Added options to change mobile menu button labels. ( Customize > Header Options > Header Settings )

= 1.3.4 =
* Fix: Flexslider did not load properly when the magazine page template has not assigned as the front page.

= 1.3.3 =
* Now Google Fonts are loaded from locally to comply with GDPR law.

= 1.3.2 =
* Now flexslider and magnific popup jquery libraries are loaded only where needed. 
* Updated kirki framework.
* Added alt attribute to slider thumbnails.
* Added screen-reader-text for the archive read more button to improve SEO.
* Tested with WordPress 5.9
* Fixed a theme styling issue with the social icon block.

= 1.3.1 =
* Changed the width of the block widget editor area to have a better idea about Post Widgets.
* Fixed a translation issue.

= 1.3.0 =
* Added theme support for responsive embeds.
* Added a new block style for the Gutenberg heading block to be used as widget titles.
* Added custom styles for search block.

= 1.2.9 =
* Fixed a php issue on the Featured Slider. ( Trying to access array offset on value of type bool )
* Fixed few jQuery deprecated issues.

= 1.2.8 =
* HTG is successfully tested with WordPress 5.5
* Fixed compatibility issue with WordPress 5.5. Adapt customizer color-picker script with latest version of WP 5.5
* Added missing translation word "by" in the translation file.
* Fixed few things according to the Theme Reveiw plugin.
* Reduced screenshot file size.

= 1.2.7 =
* Renamed font awesome handle name due to font awesome conflicts with plugins.

= 1.2.6 =
* Added 'no_found_rows' => true parameter for posts widgets to improve performance.
* Added wp_body_open() function to the header.
* Fixed undefined index issue that comes with posts widgets. 
* Fixed customizer "Display Site Title and Tagline" checkbox not working issue.
* Removed unwanted files/translations from integrated kirki plugin.

= 1.2.5 =
* Feature: Now it is possible to set Header image as Header Background image.

= 1.2.4 =
* Changed readme file as per WPTRT requirements.

= 1.2.3 =
* Added an option to display full content on blog/archive pages.
* Improved starter content.

= 1.2.2 =
* Added new theme hooks to be used on child themes.
* Improved some stylings.

= 1.2.1 =
* Added Gutenberg Support.

= 1.2.0 =
* Updated Popular Posts, Comments, Tags widget to display only approved comments.
* Used (document).ready() method instead of (window).load() method for slider.
* Used localized date method instead of date on top bar. 
* Fixed the layout issue that comes when adding elements inside posts.

= 1.1.9 =
* Renamed "sticky posts" widget controls.
* Fixed layout issue when there is no posts on "HTG_Single_Category_Posts" widget. 

= 1.1.8 =
* Added two page templates to be used on page builders.
* Fixed "Read More" button's active state color issue.
* Fixed footer widget links hover state color issue.

= 1.1.7 =
* Changed kirki version due to a compatiblity issue on versions < 4.9

= 1.1.6 =
* Updated kirki library.
* Fixed some issues in mobile navigation.

= 1.1.5 =
* Changed few stylings.

= 1.1.4 =
* Added search box to the mobile navigation menu.
* Added stylings for default widgets.
* Changed the header sidebar height.
* Added some stylings to WooCommerce.

= 1.1.3 =
* Added WooCommerce Support.

= 1.1.2 =
* Added yelp icon.
* Fixed some styling issues in blog posts listings.

= 1.1.1 =
* Fixed the theme primary color control issue with WordPress 4.9.
* Updated the translation file.

= 1.1.0 =
* Fixed white space issue on header.

= 1.0.9 =
* Fixed few issues in RTL styles.
* Fixed a plugin conflict with slider.
* Fixed IE 11 menu dropdown icon issue.
* Added styles to Category Widget and Archive Widget.

= 1.0.8 =
* Fixed an issue in header image.
* Changed the youtube icon in social media menu.
* Changed max-height in header sidebar.
* Fixed a issue in custom color option.

= 1.0.7 =
* Declared RTL support.

= 1.0.6 =
* Used get_templated_directory instead of dirname in functions.php.
* Fixed anonymous function issue.
* Fixed some escaping issues.
* Removed wp_reset_query() function on related-posts.php.
* Fixed translation issues.

= 1.0.5 =
* Used escaping functions for translation.
* Removed social sharing function.

= 1.0.4 =
* Fixed mobile menu not disappearing issue.
* HTG Popular posts, tags, comments restyled for footer.
* All the widgets are now working on any sidebar except header advertisement area.
* Removed block styling for <q> tag.
* Removed social media buttons.
* Header sidebar renamed to Header Advertisement Area.
* Escaped translation functions.
* Posts widgets styled for footer.
* Edited translation file.

= 1.0.3 =
* Fixed few escaping issues.

= 1.0.2 =
* Added starter content.
* Fixed a issue in template-tags.php
* Added an editor stylesheet.
* Added RTL language support.
* Added "view all" buttons to category posts widgets.

= 1.0.1 =
* Removed unwanted files and folders.
* Added social media sharing functionality.
* Added two widgets.
* Added custom color feature.
* Fixed few escaping issues.
* Updated Screenshot.
* Added few controls to the customizer.
* Updated translation file.

= 1.0.0 =
* Initial release

== Credits ==

* Based on Underscores http://underscores.me/, (C) 2012-2016 Automattic, Inc., [GPLv2 or later](https://www.gnu.org/licenses/gpl-2.0.html)
* normalize.css http://necolas.github.io/normalize.css/, (C) 2012-2016 Nicolas Gallagher and Jonathan Neal, [MIT](http://opensource.org/licenses/MIT)

* Kirki by Aristeides Stathopoulos. 
  Kirki is licenced under the terms of the MIT licence - https://github.com/aristath/kirki/blob/master/LICENSE

* Swiper by Vladimir Kharlampidi.
  Swiper is Licensed under the MIT license. https://github.com/nolimits4web/swiper/blob/master/LICENSE

* Magnific Popup by Dmitry Semenov
  Magnific popup is licensed under MIT licence. https://github.com/dimsemenov/Magnific-Popup/blob/master/LICENSE

* HTML5 Shiv @afarkas @jdalton @jon_neal @rem 
  MIT/GPL2 Licensed - https://github.com/aFarkas/html5shiv/blob/master/MIT%20and%20GPL2%20licenses.md

* Lato Font by Łukasz Dziedzic https://fonts.google.com/specimen/Lato
  Licensed under Open Font License - http://scripts.sil.org/cms/scripts/page.php?site_id=nrsi&id=OFL_web

* Open Sans Font by Steve Matteson https://fonts.google.com/specimen/Open+Sans
  Licensed under Apache License, version 2.0, http://www.apache.org/licenses/LICENSE-2.0.html

* Ubuntu Font by Dalton Maag https://fonts.google.com/specimen/Ubuntu
  License - http://font.ubuntu.com/ufl/ubuntu-font-licence-1.0.txt

* TRT Customizer Pro by Justin Tadlock https://github.com/justintadlock/trt-customizer-pro
  License - https://github.com/justintadlock/trt-customizer-pro/blob/master/license.md

* Screenshot images are all licensed under Creative Commons Zero (CC0) ( https://pxhere.com/en/license )
  https://pxhere.com/en/photo/578684 
  https://pxhere.com/en/photo/1173363 
  https://pxhere.com/en/photo/1368604 
  https://pxhere.com/en/photo/601444 
  https://pxhere.com/en/photo/764632 
  https://pxhere.com/en/photo/1373626 
  https://pxhere.com/en/photo/869156 
  https://pxhere.com/en/photo/1229568 
  https://pxhere.com/en/photo/1386455 
  https://pxhere.com/en/photo/1346731 

