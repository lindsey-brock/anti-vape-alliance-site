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
| Check the Lesson Pack PDF. *Be the Boss of Your Choices!* (Student Worksheet 1) is included and attached automatically when the theme is activated | **Media** / **AVA Content → Curriculum** |
| Upload the Teacher Notes and Parent Handout PDFs when they're ready | **Media → Add New** |
| Attach each PDF to its card (until then its button shows "Coming soon") | **AVA Content → Curriculum** → edit item → *Choose file from Media Library* |
| Check the contact email and the notification email | **Appearance → Customize → AVA Site Content → Contact / Form notifications** |
| Install an SMTP plugin so notification emails arrive (recommended on GoDaddy) | **Plugins → Add New → "WP Mail SMTP"** |
| Create a Privacy Policy page and add it to the footer | **Settings → Privacy**, then **Appearance → Menus** → *Footer links* |
| Add a sharing image (optional; a branded default is included) | **Customize → AVA Site Content → SEO & Social Sharing** |

The **AVA Content → Start here** screen in WP Admin lists where every part of the site is edited.

## 4. Where the team edits content

Nothing the team updates regularly is hard-coded:

- **Homepage text and buttons** (hero, mission heading and intro, quote, contact details, pledge text), with live preview: *Appearance → Customize → AVA Site Content*. Each section can also be hidden there.
- **Cards and lists**: *AVA Content → Mission Points / Programs / Curriculum / Resources*. Each program can have a button under it (e.g. *Bring AVA to Your School*, *Start a Chapter*, *Support AVA*). Set its label and link in the **Button under this card** box when editing the program. Use the **Order** box to sort them, lowest number first.
- **Menus**: *Appearance → Menus*. Links like `#mission` jump to that homepage section from any page. Any link to `#pledge` opens the pledge pop-up.
- **Logo and browser icon**: *Customize → Site Identity*.
- **Hero background photo**: *Customize → AVA Site Content → Hero*. Choose any photo from the Media Library, set which side stays in view on phones, and make the dark overlay lighter or stronger if the text gets hard to read.
- **Scrolling effects**: *Customize → AVA Site Content → Top Bar, Header & Scrolling* turns the stacked-section scroll and the fade-in animations on or off. The word-by-word quote effect is switched on or off under *The AVA Difference*.
- **Extra pages** (for example *Parent & Community*): *Pages → Add New*, built with the normal block editor in the AVA colors and fonts.

## 4b. Blog

- The **"From the Blog"** strip under the Resources links shows the newest posts, 3 by default. A **Blog** page (`/blog/`) lists all of them.
- **Writing a post:** *Posts → Add New*. Set a **Featured image**, which becomes the card photo; without one the card shows an orange AVA placeholder. Fill in the **Excerpt**, which is the card's summary, and pick a **Category** (Facts, Tips, For Families, …).
- **Starter posts:** on first activation the theme creates three posts, all fact-based with no statistics. Please have AVA's team read them, then edit or replace them:
  - *Myth vs. Fact: What's Really in a Vape?*, with an image
  - *5 Ways to Say No to Vaping (Without Making It Awkward)*, with a photo
  - *Talking With Your Child About Vaping: A Starter Guide for Families*
- On a fresh install it also sets **Settings → Reading** to a static homepage ("Home") with "Blog" as the posts page, and removes WordPress's untouched sample "Hello world!" post.
- Change the heading, the number of posts or the "See all posts" text, or hide the strip, under *Customize → AVA Site Content → Resources*.

## 5. Forms (pledge and contact)

- The built-in forms work out of the box. Spam protection uses a hidden honeypot field, a signed timestamp that rejects instant bot posts, and a limit of 5 submissions per visitor every 10 minutes. All input is cleaned and length-limited, and redirects are restricted to the site.
- The forms keep working with GoDaddy's page caching and without JavaScript.
- Submissions are stored privately. Only administrators can see them under **AVA Content → Pledges / Messages**, and each list has a **Download CSV** link.
- WordPress's **Tools → Export/Erase Personal Data** covers these submissions, and suggested privacy-policy wording is added under *Settings → Privacy*.
- To use a form plugin instead (WPForms, Fluent Forms, Contact Form 7…), set *Customize → AVA Site Content → Pledge pop-up* (or *Contact*) → **Form plugin shortcode** and paste the shortcode.

> **Children's privacy:** The pledge is aimed at students in grades 4–6, many of them under 13. In the US, COPPA restricts collecting personal information (such as full names and email addresses) online from children under 13 without verifiable parental consent. The pledge form can collect less: under *Customize → Pledge pop-up*, set **Last name field** to *Last initial only* (or *Do not ask*) and untick **Ask for an email address**. Have AVA's leadership or advisor decide on this.

## 5b. Pledge counter and "just took the pledge" pop-ups

- A small counter ("312 pledges signed") and occasional pop-ups ("Maya just took the pledge · 2 min ago") appear in the bottom-left corner. On the homepage they wait until visitors scroll past the hero buttons.
- **Everything shown is real.** The count is the number of pledges in *AVA Content → Pledges*, and it updates about once a minute.
- **Pop-ups show first names only, and only for signers who tick** "Show my first name in the 'just took the pledge' pop-up" on the pledge form. That box is unticked by default. Last names and emails are never shown. Only signers from the last 30 days appear, and each visitor sees at most 5 pop-ups per visit. The × closes them for the rest of the visit.
- Settings are in *Customize → AVA Site Content → Pledge counter & "just signed" pop-ups*:
  - turn the counter or the pop-ups on or off;
  - change the text after the number;
  - **hide the counter until there are at least N pledges** (25 by default), so the site never says "3 pledges signed";
  - **add pledges collected outside the website**, for example paper pledges at school events.
- In the GitHub Pages / Netlify design preview, curriculum buttons open a **view-only preview** of the PDF: watermarked page images, with no file to download. The real PDF only downloads from the live WordPress site.
- The GitHub Pages / Netlify design preview has no live data, so it shows **sample** signers and a sample count that ticks up. Every pop-up there is labelled "· sample".

## 6. SEO and sharing

- The browser title is the page name followed by the site name. The homepage uses the site name and the tagline.
- The theme adds a meta description, canonical link, Open Graph and Twitter Card tags, and Organization structured data.
- **Page descriptions** come from each page's *Excerpt* field. The homepage description is set in *Customize → SEO & Social Sharing*.
- If an SEO plugin (Yoast, Rank Math, SEOPress, AIOSEO, The SEO Framework) is active, the theme stops adding these tags so they aren't duplicated.

## 7. Acceptance checklist: how it's met

| Requirement | How |
|---|---|
| Student-founded / student-led / nonprofit identity above the fold | Hero intro and pill, plus an optional top bar above the menu (all editable in the Customizer). **The top bar is now off by default, so make sure the hero intro mentions "student-founded, student-led nonprofit".** |
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
