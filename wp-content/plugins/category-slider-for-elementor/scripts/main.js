function miga_category_slider_init() {
	var spv = document.querySelector(".miga_category_slider__container").getAttribute("data-spv") || 4;
	var spvt = document.querySelector(".miga_category_slider__container").getAttribute("data-spvt") || 2;
	var spvp = document.querySelector(".miga_category_slider__container").getAttribute("data-spvp") || 1;
	var loop = document.querySelector(".miga_category_slider__container").getAttribute("data-loop") || false;
	var autoplayEnabled = document.querySelector(".miga_category_slider__container").getAttribute("data-autoplay") || false;
	var autoplayDelay = document.querySelector(".miga_category_slider__container").getAttribute("data-autoplay-delay") || 2000;
	var spaceBetween = document.querySelector(".miga_category_slider__container").getAttribute("data-spacebetween") || 30;
	var autoplay = false;
	if (autoplayEnabled == 1 && autoplayDelay > 0) {
		autoplay = {
			delay: autoplayDelay,
			disableOnInteraction: false,
			pauseOnMouseEnter: true,
		}
	}
	const swiper = new Swiper('.miga_category_slider__container.swiper', {
		slidesPerView: spvp,
		spaceBetween: parseInt(spaceBetween),
		loop: parseInt(loop),
		autoplay: autoplay,
		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
		breakpoints: {
			767: {
				slidesPerView: spvt,
				spaceBetween: parseInt(spaceBetween)
			},
			1024: {
				slidesPerView: spv,
				spaceBetween: parseInt(spaceBetween)
			}
		}
	});

	if (autoplayEnabled == 1 && autoplayDelay > 0) {
		jQuery(".miga_category_slider__container").hover(function() {
			(this).swiper.autoplay.stop();
		}, function() {
			(this).swiper.autoplay.start();
		});
	}
}

jQuery(document).ready(function($) {
	if (document.querySelectorAll(".miga_category_slider__container").length > 0) {
		// just start it when there is one
		miga_category_slider_init();
	}
});
