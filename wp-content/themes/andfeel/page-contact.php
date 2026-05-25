<?php
/**
 * Template Name: ご相談
 *
 * @package andfeel
 */
defined( 'ABSPATH' ) || exit;

get_header();
$theme_uri = get_template_directory_uri();
?>

<main>

<!-- ページヒーロー -->
<section class="page-hero" aria-label="ご相談ページヒーロー">
  <div class="page-hero-deco js-fade-in" aria-hidden="true">
    <span class="deco-label">CONTACT</span>
    <div class="deco-line-contact"></div>
    <img src="<?php echo esc_url( $theme_uri ); ?>/images/icons/arrow-down.svg" alt="" width="10" height="15">
  </div>
  <div class="page-hero-content">
    <div class="hero-title-row">
      <h1>設計のこと、空間のこと、<br>お気軽にご相談ください。</h1>
    </div>
    <p class="hero-body">
      住まいや建築に関するご相談を、丁寧にお伺いしています。<br>
      土地やご予算、想いに寄り添いながら、最適なかたちをご提案します。
    </p>
  </div>
</section>

<!-- TELボックス -->
<div class="tel-box">
  <div class="tel-box-inner">
    <div class="tel-box-text">
      <p class="tel-label">-TEL-</p>
      <p class="tel-number"><a href="tel:+819071469702">090-7146-9702</a></p>
      <p class="tel-hours">
        <span class="tel-hours-label">営業時間</span><span class="tel-hours-time">　9:00-18:00</span>
      </p>
    </div>
    <img src="<?php echo esc_url( $theme_uri ); ?>/images/contact/tel-photo.jpg" class="tel-box-photo" alt="" width="2048" height="1366" loading="lazy">
  </div>
</div>

<!-- お問い合わせフォーム（Contact Form 7） -->
<div class="contact-form-wrap">
  <div class="contact-form-inner">
    <?php echo do_shortcode( '[contact-form-7 id="599878b" title="お問い合わせフォーム"]' ); ?>
  </div>
</div>

<!-- 事務所情報 -->
<div class="office-info">
  <div class="office-cols">
    <div class="office-col">
      <p class="office-col-text"><span class="office-col-title">【自邸兼事務所】</span><br>〒770-8041<br>徳島県徳島市上八万町西山 448</p>
      <div class="office-map js-map" data-location="office"></div>
    </div>
    <div class="office-col">
      <p class="office-col-text"><span class="office-col-title">【スタジオ】</span><br>〒770-0922<br>徳島県徳島市鷹匠町2丁目20 三谷ビル2F北</p>
      <div class="office-map js-map" data-location="studio"></div>
    </div>
  </div>
</div>

</main>

<?php andfeel_breadcrumb(); ?>

<?php get_footer(); ?>
