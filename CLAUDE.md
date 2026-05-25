# andfeel2026 — WordPress Custom Theme

## Critical Rules
- NEVER edit WP core files — only `wp-content/themes/andfeel/` is our code
- NEVER read, commit, or display contents of `ログイン情報/` directory
- All production deployments require upload-approve.sh token (see `.claude/hooks/`)
- Do NOT install npm/composer packages — this is a zero-dependency PHP theme
- Ask clarifying questions before making architectural changes to functions.php

## Project Overview
- Client: and feel.建築設計事務所 (architect in Tokushima, Japan)
- Site: https://andfeel-architect.com
- Stack: WordPress 6.4+ / PHP 8.0+ / Classic Editor (no Gutenberg)
- Theme: `wp-content/themes/andfeel/` (custom, no parent theme)
- Hosting: Sakura Internet (shared hosting, FTP deploy)

## Tech Stack
- CSS: Vanilla CSS, no preprocessor. Conditional loading via wp_enqueue_style
  google-fonts → base.css → layout.css → components.css → common.css → page-*.css
- JS: Vanilla JS + jQuery (WP bundled) + jQuery Ripples (CDN)
- Maps: Google Maps JavaScript API (key in functions.php)
- Forms: Contact Form 7
- CPT: `works` (建築作品) with custom taxonomy `works_category`
- SEO: Built-in (no plugin) — meta/OGP/JSON-LD in functions.php
- Editor: Classic Editor (Gutenberg disabled)

## File Structure
```
wp-content/themes/andfeel/
├── style.css              # Theme declaration only
├── functions.php          # ALL theme logic (~1200 lines, 13 sections)
├── front-page.php         # Homepage
├── header.php / footer.php
├── page-about.php / page-contact.php / page-privacy.php
├── archive-works.php / single-works.php
├── home.php / single.php / index.php
├── assets/css/            # 12 CSS files, page-specific loading
├── assets/js/             # 8 JS files, conditionally enqueued
└── images/                # hero, logo, icons, about, works, blog, contact, favicon
```

## functions.php Sections
1. Theme support  2. CSS/JS enqueue  3. CPT registration (works)
4. pre_get_posts  5. Breadcrumb + Pagination  6. Excerpt
7. Admin (7b: works metabox, 7c: gallery, 7d: blog structure)
8. SEO (meta/OGP)  9. Google Fonts + Analytics  10. JSON-LD
11. CF7  12. Security headers  13. User permissions

## Design Tokens
- Background: #e5e3e1 / Text: #251E1C
- Font: Noto Serif JP (300/400/500/600/700)
- Left margin: 11.11%
- Breakpoints: 1024px (tablet) / 768px (mobile) / 430px (smartphone)
- Desktop-first responsive (max-width media queries)

## Coding Conventions
- PHP: WordPress coding standards, esc_* for all output, andfeel_ prefix
- CSS: BEM-like naming, lowercase-hyphenated, no !important
- JS: Vanilla preferred, defer via footer loading, no ES modules
- Meta keys: works_ for CPT, blog_ for posts
- All user-facing text in Japanese

## Deployment
- Method: FTP via upload-approve.sh (10-min, single-use token)
- Guard: production-guard.sh blocks SSH/rsync/scp/production wp-cli
- NEVER deploy without testing in LocalWP first

## Critical Reminders
- Test all PHP changes in LocalWP before deployment
- Keep functions.php sections numbered (1-13) — append new sections
- Preserve existing hook priorities when adding filters
- Images: max 2048px width, JPG, optimize before commit
- After deploy: disconnect FileZilla (サーバー → 切断)
