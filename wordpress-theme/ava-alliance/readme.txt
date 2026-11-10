=== Anti-Vape Alliance ===
Requires at least: 6.3
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Lightweight, accessible theme for the Anti-Vape Alliance, a student-founded and student-led nonprofit.

== Where to edit things ==

* Homepage text, buttons, pledge pop-up, contact details, SEO: Appearance > Customize > AVA Site Content
* Logo and browser icon: Appearance > Customize > Site Identity
* Header and footer links: Appearance > Menus (locations "Main menu" and "Footer links")
* Mission cards, Programs, Curriculum downloads, Resources: AVA Content menu (use "Order" to sort)
* PDFs and images: Media > Add New, then pick them in AVA Content > Curriculum
* Other pages (e.g. Parent & Community, Privacy Policy): Pages
* Signed pledges and contact messages (admins only, CSV download): AVA Content > Pledges / Messages

Any link to #pledge opens the pledge pop-up; yoursite.org/#pledge opens it directly.

== Forms ==

The built-in pledge and contact forms are protected by a honeypot, a signed
timestamp and rate limiting, store submissions privately and work with page
caching. To use a form plugin (WPForms, Fluent Forms, Contact Form 7…) instead,
paste its shortcode under Customize > AVA Site Content > Pledge pop-up / Contact.
For reliable notification emails on GoDaddy, install an SMTP plugin such as WP Mail SMTP.

== SEO ==

Meta description, Open Graph / Twitter tags and Organization structured data
are built in. They switch off automatically if Yoast, Rank Math, SEOPress,
AIOSEO or The SEO Framework is active.

== Credits ==

Montserrat and Nunito Sans fonts: SIL Open Font License 1.1 (assets/fonts).
Logo and images: (c) Anti-Vape Alliance.

== Changelog ==

= 1.1.0 =
* Stacked-section scrolling and fade-in animations on the homepage (can be switched off in Customize > AVA Site Content > Top Bar, Header & Scrolling).

= 1.0.0 =
* Initial release.
