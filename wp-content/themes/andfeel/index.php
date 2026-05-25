<?php
/**
 * index.php — フォールバックテンプレート
 *
 * WordPress では index.php が必須。
 * 実際のページは front-page.php / home.php / single.php 等で処理されます。
 *
 * @package andfeel
 */
defined( 'ABSPATH' ) || exit;

get_header();
?>

<main>

<?php
if (have_posts()) :
    while (have_posts()) : the_post();
        the_content();
    endwhile;
endif;
?>

</main>

<?php get_footer();
