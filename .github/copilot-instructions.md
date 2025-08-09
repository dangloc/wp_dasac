# CommicPro Theme - AI Coding Instructions

## Project Overview
This is a WordPress theme for a Vietnamese novel reading platform (`truyện chữ`). The theme implements a complete story/chapter management system with VIP subscriptions, virtual currency (Kim Tệ), and reading history tracking.

## Core Architecture

### Custom Post Types & Taxonomies
- **`truyen_chu`**: Main story/novel post type with slug `truyen-chu`
- **`chuong_truyen`**: Chapter post type with slug `chuong`, linked to stories via ACF `chuong_with_truyen` field
- **Taxonomies**: `tac_gia` (authors), `the_loai` (genres), `nam_phat_hanh` (year), `trang_thai` (status)
- **`thong_bao`**: Notification post type with taxonomy `danh-muc-thong-bao`

### Database Extensions
- Custom `reading_history` table tracks user reading progress per story/chapter
- User meta fields: `_user_balance` (Kim Tệ currency), `_purchased_chapters`, `vip_package`

### Template Hierarchy
- Single templates: `single-truyen_chu.php`, `single-chuong_truyen.php`, `single-thong-bao.php`
- Archive templates: `archive-truyen_chu.php`, `archive-thong-bao.php`
- Taxonomy templates: `taxonomy-{taxonomy}.php` for each custom taxonomy
- Special pages: `page-goi-vip.php`, `page-nap-kim-te.php`, `page-vi-tien.php`, `page-reading-history.php`

## Development Patterns

### Theme Structure
```
functions.php         - Custom post types, VIP/payment functions
ajax-handler.php      - AJAX endpoints for chapter counts, latest chapters
inc/reading-history.php - Reading progress tracking
template-parts/home/  - Modular homepage components (sliders, cards)
assets/scss/         - SCSS with BEM-like methodology
```

### Key Functions (in functions.php)
- `check_user_vip_status()` - VIP subscription validation
- `has_user_purchased_chapter()` - Chapter purchase verification  
- `get_user_balance()` - Kim Tệ balance retrieval
- Chapter relationship queries use ACF `chuong_with_truyen` field

### AJAX Pattern
All AJAX actions follow pattern:
```php
add_action('wp_ajax_{action}', 'handler_function');
add_action('wp_ajax_nopriv_{action}', 'handler_function');
```
Security via `check_ajax_referer()` with nonces. See `ajax-handler.php` for examples.

### Payment/VIP System
- Virtual currency: Kim Tệ stored in `_user_balance` user meta
- VIP packages: 2-month (`vip_2_months`) and permanent (`vip_permanent`)
- Chapter purchases tracked per user in `_purchased_chapters` meta array
- Template files handle VIP status display and purchase flows

## Build System

### Development Commands
```bash
npm run watch          # SCSS compilation with watch
npm run compile:css    # Compile SCSS to CSS
npm run compile:rtl    # Generate RTL stylesheet
npm run lint:scss      # SCSS linting
composer lint:wpcs     # WordPress coding standards
composer make-pot      # Generate translation files
```

### Asset Management
- SCSS structure: abstracts/ → base/ → components/ → layout/ → pages/
- External libraries in `assets/libs/`: AOS, Swiper, Flickity, FontAwesome
- Custom JS: `assets/js/chapter-count.js`, `latest-chapter.js`

## Vietnamese Content Conventions
- All admin labels and frontend text in Vietnamese
- URL slugs use Vietnamese with hyphens: `truyen-chu`, `chuong`, `tac-gia`
- Currency terminology: "Kim Tệ" for virtual currency, "đạo hữu" for users
- Story terminology: "truyện" (stories), "chương" (chapters), "tác giả" (authors)

## Key Integration Points
- **ACF Required**: Chapter-to-story relationships via Post Object fields
- **Custom Database**: Reading history table created on theme activation
- **AJAX Endpoints**: Chapter counting, latest chapter fetching for dynamic content
- **User Meta Extensions**: Balance, purchases, VIP status tracking

When working with this theme, always consider the Vietnamese context, VIP/payment implications, and the story-chapter relationship structure.
