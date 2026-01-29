/**
 * Engagement Features JavaScript
 * Quiz, Accordion, Newsletter, Progress Bar, Social Share
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
		const quizzes = document.querySelectorAll('.HTG-quiz-container');
		
		quizzes.forEach(function(quiz) {
			const options = quiz.querySelectorAll('.HTG-quiz-option');
			
			options.forEach(function(option) {
				option.addEventListener('click', function() {
					handleQuizVote(quiz, this);
				});
			});
		});
	}

	function handleQuizVote(quiz, option) {
		const postId = quiz.getAttribute('data-post-id');
		const optionIndex = option.getAttribute('data-option-index');
		const optionText = option.getAttribute('data-option-text');
		const loading = quiz.querySelector('.HTG-quiz-loading');

		// Show loading
		loading.style.display = 'flex';

		// Send AJAX request
		const formData = new FormData();
		formData.append('action', 'HTG_quiz_vote');
		formData.append('nonce', HTG_engagement.nonce);
		formData.append('post_id', postId);
		formData.append('option_index', optionIndex);

		fetch(HTG_engagement.ajax_url, {
			method: 'POST',
			body: formData,
			credentials: 'same-origin'
		})
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				displayQuizResults(quiz, data.data);
			} else {
				showErrorMessage(quiz, data.data.message || 'Error voting. Please try again.');
			}
		})
		.catch(error => {
			console.error('Quiz vote error:', error);
			showErrorMessage(quiz, 'Network error. Please try again.');
		})
		.finally(() => {
			loading.style.display = 'none';
		});
	}

	function displayQuizResults(quiz, data) {
		const optionsContainer = quiz.querySelector('.HTG-quiz-options');
		const resultsHTML = data.results.map(result => `
			<div class="HTG-quiz-result ${result.is_user_choice ? 'user-choice' : ''}">
				<div class="HTG-quiz-result-header">
					<span class="HTG-quiz-result-text">
						${escapeHtml(result.text)}
						${result.is_user_choice ? '<span class="HTG-quiz-your-vote">✓ Your vote</span>' : ''}
					</span>
					<span class="HTG-quiz-result-percentage">${result.percentage}%</span>
				</div>
				<div class="HTG-quiz-result-bar">
					<div class="HTG-quiz-result-fill" style="width: ${result.percentage}%"></div>
				</div>
				<div class="HTG-quiz-result-votes">${result.votes.toLocaleString()} ${result.votes === 1 ? 'vote' : 'votes'}</div>
			</div>
		`).join('');

		optionsContainer.outerHTML = '<div class="HTG-quiz-results">' + resultsHTML + '</div>';

		// Update footer
		const totalVotesEl = quiz.querySelector('.HTG-quiz-total-votes');
		const promptEl = quiz.querySelector('.HTG-quiz-prompt');
		
		if (totalVotesEl) {
			totalVotesEl.textContent = `${data.total_votes.toLocaleString()} ${data.total_votes === 1 ? 'vote' : 'votes'}`;
		}
		
		if (promptEl) {
			promptEl.innerHTML = '<span class="HTG-quiz-thanks">Thanks for voting! 🎉</span>';
		}
	}

	/* ===========================================
	   Accordion System
	   =========================================== */

	function initAccordionSystem() {
		const accordions = document.querySelectorAll('.HTG-accordion');
		
		accordions.forEach(function(accordion) {
			const items = accordion.querySelectorAll('.HTG-accordion-item');
			
			items.forEach(function(item) {
				const header = item.querySelector('.HTG-accordion-header');
				const content = item.querySelector('.HTG-accordion-content');
				
				header.addEventListener('click', function() {
					toggleAccordion(item, content, header);
				});
			});
		});
	}

	function toggleAccordion(item, content, header) {
		const isOpen = item.classList.contains('is-open');
		
		if (isOpen) {
			// Close
			item.classList.remove('is-open');
			header.setAttribute('aria-expanded', 'false');
			content.style.display = 'none';
		} else {
			// Open
			item.classList.add('is-open');
			header.setAttribute('aria-expanded', 'true');
			content.style.display = 'block';
			
			// Track interaction
			trackAccordionOpen(item);
		}
	}

	function trackAccordionOpen(item) {
		const accordion = item.closest('.HTG-accordion');
		const accordionId = accordion ? accordion.getAttribute('data-accordion-id') : '';
		
		if (!accordionId) return;

		const formData = new FormData();
		formData.append('action', 'HTG_accordion_track');
		formData.append('nonce', HTG_engagement.nonce);
		formData.append('accordion_id', accordionId);
		formData.append('post_id', HTG_engagement.post_id);
		formData.append('action_type', 'open');

		fetch(HTG_engagement.ajax_url, {
			method: 'POST',
			body: formData,
			credentials: 'same-origin'
		});
	}

	/* ===========================================
	   Newsletter System
	   =========================================== */

	function initNewsletterSystem() {
		const forms = document.querySelectorAll('.HTG-newsletter-form');
		
		forms.forEach(function(form) {
			form.addEventListener('submit', function(e) {
				e.preventDefault();
				handleNewsletterSubmit(this);
			});
		});
	}

	function handleNewsletterSubmit(form) {
		const email = form.querySelector('.HTG-newsletter-input').value;
		const postId = form.getAttribute('data-post-id');
		const button = form.querySelector('.HTG-newsletter-button');
		const messageEl = form.querySelector('.HTG-newsletter-message');
		
		// Disable button
		button.disabled = true;
		button.textContent = 'Subscribing...';

		// Send AJAX request
		const formData = new FormData();
		formData.append('action', 'HTG_newsletter_subscribe');
		formData.append('nonce', HTG_engagement.nonce);
		formData.append('email', email);
		formData.append('post_id', postId);

		fetch(HTG_engagement.ajax_url, {
			method: 'POST',
			body: formData,
			credentials: 'same-origin'
		})
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				showNewsletterMessage(messageEl, data.data.message, 'success');
				form.querySelector('.HTG-newsletter-input').value = '';
			} else {
				showNewsletterMessage(messageEl, data.data.message, 'error');
			}
		})
		.catch(error => {
			console.error('Newsletter error:', error);
			showNewsletterMessage(messageEl, 'Network error. Please try again.', 'error');
		})
		.finally(() => {
			button.disabled = false;
			button.innerHTML = '<span class="HTG-newsletter-button-text">Subscribe</span><span class="HTG-newsletter-button-icon">→</span>';
		});
	}

	function showNewsletterMessage(messageEl, message, type) {
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
		const progressBar = document.createElement('div');
		progressBar.className = 'HTG-progress-bar';
		progressBar.innerHTML = '<div class="HTG-progress-fill"></div>';
		document.body.appendChild(progressBar);

		const progressFill = progressBar.querySelector('.HTG-progress-fill');

		// Update progress on scroll
		window.addEventListener('scroll', function() {
			const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
			const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
			const scrollPercentage = (scrollTop / scrollHeight) * 100;
			
			progressFill.style.width = scrollPercentage + '%';
		});
	}

	/* ===========================================
	   Sticky Social Share
	   =========================================== */

	function initStickyShare() {
		if (!document.body.classList.contains('single-post') && !document.body.classList.contains('single')) {
			return;
		}

		// Create sticky share bar
		const shareBar = document.createElement('div');
		shareBar.className = 'HTG-sticky-share';
		
		const postTitle = encodeURIComponent(document.title);
		const postUrl = encodeURIComponent(window.location.href);
		
		const shareButtons = [
			{
				network: 'facebook',
				url: `https://www.facebook.com/sharer/sharer.php?u=${postUrl}`,
				icon: 'f',
				label: 'Share on Facebook'
			},
			{
				network: 'twitter',
				url: `https://twitter.com/intent/tweet?url=${postUrl}&text=${postTitle}`,
				icon: '𝕏',
				label: 'Share on Twitter'
			},
			{
				network: 'linkedin',
				url: `https://www.linkedin.com/shareArticle?mini=true&url=${postUrl}&title=${postTitle}`,
				icon: 'in',
				label: 'Share on LinkedIn'
			},
			{
				network: 'whatsapp',
				url: `https://wa.me/?text=${postTitle}%20${postUrl}`,
				icon: 'W',
				label: 'Share on WhatsApp'
			}
		];

		shareButtons.forEach(function(button) {
			const link = document.createElement('a');
			link.href = button.url;
			link.className = 'HTG-share-btn-sticky HTG-share-' + button.network;
			link.target = '_blank';
			link.rel = 'noopener noreferrer';
			link.setAttribute('aria-label', button.label);
			link.textContent = button.icon;
			
			link.addEventListener('click', function(e) {
				e.preventDefault();
				window.open(this.href, 'share-dialog', 'width=600,height=400');
			});
			
			shareBar.appendChild(link);
		});

		document.body.appendChild(shareBar);

		// Show/hide on scroll
		let lastScrollTop = 0;
		window.addEventListener('scroll', function() {
			const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
			
			if (scrollTop > 300) {
				shareBar.style.opacity = '1';
				shareBar.style.pointerEvents = 'auto';
			} else {
				shareBar.style.opacity = '0';
				shareBar.style.pointerEvents = 'none';
			}
			
			lastScrollTop = scrollTop;
		});
	}

	/* ===========================================
	   AJAX Post Loading (innovation4world inspired)
	   =========================================== */

	function initAjaxPosts() {
		const containers = document.querySelectorAll('.HTG-ajax-post-grid-container');
		
		containers.forEach(function(container) {
			initFilterButtons(container);
			initLoadMoreButton(container);
		});
	}

	function initFilterButtons(container) {
		const filterButtons = container.querySelectorAll('.HTG-filter-btn');
		const grid = container.querySelector('.HTG-ajax-posts-grid');
		const loadMoreBtn = container.querySelector('.HTG-load-more-btn');
		const postsPerPage = container.getAttribute('data-posts-per-page') || 9;
		
		filterButtons.forEach(function(button) {
			button.addEventListener('click', function() {
				const category = this.getAttribute('data-category');
				
				// Update active state
				filterButtons.forEach(function(btn) {
					btn.classList.remove('active');
				});
				this.classList.add('active');
				
				// Show loading state
				grid.style.opacity = '0.5';
				grid.style.pointerEvents = 'none';
				
				// Send AJAX request
				const formData = new FormData();
				formData.append('action', 'HTG_filter_posts');
				formData.append('nonce', HTG_ajax.nonce);
				formData.append('category', category);
				formData.append('posts_per_page', postsPerPage);
				
				fetch(HTG_ajax.ajax_url, {
					method: 'POST',
					body: formData,
					credentials: 'same-origin'
				})
				.then(response => response.json())
				.then(data => {
					if (data.success) {
						grid.innerHTML = data.data.html;
						
						// Update load more button
						if (loadMoreBtn) {
							loadMoreBtn.setAttribute('data-page', '1');
							loadMoreBtn.setAttribute('data-max-pages', data.data.max_pages);
							loadMoreBtn.setAttribute('data-category', category);
							
							if (data.data.has_more) {
								loadMoreBtn.style.display = 'inline-flex';
							} else {
								loadMoreBtn.style.display = 'none';
							}
						}
					}
				})
				.catch(error => {
					console.error('Filter error:', error);
				})
				.finally(() => {
					grid.style.opacity = '1';
					grid.style.pointerEvents = 'auto';
				});
			});
		});
	}

	function initLoadMoreButton(container) {
		const loadMoreBtn = container.querySelector('.HTG-load-more-btn');
		
		if (!loadMoreBtn) return;
		
		loadMoreBtn.addEventListener('click', function() {
			const grid = container.querySelector('.HTG-ajax-posts-grid');
			const currentPage = parseInt(this.getAttribute('data-page')) || 1;
			const maxPages = parseInt(this.getAttribute('data-max-pages')) || 1;
			const category = this.getAttribute('data-category') || 'all';
			const postsPerPage = container.getAttribute('data-posts-per-page') || 9;
			const nextPage = currentPage + 1;
			
			// Show loading state
			const textEl = this.querySelector('.HTG-load-more-text');
			const loadingEl = this.querySelector('.HTG-load-more-loading');
			
			textEl.style.display = 'none';
			loadingEl.style.display = 'inline-flex';
			this.disabled = true;
			
			// Send AJAX request
			const formData = new FormData();
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
			.then(response => response.json())
			.then(data => {
				if (data.success) {
					// Append new posts
					grid.insertAdjacentHTML('beforeend', data.data.html);
					
					// Update button state
					this.setAttribute('data-page', nextPage);
					
					if (!data.data.has_more) {
						this.style.display = 'none';
					}
				} else {
					showLoadMoreError(this, data.data.message || 'No more posts to load.');
				}
			})
			.catch(error => {
				console.error('Load more error:', error);
				showLoadMoreError(this, 'Error loading posts. Please try again.');
			})
			.finally(() => {
				textEl.style.display = 'inline';
				loadingEl.style.display = 'none';
				this.disabled = false;
			});
		});
	}

	/* ===========================================
	   Utility Functions
	   =========================================== */

	function escapeHtml(text) {
		const div = document.createElement('div');
		div.textContent = text;
		return div.innerHTML;
	}

	/**
	 * Show error message with proper UI
	 */
	function showErrorMessage(container, message) {
		const errorEl = document.createElement('div');
		errorEl.className = 'HTG-error-message';
		errorEl.innerHTML = `
			<div class="HTG-error-content">
				<span class="HTG-error-icon">⚠️</span>
				<span class="HTG-error-text">${escapeHtml(message)}</span>
			</div>
		`;
		
		container.appendChild(errorEl);
		
		setTimeout(() => {
			errorEl.style.opacity = '0';
			setTimeout(() => errorEl.remove(), 300);
		}, 5000);
	}

	/**
	 * Show load more error
	 */
	function showLoadMoreError(button, message) {
		const container = button.closest('.HTG-ajax-post-grid-container');
		if (container) {
			showErrorMessage(container, message);
		}
	}

	/* ===========================================
	   Initialize All Features
	   =========================================== */

	function init() {
		// Wait for DOM to be ready
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
		initStickyShare();
		initAjaxPosts();

	// Add smooth scroll for all links
	document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
		anchor.addEventListener('click', function(e) {
			const href = this.getAttribute('href');
			// Skip if href is just '#' or empty
			if (!href || href === '#' || href.length <= 1) {
				return;
			}
			try {
				const target = document.querySelector(href);
				if (target) {
					e.preventDefault();
					target.scrollIntoView({ behavior: 'smooth' });
				}
			} catch (err) {
				// Invalid selector, skip it
				console.warn('Invalid anchor selector:', href);
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
		initStickyShare: initStickyShare,
		initAjaxPosts: initAjaxPosts
	};

})();

