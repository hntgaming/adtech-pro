// Modern jQuery initialization
jQuery(function($) {
	// Search toggle
	$(".hm-search-button-icon").on( "click", function() {
		$(".hm-search-box-container").toggle('fast');
		$(".hm-search-box .search-field").trigger('focus');
		$(this).toggleClass("hm-search-close");
	});

	// Lightbox for images
	if ( typeof $.fn.magnificPopup !== "undefined" ) {
		$('.image-link').magnificPopup({
			type: 'image'
		});
	}

	// Tabs Widget
	if ( typeof $.fn.tabs === "function" ) {
		$( ".hm-tabs-wdt" ).tabs();
	}
});

/* Featured Slider */
(function(){
	if (typeof Swiper === "function") {
		var thumbSwiper = new Swiper(".hm-thumb-swiper", {
			spaceBetween: 10,
			slidesPerView: 2,
			watchSlidesProgress: true,
			breakpoints: {
				// when window width is >= 320px
				320: {
				  slidesPerView: 3,
				},
				// when window width is >= 480px
				480: {
				  slidesPerView: 4,
				},
				// when window width is >= 640px
				640: {
				  slidesPerView: 5,
				}
			},
			containerModifierClass: 'hm-swiper-',
			noSwipingClass: 'hm-swiper-no-swiping',
			slideActiveClass: 'hm-swiper-slide-active',
			slideBlankClass: 'hm-swiper-slide-invisible-blank',
			slideClass: 'hm-swiper-slide',
			slideDuplicateActiveClass: 'hm-swiper-slide-duplicate-active',
			slideDuplicateClass: 'hm-swiper-slide-duplicate',
			slideDuplicateNextClass: 'hm-swiper-slide-duplicate-next',
			slideDuplicatePrevClass: 'hm-swiper-slide-duplicate-prev',
			slideNextClass: 'hm-swiper-slide-next',
			slidePrevClass: 'hm-swiper-slide-prev',
			slideThumbActiveClass: 'swiper-slide-thumb-active',
			slideVisibleClass: 'hm-swiper-slide-visible',
			wrapperClass: 'hm-swiper-wrapper',
		});
		var swiper = new Swiper(".hm-slider", {
			speed: 500,
			autoplay: {
				delay: 6000,
				disableOnInteraction: false,
				pauseOnMouseEnter: true
			},
			navigation: {
			  nextEl: '.hm-swiper-button-next',
			  prevEl: '.hm-swiper-button-prev',
			},
			thumbs: {
				swiper: thumbSwiper,
			},
			containerModifierClass: 'hm-swiper-',
			noSwipingClass: 'hm-swiper-no-swiping',
			slideActiveClass: 'hm-swiper-slide-active',
			slideBlankClass: 'hm-swiper-slide-invisible-blank',
			slideClass: 'hm-swiper-slide',
			slideDuplicateActiveClass: 'hm-swiper-slide-duplicate-active',
			slideDuplicateClass: 'hm-swiper-slide-duplicate',
			slideDuplicateNextClass: 'hm-swiper-slide-duplicate-next',
			slideDuplicatePrevClass: 'hm-swiper-slide-duplicate-prev',
			slideNextClass: 'hm-swiper-slide-next',
			slidePrevClass: 'hm-swiper-slide-prev',
			slideVisibleClass: 'hm-swiper-slide-visible',
			wrapperClass: 'hm-swiper-wrapper'
		});
	}
})();