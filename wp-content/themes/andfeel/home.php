<?php
/**
 * Blog archive template (home.php)
 *
 * WordPress uses home.php for the blog posts page
 * when a static front page is set.
 *
 * @package andfeel
 */
defined( 'ABSPATH' ) || exit;

get_header();
$theme_uri = get_template_directory_uri();
?>

<main>
<!-- ページヒーロー -->
<section class="page-hero" aria-label="日々ページヒーロー">
  <div class="page-hero-deco js-fade-in" aria-hidden="true">
    <span class="deco-label">BLOG</span>
    <div class="deco-line-blog"></div>
    <img src="<?php echo esc_url( $theme_uri . '/images/icons/arrow-down.svg' ); ?>" alt="" class="deco-arrow-img" width="10" height="16">
  </div>
  <div class="page-hero-content">
    <div class="hero-title-row">
      <h1>思考のかけらは、<br>日常の中にある。</h1>
    </div>
    <p class="hero-body">建築設計や住宅デザイン、店舗づくりの背景にある考え方やプロセス、日々の中で感じたことを綴っています。新築住宅やリノベーション、空間設計に関するヒントやアイデアだけでなく、設計の裏側にある思考や価値観も大切に記録しています。まだかたちになっていない想いや気づきを言葉にすることで、次の建築へとつながる視点を共有していきます。</p>
  </div>
</section>

<!-- ブログ記事リスト -->
<div class="blog-list">

  <?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>
      <article class="blog-item">
        <div class="blog-item-left">
          <time class="blog-item-date" datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( get_the_date( 'Y/m/d' ) ); ?></time>
          <a href="<?php the_permalink(); ?>" class="blog-item-photo-link">
            <?php if ( has_post_thumbnail() ) : ?>
              <div class="blog-item-photo" role="img" aria-label="<?php echo esc_attr( get_the_title() ); ?> 記事写真">
                <?php the_post_thumbnail( 'medium' ); ?>
              </div>
            <?php else : ?>
              <div class="blog-item-photo" role="img" aria-label="記事写真"></div>
            <?php endif; ?>
          </a>
        </div>
        <div class="blog-item-right">
          <div class="blog-item-right-inner">
            <h2 class="blog-item-title"><a href="<?php the_permalink(); ?>"><?php echo esc_html( get_the_title() ); ?></a></h2>
            <p class="blog-item-body"><?php echo esc_html( get_the_excerpt() ); ?></p>
          </div>
          <a href="<?php the_permalink(); ?>" class="blog-item-link">
            記事を読む
            <span class="blog-item-link-arrow" aria-hidden="true">
              <svg viewBox="0 0 20 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 4H18M18 4L14.5 1M18 4L14.5 7" stroke="#000" stroke-width="1"/>
              </svg>
            </span>
          </a>
        </div>
      </article>
    <?php endwhile; ?>
  <?php else : ?>
    <p>記事が見つかりませんでした。</p>
  <?php endif; ?>

</div>

<!-- ページネーション -->
<?php andfeel_pagination(); ?>
</main>

<!-- パンくずリスト -->
<?php andfeel_breadcrumb(); ?>

<?php get_footer(); ?>
