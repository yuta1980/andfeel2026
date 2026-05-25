<?php
/**
 * Single post template (blog)
 *
 * @package andfeel
 */
defined( 'ABSPATH' ) || exit;

get_header();
$theme_uri = get_template_directory_uri();
?>

<!-- ブログ記事詳細 -->
<main class="blog-single">
  <?php while ( have_posts() ) : the_post(); ?>
  <article class="blog-single-article">
    <div class="blog-single-header">
      <div class="blog-single-text">
        <h1 class="blog-single-title"><?php echo esc_html( get_the_title() ); ?></h1>
        <time class="blog-single-date" datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( get_the_date( 'Y/m/d' ) ); ?></time>
      </div>
      <div class="blog-single-eyecatch">
        <?php if ( has_post_thumbnail() ) : ?>
          <?php the_post_thumbnail( 'full', array(
            'class'   => 'blog-single-eyecatch-img',
            'loading' => 'eager',
          ) ); ?>
        <?php else : ?>
          <div class="blog-single-eyecatch-placeholder" role="img" aria-label="記事のアイキャッチ画像"></div>
        <?php endif; ?>
      </div>
    </div>

    <div class="blog-single-body">
      <?php
      $blog_intro = get_post_meta( get_the_ID(), 'blog_intro', true );
      $has_custom_fields = (bool) $blog_intro;
      if ( ! $has_custom_fields ) {
          for ( $j = 1; $j <= 3; $j++ ) {
              if ( get_post_meta( get_the_ID(), "blog_section_h2_{$j}", true ) ) {
                  $has_custom_fields = true;
                  break;
              }
          }
      }

      if ( $has_custom_fields ) :
          if ( $blog_intro ) : ?>
            <p><?php echo nl2br( esc_html( $blog_intro ) ); ?></p>
          <?php endif;
          for ( $i = 1; $i <= 3; $i++ ) :
              $h2   = get_post_meta( get_the_ID(), "blog_section_h2_{$i}", true );
              $body = get_post_meta( get_the_ID(), "blog_section_body_{$i}", true );
              if ( $h2 ) : ?>
                <h2 class="blog-single-heading"><?php echo esc_html( $h2 ); ?></h2>
                <?php if ( $body ) : ?>
                  <p><?php echo nl2br( esc_html( $body ) ); ?></p>
                <?php endif;
              endif;
          endfor;
      else :
          the_content();
      endif;
      ?>
    </div>
  </article>
  <?php endwhile; ?>

  <!-- 記事ナビゲーション -->
  <nav class="post-nav" aria-label="前後の記事">
    <?php
    $prev_post = get_previous_post();
    $next_post = get_next_post();
    $blog_page_url = get_permalink( get_option( 'page_for_posts' ) );
    ?>
    <?php if ( $prev_post ) : ?>
      <a href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>" class="post-nav-prev" aria-label="前の記事">&larr;</a>
    <?php endif; ?>

    <div class="post-nav-sep" aria-hidden="true"></div>
    <a href="<?php echo esc_url( $blog_page_url ); ?>" class="post-nav-list">一覧</a>
    <div class="post-nav-sep" aria-hidden="true"></div>

    <?php if ( $next_post ) : ?>
      <a href="<?php echo esc_url( get_permalink( $next_post ) ); ?>" class="post-nav-next" aria-label="次の記事">&rarr;</a>
    <?php else : ?>
      <span class="post-nav-next is-disabled" aria-hidden="true">&rarr;</span>
    <?php endif; ?>
  </nav>
</main>

<!-- パンくずリスト -->
<?php andfeel_breadcrumb(); ?>

<?php get_footer(); ?>
