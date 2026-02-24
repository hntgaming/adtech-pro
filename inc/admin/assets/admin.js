/**
 * H&T AdTech Pro - Enterprise Admin Dashboard
 * Production-ready admin interface functionality
 *
 * @package HTG
 * @version 2.3.0
 */

(function($) {
	'use strict';

	// Configuration
	var HTG_Admin = {
		animationDuration: 300,
		statsAnimationDuration: 1500,
		debounceDelay: 250
	};

	/**
	 * Initialize on document ready
	 */
	$(document).ready(function() {
		initStatsAnimation();
		initTabSwitching();
		initColorPickers();
		initToggleSwitches();
		initFormValidation();
		initSmoothScrolling();
		initCardHoverEffects();
	});

	/**
	 * Animate stats counters on page load
	 */
	function initStatsAnimation() {
		var $stats = $('.HTG-stat-value');
		
		if (!$stats.length) return;

		// Check if element is in viewport
		var observer = new IntersectionObserver(function(entries) {
			entries.forEach(function(entry) {
				if (entry.isIntersecting) {
					animateCounter($(entry.target));
					observer.unobserve(entry.target);
				}
			});
		}, { threshold: 0.5 });

		$stats.each(function() {
			observer.observe(this);
		});
	}

	/**
	 * Animate a single counter
	 */
	function animateCounter($element) {
		var text = $element.text();
		var countTo = parseFloat(text.replace(/[^0-9.]/g, ''));
		
		if (!countTo || isNaN(countTo)) return;

		var isDecimal = text.indexOf('.') !== -1;
		var suffix = text.replace(/[0-9.,]/g, '');

		$({ count: 0 }).animate({
			count: countTo
		}, {
			duration: HTG_Admin.statsAnimationDuration,
			easing: 'swing',
			step: function() {
				var value = isDecimal ? this.count.toFixed(1) : Math.floor(this.count);
				$element.text(Number(value).toLocaleString() + suffix);
			},
			complete: function() {
				var value = isDecimal ? countTo.toFixed(1) : Math.floor(countTo);
				$element.text(Number(value).toLocaleString() + suffix);
			}
		});
	}

	/**
	 * Initialize tab switching functionality
	 */
	function initTabSwitching() {
		$(document).on('click', '.nav-tab', function(e) {
			e.preventDefault();
			
			var $this = $(this);
			var target = $this.attr('href');
			var $container = $this.closest('.HTG-settings-tabs');
			
			// Skip if already active
			if ($this.hasClass('nav-tab-active')) return;

			// Update tabs
			$container.find('.nav-tab').removeClass('nav-tab-active');
			$this.addClass('nav-tab-active');

			// Animate content switch
			$container.find('.HTG-tab-content').stop(true, true).fadeOut(HTG_Admin.animationDuration / 2, function() {
				$(target).fadeIn(HTG_Admin.animationDuration / 2);
			});

			// Reinitialize color pickers in new tab
			setTimeout(function() {
				$(target).find('.HTG-color-picker').each(function() {
					if (!$(this).closest('.wp-picker-container').length) {
						$(this).wpColorPicker();
					}
				});
			}, HTG_Admin.animationDuration);

			// Update URL hash without scrolling
			if (history.replaceState) {
				history.replaceState(null, null, target);
			}
		});

		// Check URL hash on load
		var hash = window.location.hash;
		if (hash && $(hash).length && $(hash).hasClass('HTG-tab-content')) {
			$('.nav-tab[href="' + hash + '"]').trigger('click');
		}
	}

	/**
	 * Initialize WordPress color pickers
	 */
	function initColorPickers() {
		if (typeof $.fn.wpColorPicker === 'undefined') return;

		$('.HTG-color-picker').each(function() {
			var $input = $(this);
			
			if ($input.closest('.wp-picker-container').length) return;

			$input.wpColorPicker({
				change: function(event, ui) {
					// Trigger change event for form validation
					$input.trigger('change');
				},
				clear: function() {
					$input.trigger('change');
				}
			});
		});
	}

	/**
	 * Initialize toggle switch interactions
	 */
	function initToggleSwitches() {
		$('.HTG-toggle input[type="checkbox"]').on('change', function() {
			var $toggle = $(this).closest('.HTG-toggle');
			
			// Add visual feedback
			$toggle.addClass('HTG-toggle-changed');
			setTimeout(function() {
				$toggle.removeClass('HTG-toggle-changed');
			}, 300);
		});
	}

	/**
	 * Initialize form validation
	 */
	function initFormValidation() {
		// Add unsaved changes warning
		var formChanged = false;

		$('.HTG-admin-wrap form').on('change', 'input, select, textarea', function() {
			formChanged = true;
		});

		$('.HTG-admin-wrap form').on('submit', function() {
			formChanged = false;
		});

		$(window).on('beforeunload', function() {
			if (formChanged) {
				return 'You have unsaved changes. Are you sure you want to leave?';
			}
		});

		// Real-time validation for number inputs
		$('.HTG-admin-wrap input[type="number"]').on('input', function() {
			var $input = $(this);
			var min = parseFloat($input.attr('min'));
			var max = parseFloat($input.attr('max'));
			var value = parseFloat($input.val());

			if (!isNaN(min) && value < min) {
				$input.addClass('HTG-input-error');
			} else if (!isNaN(max) && value > max) {
				$input.addClass('HTG-input-error');
			} else {
				$input.removeClass('HTG-input-error');
			}
		});
	}

	/**
	 * Initialize smooth scrolling for anchor links
	 */
	function initSmoothScrolling() {
		$('a[href^="#"]').not('.nav-tab').on('click', function(e) {
			var target = $(this.getAttribute('href'));
			
			if (target.length) {
				e.preventDefault();
				$('html, body').animate({
					scrollTop: target.offset().top - 50
				}, HTG_Admin.animationDuration);
			}
		});
	}

	/**
	 * Initialize card hover effects
	 */
	function initCardHoverEffects() {
		$('.HTG-quick-action, .HTG-stat-card, .HTG-admin-card').on('mouseenter', function() {
			$(this).addClass('HTG-hover');
		}).on('mouseleave', function() {
			$(this).removeClass('HTG-hover');
		});
	}

	/**
	 * Utility: Debounce function
	 */
	function debounce(func, wait) {
		var timeout;
		return function executedFunction() {
			var context = this;
			var args = arguments;
			clearTimeout(timeout);
			timeout = setTimeout(function() {
				func.apply(context, args);
			}, wait);
		};
	}

	/**
	 * Utility: Show notification
	 */
	window.HTG_showNotification = function(message, type) {
		type = type || 'success';
		
		var allowedTypes = ['success', 'error', 'warning', 'info'];
		type = (allowedTypes.indexOf(type) !== -1) ? type : 'success';
		var $notification = $('<div class="notice notice-' + type + ' is-dismissible"></div>');
		$notification.append($('<p></p>').text(message));
		
		$('.HTG-admin-wrap .HTG-admin-header').after($notification);
		
		$notification.hide().slideDown(HTG_Admin.animationDuration);
		
		setTimeout(function() {
			$notification.slideUp(HTG_Admin.animationDuration, function() {
				$(this).remove();
			});
		}, 5000);
	};

})(jQuery);
