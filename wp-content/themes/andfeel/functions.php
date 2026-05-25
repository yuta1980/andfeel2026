<?php
/**
 * and feel. テーマ functions.php
 *
 * @package andfeel
 */

defined('ABSPATH') || exit;

/* ==========================================================================
   1. テーマサポート
   ========================================================================== */

function andfeel_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);

    // アイキャッチ画像サイズ
    add_image_size('works-thumb',    684, 416, true);  // 作品一覧サムネイル
    add_image_size('blog-thumb',     684, 416, true);  // ブログ一覧サムネイル
    add_image_size('works-main',    1200, 800, true);  // 作品詳細メイン
    add_image_size('hero-image',    1720, 1100, true); // トップヒーロー

    // ナビゲーションメニュー
    register_nav_menus([
        'header-nav' => 'ヘッダーナビゲーション',
    ]);
}
add_action('after_setup_theme', 'andfeel_setup');


/* ==========================================================================
   2. CSS / JS 読み込み
   ========================================================================== */

function andfeel_enqueue_assets() {
    $theme_uri = get_template_directory_uri();
    $version   = wp_get_theme()->get('Version');

    /* ---------- 共通 CSS ---------- */
    wp_enqueue_style('google-fonts',
        'https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@300;400;500;600;700&display=swap',
        [], null
    );
    wp_enqueue_style('andfeel-base',       "{$theme_uri}/assets/css/base.css",       ['google-fonts'], $version);
    wp_enqueue_style('andfeel-layout',     "{$theme_uri}/assets/css/layout.css",     ['andfeel-base'],  $version);
    wp_enqueue_style('andfeel-components', "{$theme_uri}/assets/css/components.css", ['andfeel-layout'], $version);
    wp_enqueue_style('andfeel-common',     "{$theme_uri}/assets/css/common.css",     ['andfeel-components'], $version);

    /* ---------- ページ固有 CSS ---------- */
    if (is_front_page()) {
        wp_enqueue_style('andfeel-front', "{$theme_uri}/assets/css/page-front.css", ['andfeel-common'], $version);
    } elseif (is_page('about')) {
        wp_enqueue_style('andfeel-about', "{$theme_uri}/assets/css/about.css", ['andfeel-common'], $version);
    } elseif (is_post_type_archive('works') || is_tax('works_category')) {
        wp_enqueue_style('andfeel-works', "{$theme_uri}/assets/css/works.css", ['andfeel-common'], $version);
    } elseif (is_singular('works')) {
        wp_enqueue_style('andfeel-works-single', "{$theme_uri}/assets/css/works-single.css", ['andfeel-common'], $version);
    } elseif (is_home() || is_category() || is_tag()) {
        wp_enqueue_style('andfeel-blog', "{$theme_uri}/assets/css/blog.css", ['andfeel-common'], $version);
    } elseif (is_singular('post')) {
        wp_enqueue_style('andfeel-blog-single', "{$theme_uri}/assets/css/blog-single.css", ['andfeel-common'], $version);
    } elseif (is_page('contact')) {
        wp_enqueue_style('andfeel-contact', "{$theme_uri}/assets/css/contact.css", ['andfeel-common'], $version);
    } elseif (is_page('privacy')) {
        wp_enqueue_style('andfeel-privacy', "{$theme_uri}/assets/css/privacy.css", ['andfeel-common'], $version);
    }

    /* ---------- 共通 JS ---------- */
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ripples',
        'https://cdn.jsdelivr.net/npm/jquery.ripples@0.6.3/dist/jquery.ripples.min.js',
        ['jquery'], '0.6.3', true
    );
    // jQuery Ripples の UMD ラッパーは window.$ を参照するため、
    // WordPress の noConflict モードでは $ が未定義になる。
    // 読み込み直前に $ = jQuery を設定し、直後に解除する。
    wp_add_inline_script('jquery-ripples', 'window.$ = jQuery;', 'before');
    wp_add_inline_script('jquery-ripples', 'delete window.$;', 'after');
    wp_enqueue_script('andfeel-common',
        "{$theme_uri}/assets/js/common.js",
        [], $version, true
    );
    wp_enqueue_script('andfeel-ripples-init',
        "{$theme_uri}/assets/js/ripples-init.js",
        ['jquery', 'jquery-ripples'], $version, true
    );
    wp_enqueue_script('andfeel-scroll-fade',
        "{$theme_uri}/assets/js/scroll-fade.js",
        [], $version, true
    );

    /* ---------- ページ固有 JS ---------- */
    if (is_page('about') || is_page('contact')) {
        wp_enqueue_script('andfeel-map',
            "{$theme_uri}/assets/js/map.js",
            [], $version, true
        );
        wp_enqueue_script('google-maps',
            'https://maps.googleapis.com/maps/api/js?key=AIzaSyDMXZR7yjHJyC5gRENCl13WI6v0RoRhdpA&v=weekly&loading=async&callback=initMap',
            ['andfeel-map'], null, true
        );
    }

    if (is_post_type_archive('works') || is_tax('works_category')) {
        wp_enqueue_script('andfeel-works-filter',
            "{$theme_uri}/assets/js/works-filter.js",
            [], $version, true
        );
        wp_enqueue_script('andfeel-works-thumb-slide',
            "{$theme_uri}/assets/js/works-thumb-slide.js",
            [], $version, true
        );
    }

    if (is_singular('works')) {
        wp_enqueue_script('andfeel-works-modal',
            "{$theme_uri}/assets/js/works-modal.js",
            [], $version, true
        );
    }
}
add_action('wp_enqueue_scripts', 'andfeel_enqueue_assets');

/**
 * jQuery Ripples 関連スクリプトを Autoptimize の結合・遅延読み込みから除外し、
 * インラインスクリプト（window.$ = jQuery）の実行順序を保証する。
 * ※ jsDelivr の動的生成ファイルは SRI 非対応のため SRI は付与しない。
 */
