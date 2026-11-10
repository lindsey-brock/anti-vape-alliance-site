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


def render_pdf_preview(source, out_dir):
    """Render each PDF page to a watermarked WebP image. Returns the page count."""
    import subprocess
    import tempfile
    from PIL import Image, ImageDraw, ImageFont

    out_dir.mkdir(parents=True, exist_ok=True)
    with tempfile.TemporaryDirectory() as tmp:
        subprocess.run(["pdftoppm", "-r", "110", "-png", str(source), tmp + "/p"], check=True)
        pages = sorted(Path(tmp).glob("p*.png"), key=lambda p: int(re.sub(r"\D", "", p.stem) or 0))
        try:
            font = ImageFont.truetype("DejaVuSans-Bold.ttf", 64)
        except OSError:
            font = ImageFont.load_default(size=64)
        for i, page in enumerate(pages, 1):
            img = Image.open(page).convert("RGBA")
            mark = Image.new("RGBA", img.size, (0, 0, 0, 0))
            d = ImageDraw.Draw(mark)
            step = 260
            for y in range(-img.height, img.height * 2, step):
                for x in range(-img.width, img.width * 2, step * 2):
                    d.text((x, y), "PREVIEW", font=font, fill=(88, 54, 43, 34))
            mark = mark.rotate(30, resample=Image.BICUBIC)
            Image.alpha_composite(img, mark).convert("RGB").save(out_dir / ("p%d.webp" % i), quality=72, method=6)
    return len(pages)


PDF_VIEWER = """
<div class="pdfv" id="pdfv" role="dialog" aria-modal="true" aria-label="Document preview" hidden>
  <div class="pdfv-bar"><strong>Preview only</strong><span>The full PDF downloads from the live WordPress site.</span><button type="button" class="pdfv-x" aria-label="Close preview">&times;</button></div>
  <div class="pdfv-pages"></div>
</div>
<style>
.pdfv{position:fixed;inset:0;z-index:2000;background:rgba(31,18,12,.86);display:flex;flex-direction:column}
.pdfv[hidden]{display:none}
.pdfv-bar{display:flex;align-items:center;gap:12px;padding:12px 18px;background:#2E1B14;color:#fff;font:600 14px "Nunito Sans",Arial,sans-serif}
.pdfv-bar strong{font-family:Montserrat,Arial,sans-serif;font-weight:800;color:#FEB228}
.pdfv-bar span{opacity:.85;flex:1}
.pdfv-x{background:none;border:0;color:#fff;font-size:30px;line-height:1;width:44px;height:44px;border-radius:10px;cursor:pointer}
.pdfv-x:hover{background:rgba(255,255,255,.12)}
.pdfv-pages{overflow:auto;padding:24px 16px 48px;display:flex;flex-direction:column;align-items:center;gap:18px}
.pdfv-pages img{width:min(100%,820px);height:auto;background:#fff;box-shadow:0 12px 30px rgba(0,0,0,.35);user-select:none;-webkit-user-select:none;-webkit-user-drag:none;pointer-events:none}
body.pdfv-open{overflow:hidden}
</style>
<script>
(function(){
  var v=document.getElementById('pdfv'),wrap=v.querySelector('.pdfv-pages'),x=v.querySelector('.pdfv-x'),last=null;
  function close(){v.hidden=true;document.body.classList.remove('pdfv-open');wrap.innerHTML='';if(last)last.focus();}
  document.addEventListener('click',function(e){
    var a=e.target.closest('[data-pdf-preview]');if(!a)return;e.preventDefault();last=a;
    var slug=a.getAttribute('data-pdf-preview'),n=+a.getAttribute('data-pages');wrap.innerHTML='';
    for(var i=1;i<=n;i++){var img=document.createElement('img');img.src='preview-pdf/'+slug+'/p'+i+'.webp';img.alt='Page '+i+' of '+n;img.loading=i>2?'lazy':'eager';img.draggable=false;wrap.appendChild(img);}
    v.hidden=false;document.body.classList.add('pdfv-open');wrap.scrollTop=0;x.focus();
  });
  x.addEventListener('click',close);
  v.addEventListener('click',function(e){if(e.target===v||e.target===wrap)close();});
  v.addEventListener('contextmenu',function(e){e.preventDefault();});
  document.addEventListener('keydown',function(e){if(!v.hidden&&e.key==='Escape')close();});
})();
</script>
"""

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
# Media Library PDFs (wp-content/uploads/YYYY/MM/name.pdf) are served from files/ in the preview.
html = re.sub(re.escape(origin) + r"/wp-content/uploads/\d{4}/\d{2}/([^\"'<>]+\.pdf)", r"files/\1", html)
html = html.replace(f'href="{origin}/"', 'href="./"')

