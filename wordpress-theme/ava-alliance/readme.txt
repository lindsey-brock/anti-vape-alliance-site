=== Anti-Vaping Alliance ===
Requires at least: 6.3
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.6.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Lightweight, accessible theme for the Anti-Vaping Alliance, a student-founded and student-led nonprofit.

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
Logo and images: (c) Anti-Vaping Alliance.

== Changelog ==

= 1.6.1 =
* Curriculum downloads without a file yet keep their button, shown greyed out with "Coming soon".

= 1.6.0 =
* Includes the Lesson Pack PDF ("Be the Boss of Your Choices!", Student Worksheet 1). On first activation it is added to the Media Library and attached to the Lesson Pack card.

= 1.5.2 =
* Extra space under the footer text so the pledge counter never covers it; pop-ups pause while the footer is on screen.

= 1.5.1 =
* The contact section is now labelled "Get Involved", and the Get Involved menu link goes there.

= 1.5.0 =
* Removed the orange Get Involved band; each Program card now has its own button (editable per program). The Get Involved menu link jumps to Programs.

= 1.4.3 =
* The Mission card now pins with the quote block at the bottom of the screen while the quote lights up; no extra dark space under the quote.

= 1.4.2 =
* Less empty space above the AVA Difference quote.

= 1.4.1 =
* Square-cornered stacking sections; Mission and its quote now read as one card, joined by an orange line instead of a shadow.

= 1.4.0 =
* Pledge counter and "just took the pledge" pop-ups (bottom-left) using real pledges; first names shown only for signers who opt in. Settings in Customize > AVA Site Content > Pledge counter.

= 1.3.0 =
* The AVA Difference quote is now attached to the end of the Mission card and brightens word by word on scroll; the next section waits until it finishes (Customize > AVA Site Content > The AVA Difference).

= 1.2.0 =
* Removed the "By students, for students" band; its heading and text are now the Mission section heading and intro.

= 1.1.2 =
* Hero pill now reads "Join the Movement".

= 1.1.1 =
* Hero pill now reads "A movement led by young people".

= 1.1.0 =
* Renamed to Anti-Vaping Alliance.
* Hero is now a full-width photo with a dark gradient (photo, focus point and overlay strength editable in the Customizer) and shorter copy.
* Stacked-section scrolling and fade-in animations on the homepage (can be switched off in Customize > AVA Site Content > Top Bar, Header & Scrolling).

= 1.0.0 =
* Initial release.
