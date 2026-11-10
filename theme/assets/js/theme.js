/* Anti-Vape Alliance theme: mobile menu, pledge pop-up, AJAX forms. No dependencies. */
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
})();
