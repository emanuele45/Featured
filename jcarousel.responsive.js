/*! jCarousel - v0.3.1 - 2014-04-26
* http://sorgalla.com/jcarousel
* Copyright (c) 2014 Jan Sorgalla; Licensed MIT */

(function($) {
	$(function() {
		var jcarousel = $('.jcarousel');

		jcarousel
			.on('jcarousel:reload jcarousel:create', function () {
				var width = jcarousel.innerWidth();
				width = width - 120;

				jcarousel.jcarousel('items').css('width', width + 'px');
			})
			.jcarousel({
				wrap: 'circular'
			})
			.jcarouselAutoscroll({
				interval: 5000,
				target: '+=1',
				autostart: true
			});

		$('.jcarousel-control-prev')
			.jcarouselControl({
				target: '-=1'
			});

		$('.jcarousel-control-next')
			.jcarouselControl({
				target: '+=1'
			});

		$('.jcarousel-pagination')
			.on('jcarouselpagination:active', 'a', function() {
				$(this).addClass('active');
			})
			.on('jcarouselpagination:inactive', 'a', function() {
				$(this).removeClass('active');
			})
			.on('click', function(e) {
				e.preventDefault();
			})
			.jcarouselPagination({
				perPage: 1,
				item: function(page) {
					return '<a href="#' + page + '">' + page + '</a>';
				}
			});
	});
})(jQuery);
