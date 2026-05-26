# Nightly QA Report

**日時:** 2026-05-26 12:12 (UTC)
**結果:** ⚠️ Issues Found

---

## PHP構文チェック

✅ 全 12 ファイル — 構文エラーなし

| ファイル | 結果 |
|---|---|
| archive-works.php | OK |
| footer.php | OK |
| front-page.php | OK |
| functions.php | OK |
| header.php | OK |
| home.php | OK |
| index.php | OK |
| page-about.php | OK |
| page-contact.php | OK |
| page-privacy.php | OK |
| single-works.php | OK |
| single.php | OK |

---

## セキュリティチェック

### eval( の使用
✅ 検出なし

### base64_decode( の使用
✅ 検出なし

### 未サニタイズの $\_GET / $\_POST / $\_REQUEST
✅ 問題なし — 検出された全使用箇所は適切に保護済み

詳細（全件、いずれも安全）:

| ファイル:行 | 内容 | 判定 |
|---|---|---|
| functions.php:460 | `$_POST['andfeel_works_data_nonce_field']` | `wp_verify_nonce()` でnonce検証済み |
| functions.php:467 | `$_POST[$key]` | `sanitize_text_field()` でラップ済み |
| functions.php:591 | `$_POST['andfeel_gallery_nonce_field']` | `wp_verify_nonce()` でnonce検証済み |
| functions.php:601 | `$_POST['works_gallery_ids']` | `sanitize_text_field()` + `absint()` でラップ済み |
| functions.php:710 | `$_POST['andfeel_blog_structure_nonce']` | `wp_verify_nonce()` でnonce検証済み |
| functions.php:721 | `$_POST['blog_intro']` | `sanitize_textarea_field(wp_unslash(...))` でラップ済み |
| functions.php:728 | `$_POST[$h2_key]` | `sanitize_text_field(wp_unslash(...))` でラップ済み |
| functions.php:731 | `$_POST[$body_key]` | `sanitize_textarea_field(wp_unslash(...))` でラップ済み |
| functions.php:1210 | `$_GET['post_type']` | `in_array(..., true)` で既知リストと厳密比較済み、出力なし |

---

## コード品質チェック

### CSSの `!important` 使用数

⚠️ **合計 4 件** — コーディング規約 (`no !important`) に違反（前日から変化なし）

| ファイル | 件数 | 行 | 内容 |
|---|---|---|---|
| assets/css/blog.css | 2 | 71, 72 | `width: 100% !important;` / `height: 100% !important;` |
| assets/css/common.css | 1 | 243 | `visibility: hidden !important;` |
| assets/css/contact.css | 1 | 760 | `visibility: hidden !important;` |

> 対応推奨: セレクタ詳細度を上げて `!important` を除去してください。

### TODO / FIXME / HACK コメント
✅ 検出なし

### JS内の `console.log`
✅ 検出なし

### PHP内のデバッグ出力 (`var_dump` / `print_r` / `error_log`)
✅ 検出なし

---

## サマリー

| チェック項目 | 結果 |
|---|---|
| PHP構文 (12ファイル) | ✅ All Clear |
| eval / base64_decode | ✅ All Clear |
| 未サニタイズ変数 | ✅ All Clear |
| !important 使用 | ⚠️ 4件 (blog.css×2, common.css×1, contact.css×1) |
| TODO/FIXME/HACK | ✅ All Clear |
| console.log | ✅ All Clear |
| デバッグ出力 | ✅ All Clear |