# Forms: no server here, so show a preview message instead of sending.
html = html.replace(f"{origin}/wp-admin/admin-post.php", "#")
html = html.replace(f'"ajaxUrl":"{origin}/wp-admin/admin-ajax.php"', '"ajaxUrl":""')

# Pledge counter: no live data here, so the preview uses clearly labelled
# sample signers and a count that ticks up ("· sample" on every pop-up).
esc_origin = origin.replace("/", "\\/")
for o in (origin, esc_origin):
    sep = "\\/" if o is esc_origin else "/"
    html = html.replace('"url":"' + o + sep.join(["", "wp-json", "ava", "v1", "pledges"]) + '"', '"url":""')
demo_feed = """<script>
(function(){var now=Math.round(Date.now()/1000);
window.AVA_DEMO_FEED={count:312,
recent:[{name:'Maya',ts:now-140},{name:'Jordan',ts:now-960},{name:'Priya',ts:now-2500},{name:'Eli',ts:now-5600}],
names:['Ava','Noah','Sofia','Marcus','Zoe','Diego','Lena','Kai','Amara','Leo','Hana','Mateo']};})();
</script>
"""

preview = """<meta name="robots" content="noindex">
<style>.ava-preview-pill{position:fixed;right:12px;bottom:12px;opacity:.85;z-index:900;background:#2E1B14;color:#fff;font:700 12px/1.3 "Nunito Sans",Arial,sans-serif;padding:8px 12px;border-radius:999px;box-shadow:0 6px 18px rgba(0,0,0,.2)}</style>
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
html = html.replace("</head>", preview + demo_feed + "</head>", 1)
html = html.replace(
    "</body>",
    '<div class="ava-preview-pill" aria-hidden="true">Design preview of the AVA WordPress theme</div>\n</body>',
    1,
)

host = origin.split("//", 1)[-1]
# Curriculum PDFs: the preview never ships the PDF itself. Each linked PDF is
# rendered to watermarked page images and the button opens a view-only viewer.
pdf_dir = root / "preview-pdf"
if pdf_dir.exists():
    shutil.rmtree(pdf_dir)
pdf_pages = {}
for name in sorted(set(re.findall(r'href="files/([^"]+\.pdf)"', html))):
    source = theme_src / "assets" / "files" / name
    if not source.exists():
        continue
    slug = re.sub(r"[^a-z0-9]+", "-", name[:-4].lower()).strip("-")
    pdf_pages[name] = (slug, render_pdf_preview(source, pdf_dir / slug))
for name, (slug, count) in pdf_pages.items():
    html = re.sub(
        r'href="files/' + re.escape(name) + r'"( download)?',
        'href="#" data-pdf-preview="%s" data-pages="%d"' % (slug, count),
        html,
    )
if pdf_pages:
    html = html.replace("</body>", PDF_VIEWER + "</body>", 1)

leftover = re.findall(re.escape(host) + r"[^\"' )]*", html)
if leftover:
    sys.exit("Unconverted local URLs: " + ", ".join(sorted(set(leftover))))

(root / "index.html").write_text(html, encoding="utf-8")

# Theme stylesheet + assets the page uses.
out = root / "theme"
if out.exists():
    shutil.rmtree(out)
out.mkdir()
shutil.copy2(theme_src / "style.css", out / "style.css")
shutil.copytree(theme_src / "assets", out / "assets", ignore=shutil.ignore_patterns("editor.css", "OFL-*", "files"))
print("Preview written: index.html + theme/")
