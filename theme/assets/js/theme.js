/* Anti-Vaping Alliance theme: mobile menu, pledge pop-up, AJAX forms. No dependencies. */
(function () {
	'use strict';

	/* ---------- Mobile menu ---------- */
	var toggle = document.querySelector('.menu-toggle');
	var nav = document.getElementById('site-navigation');
	function setMenu(open) {
		if (!toggle || !nav) { return; }
		toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		nav.classList.toggle('open', open);
	}
	if (toggle && nav) {
		toggle.addEventListener('click', function () {
			setMenu(toggle.getAttribute('aria-expanded') !== 'true');
		});
		nav.addEventListener('click', function (e) {
			if (e.target.closest('a')) { setMenu(false); }
		});
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && nav.classList.contains('open')) { setMenu(false); toggle.focus(); }
		});
		window.addEventListener('resize', function () {
			if (window.innerWidth > 900) { setMenu(false); }
		});
	}

	/* ---------- Pledge pop-up (#pledge) ---------- */
	var modal = document.getElementById('pledge-modal');
	var lastFocus = null;
	var focusable = 'a[href],button:not([disabled]),input:not([disabled]):not([type=hidden]),select,textarea,[tabindex]:not([tabindex="-1"])';

	function isPledgeLink(a) {
		var href = a.getAttribute('href') || '';
		if (href === '#pledge') { return true; }
		try {
			var u = new URL(a.href, location.href);
			return u.hash === '#pledge' && u.origin === location.origin && u.pathname === location.pathname;
		} catch (err) { return false; }
	}

	function openPledge(push) {
		if (!modal) { return; }
		lastFocus = document.activeElement;
		modal.hidden = false;
		modal.classList.add('show');
		document.body.classList.add('ava-modal-open');
		if (push !== false && location.hash !== '#pledge') {
			history.pushState(null, '', '#pledge');
		}
		var first = modal.querySelector('input:not([type=hidden]):not([tabindex="-1"])') || modal.querySelector('.close');
		setTimeout(function () { if (first) { first.focus(); } }, 30);
	}

	function closePledge() {
		if (!modal || !modal.classList.contains('show')) { return; }
		modal.classList.remove('show');
		modal.hidden = true;
		document.body.classList.remove('ava-modal-open');
		if (location.hash === '#pledge') {
			history.replaceState(null, '', location.pathname + location.search);
		}
		if (lastFocus && lastFocus.focus) { lastFocus.focus(); }
	}

	if (modal) {
		document.addEventListener('click', function (e) {
			var a = e.target.closest('a');
			if (a && isPledgeLink(a)) {
				e.preventDefault();
				setMenu(false);
				openPledge();
			}
		});
		modal.addEventListener('click', function (e) {
			if (e.target === modal || e.target.closest('[data-ava-close]')) { closePledge(); }
		});
		document.addEventListener('keydown', function (e) {
			if (!modal.classList.contains('show')) { return; }
			if (e.key === 'Escape') { closePledge(); return; }
			if (e.key === 'Tab') {
				var items = Array.prototype.filter.call(modal.querySelectorAll(focusable), function (el) {
					return el.offsetParent !== null;
				});
				if (!items.length) { return; }
				var first = items[0], last = items[items.length - 1];
				if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
				else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
			}
		});
		window.addEventListener('hashchange', function () {
			if (location.hash === '#pledge') { openPledge(false); } else { closePledge(); }
		});
		window.addEventListener('popstate', function () {
			if (location.hash !== '#pledge') { closePledge(); }
		});
		if (location.hash === '#pledge') { openPledge(false); }
	}

	/* ---------- Forms: submit without reloading (falls back to normal post) ---------- */
	var cfg = window.AVA || {};
	Array.prototype.forEach.call(document.querySelectorAll('form[data-ava-form]'), function (form) {
		form.addEventListener('submit', function (e) {
			if (!window.fetch || !window.FormData || !cfg.ajaxUrl) { return; }
			e.preventDefault();
			var wrap = form.closest('.ava-form-wrap');
			var ok = wrap.querySelector('.ava-notice.success');
			var bad = wrap.querySelector('.ava-notice.error');
			bad.hidden = true;

			// Native validation messages, shown accessibly.
			if (!form.checkValidity()) {
				var invalid = form.querySelector(':invalid');
				bad.textContent = invalid && invalid.validationMessage ? invalid.validationMessage : cfg.errorMsg;
				bad.hidden = false;
				if (invalid) { invalid.focus(); }
				return;
			}

			var btn = form.querySelector('button[type=submit]');
			btn.disabled = true;
			fetch(cfg.ajaxUrl, { method: 'POST', body: new FormData(form), credentials: 'same-origin' })
				.then(function (r) { return r.json().catch(function () { return { success: false }; }); })
				.then(function (res) {
					if (res && res.success) {
						ok.textContent = res.data && res.data.message ? res.data.message : ok.textContent;
						ok.hidden = false;
						form.remove();
						ok.setAttribute('tabindex', '-1');
						ok.focus();
					} else {
						bad.textContent = res && res.data && res.data.message ? res.data.message : cfg.errorMsg;
						bad.hidden = false;
						btn.disabled = false;
					}
				})
				.catch(function () {
					bad.textContent = cfg.errorMsg;
					bad.hidden = false;
					btn.disabled = false;
				});
		});
	});

	/* ---------- Stacked sections + fade-in ---------- */
	var root = document.documentElement;
	var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var header = document.querySelector('.ava-header');
	var panels = Array.prototype.slice.call(document.querySelectorAll('.ava-panel, .ava-last'));
	var stackOn = document.body.classList.contains('ava-stack') && !reduce && panels.length > 1 &&
		window.CSS && CSS.supports && CSS.supports('position', 'sticky');
	var flowTop = {};

	function headerHeight() {
		if (!header) { return 0; }
		var top = parseFloat(getComputedStyle(header).top) || 0;
		return header.offsetHeight + top;
	}

	// Sticky sections pin as soon as they reach the header. A section taller than
	// the screen gets a negative offset so it scrolls through its own content
	// first; and because pinned sections report their pinned position, each
	// section's place in the page is rebuilt from the heights before it.
	function layoutStack() {
		var hdr = headerHeight();
		var vh = window.innerHeight;
		root.style.setProperty('--ava-hdr', hdr + 'px');
		var main = document.getElementById('main');
		var y = main ? main.getBoundingClientRect().top + window.pageYOffset : 0;
		panels.forEach(function (el, i) {
			el.style.zIndex = i + 1;
			if (el.classList.contains('ava-panel')) {
				el.style.top = Math.min(hdr, vh - el.offsetHeight) + 'px';
			}
			if (el.id) { flowTop[el.id] = y; }
			y += el.offsetHeight;
		});
	}

	function scrollToSection(id, smooth) {
		if (flowTop[id] === undefined) { return false; }
		window.scrollTo({ top: Math.max(0, flowTop[id] - headerHeight()), behavior: smooth ? 'smooth' : 'auto' });
		return true;
	}

	if (stackOn) {
		root.classList.add('ava-stack-on');
		layoutStack();
		var queued = false;
		var relayout = function () {
			if (queued) { return; }
			queued = true;
			requestAnimationFrame(function () { queued = false; layoutStack(); });
		};
		window.addEventListener('resize', relayout);
		window.addEventListener('load', function () {
			layoutStack();
			var id = location.hash.slice(1);
			if (id && id !== 'pledge') { scrollToSection(id, false); }
		});
		if (window.ResizeObserver) {
			var ro = new ResizeObserver(relayout);
			panels.forEach(function (el) { ro.observe(el); });
		}
		if (document.fonts && document.fonts.ready) { document.fonts.ready.then(relayout); }

		// In-page links (#mission, #contact…) jump to the section's real position.
		document.addEventListener('click', function (e) {
			var a = e.target.closest('a[href*="#"]');
			if (!a || e.defaultPrevented) { return; }
			var u;
			try { u = new URL(a.href, location.href); } catch (err) { return; }
			if (u.origin !== location.origin || u.pathname !== location.pathname) { return; }
			var id = u.hash.slice(1);
			if (!id || id === 'pledge' || id === 'main') { return; }
			if (scrollToSection(id, true)) {
				e.preventDefault();
				history.pushState(null, '', '#' + id);
				var target = document.getElementById(id);
				if (target) { target.setAttribute('tabindex', '-1'); target.focus({ preventScroll: true }); }
			}
		});
	}

	if (document.body.classList.contains('ava-reveal') && !reduce && 'IntersectionObserver' in window) {
		var items = document.querySelectorAll('.ava-section .eyebrow, .ava-section h2, .ava-section .sub, .ava-section .card, .ava-section .resource, .ava-section .quote, .ava-section .buttons');
		if (items.length) {
			root.classList.add('ava-motion');
			var io = new IntersectionObserver(function (entries) {
				entries.forEach(function (en) {
					if (en.isIntersecting) { en.target.classList.add('visible'); io.unobserve(en.target); }
				});
			}, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
			Array.prototype.forEach.call(items, function (el, i) {
				el.classList.add('reveal');
				// Stagger cards in the same row.
				if (el.matches('.card, .resource')) {
					var sibs = Array.prototype.indexOf.call(el.parentNode.children, el);
					el.style.transitionDelay = (sibs % 3) * 90 + 'ms';
				}
				io.observe(el);
			});
		}
	}
})();