function andfeel_ripples_script_attrs( $tag, $handle, $src ) {
    $no_optimize_handles = array( 'jquery-ripples', 'andfeel-ripples-init' );
    if ( in_array( $handle, $no_optimize_handles, true ) ) {
        $tag = str_replace( ' src=', ' data-no-optimize="1" src=', $tag );
    }
    return $tag;
}
add_filter( 'script_loader_tag', 'andfeel_ripples_script_attrs', 10, 3 );


/* ==========================================================================
   3. カスタム投稿タイプ：作品（Works）
   ========================================================================== */

function andfeel_register_works_cpt() {
    register_post_type('works', [
        'labels' => [
            'name'               => 'かたち',
            'singular_name'      => 'かたち',
            'add_new'            => '新規追加',
            'add_new_item'       => '新しい作品を追加',
            'edit_item'          => '作品を編集',
            'view_item'          => '作品を表示',
            'all_items'          => 'すべての作品',
            'search_items'       => '作品を検索',
            'not_found'          => '作品が見つかりません',
            'not_found_in_trash' => 'ゴミ箱に作品はありません',
        ],
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'works', 'with_front' => false],
        'menu_icon'    => 'dashicons-building',
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest' => true,
    ]);

    register_taxonomy('works_category', 'works', [
        'labels' => [
            'name'          => '作品カテゴリー',
            'singular_name' => '作品カテゴリー',
            'add_new_item'  => '新しいカテゴリーを追加',
        ],
        'public'       => true,
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'works-cat', 'with_front' => false],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'andfeel_register_works_cpt');


/* ==========================================================================
   4. 投稿一覧の件数制御
   ========================================================================== */

function andfeel_pre_get_posts($query) {
    if (is_admin() || ! $query->is_main_query()) {
        return;
    }
    if ($query->is_post_type_archive('works') || $query->is_tax('works_category')) {
        $query->set('posts_per_page', 4);
    }
    if ($query->is_home()) {
        $query->set('posts_per_page', 4);
    }
}
add_action('pre_get_posts', 'andfeel_pre_get_posts');


/* ==========================================================================
   5. パンくずリスト
   ========================================================================== */

function andfeel_breadcrumb() {
    if (is_front_page()) return;

    echo '<nav class="breadcrumb" aria-label="パンくずリスト"><ol>';
    echo '<li><a href="' . esc_url(home_url('/')) . '">HOME</a></li>';

    if (is_page()) {
        $breadcrumb_title = andfeel_get_page_display_title() ?? get_the_title();
        echo '<li aria-current="page">' . esc_html($breadcrumb_title) . '</li>';
    } elseif (is_singular('works')) {
        echo '<li><a href="' . esc_url(get_post_type_archive_link('works')) . '">かたち</a></li>';
        echo '<li aria-current="page">' . esc_html(get_the_title()) . '</li>';
    } elseif (is_singular('post')) {
        echo '<li><a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '">日々</a></li>';
        echo '<li aria-current="page">' . esc_html(get_the_title()) . '</li>';
    } elseif (is_post_type_archive('works')) {
        echo '<li aria-current="page">かたち</li>';
    } elseif (is_home()) {
        echo '<li aria-current="page">日々</li>';
    }

    echo '</ol></nav>';
}


/* ==========================================================================
   5b. カスタムページネーション
   ========================================================================== */

/**
 * 静的HTML版デザインに合わせたページネーションを出力
 *
 * @param string $wrapper_class 外側ラッパーに追加するクラス（works用 'pagination-lines' など）
 */
function andfeel_pagination( $wrapper_class = '' ) {
    global $wp_query;

    $total = $wp_query->max_num_pages;
    if ( $total <= 1 ) {
        return;
    }

    $current  = max( 1, get_query_var( 'paged', 1 ) );
    $svg_arrow = '<svg viewBox="0 0 20 8" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M0 4H18M18 4L14.5 1M18 4L14.5 7" stroke="#000" stroke-width="1"/></svg>';

    // works用: pagination-lines ラッパーを開く
    if ( $wrapper_class ) {
        echo '<nav class="pagination" aria-label="ページナビゲーション">';
        echo '<div class="' . esc_attr( $wrapper_class ) . '">';
    } else {
        echo '<nav class="pagination" aria-label="ページネーション">';
    }

    echo '<div class="pagination-line" aria-hidden="true"></div>';
    echo '<div class="pagination-nums">';

    for ( $i = 1; $i <= $total; $i++ ) {
        if ( $i === $current ) {
            echo '<span class="current" aria-current="page">' . $i . '</span>';
        } else {
            echo '<a href="' . esc_url( get_pagenum_link( $i ) ) . '">' . $i . '</a>';
        }
    }

    echo '</div>';
    echo '<div class="pagination-line" aria-hidden="true"></div>';

    // 次のページがある場合のみ矢印を表示
    if ( $current < $total ) {
        echo '<a href="' . esc_url( get_pagenum_link( $current + 1 ) ) . '" class="pagination-arrow" aria-label="次のページ">' . $svg_arrow . '</a>';
    }

    // works用: pagination-lines ラッパーを閉じる
    if ( $wrapper_class ) {
        echo '</div>';
    }

    echo '</nav>';
}


/* ==========================================================================
   6. 抜粋のカスタマイズ
   ========================================================================== */

function andfeel_excerpt_length($length) {
    return 80;
}
add_filter('excerpt_length', 'andfeel_excerpt_length');

function andfeel_excerpt_more($more) {
    return '…';
}
add_filter('excerpt_mblength', function() { return 80; });
add_filter('excerpt_more', 'andfeel_excerpt_more');

// カスタムフィールドから抜粋を生成（セクション1本文 → 導入文 → post_content の優先順）
function andfeel_custom_excerpt( $excerpt ) {
    if ( get_post_type() !== 'post' ) {
        return $excerpt;
    }
    $body1 = get_post_meta( get_the_ID(), 'blog_section_body_1', true );
    if ( $body1 ) {
        return $body1;
    }
    $intro = get_post_meta( get_the_ID(), 'blog_intro', true );
    if ( $intro ) {
        return $intro;
    }
    $content = get_the_content();
    if ( $content ) {
        return wp_strip_all_tags( strip_shortcodes( $content ) );
    }
    return $excerpt;
}
add_filter( 'get_the_excerpt', 'andfeel_custom_excerpt' );


