<?php
/**
 * Single template for Works (custom post type)
 *
 * @package andfeel
 */
defined( 'ABSPATH' ) || exit;

get_header();
$theme_uri = get_template_directory_uri();
?>

<!-- 作品詳細 -->
<main class="works-single">
  <?php while ( have_posts() ) : the_post(); ?>
  <article class="works-single-article">

    <!-- 作品タイトル -->
    <div class="works-single-title-row">
      <div class="works-title-line" aria-hidden="true"></div>
      <h1 class="works-single-title"><?php echo esc_html( get_the_title() ); ?></h1>
      <div class="works-title-line" aria-hidden="true"></div>
    </div>

    <!-- スライドショー -->
    <?php
    $gallery_ids    = get_post_meta( get_the_ID(), 'works_gallery', true );
    $gallery_ids    = is_array( $gallery_ids ) ? $gallery_ids : array();

    // 全スライド画像を構築（アイキャッチ + ギャラリー）
    $slides = array();
    if ( has_post_thumbnail() ) {
      $thumb_id   = get_post_thumbnail_id();
      $thumb_data = wp_get_attachment_image_src( $thumb_id, 'full' );
      $slides[] = array(
        'url'  => $thumb_data ? $thumb_data[0] : get_the_post_thumbnail_url( get_the_ID(), 'full' ),
        'alt'  => get_post_meta( $thumb_id, '_wp_attachment_image_alt', true ) ?: get_the_title(),
        'w'    => $thumb_data ? $thumb_data[1] : '',
        'h'    => $thumb_data ? $thumb_data[2] : '',
      );
    }
    foreach ( $gallery_ids as $att_id ) {
      $img_data = wp_get_attachment_image_src( $att_id, 'full' );
      $img_alt  = get_post_meta( $att_id, '_wp_attachment_image_alt', true );
      if ( $img_data ) {
        $slides[] = array(
          'url' => $img_data[0],
          'alt' => $img_alt ?: get_the_title() . ' 写真' . count( $slides ),
          'w'   => $img_data[1],
          'h'   => $img_data[2],
        );
      }
    }
    $total_slides = count( $slides );
    ?>

    <?php if ( $total_slides > 0 ) : ?>
    <div class="works-slideshow" id="worksSlideshow">
      <div class="works-slideshow-viewport">
        <div class="works-slideshow-track" style="width: <?php echo $total_slides * 100; ?>%;">
          <?php foreach ( $slides as $si => $slide ) : ?>
            <div class="works-slide" data-index="<?php echo $si; ?>">
              <img src="<?php echo esc_url( $slide['url'] ); ?>"
                   alt="<?php echo esc_attr( $slide['alt'] ); ?>"
                   <?php if ( ! empty( $slide['w'] ) && ! empty( $slide['h'] ) ) : ?>width="<?php echo esc_attr( $slide['w'] ); ?>" height="<?php echo esc_attr( $slide['h'] ); ?>"<?php endif; ?>
                   loading="<?php echo $si === 0 ? 'eager' : 'lazy'; ?>">
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <?php if ( $total_slides > 1 ) : ?>
      <button class="works-slideshow-prev" aria-label="前の写真">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M15 19l-7-7 7-7"/></svg>
      </button>
      <button class="works-slideshow-next" aria-label="次の写真">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 5l7 7-7 7"/></svg>
      </button>

      <div class="works-slideshow-dots">
        <?php for ( $di = 0; $di < $total_slides; $di++ ) : ?>
          <button class="works-slideshow-dot<?php echo $di === 0 ? ' is-active' : ''; ?>"
                  data-index="<?php echo $di; ?>"
                  aria-label="写真 <?php echo $di + 1; ?>"></button>
        <?php endfor; ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- モーダル -->
    <div class="works-modal" id="worksModal" aria-hidden="true">
      <div class="works-modal-overlay"></div>
      <div class="works-modal-content">
        <button class="works-modal-prev" aria-label="前の写真">&#8592;</button>
        <img class="works-modal-image" src="" alt="">
        <button class="works-modal-next" aria-label="次の写真">&#8594;</button>
      </div>
      <button class="works-modal-close" aria-label="閉じる">&times;</button>
    </div>
    <?php endif; ?>

    <!-- 作品説明 -->
    <div class="works-single-body">
      <?php the_content(); ?>
    </div>

    <!-- 作品データ -->
    <?php
    $location  = get_post_meta( get_the_ID(), 'works_location',  true );
    $year      = get_post_meta( get_the_ID(), 'works_year',      true );
    $structure = get_post_meta( get_the_ID(), 'works_structure',  true );
    $area      = get_post_meta( get_the_ID(), 'works_area',      true );
    if ( $location || $year || $structure || $area ) : ?>
    <dl class="works-single-data">
      <?php if ( $location ) : ?>
        <div class="works-data-row">
          <dt>所在地</dt>
          <dd><?php echo esc_html( $location ); ?></dd>
        </div>
      <?php endif; ?>
      <?php if ( $year ) : ?>
        <div class="works-data-row">
          <dt>竣工</dt>
          <dd><?php echo esc_html( $year ); ?></dd>
        </div>
      <?php endif; ?>
      <?php if ( $structure ) : ?>
        <div class="works-data-row">
          <dt>構造</dt>
          <dd><?php echo esc_html( $structure ); ?></dd>
        </div>
      <?php endif; ?>
      <?php if ( $area ) : ?>
        <div class="works-data-row">
          <dt>延床面積</dt>
          <dd><?php echo esc_html( $area ); ?></dd>
        </div>
      <?php endif; ?>
    </dl>
    <?php endif; ?>
  </article>
  <?php endwhile; ?>

  <!-- 記事ナビゲーション -->
  <nav class="post-nav" aria-label="前後の作品">
    <?php
    $prev_post = get_previous_post();
    $next_post = get_next_post();
    ?>
    <?php if ( $prev_post ) : ?>
      <a href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>" class="post-nav-prev" aria-label="前の作品">&larr;</a>
    <?php else : ?>
      <span class="post-nav-prev is-disabled" aria-hidden="true">&larr;</span>
    <?php endif; ?>

    <div class="post-nav-sep" aria-hidden="true"></div>
    <a href="<?php echo esc_url( get_post_type_archive_link( 'works' ) ); ?>" class="post-nav-list">一覧</a>
    <div class="post-nav-sep" aria-hidden="true"></div>

    <?php if ( $next_post ) : ?>
      <a href="<?php echo esc_url( get_permalink( $next_post ) ); ?>" class="post-nav-next" aria-label="次の作品">&rarr;</a>
    <?php else : ?>
      <span class="post-nav-next is-disabled" aria-hidden="true">&rarr;</span>
    <?php endif; ?>
  </nav>
</main>

<!-- パンくずリスト -->
<?php andfeel_breadcrumb(); ?>

<?php get_footer(); ?>
