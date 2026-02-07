/**
 * Engagement Features JavaScript
 * Quiz, Accordion, Newsletter, Progress Bar
 *
 * @package HTG
 * @since 2.1.0
 */

(function() {
	'use strict';

	/* ===========================================
	   Interactive Quiz System
	   =========================================== */

	function initQuizSystem() {
		var quizzes = document.querySelectorAll('.HTG-quiz-container');
		
		quizzes.forEach(function(quiz) {
			var options = quiz.querySelectorAll('.HTG-quiz-option');
			
			options.forEach(function(option) {
				option.addEventListener('click', function() {
					handleQuizVote(quiz, this);
				});
			});
		});
	}

	function handleQuizVote(quiz, option) {
		// Guard: check global exists
		if (typeof HTG_engagement === 'undefined') {
			console.warn('HTG: Engagement script data not available.');
			return;
		}

		var postId = quiz.getAttribute('data-post-id');
		var optionIndex = option.getAttribute('data-option-index');
		var loading = quiz.querySelector('.HTG-quiz-loading');

		// Show loading (null safe)
		if (loading) {
			loading.style.display = 'flex';
		}

		// Send AJAX request
		var formData = new FormData();
		formData.append('action', 'HTG_quiz_vote');
		formData.append('nonce', HTG_engagement.nonce);
		formData.append('post_id', postId);
		formData.append('option_index', optionIndex);

		fetch(HTG_engagement.ajax_url, {
			method: 'POST',
			body: formData,
			credentials: 'same-origin'
		})
		.then(function(response) {
			if (!response.ok) {
				throw new Error('Server error: ' + response.status);
			}
			return response.json();
		})
		.then(function(data) {
			if (data.success) {
				displayQuizResults(quiz, data.data);
			} else {
				showErrorMessage(quiz, (data.data && data.data.message) || 'Error voting. Please try again.');
			}
		})
		.catch(function(error) {
			console.error('Quiz vote error:', error);
			showErrorMessage(quiz, 'Network error. Please try again.');
		})
		.finally(function() {
			if (loading) {
				loading.style.display = 'none';
			}
		});
	}

	function displayQuizResults(quiz, data) {
		var optionsContainer = quiz.querySelector('.HTG-quiz-options');
		if (!optionsContainer || !data.results) return;

		var resultsHTML = data.results.map(function(result) {
			var percentage = parseInt(result.percentage, 10) || 0;
			var votes = parseInt(result.votes, 10) || 0;
			return '<div class="HTG-quiz-result ' + (result.is_user_choice ? 'user-choice' : '') + '">' +
				'<div class="HTG-quiz-result-header">' +
					'<span class="HTG-quiz-result-text">' +
						escapeHtml(result.text) +
						(result.is_user_choice ? ' <span class="HTG-quiz-your-vote">&#10003; Your vote</span>' : '') +
					'</span>' +
					'<span class="HTG-quiz-result-percentage">' + percentage + '%</span>' +
				'</div>' +
				'<div class="HTG-quiz-result-bar">' +
					'<div class="HTG-quiz-result-fill" style="width: ' + percentage + '%"></div>' +
				'</div>' +
				'<div class="HTG-quiz-result-votes">' + votes.toLocaleString() + ' ' + (votes === 1 ? 'vote' : 'votes') + '</div>' +
			'</div>';
		}).join('');

		optionsContainer.outerHTML = '<div class="HTG-quiz-results">' + resultsHTML + '</div>';

		// Update footer
		var totalVotesEl = quiz.querySelector('.HTG-quiz-total-votes');
		var promptEl = quiz.querySelector('.HTG-quiz-prompt');
		
		if (totalVotesEl && data.total_votes !== undefined) {
			var totalVotes = parseInt(data.total_votes, 10) || 0;
			totalVotesEl.textContent = totalVotes.toLocaleString() + ' ' + (totalVotes === 1 ? 'vote' : 'votes');
		}
		
		if (promptEl) {
			promptEl.textContent = 'Thanks for voting!';
		}
	}

	/* ===========================================
	   Accordion System
	   =========================================== */

	function initAccordionSystem() {
		var accordions = document.querySelectorAll('.HTG-accordion');
		
		accordions.forEach(function(accordion) {
			var items = accordion.querySelectorAll('.HTG-accordion-item');
			
			items.forEach(function(item) {
				var header = item.querySelector('.HTG-accordion-header');
				var content = item.querySelector('.HTG-accordion-content');
				
				if (header && content) {
					header.addEventListener('click', function() {
						toggleAccordion(item, content, header);
					});
				}
			});
		});
	}

	function toggleAccordion(item, content, header) {
		var isOpen = item.classList.contains('is-open');
		
		if (isOpen) {
			item.classList.remove('is-open');
			header.setAttribute('aria-expanded', 'false');
			content.style.display = 'none';
		} else {
			item.classList.add('is-open');
			header.setAttribute('aria-expanded', 'true');
			content.style.display = 'block';
			
			trackAccordionOpen(item);
		}
	}

	function trackAccordionOpen(item) {
		// Guard: check global exists
		if (typeof HTG_engagement === 'undefined') return;

		var accordion = item.closest('.HTG-accordion');
		var accordionId = accordion ? accordion.getAttribute('data-accordion-id') : '';
		
		if (!accordionId) return;

		var formData = new FormData();
		formData.append('action', 'HTG_accordion_track');
		formData.append('nonce', HTG_engagement.nonce);
		formData.append('accordion_id', accordionId);
		formData.append('post_id', HTG_engagement.post_id);
		formData.append('action_type', 'open');

		fetch(HTG_engagement.ajax_url, {
			method: 'POST',
			body: formData,
			credentials: 'same-origin'
		}).catch(function() {
			// Silent fail for tracking
		});
	}

	/* ===========================================
	   Newsletter System
	   =========================================== */

	function initNewsletterSystem() {
		var forms = document.querySelectorAll('.HTG-newsletter-form');
		
		forms.forEach(function(form) {
			form.addEventListener('submit', function(e) {
				e.preventDefault();
				handleNewsletterSubmit(this);
			});
		});
	}

	function handleNewsletterSubmit(form) {
		// Guard: check global exists
		if (typeof HTG_engagement === 'undefined') return;

		var emailInput = form.querySelector('.HTG-newsletter-input');
		if (!emailInput) return;

		var email = emailInput.value;
		var postId = form.getAttribute('data-post-id');
		var button = form.querySelector('.HTG-newsletter-button');
		var messageEl = form.querySelector('.HTG-newsletter-message');
		
		if (!button) return;

		// Disable button
		button.disabled = true;
		button.textContent = 'Subscribing...';

		// Send AJAX request
		var formData = new FormData();
		formData.append('action', 'HTG_newsletter_subscribe');
		formData.append('nonce', HTG_engagement.nonce);
		formData.append('email', email);
		formData.append('post_id', postId);

		fetch(HTG_engagement.ajax_url, {
			method: 'POST',
			body: formData,
			credentials: 'same-origin'
		})
		.then(function(response) {
			if (!response.ok) {
				throw new Error('Server error: ' + response.status);
			}
			return response.json();
		})
		.then(function(data) {
			if (data.success) {
				if (messageEl) showNewsletterMessage(messageEl, data.data.message, 'success');
				emailInput.value = '';
			} else {
				if (messageEl) showNewsletterMessage(messageEl, data.data.message, 'error');
			}
		})
		.catch(function(error) {
			console.error('Newsletter error:', error);
			if (messageEl) showNewsletterMessage(messageEl, 'Network error. Please try again.', 'error');
		})
		.finally(function() {
			button.disabled = false;
			button.innerHTML = '<span class="HTG-newsletter-button-text">Subscribe</span><span class="HTG-newsletter-button-icon">&rarr;</span>';
		});
	}

	function showNewsletterMessage(messageEl, message, type) {
		if (!messageEl) return;
		messageEl.textContent = message;
		messageEl.className = 'HTG-newsletter-message ' + type;
		messageEl.style.display = 'block';
		
		setTimeout(function() {
			messageEl.style.display = 'none';
		}, 5000);
	}

	/* ===========================================
	   Reading Progress Bar
	   =========================================== */

	function initProgressBar() {
		if (!document.body.classList.contains('single-post') && !document.body.classList.contains('single')) {
			return;
		}

		// Create progress bar
		var progressBar = document.createElement('div');
		progressBar.className = 'HTG-progress-bar';
		progressBar.innerHTML = '<div class="HTG-progress-fill"></div>';
		document.body.appendChild(progressBar);

		var progressFill = progressBar.querySelector('.HTG-progress-fill');
		if (!progressFill) return;

		// Throttled scroll handler using requestAnimationFrame
		var ticking = false;
		window.addEventListener('scroll', function() {
			if (!ticking) {
				window.requestAnimationFrame(function() {
					var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
					var scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
					if (scrollHeight > 0) {
						var scrollPercentage = (scrollTop / scrollHeight) * 100;
						progressFill.style.width = scrollPercentage + '%';
					}
					ticking = false;
				});
				ticking = true;
			}
		});
	}

	/* ===========================================
	   AJAX Post Loading
	   =========================================== */

	function initAjaxPosts() {
		var containers = document.querySelectorAll('.HTG-ajax-post-grid-container');
		
		containers.forEach(function(container) {
			initFilterButtons(container);
			initLoadMoreButton(container);
		});
	}

	function initFilterButtons(container) {
		// Guard: check global exists
		if (typeof HTG_ajax === 'undefined') return;

		var filterButtons = container.querySelectorAll('.HTG-filter-btn');
		var grid = container.querySelector('.HTG-ajax-posts-grid');
		var loadMoreBtn = container.querySelector('.HTG-load-more-btn');
		var postsPerPage = container.getAttribute('data-posts-per-page') || 9;

		if (!grid) return;
		
		filterButtons.forEach(function(button) {
			button.addEventListener('click', function() {
				var category = this.getAttribute('data-category');
				
				// Update active state
				filterButtons.forEach(function(btn) {
					btn.classList.remove('active');
				});
				this.classList.add('active');
				
				// Show loading state
				grid.style.opacity = '0.5';
				grid.style.pointerEvents = 'none';
				
				// Send AJAX request
				var formData = new FormData();
				formData.append('action', 'HTG_filter_posts');
				formData.append('nonce', HTG_ajax.nonce);
				formData.append('category', category);
				formData.append('posts_per_page', postsPerPage);
				
				fetch(HTG_ajax.ajax_url, {
					method: 'POST',
					body: formData,
					credentials: 'same-origin'
				})
				.then(function(response) {
					if (!response.ok) {
						throw new Error('Server error: ' + response.status);
					}
					return response.json();
				})
				.then(function(data) {
					if (data.success) {
						grid.innerHTML = data.data.html;
						
						// Update load more button
						if (loadMoreBtn) {
							loadMoreBtn.setAttribute('data-page', '1');
							loadMoreBtn.setAttribute('data-max-pages', data.data.max_pages);
							loadMoreBtn.setAttribute('data-category', category);
							
							loadMoreBtn.style.display = data.data.has_more ? 'inline-flex' : 'none';
						}
					}
				})
				.catch(function(error) {
					console.error('Filter error:', error);
				})
				.finally(function() {
					grid.style.opacity = '1';
					grid.style.pointerEvents = 'auto';
				});
			});
		});
	}

	function initLoadMoreButton(container) {
		// Guard: check global exists
		if (typeof HTG_ajax === 'undefined') return;

		var loadMoreBtn = container.querySelector('.HTG-load-more-btn');
		
		if (!loadMoreBtn) return;
		
		loadMoreBtn.addEventListener('click', function() {
			var btn = this;
			var grid = container.querySelector('.HTG-ajax-posts-grid');
			if (!grid) return;

			var currentPage = parseInt(btn.getAttribute('data-page'), 10) || 1;
			var category = btn.getAttribute('data-category') || 'all';
			var postsPerPage = container.getAttribute('data-posts-per-page') || 9;
			var nextPage = currentPage + 1;
			
			// Show loading state (null safe)
			var textEl = btn.querySelector('.HTG-load-more-text');
			var loadingEl = btn.querySelector('.HTG-load-more-loading');
			
			if (textEl) textEl.style.display = 'none';
			if (loadingEl) loadingEl.style.display = 'inline-flex';
			btn.disabled = true;
			
			// Send AJAX request
			var formData = new FormData();
			formData.append('action', 'HTG_load_more_posts');
			formData.append('nonce', HTG_ajax.nonce);
			formData.append('page', nextPage);
			formData.append('posts_per_page', postsPerPage);
			formData.append('category', category);
			
			fetch(HTG_ajax.ajax_url, {
				method: 'POST',
				body: formData,
				credentials: 'same-origin'
			})
			.then(function(response) {
				if (!response.ok) {
					throw new Error('Server error: ' + response.status);
				}
				return response.json();
			})
			.then(function(data) {
				if (data.success) {
					grid.insertAdjacentHTML('beforeend', data.data.html);
					btn.setAttribute('data-page', nextPage);
					
					if (!data.data.has_more) {
						btn.style.display = 'none';
					}
				} else {
					showLoadMoreError(btn, (data.data && data.data.message) || 'No more posts to load.');
				}
			})
			.catch(function(error) {
				console.error('Load more error:', error);
				showLoadMoreError(btn, 'Error loading posts. Please try again.');
			})
			.finally(function() {
				if (textEl) textEl.style.display = 'inline';
				if (loadingEl) loadingEl.style.display = 'none';
				btn.disabled = false;
			});
		});
	}

	/* ===========================================
	   Utility Functions
	   =========================================== */

	function escapeHtml(text) {
		var div = document.createElement('div');
		div.textContent = text;
		return div.innerHTML;
	}

	function showErrorMessage(container, message) {
		var errorEl = document.createElement('div');
		errorEl.className = 'HTG-error-message';
		errorEl.innerHTML = '<div class="HTG-error-content">' +
			'<span class="HTG-error-text">' + escapeHtml(message) + '</span>' +
		'</div>';
		
		container.appendChild(errorEl);
		
		setTimeout(function() {
			errorEl.style.opacity = '0';
			setTimeout(function() {
				if (errorEl.parentNode) {
					errorEl.parentNode.removeChild(errorEl);
				}
			}, 300);
		}, 5000);
	}

	function showLoadMoreError(button, message) {
		var container = button.closest('.HTG-ajax-post-grid-container');
		if (container) {
			showErrorMessage(container, message);
		}
	}

	/* ===========================================
	   Initialize All Features
	   =========================================== */

	function init() {
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', initAll);
		} else {
			initAll();
		}
	}

	function initAll() {
		initQuizSystem();
		initAccordionSystem();
		initNewsletterSystem();
		initProgressBar();
		initAjaxPosts();

		// Smooth scroll for anchor links
		document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
			anchor.addEventListener('click', function(e) {
				var href = this.getAttribute('href');
				if (!href || href === '#' || href.length <= 1) {
					return;
				}
				try {
					var target = document.querySelector(href);
					if (target) {
						e.preventDefault();
						target.scrollIntoView({ behavior: 'smooth' });
					}
				} catch (err) {
					// Invalid selector, skip
				}
			});
		});
	}

	// Initialize
	init();

	// Expose for external use
	window.HTGEngagement = {
		initQuizSystem: initQuizSystem,
		initAccordionSystem: initAccordionSystem,
		initNewsletterSystem: initNewsletterSystem,
		initProgressBar: initProgressBar,
		initAjaxPosts: initAjaxPosts
	};

})();