/* ==========================================================================
   7. 管理画面カスタマイズ
   ========================================================================== */

// 不要なダッシュボードウィジェットを非表示
function andfeel_remove_dashboard_widgets() {
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
    remove_meta_box('dashboard_primary', 'dashboard', 'side');
}
add_action('wp_dashboard_setup', 'andfeel_remove_dashboard_widgets');

// works・post でブロックエディタ（Gutenberg）を無効化 → クラシックエディタ使用
function andfeel_disable_gutenberg( $use_block_editor, $post_type ) {
    if ( $post_type === 'works' || $post_type === 'post' ) {
        return false;
    }
    return $use_block_editor;
}
add_filter('use_block_editor_for_post_type', 'andfeel_disable_gutenberg', 10, 2);

// post のデフォルトエディタを非表示（カスタムメタボックスで代替）
function andfeel_remove_post_editor() {
    remove_post_type_support( 'post', 'editor' );
}
add_action( 'init', 'andfeel_remove_post_editor' );

// works 編集画面の不要メタボックスを非表示
function andfeel_remove_works_metaboxes() {
    remove_meta_box('slugdiv',           'works', 'normal');  // スラッグ
    remove_meta_box('postcustom',        'works', 'normal');  // カスタムフィールド（ACF使用のため）
    remove_meta_box('commentsdiv',       'works', 'normal');  // コメント
    remove_meta_box('commentstatusdiv',  'works', 'normal');  // ディスカッション
    remove_meta_box('authordiv',         'works', 'normal');  // 作成者
    remove_meta_box('revisionsdiv',      'works', 'normal');  // リビジョン
}
add_action('add_meta_boxes', 'andfeel_remove_works_metaboxes');

// post（ブログ）編集画面の不要メタボックスを非表示
function andfeel_remove_post_metaboxes() {
    remove_meta_box('slugdiv',           'post', 'normal');   // スラッグ
    remove_meta_box('postcustom',        'post', 'normal');   // カスタムフィールド
    remove_meta_box('commentsdiv',       'post', 'normal');   // コメント
    remove_meta_box('commentstatusdiv',  'post', 'normal');   // ディスカッション
    remove_meta_box('authordiv',         'post', 'normal');   // 作成者
    remove_meta_box('revisionsdiv',      'post', 'normal');   // リビジョン
    remove_meta_box('trackbacksdiv',     'post', 'normal');   // トラックバック
    remove_meta_box('tagsdiv-post_tag',  'post', 'side');     // タグ

    if ( ! current_user_can( 'administrator' ) ) {
        remove_meta_box('categorydiv',  'post', 'side');     // カテゴリー
        remove_meta_box('ao_metabox',  'post', 'advanced'); // Autoptimize
        remove_meta_box('ao_metabox',  'post', 'normal');   // Autoptimize（位置違い対策）
        remove_meta_box('ao_metabox',  'post', 'side');     // Autoptimize（位置違い対策）
    }
}
add_action('add_meta_boxes', 'andfeel_remove_post_metaboxes', 99);

// メディアモーダルのキャプション・説明フィールドを非表示
function andfeel_hide_media_fields() {
    echo '<style>
        .attachment-details .setting[data-setting="caption"],
        .attachment-details .setting[data-setting="description"],
        .media-sidebar .setting[data-setting="caption"],
        .media-sidebar .setting[data-setting="description"] { display: none !important; }
    </style>';
}
add_action('admin_head', 'andfeel_hide_media_fields');

// works 編集画面のカラム数をデフォルト1列にする
function andfeel_screen_layout( $columns ) {
    $columns['works'] = 1;
    return $columns;
}
add_filter('screen_layout_columns', 'andfeel_screen_layout');
add_filter('get_user_option_screen_layout_works', function() { return 1; });

/* ==========================================================================
   7b. 作品データ メタボックス（ACF不要・テーマ内蔵）
   ========================================================================== */

/**
 * 作品データ メタボックス登録
 */
function andfeel_works_data_metabox() {
    add_meta_box(
        'works_data_box',
        '作品の詳細',
        'andfeel_works_data_html',
        'works',
        'normal',
        'default'
    );
}
add_action( 'add_meta_boxes', 'andfeel_works_data_metabox' );

/**
 * 作品データ メタボックスHTML
 */
function andfeel_works_data_html( $post ) {
    wp_nonce_field( 'andfeel_works_data_nonce', 'andfeel_works_data_nonce_field' );
    $location  = get_post_meta( $post->ID, 'works_location',  true );
    $year      = get_post_meta( $post->ID, 'works_year',      true );
    $structure = get_post_meta( $post->ID, 'works_structure',  true );
    $area      = get_post_meta( $post->ID, 'works_area',      true );
    ?>
    <table class="form-table" style="margin:0;">
        <tr>
            <th style="width:100px;"><label for="works_location">所在地</label></th>
            <td><input type="text" id="works_location" name="works_location" value="<?php echo esc_attr( $location ); ?>" placeholder="徳島県徳島市" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="works_year">竣工年</label></th>
            <td><input type="text" id="works_year" name="works_year" value="<?php echo esc_attr( $year ); ?>" placeholder="2024" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="works_structure">構造</label></th>
            <td><input type="text" id="works_structure" name="works_structure" value="<?php echo esc_attr( $structure ); ?>" placeholder="木造2階建" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="works_area">延床面積</label></th>
            <td><input type="text" id="works_area" name="works_area" value="<?php echo esc_attr( $area ); ?>" placeholder="120.50㎡" class="regular-text"></td>
        </tr>
    </table>
    <?php
}

/**
 * 作品データ保存
 */
