<?php
/**
 * Template Name: 私について
 *
 * @package andfeel
 */
defined( 'ABSPATH' ) || exit;

get_header();
$theme_uri = get_template_directory_uri();
?>

<main>

<!-- ページヒーロー -->
<section class="page-hero" aria-label="私についてページヒーロー">
  <div class="page-hero-deco js-fade-in" aria-hidden="true">
    <span class="deco-label">ABOUT</span>
    <div class="deco-line-about"></div>
    <img src="<?php echo esc_url( $theme_uri ); ?>/images/icons/arrow-down.svg" alt="" width="10" height="15">
  </div>
  <div class="page-hero-content">
    <div class="hero-title-row">
      <h1>
        理想の建築は<br>
        ひとつの対話からはじまる。
      </h1>
      <div class="hero-h-line" aria-hidden="true"></div>
    </div>
    <p class="section-body">
      時間の移ろいや季節の変化、光や風の気配といった、日常の中では見過ごされがちな感覚を大切にしています。新築住宅や店舗設計、改修工事において、機能やデザインだけでなく、「感じること」を設計に取り込むことを考えています。
    </p>
  </div>
</section>

<!-- プロフィールセクション -->
<section class="profile-section" aria-label="プロフィール">
  <div class="profile-inner">
    <img src="<?php echo esc_url( $theme_uri ); ?>/images/about/profile.jpg" class="profile-photo" alt="奥野智士 プロフィール写真" width="500" height="750" loading="lazy">
    <div class="profile-info">
      <p class="profile-name">奥野智士　/　<span class="profile-name-en">Satoshi Okuno</span></p>
      <div class="profile-career">
        <p class="profile-career-row">
          <span class="profile-career-year">1995</span>
          <span class="profile-career-text">徳島県板野郡</span>
        </p>
        <p class="profile-career-row">
          <span class="profile-career-year">2018</span>
          <span class="profile-career-text">修成建設専門学校 CGデザイン学科　卒</span>
        </p>
        <div class="profile-career-vline" aria-hidden="true">
          <span class="profile-vline"></span>
        </div>
        <p class="profile-career-row">
          <span class="profile-career-year" aria-hidden="true"></span>
          <span class="profile-career-text">設計事務所 2社を経て</span>
        </p>
        <p class="profile-career-row">
          <span class="profile-career-year">2024</span>
          <span class="profile-career-text"><span class="profile-brand">and feel.</span>建築設計事務所 設立</span>
        </p>
      </div>
    </div>
  </div>
</section>

<!-- 区切り線 -->
<hr class="section-divider" aria-hidden="true">

<!-- 事務所情報セクション -->
<section class="office-section" aria-label="事務所情報">
  <div class="office-inner">
    <div class="office-col">
      <p class="office-col-title">【自邸兼事務所】</p>
      <p>〒770-8041</p>
      <p>徳島県徳島市上八万町西山 448</p>
      <div class="office-map js-map" data-location="office"></div>
    </div>
    <div class="office-col">
      <p class="office-col-title">【スタジオ】</p>
      <p>〒770-0922</p>
      <p>徳島県徳島市鷹匠町2丁目20 三谷ビル2F北</p>
      <div class="office-map js-map" data-location="studio"></div>
    </div>
  </div>
</section>

</main>

<?php andfeel_breadcrumb(); ?>

<?php get_footer(); ?>
