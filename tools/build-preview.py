#!/usr/bin/env python3
"""Turn the WordPress theme's rendered homepage into a static preview.

The preview (index.html + theme/) is served by GitHub Pages and Netlify so
people can see the design without a WordPress install. Forms show a
"preview only" message instead of sending.

Usage:
    python3 tools/build-preview.py <rendered-homepage.html> <wordpress-origin>
    e.g. python3 tools/build-preview.py snap.html http://localhost:8890

Run from the repository root. The page is fetched from a local WordPress
install with the theme active and the visitor logged out.
"""
import re
import shutil
import sys
from pathlib import Path

PAGES_URL = "https://lindsey-brock.github.io/anti-vape-alliance-site/"

src, origin = Path(sys.argv[1]), sys.argv[2].rstrip("/")
root = Path.cwd()
theme_src = root / "wordpress-theme" / "ava-alliance"
html = src.read_text(encoding="utf-8")

# Feed, REST API and similar WordPress-only discovery links.
html = re.sub(r'<link rel="(alternate|https://api\.w\.org/|EditURI|shortlink)"[^>]*>\n?', "", html)
html = re.sub(r"/\*# sourceURL=[^*]*\*/\n?", "", html)

# Asset version query strings.
html = re.sub(r"(\.(?:css|js|woff2|png|jpg|webp))\?ver=[^'\"]*", r"\1", html)

# Sharing tags must use absolute URLs.
html = html.replace(f'<link rel="canonical" href="{origin}/">', f'<link rel="canonical" href="{PAGES_URL}">')
html = re.sub(
    r'(<meta (?:property|name)="(?:og:image|og:url|twitter:image)" content=")' + re.escape(origin) + r"/wp-content/themes/ava-alliance/",
    r"\1" + PAGES_URL + "theme/",
    html,
)
html = html.replace(f'content="{origin}/"', f'content="{PAGES_URL}"')
html = html.replace(f'"url":"{origin}/"', f'"url":"{PAGES_URL}"')
html = html.replace(f'"logo":"{origin}/wp-content/themes/ava-alliance/', f'"logo":"{PAGES_URL}theme/')

# Local paths.
html = html.replace(f"{origin}/wp-content/themes/ava-alliance/", "theme/")
html = html.replace(f"{origin}/files/", "files/")
html = html.replace(f'href="{origin}/"', 'href="./"')

# Forms: no server here, so show a preview message instead of sending.
html = html.replace(f"{origin}/wp-admin/admin-post.php", "#")
html = html.replace(f'"ajaxUrl":"{origin}/wp-admin/admin-ajax.php"', '"ajaxUrl":""')

preview = """<meta name="robots" content="noindex">
<style>.ava-preview-pill{position:fixed;left:12px;bottom:12px;z-index:900;background:#2E1B14;color:#fff;font:700 12px/1.3 "Nunito Sans",Arial,sans-serif;padding:8px 12px;border-radius:999px;box-shadow:0 6px 18px rgba(0,0,0,.2)}</style>
<script>
document.addEventListener('submit',function(e){
  var f=e.target.closest('form[data-ava-form]');if(!f)return;
  e.preventDefault();e.stopImmediatePropagation();
  var w=f.closest('.ava-form-wrap'),ok=w.querySelector('.ava-notice.success'),bad=w.querySelector('.ava-notice.error');
  if(!f.checkValidity()){var i=f.querySelector(':invalid');bad.textContent=i?i.validationMessage:'Please fill in the required fields.';bad.hidden=false;if(i)i.focus();return;}
  bad.hidden=true;ok.textContent=ok.textContent.replace(/ \\(Preview.*$/,'')+' (Preview only: nothing was sent. On the WordPress site this is saved for the team.)';ok.hidden=false;f.remove();
},true);
</script>
"""
html = html.replace("</head>", preview + "</head>", 1)
html = html.replace(
    "</body>",
    '<div class="ava-preview-pill" aria-hidden="true">Design preview of the AVA WordPress theme</div>\n</body>',
    1,
)

leftover = re.findall(re.escape(origin) + r"[^\"' )]*", html)
if leftover:
    sys.exit("Unconverted local URLs: " + ", ".join(sorted(set(leftover))))

(root / "index.html").write_text(html, encoding="utf-8")

# Theme stylesheet + assets the page uses.
out = root / "theme"
if out.exists():
    shutil.rmtree(out)
out.mkdir()
shutil.copy2(theme_src / "style.css", out / "style.css")
shutil.copytree(theme_src / "assets", out / "assets", ignore=shutil.ignore_patterns("editor.css", "OFL-*"))
print("Preview written: index.html + theme/")
