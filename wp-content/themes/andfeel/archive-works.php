<?php
/**
 * Archive template for Works (custom post type)
 *
 * @package andfeel
 */
defined( 'ABSPATH' ) || exit;

get_header();
$theme_uri = get_template_directory_uri();
?>

<main>
<!-- ページヒーロー -->
<section class="page-hero" aria-label="かたちページヒーロー">
  <div class="page-hero-deco js-fade-in" aria-hidden="true">
    <span class="deco-label">WORKS</span>
    <div class="deco-line-works"></div>
    <img src="<?php echo esc_url( $theme_uri . '/images/icons/arrow-down.svg' ); ?>" alt="" width="10" height="15">
  </div>
  <div class="page-hero-content">
    <div class="hero-title-row">
      <h1>
        空間は、思想の積み重ね。<br>
        その一つひとつを、かたちに。
      </h1>
    </div>
    <p class="section-body">
      一棟ごとに異なる条件と向き合いながら、唯一無二の建築を生み出してきました。機能性と美しさを両立させ、そこに関わる人や街と調和する空間を設計しています。積み重ねてきた実績は、私たちの思想そのものです。
    </p>
  </div>
</section>

<!-- フィルタータブ -->
<?php
$works_terms = get_terms( array(
  'taxonomy'   => 'works_category',
  'hide_empty' => false,
) );
?>
<?php if ( ! is_wp_error( $works_terms ) && ! empty( $works_terms ) ) : ?>
<div class="filter-section">
  <div class="filter-inner">
    <div class="filter-line"></div>
    <div class="filter-tabs">
      <?php foreach ( $works_terms as $i => $term ) : ?>
        <?php if ( $i > 0 ) : ?>
          <span class="filter-sep">/</span>
        <?php endif; ?>
        <button class="filter-tab<?php echo ( 0 === $i ) ? ' is-active' : ''; ?>" type="button" data-category="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></button>
      <?php endforeach; ?>
    </div>
    <div class="filter-line"></div>
  </div>
</div>
<?php endif; ?>

<!-- 作品リスト -->
<section class="works-list-section" aria-label="作品一覧">
  <div class="works-list">

    <?php if ( have_posts() ) : ?>
      <?php while ( have_posts() ) : the_post(); ?>
        <?php
        $category_slugs = '';
        $work_terms = get_the_terms( get_the_ID(), 'works_category' );
        if ( $work_terms && ! is_wp_error( $work_terms ) ) {
          $slugs = wp_list_pluck( $work_terms, 'slug' );
          $category_slugs = implode( ' ', $slugs );
        }
        ?>
        <article class="works-item" data-category="<?php echo esc_attr( $category_slugs ); ?>">
          <a href="<?php the_permalink(); ?>" class="works-item-link">
            <?php
            // サムネイル用スライド画像を構築（アイキャッチ + ギャラリー）
            $thumb_slides = array();
            if ( has_post_thumbnail() ) {
              $thumb_id   = get_post_thumbnail_id();
              $thumb_data = wp_get_attachment_image_src( $thumb_id, 'large' );
              $thumb_src  = $thumb_data ? $thumb_data[0] : get_the_post_thumbnail_url( get_the_ID(), 'large' );
              $thumb_w    = $thumb_data ? $thumb_data[1] : '';
              $thumb_h    = $thumb_data ? $thumb_data[2] : '';
              $thumb_alt  = get_post_meta( $thumb_id, '_wp_attachment_image_alt', true ) ?: get_the_title();
              $thumb_slides[] = array( 'src' => $thumb_src, 'alt' => $thumb_alt, 'w' => $thumb_w, 'h' => $thumb_h );
            }
            $gallery_ids = get_post_meta( get_the_ID(), 'works_gallery', true );
            $gallery_ids = is_array( $gallery_ids ) ? $gallery_ids : array();
            foreach ( $gallery_ids as $att_id ) {
              $img_data = wp_get_attachment_image_src( $att_id, 'large' );
              if ( $img_data ) {
                $img_alt = get_post_meta( $att_id, '_wp_attachment_image_alt', true ) ?: get_the_title();
                $thumb_slides[] = array( 'src' => $img_data[0], 'alt' => $img_alt, 'w' => $img_data[1], 'h' => $img_data[2] );
              }
            }
            ?>
            <div class="works-item-photo" role="img" aria-label="<?php echo esc_attr( get_the_title() ); ?> 写真">
              <?php if ( ! empty( $thumb_slides ) ) : ?>
                <?php foreach ( $thumb_slides as $si => $slide ) : ?>
                  <img class="works-thumb-slide<?php echo 0 === $si ? ' is-active' : ''; ?>"
                       src="<?php echo esc_url( $slide['src'] ); ?>"
                       alt="<?php echo esc_attr( $slide['alt'] ); ?>"
                       <?php if ( ! empty( $slide['w'] ) && ! empty( $slide['h'] ) ) : ?>width="<?php echo esc_attr( $slide['w'] ); ?>" height="<?php echo esc_attr( $slide['h'] ); ?>"<?php endif; ?>
                       loading="lazy">
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
            <div class="works-item-meta">
              <h2 class="works-item-title"><?php echo esc_html( get_the_title() ); ?></h2>
              <div class="works-item-line"></div>
            </div>
          </a>
        </article>
      <?php endwhile; ?>
    <?php else : ?>
      <p>作品が見つかりませんでした。</p>
    <?php endif; ?>

  </div>
</section>

<!-- ページネーション -->
<?php andfeel_pagination( 'pagination-lines' ); ?>
</main>

<!-- パンくずリスト -->
<?php andfeel_breadcrumb(); ?>

<?php get_footer(); ?>
