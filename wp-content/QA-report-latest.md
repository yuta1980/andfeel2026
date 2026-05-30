# Nightly QA Report

**日時:** 2026-05-30 12:04 (UTC)
**結果:** ⚠️ Issues Found

---

## PHP構文チェック

✅ 全 12 ファイルで構文エラーなし

| ファイル | 結果 |
|---------|------|
| archive-works.php | ✅ OK |
| footer.php | ✅ OK |
| front-page.php | ✅ OK |
| functions.php | ✅ OK |
| header.php | ✅ OK |
| home.php | ✅ OK |
| index.php | ✅ OK |
| page-about.php | ✅ OK |
| page-contact.php | ✅ OK |
| page-privacy.php | ✅ OK |
| single-works.php | ✅ OK |
| single.php | ✅ OK |

---

## セキュリティチェック

### eval() の使用
✅ 検出なし

### base64_decode() の使用
✅ 検出なし

### サニタイズなしの $_GET / $_POST / $_REQUEST
✅ 問題なし

`functions.php` 内に複数の `$_POST` / `$_GET` 参照が存在するが、いずれも適切に処理済み:

- **L460, L591, L710** — `wp_verify_nonce()` + `sanitize_text_field( wp_unslash() )` による nonce 検証
- **L467-468** — `sanitize_text_field( wp_unslash( $_POST[$key] ) )` でサニタイズ済み
- **L596** — `sanitize_text_field( $_POST['works_gallery_ids'] )` でサニタイズ済み
- **L721-732** — `sanitize_text_field` / `sanitize_textarea_field` + `wp_unslash` でサニタイズ済み
- **L1008** — `sanitize_text_field( wp_unslash( $_POST['your-privacy'] ) )` でサニタイズ済み
- **L1210** — `in_array( sanitize_text_field( wp_unslash( $_GET['post_type'] ) ), $post_types, true )` でサニタイズ + 厳密型チェック済み

---

## コード品質チェック

### CSS `!important` 使用数

⚠️ 計 **4 箇所** 検出（コーディング規約: 使用禁止）

| ファイル | 行 | 内容 |
|---------|-----|------|
| assets/css/blog.css | 71 | `width: 100% !important;` |
| assets/css/blog.css | 72 | `height: 100% !important;` |
| assets/css/common.css | 243 | `visibility: hidden !important;` |
| assets/css/contact.css | 760 | `visibility: hidden !important;` |

> `common.css:243` と `contact.css:760` はサードパーティ（jQuery Ripples / CF7）のスタイル上書きの可能性あり。`blog.css:71-72` は埋め込みコンテンツへの適用と推測。いずれも意図的なものか確認を推奨。

### TODO / FIXME / HACK コメント
✅ 検出なし

### JS `console.log` 残存
✅ 検出なし（7 JS ファイル全件クリア）

### PHP デバッグ出力 (`var_dump` / `print_r` / `error_log`)
✅ 検出なし

---

## サマリー

| カテゴリ | ステータス | 件数 |
|---------|-----------|------|
| PHP 構文エラー | ✅ クリア | 0 |
| セキュリティ (eval/base64) | ✅ クリア | 0 |
| 未サニタイズ入力 | ✅ クリア | 0 |
| CSS `!important` | ⚠️ 要確認 | 4 |
| TODO/FIXME/HACK | ✅ クリア | 0 |
| console.log | ✅ クリア | 0 |
| デバッグ出力 (PHP) | ✅ クリア | 0 |

> **アクション推奨:** `!important` 4 箇所が規約違反。意図的なサードパーティ上書きであれば `/* third-party override */` コメントを付加し、そうでなければリファクタリングしてください。
