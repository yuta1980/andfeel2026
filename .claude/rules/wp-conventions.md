# WordPress Conventions — andfeel Theme

## Template Hierarchy
| File | Purpose | URL |
|------|---------|-----|
| front-page.php | Homepage (static front page) | / |
| home.php | Blog archive (投稿一覧 / 日々) | /blog/ |
| archive-works.php | Works archive (作品一覧 / かたち) | /works/ |
| single-works.php | Single work detail | /works/{slug}/ |
| single.php | Single blog post | /blog/{slug}/ |
| page-about.php | About page | /about/ |
| page-contact.php | Contact page | /contact/ |
| page-privacy.php | Privacy policy | /privacy/ |
| header.php | Shared header + nav | — |
| footer.php | Shared footer | — |

## Custom Post Type: works
- Slug: works
- Taxonomy: works_category (hierarchical)
- Meta fields: works_location, works_year, works_structure, works_area
- Gallery: works_gallery (array of attachment IDs, max 8)
- Archive: 4 per page
- Image sizes: works-thumb (684x416), works-main (1200x800)

## Blog Posts
- Classic Editor (Gutenberg disabled)
- Custom meta structure (NOT standard editor):
  - blog_intro — introduction text
  - blog_section_h2_{1-3} — section headings
  - blog_section_body_{1-3} — section body text
- 4 posts per page
- Image size: blog-thumb (684x416)

## functions.php Section Map
| # | Section | Lines (approx) |
|---|---------|----------------|
| 1 | Theme support | setup, menus, thumbnails |
| 2 | CSS/JS enqueue | conditional per template |
| 3 | CPT registration | works + works_category |
| 4 | pre_get_posts | posts_per_page settings |
| 5 | Breadcrumb + Pagination | custom implementations |
| 6 | Excerpt customization | 120 char max |
| 7 | Admin (7b/7c/7d) | metaboxes, gallery, blog structure |
| 8 | SEO | meta description, OGP, canonical |
| 9 | Google Fonts + Analytics | preconnect, GA4 optional |
| 10 | JSON-LD | Organization, BreadcrumbList, Article |
| 11 | Contact Form 7 | validation, submit control |
| 12 | Security headers | X-Content-Type-Options, etc. |
| 13 | User permissions | non-admin restrictions |

## Adding New Code
- New page template: create `page-{slug}.php`, add CSS in `assets/css/`
- New CSS: register in functions.php section 2 with conditional loading
- New JS: register in functions.php section 2 with footer=true
- New meta box: add to functions.php section 7
- New section: append as section 14+ (never renumber existing)
