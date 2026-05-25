<?php
/**
 * フッターテンプレート
 *
 * @package andfeel
 */
defined( 'ABSPATH' ) || exit;

$theme_uri = get_template_directory_uri();
?>

<!-- フッター -->
<footer class="footer">
  <div class="footer-inner">
    <div class="footer-main">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-logo-col">
        <img src="<?php echo esc_url($theme_uri); ?>/images/logo/logo.svg" alt="and feel. ロゴ" class="footer-logo-img" width="184" height="33">
      </a>
      <address class="footer-info-col">
        <div class="footer-name-area">
          <p class="footer-name">and feel.建築設計事務所</p>
          <p class="footer-email"><a href="mailto:info@andfeel-architect.com">info@andfeel-architect.com</a></p>
        </div>
        <div class="footer-address footer-address-1">
          <p class="footer-address-label">事務所</p>
          <p>〒770-8041</p>
          <p>徳島市上八万町西山448</p>
        </div>
        <div class="footer-address footer-address-2">
          <p class="footer-address-label">徳島スタジオ</p>
          <p>〒770-0922 徳島市鷹匠町2丁目20 三谷ビル2F北</p>
        </div>
        <a href="<?php echo esc_url(get_permalink(get_page_by_path('privacy'))); ?>" class="footer-policy">プライバシーポリシー</a>
      </address>
    </div>
    <p class="footer-copyright">&copy; and feel.architect</p>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
