/**
 * Dark Mode Toggle
 * Handles dark mode switching
 *
 * @package HTG
 * @since 2.0.0
 */

(function() {
	'use strict';

	// Wait for DOM to be ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}

	function init() {
		var settings = typeof HTG_dark_mode !== 'undefined' ? HTG_dark_mode : {
			auto_detect: true,
			default: false
		};

		var toggle = document.getElementById('HTG-dark-mode-toggle');
		if (!toggle) return;

		// Check for saved preference or system preference
		var darkMode = getDarkModePreference(settings);
		
		if (darkMode) {
			enableDarkMode();
		}

		// Toggle click handler
		toggle.addEventListener('click', function() {
			if (document.body.classList.contains('HTG-dark-mode')) {
				disableDarkMode();
				localStorage.setItem('HTG_dark_mode', 'light');
			} else {
				enableDarkMode();
				localStorage.setItem('HTG_dark_mode', 'dark');
			}
		});

		// Listen for system theme changes
		if (settings.auto_detect && window.matchMedia) {
			var darkModeQuery = window.matchMedia('(prefers-color-scheme: dark)');
			
			// Modern browsers
			if (darkModeQuery.addEventListener) {
				darkModeQuery.addEventListener('change', function(e) {
					// Only auto-switch if user hasn't manually set a preference
					if (!localStorage.getItem('HTG_dark_mode')) {
						if (e.matches) {
							enableDarkMode();
						} else {
							disableDarkMode();
						}
					}
				});
			}
			// Older browsers
			else if (darkModeQuery.addListener) {
				darkModeQuery.addListener(function(e) {
					if (!localStorage.getItem('HTG_dark_mode')) {
						if (e.matches) {
							enableDarkMode();
						} else {
							disableDarkMode();
						}
					}
				});
			}
		}
	}

	function getDarkModePreference(settings) {
		// Check localStorage first
		var saved = localStorage.getItem('HTG_dark_mode');
		if (saved === 'dark') {
			return true;
		} else if (saved === 'light') {
			return false;
		}

		// Check system preference
		if (settings.auto_detect && window.matchMedia) {
			if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
				return true;
			}
		}

		// Use default setting
		return settings.default;
	}

	function enableDarkMode() {
		document.body.classList.add('HTG-dark-mode');
		updateToggleIcon(true);
	}

	function disableDarkMode() {
		document.body.classList.remove('HTG-dark-mode');
		updateToggleIcon(false);
	}

	function updateToggleIcon(isDark) {
		var toggle = document.getElementById('HTG-dark-mode-toggle');
		if (!toggle) return;

		var lightIcon = toggle.querySelector('.HTG-icon-light');
		var darkIcon = toggle.querySelector('.HTG-icon-dark');

		if (isDark) {
			lightIcon.style.display = 'none';
			darkIcon.style.display = 'block';
		} else {
			lightIcon.style.display = 'block';
			darkIcon.style.display = 'none';
		}
	}

})();

