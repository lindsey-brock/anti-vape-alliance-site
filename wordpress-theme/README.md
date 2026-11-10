# Anti-Vaping Alliance — WordPress theme

A complete, lightweight WordPress theme (`ava-alliance/`) that matches the approved AVA design. It is built for GoDaddy Managed WordPress and needs no page builder and no required plugins.

## 1. Get the installable zip

GitHub builds the zip automatically every time the theme changes:

- **Releases** (right-hand side of the repository page) → latest **AVA WordPress theme** → download **`ava-alliance.zip`**. Releases are published from the `main` branch.
- Or **Actions** → latest **Build WordPress theme** run → **Artifacts**. GitHub wraps artifacts in an extra zip, so unzip it once to get `ava-alliance.zip`.

## 2. Install on GoDaddy

1. In GoDaddy, open the WordPress site and go to **WP Admin**.
2. Go to **Appearance → Themes → Add New → Upload Theme**, choose `ava-alliance.zip`, then click **Install Now → Activate**.
3. On first activation the theme creates the homepage cards, curriculum items, resource links and the main menu. It never overwrites content that already exists.
4. Go to **Settings → General**: Site Title = `Anti-Vaping Alliance`, Tagline = `Student Founded & Led`. The tagline is used in the homepage browser title.
5. Go to **Settings → Permalinks** and choose **Post name**.

### Turn on HTTPS

HTTPS is a hosting setting, not part of the theme.

1. In the GoDaddy dashboard, open the site → **Settings / Security** → **SSL**. Managed WordPress plans include a free certificate. Turn it on and wait for it to show **Active**.
2. In WP Admin → **Settings → General**, make sure both **WordPress Address** and **Site Address** start with `https://`.
3. Visit the `http://` address and confirm it redirects to `https://`. If it doesn't, enable "Force HTTPS" in GoDaddy (or install the *Really Simple SSL* plugin).

## 3. After installing: the five-minute setup

| Do this | Where |
|---|---|
| Upload the three curriculum PDFs | **Media → Add New** |
| Attach each PDF to its card (the button stays hidden until a file is chosen) | **AVA Content → Curriculum** → edit item → *Choose file from Media Library* |
| Check the contact email and the notification email | **Appearance → Customize → AVA Site Content → Contact / Form notifications** |
| Install an SMTP plugin so notification emails arrive (recommended on GoDaddy) | **Plugins → Add New → "WP Mail SMTP"** |
| Create a Privacy Policy page and add it to the footer | **Settings → Privacy**, then **Appearance → Menus** → *Footer links* |
| Add a sharing image (optional; a branded default is included) | **Customize → AVA Site Content → SEO & Social Sharing** |

The **AVA Content → Start here** screen in WP Admin lists where every part of the site is edited.

## 4. Where the team edits content

Nothing the team updates regularly is hard-coded:

- **Homepage text and buttons** (hero, "By students" band, mission intro, quote, Get Involved, contact details, pledge text), with live preview: *Appearance → Customize → AVA Site Content*. Each section can also be hidden there.
- **Cards and lists**: *AVA Content → Mission Points / Programs / Curriculum / Resources*. Use the **Order** box to sort them, lowest number first.
- **Menus**: *Appearance → Menus*. Links like `#mission` jump to that homepage section from any page. Any link to `#pledge` opens the pledge pop-up.
- **Logo and browser icon**: *Customize → Site Identity*.
- **Hero background photo**: *Customize → AVA Site Content → Hero*. Choose any photo from the Media Library, set which side stays in view on phones, and make the dark overlay lighter or stronger if the text gets hard to read.
- **Scrolling effects**: *Customize → AVA Site Content → Top Bar, Header & Scrolling* turns the stacked-section scroll and the fade-in animations on or off. The word-by-word quote effect is switched on or off under *The AVA Difference*.
- **Extra pages** (for example *Parent & Community*): *Pages → Add New*, built with the normal block editor in the AVA colors and fonts.

## 5. Forms (pledge and contact)

- The built-in forms work out of the box. Spam protection uses a hidden honeypot field, a signed timestamp that rejects instant bot posts, and a limit of 5 submissions per visitor every 10 minutes. All input is cleaned and length-limited, and redirects are restricted to the site.
- The forms keep working with GoDaddy's page caching and without JavaScript.
- Submissions are stored privately. Only administrators can see them under **AVA Content → Pledges / Messages**, and each list has a **Download CSV** link.
- WordPress's **Tools → Export/Erase Personal Data** covers these submissions, and suggested privacy-policy wording is added under *Settings → Privacy*.
- To use a form plugin instead (WPForms, Fluent Forms, Contact Form 7…), set *Customize → AVA Site Content → Pledge pop-up* (or *Contact*) → **Form plugin shortcode** and paste the shortcode.

> **Children's privacy:** The pledge is aimed at students in grades 4–6, many of them under 13. In the US, COPPA restricts collecting personal information (such as full names and email addresses) online from children under 13 without verifiable parental consent. The pledge form can collect less: under *Customize → Pledge pop-up*, set **Last name field** to *Last initial only* (or *Do not ask*) and untick **Ask for an email address**. Have AVA's leadership or advisor decide on this.

## 6. SEO and sharing

- The browser title is the page name followed by the site name. The homepage uses the site name and the tagline.
- The theme adds a meta description, canonical link, Open Graph and Twitter Card tags, and Organization structured data.
- **Page descriptions** come from each page's *Excerpt* field. The homepage description is set in *Customize → SEO & Social Sharing*.
- If an SEO plugin (Yoast, Rank Math, SEOPress, AIOSEO, The SEO Framework) is active, the theme stops adding these tags so they aren't duplicated.

## 7. Acceptance checklist: how it's met

| Requirement | How |
|---|---|
| Student-founded / student-led / nonprofit identity above the fold | Top bar message and hero intro (both editable); the hero pill says "Join the Movement" |
| Visual hierarchy matches the preview | Same layout, colors and fonts as the approved design |
| Navigation on desktop and mobile | WordPress menu, with an accessible hamburger button under 900px |
| Take the Pledge opens and closes | Header button and any `#pledge` link. Closes with ×, the Esc key or a click outside. Keyboard focus stays inside the pop-up and returns afterwards |
| `#pledge` share URL works | `yoursite.org/#pledge` opens the pop-up on load, and the browser Back button closes it |
| Curriculum and resource links work and are editable | Media Library PDFs or links in *AVA Content* |
| Removed content stays absent | No abstinence question, no event poster, no Students Reached / School Partners / Active Chapters counters, no numerical impact claims |
| Responsive and accessible | Skip link, labelled landmarks and forms, visible focus, contrast-checked colors, reduced-motion support |
| Lightweight | Fonts and assets are local (no Google or other third-party requests), the whole homepage is about 190 KB, JavaScript without jQuery, emoji script removed |
| Complete WordPress implementation | Valid `style.css` header, `functions.php`, `header.php`, `footer.php`, `front-page.php`, `page.php`, `index.php`, `404.php`, `screenshot.png` |

**Tested:** WordPress 6.9 on PHP 8.3, in Chromium at desktop and phone sizes. Test the site in Safari, Firefox and Edge on real devices before sign-off.
