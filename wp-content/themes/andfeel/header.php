<?php
/**
 * ヘッダーテンプレート
 *
 * @package andfeel
 */
defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- favicon / apple-touch-icon -->
  <link rel="icon" href="<?php echo esc_url(get_template_directory_uri()); ?>/images/favicon/favicon.ico" sizes="any">
  <link rel="icon" href="<?php echo esc_url(get_template_directory_uri()); ?>/images/favicon/favicon.svg" type="image/svg+xml">
  <link rel="apple-touch-icon" href="<?php echo esc_url(get_template_directory_uri()); ?>/images/favicon/apple-touch-icon.png">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- ヘッダー -->
<header class="header">
  <a href="<?php echo esc_url(home_url('/')); ?>" class="header-logo">
    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/logo/logo.svg" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="header-logo-img" width="184" height="33">
  </a>
  <nav class="header-nav">
    <a href="<?php echo esc_url(home_url('/')); ?>" class="nav-mobile-logo">
      <img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/logo/logo.svg" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="nav-mobile-logo-img" width="184" height="33">
    </a>
    <a href="<?php echo esc_url(get_permalink(get_page_by_path('about'))); ?>">
      <span class="nav-slide-text" data-text="ABOUT"><span>私について</span></span>
    </a>
    <a href="<?php echo esc_url(get_post_type_archive_link('works')); ?>">
      <span class="nav-slide-text" data-text="WORKS"><span>かたち</span></span>
    </a>
    <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>">
      <span class="nav-slide-text" data-text="BLOG"><span>日々</span></span>
    </a>
    <div class="nav-divider"></div>
    <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="nav-contact-btn">ご相談</a>
    <a href="https://www.instagram.com/andfeel.architect?igsh=MWpic3ZwNDllazdlcA==" class="nav-instagram" aria-label="Instagram" target="_blank" rel="noopener noreferrer">
      <img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/icons/instagram.svg" alt="Instagram" width="20" height="20">
    </a>
  </nav>
  <button class="hamburger" id="hamburger" aria-label="メニュー" aria-expanded="false">
    <span></span>
    <span></span>
    <span></span>
  </button>
</header>
