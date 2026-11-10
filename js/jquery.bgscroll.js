/*
 * bgscroll — slowly slides an element's background image (same look as before:
 * 1px every `scrollSpeed` ms). Rewritten for smooth scrolling: one shared
 * requestAnimationFrame loop that only runs while an element is on screen
 * (IntersectionObserver), no eval() and no per-element setInterval.
 * Nothing moves for visitors who prefer reduced motion.
 */
(function ($) {
	'use strict';

	var items = [];
	var running = false;
	var last = 0;
	var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	function frame(now) {
		var active = false;
		var dt = last ? Math.min(now - last, 100) : 0;
		last = now;
		for (var i = 0; i < items.length; i++) {
			var it = items[i];
			if (!it.visible) continue;
			active = true;
			it.pos -= dt / it.speed;
			var p = Math.round(it.pos);
			if (p !== it.drawn) {
				it.drawn = p;
				it.el.style.backgroundPosition = it.dir === 'v' ? '0 ' + p + 'px' : (it.dir === 'd' ? p + 'px ' + p + 'px' : p + 'px 0');
			}
		}
		if (active) {
			requestAnimationFrame(frame);
		} else {
			running = false;
			last = 0;
		}
	}

	function start() {
		if (running) return;
		running = true;
		last = 0;
		requestAnimationFrame(frame);
	}

	var observer = 'IntersectionObserver' in window ? new IntersectionObserver(function (entries) {
		entries.forEach(function (entry) {
			if (entry.target.__vkBgScroll) entry.target.__vkBgScroll.visible = entry.isIntersecting;
		});
		start();
	}) : null;

	$.fn.bgscroll = $.fn.bgScroll = function (options) {
		if (!this.length || reduceMotion) return this;
		options = options || {};
		return this.each(function () {
			if (this.__vkBgScroll) return;
			var it = { el: this, speed: options.scrollSpeed || 70, dir: options.direction || 'h', pos: 0, drawn: null, visible: !observer };
			this.__vkBgScroll = it;
			items.push(it);
			if (observer) observer.observe(this); else start();
		});
	};
})(jQuery);
