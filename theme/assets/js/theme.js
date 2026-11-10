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
						if (form.querySelector('[name="ava_type"]').value === 'pledge') {
							document.dispatchEvent(new CustomEvent('ava:pledged'));
						}
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

	/* ---------- Mission carousel: number tabs + progress bar ---------- */
	Array.prototype.forEach.call(document.querySelectorAll('.ava-carousel'), function (car) {
		var tabs = Array.prototype.slice.call(car.querySelectorAll('[role="tab"]'));
		var cpanels = Array.prototype.slice.call(car.querySelectorAll('[role="tabpanel"]'));
		if (tabs.length < 2) { return; }
		var pauseBtn = car.querySelector('.ava-carousel-pause');
		var auto = car.hasAttribute('data-autoplay') && !reduce;
		var idx = 0, paused = false, hover = false, focusIn = false, inView = true;
		car.style.setProperty('--ava-ctabs', tabs.length);
		car.style.setProperty('--ava-cdur', (parseInt(car.getAttribute('data-interval'), 10) || 7) + 's');
		car.classList.add('is-ready');
		if (auto) { car.classList.add('is-auto'); }

		function hold() { car.classList.toggle('is-held', hover || focusIn || !inView); }
		function select(i, focus) {
			idx = (i + tabs.length) % tabs.length;
			tabs.forEach(function (t, n) {
				var on = n === idx;
				t.setAttribute('aria-selected', on ? 'true' : 'false');
				t.tabIndex = on ? 0 : -1;
				cpanels[n].classList.toggle('is-active', on);
			});
			if (focus) { tabs[idx].focus(); }
			// Restart the active tab's progress bar.
			var fill = tabs[idx].querySelector('.fill');
			if (fill && auto) { fill.style.animation = 'none'; void fill.offsetWidth; fill.style.animation = ''; }
		}
		tabs.forEach(function (t, n) {
			t.addEventListener('click', function () { select(n); });
			t.addEventListener('keydown', function (e) {
				var k = e.key, to = null;
				if (k === 'ArrowRight' || k === 'ArrowDown') { to = idx + 1; }
				else if (k === 'ArrowLeft' || k === 'ArrowUp') { to = idx - 1; }
				else if (k === 'Home') { to = 0; }
				else if (k === 'End') { to = tabs.length - 1; }
				if (to !== null) { e.preventDefault(); select(to, true); }
			});
			var fill = t.querySelector('.fill');
			if (fill) {
				fill.addEventListener('animationend', function () {
					if (auto && n === idx) { select(idx + 1); }
				});
			}
		});
		if (!auto) { return; }
		// Hovering with a mouse holds the bar (a tap on a phone doesn't).
		car.addEventListener('pointerenter', function (e) { if (e.pointerType === 'mouse') { hover = true; hold(); } });
		car.addEventListener('pointerleave', function (e) { if (e.pointerType === 'mouse') { hover = false; hold(); } });
		// Keyboard focus holds the bar; a mouse click on a tab doesn't.
		car.addEventListener('focusin', function (e) { var fv = true; try { fv = e.target.matches(':focus-visible'); } catch (err) {} focusIn = fv; hold(); });
		car.addEventListener('focusout', function (e) { if (!car.contains(e.relatedTarget)) { focusIn = false; hold(); } });
		if ('IntersectionObserver' in window) {
			new IntersectionObserver(function (en) { inView = en[0].isIntersecting; hold(); }, { threshold: 0.2 }).observe(car);
		}
		document.addEventListener('visibilitychange', function () { inView = !document.hidden; hold(); });
		if (pauseBtn) {
			var lbl = pauseBtn.querySelector('.lbl');
			pauseBtn.hidden = false;
			pauseBtn.addEventListener('click', function () {
				paused = !paused;
				car.classList.toggle('is-paused', paused);
				lbl.textContent = lbl.getAttribute(paused ? 'data-play' : 'data-pause');
			});
		}
	});
	var header = document.querySelector('.ava-header');
	var panels = Array.prototype.slice.call(document.querySelectorAll('.ava-panel, .ava-last'));
	var stackOn = document.body.classList.contains('ava-stack') && !reduce && panels.length > 1 &&
		window.CSS && CSS.supports && CSS.supports('position', 'sticky');
	var flowTop = {};

	// Quote scrub: split the quote into words that brighten with scroll progress.
	var scrubSection = document.querySelector('section.ava-scrub');
	var scrubText = scrubSection && scrubSection.querySelector('[data-scrub]');
	var scrubGroup = scrubSection && scrubSection.closest('.ava-group');
	// Needs the stacked layout: the card has to pin for the quote to play out.
	var scrubOn = !!(scrubText && scrubGroup && stackOn && document.body.classList.contains('ava-has-scrub'));
	var scrubRun = null;
	var scrubWords = [];
	if (scrubOn) {
		var words = scrubText.textContent.trim().split(/\s+/);
		var full = scrubText.textContent.trim();
		scrubText.textContent = '';
		var sr = document.createElement('span');
		sr.className = 'screen-reader-text';
		sr.textContent = full;
		scrubText.appendChild(sr);
		words.forEach(function (w, i) {
			var span = document.createElement('span');
			span.className = 'scrub-word';
			span.setAttribute('aria-hidden', 'true');
			span.textContent = w;
			scrubText.appendChild(span);
			if (i < words.length - 1) { scrubText.appendChild(document.createTextNode(' ')); }
			scrubWords.push(span);
		});
		root.classList.add('ava-scrub-on');
		// Invisible runway right after the card: while it scrolls past, the card
		// stays pinned (dark block at the bottom of the screen) and the quote lights up.
		scrubRun = document.createElement('div');
		scrubRun.className = 'ava-scrub-run';
		scrubRun.setAttribute('aria-hidden', 'true');
		scrubGroup.parentNode.insertBefore(scrubRun, scrubGroup.nextSibling);
		var scrubTick = false;
		var updateScrub = function () {
			scrubTick = false;
			var r = scrubRun.getBoundingClientRect();
			var p = (window.innerHeight - r.top) / Math.max(1, r.height);
			p = Math.min(1, Math.max(0, p / 0.85));
			var n = scrubWords.length;
			for (var i = 0; i < n; i++) {
				var v = Math.min(1, Math.max(0, p * n - i));
				scrubWords[i].style.opacity = (0.2 + 0.8 * v).toFixed(3);
			}
		};
		var queueScrub = function () {
			if (!scrubTick) { scrubTick = true; requestAnimationFrame(updateScrub); }
		};
		window.addEventListener('scroll', queueScrub, { passive: true });
		window.addEventListener('resize', queueScrub);
		updateScrub();
	}

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
		var z = 0;
		// Walk everything in <main> (panels and the quote runway) to rebuild flow positions.
		Array.prototype.forEach.call(main ? main.children : [], function (el) {
			if (panels.indexOf(el) !== -1) {
				el.style.zIndex = ++z;
				if (el.classList.contains('ava-panel')) {
					el.style.top = Math.min(hdr, vh - el.offsetHeight) + 'px';
				}
				if (el.id) { flowTop[el.id] = y; }
				// Sections inside a grouped card: position within the card.
				Array.prototype.forEach.call(el.querySelectorAll('section[id]'), function (s) {
					flowTop[s.id] = y + s.offsetTop;
				});
			}
			if (el === scrubRun) {
				// "#difference" lands where the card pins, with the quote ready to play.
				flowTop.difference = y - vh + hdr;
			}
			y += el.offsetHeight;
		});
		// "#involved" links go to the Get Involved (contact) section.
		if (flowTop.involved === undefined && flowTop.contact !== undefined) { flowTop.involved = flowTop.contact; }
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

	/* ---------- Pledge counter + "just took the pledge" pop-ups ---------- */
	(function () {
		var feedCfg = (window.AVA && window.AVA.feed) || {};
		var demo = window.AVA_DEMO_FEED || null; // design preview only
		var box = document.getElementById('ava-proof');
		if (!box || (!feedCfg.url && !demo)) { return; }
		var t = feedCfg.i18n || {};
		var counterEl = box.querySelector('.ava-counter');
		var numEl = box.querySelector('.ava-counter-num');
		var toast = box.querySelector('.ava-toast');
		var msgEl = toast.querySelector('.t-msg');
		var agoEl = toast.querySelector('.t-ago');
		var closeBtn = toast.querySelector('.t-close');
		closeBtn.setAttribute('aria-label', t.dismiss || 'Dismiss');
		var shown = 0, current = 0, seen = {}, queue = [], showing = false, serverSkew = 0;
		var MAX_TOASTS = demo ? 12 : 5;
		var muted = false;
		try { muted = sessionStorage.getItem('avaToastsOff') === '1'; } catch (e) {}

		function fmt(n) { return n.toLocaleString(); }
		function ago(ts) {
			var s = Math.max(0, Math.round(Date.now() / 1000 - serverSkew - ts));
			if (s < 60) { return t.justNow || 'just now'; }
			if (s < 3600) { return (t.min || '%d min ago').replace('%d', Math.round(s / 60)); }
			if (s < 86400) { return (t.hr || '%d hr ago').replace('%d', Math.round(s / 3600)); }
			if (s < 172800) { return t.yday || 'yesterday'; }
			return (t.day || '%d days ago').replace('%d', Math.round(s / 86400));
		}
		function animateTo(target) {
			if (!counterEl) { return; }
			var from = current;
			current = target;
			if (reduce || from === 0) {
				numEl.textContent = fmt(target);
				if (from === 0 && !reduce) {
					// first appearance: quick count-up
					var start = performance.now(), dur = 1100;
					var step = function (now) {
						var k = Math.min(1, (now - start) / dur);
						numEl.textContent = fmt(Math.round(target * (1 - Math.pow(1 - k, 3))));
						if (k < 1) { requestAnimationFrame(step); }
					};
					requestAnimationFrame(step);
				}
				return;
			}
			numEl.textContent = fmt(target);
			counterEl.classList.remove('bump');
			void counterEl.offsetWidth;
			counterEl.classList.add('bump');
		}
		function setCount(n) {
			if (!feedCfg.counter && !demo) { return; }
			if (n < (feedCfg.min || 0) && !demo) { return; }
			counterEl.hidden = false;
			if (n !== current) { animateTo(n); }
		}
		// Keep the corner clear while the hero's buttons are on screen.
		var away = false;
		var heroBtns = document.querySelector('.ava-hero .buttons');
		if (heroBtns) {
			var hero = heroBtns.closest('.ava-hero');
			var nextEl = hero ? hero.nextElementSibling : null;
			var checkAway = function () {
				var br = heroBtns.getBoundingClientRect();
				var onScreen = br.bottom > 0 && br.top < window.innerHeight;
				// With stacked sections the hero stays pinned; it's hidden once the next card covers the buttons.
				var covered = nextEl && nextEl.getBoundingClientRect().top < br.bottom;
				var now = onScreen && !covered;
				if (now !== away) {
					away = now;
					box.classList.toggle('away', away);
					if (!away) { nextToast(); }
				}
			};
			var awayTick = false;
			window.addEventListener('scroll', function () {
				if (!awayTick) { awayTick = true; requestAnimationFrame(function () { awayTick = false; checkAway(); }); }
			}, { passive: true });
			window.addEventListener('resize', checkAway);
			away = false;
			checkAway();
		}
		// Pause pop-ups while the footer is on screen so they never cover it.
		var footerNear = false;
		var footerEl = document.querySelector('.ava-footer');
		if (footerEl && 'IntersectionObserver' in window) {
			new IntersectionObserver(function (entries) {
				footerNear = entries[0].isIntersecting;
				box.classList.toggle('footer-near', footerNear);
				if (!footerNear) { nextToast(); }
			}, { threshold: 0 }).observe(footerEl);
		}
		function nextToast() {
			if (footerNear || away || showing || muted || !queue.length || shown >= MAX_TOASTS) { return; }
			var item = queue.shift();
			showing = true;
			shown++;
			msgEl.innerHTML = '';
			var strong = document.createElement('strong');
			strong.textContent = item.name;
			msgEl.appendChild(strong);
			msgEl.appendChild(document.createTextNode(' ' + (t.signed || 'just took the pledge')));
			agoEl.textContent = ago(item.ts) + (demo ? ' · sample' : '');
			toast.hidden = false;
			requestAnimationFrame(function () { toast.classList.add('show'); });
			setTimeout(function () {
				toast.classList.remove('show');
				setTimeout(function () {
					toast.hidden = true;
					showing = false;
					setTimeout(nextToast, 4000 + Math.random() * 4000);
				}, reduce ? 0 : 400);
			}, 5200);
		}
		function addRecent(list) {
			if (!(feedCfg.toasts || demo)) { return; }
			list.slice().reverse().forEach(function (r) {
				var key = r.name + '|' + r.ts;
				if (seen[key]) { return; }
				seen[key] = true;
				queue.push(r);
			});
			queue.sort(function (a, b) { return b.ts - a.ts; });
			nextToast();
		}
		closeBtn.addEventListener('click', function () {
			muted = true;
			queue = [];
			toast.classList.remove('show');
			toast.hidden = true;
			try { sessionStorage.setItem('avaToastsOff', '1'); } catch (e) {}
		});

		function load() {
			if (demo) {
				setCount(demo.count);
				addRecent(demo.recent);
				return;
			}
			fetch(feedCfg.url, { credentials: 'omit', cache: 'no-store' })
				.then(function (r) { return r.ok ? r.json() : null; })
				.then(function (d) {
					if (!d) { return; }
					serverSkew = Math.round(Date.now() / 1000) - (d.now || Math.round(Date.now() / 1000));
					setCount(d.count || 0);
					addRecent(d.recent || []);
				})
				.catch(function () {});
		}

		setTimeout(load, 2500);
		if (demo) {
			// Preview only: a sample signer every 12–22 seconds.
			var names = demo.names || [];
			var tick = function () {
				demo.count += 1 + Math.floor(Math.random() * 2);
				setCount(demo.count);
				if (names.length) {
					addRecent([{ name: names[Math.floor(Math.random() * names.length)], ts: Math.round(Date.now() / 1000) - serverSkew + Math.random() }]);
				}
				setTimeout(tick, 12000 + Math.random() * 10000);
			};
			setTimeout(tick, 14000);
		} else {
			setInterval(function () { if (!document.hidden) { load(); } }, 60000);
		}

		// Your own pledge counts right away.
		document.addEventListener('ava:pledged', function () {
			if (current) { setCount(current + 1); }
		});
	})();
})();
