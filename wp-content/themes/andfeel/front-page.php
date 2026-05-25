<?php
/**
 * Template: フロントページ
 *
 * @package andfeel
 */
defined( 'ABSPATH' ) || exit;

get_header();
$theme_uri = get_template_directory_uri();
?>

<main>

<section class="hero" aria-label="ファーストビュー">
  <h1 class="sr-only"><?php bloginfo( 'name' ); ?></h1>
  <div class="hero-image">
    <img src="<?php echo esc_url( $theme_uri ); ?>/images/hero/hero.jpg" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="2048" height="1366" fetchpriority="high">
  </div>
  <div class="hero-vertical-text">
    <div class="vtext-col">やがて深く記憶に残っていくように。</div>
    <div class="vtext-col">日々の中にある、ささやかな移ろいが、</div>
    <div class="vtext-spacer"></div>
    <div class="vtext-col">丁寧にかたちにしています。</div>
    <div class="vtext-col">時間の流れを感じとれる空間を、</div>
    <div class="vtext-col">窓の向こうに広がる景色、光が織りなす陰影。</div>
    <div class="vtext-spacer"></div>
    <div class="vtext-col">絶えず移り変わっていきます。</div>
    <div class="vtext-col">同じ場所であってもその表情は、</div>
    <div class="vtext-col">ふと肌に触れる空気のやわらかさ。</div>
    <div class="vtext-col">朝と夕で異なる光。</div>
    <div class="vtext-spacer"></div>
    <div class="vtext-col">季節はめぐり、風景は静かに移ろい続けていく。</div>
    <div class="vtext-col">色づき、やがて澄みわたる空気。</div>
    <div class="vtext-col">ひらく花、揺れる緑、かすかな鳥の気配。</div>
  </div>
</section>

<section class="about" id="about">
  <div class="about-deco-wrap js-fade-in">
    <span class="deco-label">ABOUT</span>
    <div class="deco-line"></div>
    <img src="<?php echo esc_url( $theme_uri ); ?>/images/icons/arrow-down.svg" alt="" class="about-deco-arrow" width="10" height="15">
  </div>
  <div class="about-content">
    <h2 class="section-title">私について</h2>
    <p class="section-body">
      住まいは、日々を包み、静かに時間を重ねていく場所。<br>
      季節の巡りや暮らしの中に息づく所作など、目に見えない<br>ものを感じとることから設計ははじまります。<br>
      気配に耳を澄ませ、対話を重ねながら、<br>ふさわしいかたちを丁寧に結びます。
    </p>
    <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'about' ) ) ); ?>" class="section-link">
      詳細
      <img src="<?php echo esc_url( $theme_uri ); ?>/images/icons/arrow-right.svg" alt="" class="link-arrow" width="20" height="8">
    </a>
  </div>
  <div class="about-logo-area">
    <img src="<?php echo esc_url( $theme_uri ); ?>/images/logo/about-logo.svg" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?> 四国地図" width="336" height="274">
    <span class="about-logo-name">and feel.建築設計事務所</span>
  </div>
</section>

<section class="works" id="works">
  <div class="works-photo">
    <img src="<?php echo esc_url( $theme_uri ); ?>/images/works/works-01.jpg" alt="かたち 作品写真" width="2048" height="1366" loading="lazy">
  </div>
  <div class="works-inner">
    <div class="works-deco-wrap js-fade-in">
      <span class="deco-label">WORKS</span>
      <div class="deco-line"></div>
      <img src="<?php echo esc_url( $theme_uri ); ?>/images/icons/arrow-down.svg" alt="" class="works-deco-arrow" width="10" height="15">
    </div>
    <div class="works-content">
      <h2 class="section-title">かたち</h2>
      <p class="section-body">光が差し、風が抜け、気配がやわらかく重なる。<br>その積み重ねが、ひとつの「かたち」として現れていく。<br>ここにあるのは、時間や想いがすくいとられた、小さな風景の断片。<br>それぞれの場所に生まれた「かたち」を、静かに並べています。</p>
      <a href="<?php echo esc_url( get_post_type_archive_link( 'works' ) ); ?>" class="section-link">
        一覧
        <img src="<?php echo esc_url( $theme_uri ); ?>/images/icons/arrow-right.svg" alt="" class="link-arrow" width="20" height="8">
      </a>
    </div>
  </div>
</section>

<section class="blog" id="blog">
  <div class="blog-photo">
    <img src="<?php echo esc_url( $theme_uri ); ?>/images/blog/blog-01.jpg" alt="日々 写真" width="2048" height="1366" loading="lazy">
  </div>
  <div class="blog-inner">
    <div class="blog-deco-wrap js-fade-in">
      <span class="deco-label">BLOG</span>
      <div class="deco-line"></div>
      <img src="<?php echo esc_url( $theme_uri ); ?>/images/icons/arrow-down.svg" alt="" class="blog-deco-arrow" width="10" height="15">
    </div>
    <div class="blog-content">
      <h2 class="section-title">日々</h2>
      <p class="section-body">日々の中で拾い上げた気づきや思考の断片を静かに綴っています。<br>かたちになる前の想いや、まだ名前のない感覚をそのままに。小さな言葉の積み重ねが、やがて輪郭となり次の創造へとつながっていきます。</p>
      <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="section-link">
        一覧
        <img src="<?php echo esc_url( $theme_uri ); ?>/images/icons/arrow-right.svg" alt="" class="link-arrow" width="20" height="8">
      </a>
    </div>
  </div>
</section>

<section class="contact-section" id="contact">
  <div class="contact-banner">
    <h2 class="contact-title">ご相談</h2>
    <div class="contact-content-box">
      <img src="<?php echo esc_url( $theme_uri ); ?>/images/icons/contact-bg.svg" alt="" class="contact-bg" width="872" height="125">
      <p class="contact-body">
        住まいや建築に関するご相談を、丁寧にお伺いしています。<br>
        土地やご予算、想いに寄り添いながら、最適なかたちをご提案します。
      </p>
    </div>
    <div class="contact-vline"></div>
  </div>
  <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="contact-cta">
    <div class="cta-line-left"></div>
    <p class="cta-text">設計のこと、空間のこと、<br class="br-mobile">お気軽にご相談ください。</p>
    <img src="<?php echo esc_url( $theme_uri ); ?>/images/icons/arrow-cta.svg" alt="" class="cta-arrow-right" width="155" height="14">
  </a>
</section>

</main>

<?php get_footer(); ?>