function andfeel_works_data_save( $post_id ) {
    if ( ! isset( $_POST['andfeel_works_data_nonce_field'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['andfeel_works_data_nonce_field'], 'andfeel_works_data_nonce' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $fields = array( 'works_location', 'works_year', 'works_structure', 'works_area' );
    foreach ( $fields as $key ) {
        if ( isset( $_POST[ $key ] ) ) {
            update_post_meta( $post_id, $key, sanitize_text_field( $_POST[ $key ] ) );
        }
    }
}
add_action( 'save_post_works', 'andfeel_works_data_save' );


/* ==========================================================================
   7c. カスタムギャラリーメタボックス — 複数画像を一括選択
   ========================================================================== */

/**
 * メタボックス登録
 */
function andfeel_works_gallery_metabox() {
    add_meta_box(
        'works_gallery_box',
        'ギャラリー写真（最大8枚・複数選択可）',
        'andfeel_works_gallery_html',
        'works',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'andfeel_works_gallery_metabox' );

/**
 * メタボックスHTML出力
 */
function andfeel_works_gallery_html( $post ) {
    wp_nonce_field( 'andfeel_gallery_nonce', 'andfeel_gallery_nonce_field' );
    $image_ids = get_post_meta( $post->ID, 'works_gallery', true );
    $image_ids = is_array( $image_ids ) ? $image_ids : array();
    ?>
    <div id="works-gallery-wrap">
        <div id="works-gallery-list" style="display:flex; flex-wrap:wrap; gap:12px; margin-bottom:16px;">
            <?php foreach ( $image_ids as $id ) :
                $thumb = wp_get_attachment_image_url( $id, 'thumbnail' );
                if ( ! $thumb ) continue;
            ?>
            <div class="works-gallery-thumb" data-id="<?php echo esc_attr( $id ); ?>" style="position:relative; width:120px; height:120px; border:1px solid #ddd; border-radius:4px; overflow:hidden; cursor:move;">
                <img src="<?php echo esc_url( $thumb ); ?>" style="width:100%; height:100%; object-fit:cover;">
                <button type="button" class="works-gallery-remove" style="position:absolute; top:2px; right:2px; background:rgba(0,0,0,.6); color:#fff; border:none; border-radius:50%; width:22px; height:22px; cursor:pointer; font-size:14px; line-height:20px; text-align:center;">&times;</button>
            </div>
            <?php endforeach; ?>
        </div>
        <input type="hidden" name="works_gallery_ids" id="works-gallery-ids" value="<?php echo esc_attr( implode( ',', $image_ids ) ); ?>">
        <button type="button" id="works-gallery-add" class="button button-primary" style="margin-right:8px;">写真を追加</button>
        <span style="color:#666; font-size:13px;">メディアライブラリから複数枚を一度に選択できます。ドラッグで並び替え可能。</span>
    </div>

    <script>
    jQuery(function($){
        var $list   = $('#works-gallery-list');
        var $input  = $('#works-gallery-ids');
        var maxImages = 8;

        // 並び替え（jQuery UI Sortable は WP 管理画面で利用可能）
        $list.sortable({
            tolerance: 'pointer',
            update: function(){ updateIds(); }
        });

        // ID更新
        function updateIds() {
            var ids = [];
            $list.find('.works-gallery-thumb').each(function(){
                ids.push( $(this).data('id') );
            });
            $input.val( ids.join(',') );
        }

        // 削除
        $list.on('click', '.works-gallery-remove', function(e){
            e.preventDefault();
            $(this).closest('.works-gallery-thumb').remove();
            updateIds();
        });

        // 追加
        $('#works-gallery-add').on('click', function(e){
            e.preventDefault();
            var currentCount = $list.find('.works-gallery-thumb').length;
            var remaining = maxImages - currentCount;
            if ( remaining <= 0 ) {
                alert('最大8枚までです。');
                return;
            }

            var frame = wp.media({
                title: 'ギャラリー写真を選択（最大' + remaining + '枚）',
                button: { text: '選択した写真を追加' },
                multiple: true,
                library: { type: 'image' }
            });

            frame.on('select', function(){
                var selection = frame.state().get('selection').toJSON();
                var added = 0;
                $.each(selection, function(i, att){
                    if ( $list.find('.works-gallery-thumb').length >= maxImages ) return false;
                    var thumb = att.sizes && att.sizes.thumbnail ? att.sizes.thumbnail.url : att.url;
                    var $item = $('<div class="works-gallery-thumb" data-id="' + att.id + '" style="position:relative; width:120px; height:120px; border:1px solid #ddd; border-radius:4px; overflow:hidden; cursor:move;">' +
                        '<img src="' + thumb + '" style="width:100%; height:100%; object-fit:cover;">' +
                        '<button type="button" class="works-gallery-remove" style="position:absolute; top:2px; right:2px; background:rgba(0,0,0,.6); color:#fff; border:none; border-radius:50%; width:22px; height:22px; cursor:pointer; font-size:14px; line-height:20px; text-align:center;">&times;</button>' +
                        '</div>');
                    $list.append($item);
                    added++;
                });
                updateIds();
            });

            frame.open();
        });
    });
    </script>
    <?php
}

/**
 * メタボックス保存
 */
function andfeel_works_gallery_save( $post_id ) {
    if ( ! isset( $_POST['andfeel_gallery_nonce_field'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['andfeel_gallery_nonce_field'], 'andfeel_gallery_nonce' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = isset( $_POST['works_gallery_ids'] ) ? sanitize_text_field( $_POST['works_gallery_ids'] ) : '';
    if ( empty( $raw ) ) {
        delete_post_meta( $post_id, 'works_gallery' );
    } else {
        $ids = array_map( 'absint', array_filter( explode( ',', $raw ) ) );
        update_post_meta( $post_id, 'works_gallery', $ids );
    }
}
add_action( 'save_post_works', 'andfeel_works_gallery_save' );


/* ==========================================================================
   7d. ブログ記事構成メタボックス（導入文 + H2×3 + 本文×3）
   ========================================================================== */

function andfeel_blog_structure_meta_box() {
    add_meta_box(
        'andfeel_blog_structure',
        'ブログ記事構成',
        'andfeel_blog_structure_render',
        'post',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'andfeel_blog_structure_meta_box' );

function andfeel_blog_structure_render( $post ) {
    wp_nonce_field( 'andfeel_blog_structure', 'andfeel_blog_structure_nonce' );

    $intro = get_post_meta( $post->ID, 'blog_intro', true );
    $sections = [];
    for ( $i = 1; $i <= 3; $i++ ) {
        $sections[ $i ] = [
            'h2'   => get_post_meta( $post->ID, "blog_section_h2_{$i}", true ),
            'body' => get_post_meta( $post->ID, "blog_section_body_{$i}", true ),
        ];
    }
    ?>
    <style>
        #andfeel_blog_structure .inside { padding: 12px 16px; }
        .andfeel-blog-field { margin-bottom: 24px; }
        .andfeel-blog-field label {
            display: block;
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 6px;
            color: #1d2327;
        }
        .andfeel-blog-field textarea,
        .andfeel-blog-field input[type="text"] {
            width: 100%;
            box-sizing: border-box;
            font-size: 14px;
            padding: 10px 12px;
            border: 1px solid #8c8f94;
            border-radius: 4px;
            background: #fff;
        }
        .andfeel-blog-field textarea:focus,
        .andfeel-blog-field input[type="text"]:focus {
            border-color: #2271b1;
            box-shadow: 0 0 0 1px #2271b1;
            outline: none;
        }
        .andfeel-blog-field input[type="text"] {
            font-size: 18px;
            font-weight: 600;
            letter-spacing: 0.05em;
        }
        .andfeel-blog-section-divider {
            border: none;
            border-top: 2px solid #c3c4c7;
            margin: 32px 0 24px;
        }
        .andfeel-blog-section-label {
            font-size: 13px;
            font-weight: 700;
            color: #50575e;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 16px;
        }
        .andfeel-blog-hint {
            font-size: 12px;
            color: #787c82;
            margin-top: 4px;
        }
    </style>

    <div class="andfeel-blog-field">
        <label for="blog_intro">導入文</label>
        <textarea id="blog_intro" name="blog_intro" rows="4" placeholder="記事の冒頭に表示されるリード文を書きます（2〜3文で記事の概要を）"><?php echo esc_textarea( $intro ); ?></textarea>
        <p class="andfeel-blog-hint">※ 検索結果やSNSシェア時の説明文にも使われます</p>
    </div>

    <?php for ( $i = 1; $i <= 3; $i++ ) : ?>
        <hr class="andfeel-blog-section-divider">
        <p class="andfeel-blog-section-label">セクション <?php echo $i; ?><?php if ( $i > 1 ) : ?> <span style="font-weight:400;color:#a7aaad;">（空欄でもOK）</span><?php endif; ?></p>

        <div class="andfeel-blog-field">
            <label for="blog_section_h2_<?php echo $i; ?>">見出し（H2）</label>
            <input type="text" id="blog_section_h2_<?php echo $i; ?>" name="blog_section_h2_<?php echo $i; ?>" value="<?php echo esc_attr( $sections[ $i ]['h2'] ); ?>" placeholder="見出しを入力">
        </div>

        <div class="andfeel-blog-field">
            <label for="blog_section_body_<?php echo $i; ?>">本文</label>
            <textarea id="blog_section_body_<?php echo $i; ?>" name="blog_section_body_<?php echo $i; ?>" rows="8" placeholder="本文を入力"><?php echo esc_textarea( $sections[ $i ]['body'] ); ?></textarea>
        </div>
    <?php endfor; ?>
    <?php
}

function andfeel_blog_structure_save( $post_id ) {
    if ( ! isset( $_POST['andfeel_blog_structure_nonce'] ) ||
         ! wp_verify_nonce( $_POST['andfeel_blog_structure_nonce'], 'andfeel_blog_structure' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['blog_intro'] ) ) {
        update_post_meta( $post_id, 'blog_intro', sanitize_textarea_field( wp_unslash( $_POST['blog_intro'] ) ) );
    }

    for ( $i = 1; $i <= 3; $i++ ) {
        $h2_key   = "blog_section_h2_{$i}";
        $body_key = "blog_section_body_{$i}";
        if ( isset( $_POST[ $h2_key ] ) ) {
            update_post_meta( $post_id, $h2_key, sanitize_text_field( wp_unslash( $_POST[ $h2_key ] ) ) );
        }
        if ( isset( $_POST[ $body_key ] ) ) {
            update_post_meta( $post_id, $body_key, sanitize_textarea_field( wp_unslash( $_POST[ $body_key ] ) ) );
        }
    }
}
add_action( 'save_post', 'andfeel_blog_structure_save' );


/* ---------- 7.5 固定ページの日本語タイトルマッピング ---------- */

function andfeel_get_page_display_title() {
    if (!is_page()) return null;
    $map = [
        'page-about.php'   => '私について',
        'page-contact.php' => 'ご相談',
        'page-privacy.php' => 'プライバシーポリシー',
    ];
    $template = basename(get_page_template());
    return $map[$template] ?? null;
}

function andfeel_custom_title_parts($title_parts) {
    $custom = andfeel_get_page_display_title();
    if ($custom) {
        $title_parts['title'] = $custom;
    }
    return $title_parts;
}
add_filter('document_title_parts', 'andfeel_custom_title_parts');


/* ==========================================================================
   8. SEO — meta description / OGP / Twitter Card / canonical
   ========================================================================== */

function andfeel_seo_meta() {
    $site_name   = get_bloginfo('name');
    $site_url    = home_url('/');
    $theme_uri   = get_template_directory_uri();
    $default_ogp = "{$theme_uri}/images/ogp.jpg";

    // ページごとの値を設定
    if (is_front_page()) {
        $title       = $site_name;
        $description = '住まいは、日々を包み、静かに時間を重ねていく場所。季節の巡りや暮らしの中に息づく所作など、目に見えないものを感じとることから設計ははじまります。気配に耳を澄ませ、対話を重ねながら、ふさわしいかたちを丁寧に結びます。';
        $url         = $site_url;
        $og_type     = 'website';
        $og_image    = $default_ogp;
    } elseif (is_singular()) {
        $page_title  = andfeel_get_page_display_title() ?? get_the_title();
        $title       = $page_title . ' | ' . $site_name;
        $description = has_excerpt() ? wp_strip_all_tags(get_the_excerpt()) : mb_substr(wp_strip_all_tags(get_the_content()), 0, 120) . '…';
        $url         = get_permalink();
        $og_type     = 'article';
        $og_image    = has_post_thumbnail() ? get_the_post_thumbnail_url(null, 'full') : $default_ogp;
    } elseif (is_post_type_archive('works')) {
        $title       = 'かたち | ' . $site_name;
        $description = '光が差し、風が抜け、気配がやわらかく重なる。それぞれの場所に生まれた「かたち」を静かに並べています。';
        $url         = get_post_type_archive_link('works');
        $og_type     = 'website';
        $og_image    = $default_ogp;
    } elseif (is_home()) {
        $title       = '日々 | ' . $site_name;
        $description = '日々の中で拾い上げた気づきや思考の断片を静かに綴っています。';
        $url         = get_permalink(get_option('page_for_posts'));
        $og_type     = 'website';
        $og_image    = $default_ogp;
    } else {
        $title       = wp_title('|', false, 'right') . $site_name;
        $description = get_bloginfo('description');
        $url         = home_url(esc_url_raw($_SERVER['REQUEST_URI']));
        $og_type     = 'website';
        $og_image    = $default_ogp;
    }

    // 出力
    echo "\n<!-- SEO -->\n";
    echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
    echo '<link rel="canonical" href="' . esc_url($url) . '">' . "\n";

    // OGP
    echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
    echo '<meta property="og:type" content="' . esc_attr($og_type) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url($url) . '">' . "\n";
    echo '<meta property="og:image" content="' . esc_url($og_image) . '">' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '">' . "\n";
    echo '<meta property="og:locale" content="ja_JP">' . "\n";

    // Twitter Card
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
}
add_action('wp_head', 'andfeel_seo_meta', 1);


/* ==========================================================================
   9. SEO — Google Fonts preconnect
   ========================================================================== */

function andfeel_preconnect_hints() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}
add_action('wp_head', 'andfeel_preconnect_hints', 0);


/* ---------- 9.5 Google Analytics / Search Console（本番公開時に有効化） ---------- */
/**
 * GA4 トラッキングコードと Search Console 検証メタタグ
 * 本番公開時に下記の定数を wp-config.php または functions.php 冒頭で定義すること:
 *   define('ANDFEEL_GA4_ID', 'G-XXXXXXXXXX');
 *   define('ANDFEEL_GSC_VERIFICATION', 'xxxxxxxxxxxxxxxx');
 */
function andfeel_google_analytics() {
    if ( ! defined( 'ANDFEEL_GA4_ID' ) || empty( ANDFEEL_GA4_ID ) ) return;
    ?>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( ANDFEEL_GA4_ID ); ?>"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '<?php echo esc_js( ANDFEEL_GA4_ID ); ?>');
    </script>
    <?php
}
add_action( 'wp_head', 'andfeel_google_analytics', 1 );

function andfeel_search_console_verification() {
    $code = defined( 'ANDFEEL_GSC_VERIFICATION' ) && ! empty( ANDFEEL_GSC_VERIFICATION ) ? ANDFEEL_GSC_VERIFICATION : 'sSyYdCfgXIzWnaqeMybn8E0l3zeMjsIe2gI9S4yTF20';
    echo '<meta name="google-site-verification" content="' . esc_attr( $code ) . '">' . "\n";
}
add_action( 'wp_head', 'andfeel_search_console_verification', 0 );


/* ==========================================================================
   10. SEO — JSON-LD 構造化データ
   ========================================================================== */

function andfeel_jsonld_schema() {
    $theme_uri = get_template_directory_uri();

    // 全ページ共通: LocalBusiness
    if (is_front_page()) {
        $schema = [
            '@context'    => 'https://schema.org',
            '@type'       => 'ArchitecturalFirm',
            'name'        => 'and feel.建築設計事務所',
            'url'         => home_url('/'),
            'logo'        => "{$theme_uri}/images/logo/logo.png",
            'image'       => "{$theme_uri}/images/ogp.jpg",
            'description' => '徳島を拠点に、時間の移ろいや季節の変化を感じとれる空間を丁寧に設計しています。',
            'address'     => [
                '@type'           => 'PostalAddress',
                'streetAddress'   => '上八万町西山448',
                'addressLocality' => '徳島市',
                'addressRegion'   => '徳島県',
                'postalCode'      => '770-8041',
                'addressCountry'  => 'JP',
            ],
            'email'       => 'info@andfeel-architect.com',
            'founder'     => [
                '@type' => 'Person',
                'name'  => '奥野智士',
            ],
            'foundingDate' => '2024',
            'areaServed'   => '徳島県',
        ];
        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
    }

    // ブログ記事: Article
    if (is_singular('post')) {
        $schema = [
            '@context'      => 'https://schema.org',
            '@type'         => 'Article',
            'headline'      => get_the_title(),
            'datePublished' => get_the_date('c'),
            'dateModified'  => get_the_modified_date('c'),
            'author'        => [
                '@type' => 'Person',
                'name'  => get_the_author(),
            ],
            'publisher'     => [
                '@type' => 'Organization',
                'name'  => 'and feel.建築設計事務所',
                'logo'  => [
                    '@type' => 'ImageObject',
                    'url'   => "{$theme_uri}/images/logo/logo.png",
                ],
            ],
            'mainEntityOfPage' => get_permalink(),
        ];
        if (has_post_thumbnail()) {
            $schema['image'] = get_the_post_thumbnail_url(null, 'full');
        }
        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
    }

    // パンくず: BreadcrumbList（フロントページ以外）
    if (! is_front_page()) {
        $items   = [];
        $items[] = ['@type' => 'ListItem', 'position' => 1, 'name' => 'HOME', 'item' => home_url('/')];

        if (is_page()) {
            $items[] = ['@type' => 'ListItem', 'position' => 2, 'name' => andfeel_get_page_display_title() ?? get_the_title()];
        } elseif (is_singular('works')) {
            $items[] = ['@type' => 'ListItem', 'position' => 2, 'name' => 'かたち', 'item' => get_post_type_archive_link('works')];
            $items[] = ['@type' => 'ListItem', 'position' => 3, 'name' => get_the_title()];
        } elseif (is_singular('post')) {
            $items[] = ['@type' => 'ListItem', 'position' => 2, 'name' => '日々', 'item' => get_permalink(get_option('page_for_posts'))];
            $items[] = ['@type' => 'ListItem', 'position' => 3, 'name' => get_the_title()];
        } elseif (is_post_type_archive('works')) {
            $items[] = ['@type' => 'ListItem', 'position' => 2, 'name' => 'かたち'];
        } elseif (is_home()) {
            $items[] = ['@type' => 'ListItem', 'position' => 2, 'name' => '日々'];
        }

        $schema = [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
    }
}
add_action('wp_footer', 'andfeel_jsonld_schema', 99);


/* ==========================================================================
   11. Contact Form 7 — バリデーションメッセージ日本語化 & 送信ボタン制御
   ========================================================================== */

/**
 * CF7 の承諾確認（acceptance）バリデーションメッセージをカスタマイズ
 */
add_filter( 'wpcf7_display_message', function( $message, $status ) {
    if ( $status === 'accept_terms' ) {
        return 'プライバシーポリシーに同意してください。';
    }
    if ( $status === 'validation_error' ) {
        return '入力内容をご確認ください。';
    }
    return $message;
}, 10, 2 );

/**
 * CF7 送信ボタンの disabled を解除して AJAX バリデーションでエラー表示する
 * デフォルトでは acceptance 未チェック時にボタンが無効化され、エラーメッセージが出ない
 */
function andfeel_cf7_enable_submit() {
    if ( ! is_page( 'contact' ) ) return;
    wp_add_inline_script( 'contact-form-7', "
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.querySelector('.wpcf7-form');
            if (!form) return;
            var btn = form.querySelector('input[type=\"submit\"]');
            if (!btn) return;
            btn.removeAttribute('disabled');
            new MutationObserver(function(mutations) {
                mutations.forEach(function(m) {
                    if (m.attributeName === 'disabled' && btn.disabled) {
                        btn.removeAttribute('disabled');
                    }
                });
            }).observe(btn, { attributes: true });
        });
    " );
}
add_action( 'wp_enqueue_scripts', 'andfeel_cf7_enable_submit', 20 );

/**
 * CF7 acceptance フィールドのサーバー側バリデーション
 * ボタン disabled 解除に伴い、サーバー側でも未チェックを検出してエラー表示
 */
add_filter( 'wpcf7_validate_acceptance', function( $result, $tag ) {
    if ( $tag->name === 'your-privacy' ) {
        $value = isset( $_POST['your-privacy'] ) ? sanitize_text_field( wp_unslash( $_POST['your-privacy'] ) ) : '';
        if ( empty( $value ) ) {
            $result->invalidate( $tag, 'プライバシーポリシーに同意してください。' );
        }
    }
    return $result;
}, 20, 2 );


/* ==========================================================================
   12. HTTP セキュリティヘッダー
   ========================================================================== */

function andfeel_security_headers() {
    if ( is_admin() ) {
        return;
    }
    header( 'X-Content-Type-Options: nosniff' );
    header( 'X-Frame-Options: SAMEORIGIN' );
    header( 'Referrer-Policy: strict-origin-when-cross-origin' );
    header( 'Permissions-Policy: camera=(), microphone=(), geolocation=()' );
}
add_action( 'send_headers', 'andfeel_security_headers' );


/* ===== アップロード上限を引き上げる（.htaccess に php_value 追記） ===== */
function andfeel_increase_upload_limit() {
    $htaccess = ABSPATH . '.htaccess';
    if ( file_exists( $htaccess ) ) {
        $contents = file_get_contents( $htaccess );
        if ( strpos( $contents, 'upload_max_filesize' ) === false ) {
            $upload_rules = "\n# BEGIN Upload Limit\nphp_value upload_max_filesize 50M\nphp_value post_max_size 50M\nphp_value memory_limit 256M\nphp_value max_execution_time 300\n# END Upload Limit\n";
            @file_put_contents( $htaccess, $upload_rules . $contents );
        }
    }
}
add_action( 'admin_init', 'andfeel_increase_upload_limit' );


/* ==========================================================================
   13. 管理者以外の権限制限（投稿・かたち のみ操作可能）
   ========================================================================== */

/**
 * 管理者以外のログインユーザーに投稿・かたち編集に必要な権限を動的に付与
 * DBを変更せず、リクエストごとに権限を追加する安全な方式
 */
function andfeel_grant_content_caps( $allcaps, $caps, $args, $user ) {
    // 管理者はそのまま
    if ( ! empty( $allcaps['administrator'] ) ) {
        return $allcaps;
    }
    // ログイン済みユーザー（read権限あり）のみ対象
    if ( empty( $allcaps['read'] ) ) {
        return $allcaps;
    }
    // 投稿（ブログ）・かたち（works）の編集・削除に必要な権限
    $allcaps['edit_posts']             = true;
    $allcaps['publish_posts']          = true;
    $allcaps['delete_posts']           = true;
    $allcaps['edit_published_posts']   = true;
    $allcaps['delete_published_posts'] = true;
    $allcaps['edit_others_posts']      = true;  // 他ユーザーの記事を編集
    $allcaps['delete_others_posts']    = true;  // 他ユーザーの記事を削除
    $allcaps['upload_files']           = true;  // 投稿内の画像アップロード用
    return $allcaps;
}
add_filter( 'user_has_cap', 'andfeel_grant_content_caps', 10, 4 );

/**
 * 管理者以外のユーザーから不要な管理メニューを非表示にする
 * 残すもの: ダッシュボード / 投稿 / かたち（works）
 */
function andfeel_restrict_admin_menus() {
    if ( current_user_can( 'administrator' ) ) {
        return;
    }

    // 非表示にするメニュー
    remove_menu_page( 'edit.php?post_type=page' );  // 固定ページ
    remove_menu_page( 'upload.php' );                // メディア
    remove_menu_page( 'edit-comments.php' );         // コメント
    remove_menu_page( 'themes.php' );                // 外観
    remove_menu_page( 'plugins.php' );               // プラグイン
    remove_menu_page( 'users.php' );                 // ユーザー
    remove_menu_page( 'tools.php' );                 // ツール
    remove_menu_page( 'options-general.php' );       // 設定
    remove_menu_page( 'wpcf7' );                     // Contact Form 7
    remove_menu_page( 'aioseo' );                    // All in One SEO
    remove_menu_page( 'cptui_main_menu' );           // CPT UI
    remove_menu_page( 'edit.php?post_type=acf-field-group' ); // ACF
    remove_menu_page( 'backwpup' );                  // BackWPup
    remove_menu_page( 'ewww-image-optimizer' );      // EWWW
    remove_menu_page( 'autoptimize' );               // Autoptimize
    remove_menu_page( 'ai1wm_export' );              // All-in-One WP Migration
    remove_menu_page( 'wp_mail_smtp' );              // WP Mail SMTP
    // プラグイン由来のカスタム投稿タイプ
    remove_menu_page( 'edit.php?post_type=class' );       // Classes
    remove_menu_page( 'edit.php?post_type=instructor' );  // Instructors
}
add_action( 'admin_menu', 'andfeel_restrict_admin_menus', 999 );

/**
 * プラグイン（AIOS等）がサーバーサイドで削除したメニューをJS（admin_footer）で強制復元
 * サーバーサイドの add_menu_page では復元できないケースへの最終手段
 */
function andfeel_force_restore_menus_js() {
    if ( current_user_can( 'administrator' ) ) {
        return;
    }
    ?>
    <script>
    (function(){
        var menu = document.getElementById('adminmenu');
        if (!menu) return;

        // ダッシュボードが存在するか確認
        var hasDashboard = menu.querySelector('a[href="index.php"]');
        var hasPosts = menu.querySelector('a[href="edit.php"]');

        // メニュー先頭のセパレーター（挿入位置の基準）
        var firstItem = menu.querySelector('li');

        if (!hasDashboard) {
            var dashLi = document.createElement('li');
            dashLi.className = 'menu-top menu-icon-dashboard';
            dashLi.id = 'menu-dashboard';
            var currentPage = window.location.href;
            if (currentPage.indexOf('index.php') !== -1 || currentPage.match(/\/wp-admin\/$/)) {
                dashLi.className += ' current';
            }
            dashLi.innerHTML = '<a href="index.php" class="menu-top"><div class="wp-menu-arrow"><div></div></div><div class="wp-menu-image dashicons-before dashicons-dashboard" aria-hidden="true"><br></div><div class="wp-menu-name">ダッシュボード</div></a>';
            menu.insertBefore(dashLi, firstItem);
        }

        if (!hasPosts) {
            var postLi = document.createElement('li');
            postLi.className = 'menu-top menu-icon-post';
            postLi.id = 'menu-posts';
            var currentPage2 = window.location.href;
            if (currentPage2.indexOf('edit.php') !== -1 && currentPage2.indexOf('post_type=') === -1) {
                postLi.className += ' current';
            }
            postLi.innerHTML = '<a href="edit.php" class="menu-top"><div class="wp-menu-arrow"><div></div></div><div class="wp-menu-image dashicons-before dashicons-admin-post" aria-hidden="true"><br></div><div class="wp-menu-name">投稿</div></a>';
            // かたちメニューの前に挿入
            var worksMenu = document.getElementById('menu-posts-works');
            if (worksMenu) {
                menu.insertBefore(postLi, worksMenu);
            } else {
                menu.appendChild(postLi);
            }
        }
    })();
    </script>
    <?php
}
add_action( 'admin_footer', 'andfeel_force_restore_menus_js' );

/**
 * 管理者以外のユーザーから管理バーの不要項目を非表示にする
 */
function andfeel_restrict_admin_bar( $wp_admin_bar ) {
    if ( current_user_can( 'administrator' ) ) {
        return;
    }

    $wp_admin_bar->remove_node( 'new-page' );       // 新規 → 固定ページ
    $wp_admin_bar->remove_node( 'new-media' );       // 新規 → メディア
    $wp_admin_bar->remove_node( 'new-user' );        // 新規 → ユーザー
    $wp_admin_bar->remove_node( 'comments' );        // コメント
}
add_action( 'admin_bar_menu', 'andfeel_restrict_admin_bar', 999 );

/**
 * 管理者以外が許可されていないページにアクセスした場合、ダッシュボードへリダイレクト
 * （投稿・works 編集画面内のメディアアップロードは引き続き使用可能）
 */
function andfeel_restrict_admin_pages() {
    if ( current_user_can( 'administrator' ) ) {
        return;
    }

    global $pagenow;

    // 直接アクセスを禁止するページ
    $restricted_pages = array(
        'edit.php'      => array( 'page', 'class', 'instructor', 'acf-field-group' ),
        'post-new.php'  => array( 'page', 'class', 'instructor', 'acf-field-group' ),
        'upload.php'    => array(),           // メディアライブラリ
        'themes.php'    => array(),           // 外観
        'plugins.php'   => array(),           // プラグイン
        'users.php'     => array(),           // ユーザー
        'tools.php'     => array(),           // ツール
        'options-general.php' => array(),     // 設定
    );

    foreach ( $restricted_pages as $page => $post_types ) {
        if ( $pagenow === $page ) {
            if ( empty( $post_types ) ) {
                wp_redirect( admin_url() );
                exit;
            }
            if ( isset( $_GET['post_type'] ) && in_array( $_GET['post_type'], $post_types, true ) ) {
                wp_redirect( admin_url() );
                exit;
            }
        }
    }
}
add_action( 'admin_init', 'andfeel_restrict_admin_pages' );

