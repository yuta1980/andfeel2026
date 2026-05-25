<?php
/**
 * Template Name: プライバシーポリシー
 *
 * @package andfeel
 */
defined( 'ABSPATH' ) || exit;

get_header();
?>

<main class="privacy-page">

<h1 class="privacy-title">プライバシーポリシー</h1>

<div class="privacy-content">
  <p class="privacy-intro">
    当設計事務所（以下「当事務所」）は、お客様の個人情報の保護を重要な責務と考え、個人情報の適切な取り扱いに努めます。本プライバシーポリシーは、当事務所が運営するウェブサイト（以下「本サイト」）における個人情報の取り扱いについて定めたものです。
  </p>

  <section class="privacy-section">
    <h2>第1条（収集する個人情報）</h2>
    <p>当事務所は、お問い合わせの際に以下の個人情報をお客様からご提供いただきます。</p>
    <ul>
      <li>氏名</li>
      <li>電話番号</li>
      <li>メールアドレス</li>
    </ul>
  </section>

  <section class="privacy-section">
    <h2>第2条（個人情報の利用目的）</h2>
    <p>取集した個人情報は、以下の目的のみに使用します。</p>
    <ul>
      <li>お問い合わせへの回答および対応</li>
      <li>業務上のご連絡</li>
      <li>サービスに関する情報のご提供</li>
    </ul>
  </section>

  <section class="privacy-section">
    <h2>第3条（個人情報の第三者提供）</h2>
    <p>当事務所は、以下の場合を除き、お客様の個人情報を第三者に提供・開示することはありません。</p>
    <ul>
      <li>お客様本人の同意がある場合</li>
      <li>法令に基づき開示が必要な場合</li>
      <li>人の生命、身体または財産の保護のために必要な場合</li>
    </ul>
  </section>

  <section class="privacy-section">
    <h2>第4条（個人情報の管理）</h2>
    <p>当事務所は、お客様の個人情報を正確かつ最新の状態に保つとともに、不正アクセス・紛失・破損・改ざん・漏洩などを防止するため、適切なセキュリティ対策を講じます。</p>
  </section>

  <section class="privacy-section">
    <h2>第5条（個人情報の開示・訂正・削除）</h2>
    <p>お客様は、当事務所が保有する自己の個人情報について、開示・訂正・削除を求める権利を有します。ご希望の場合は、下記お問い合わせ窓口までご連絡ください。合理的な期間内に対応いたします。</p>
  </section>

  <section class="privacy-section">
    <h2>第6条（プライバシーポリシーの変更）</h2>
    <p>当事務所は、法令の変更や業務内容の変更に伴い、本プライバシーポリシーを変更することがあります。変更後のポリシーは、本サイトに掲載した時点で効力を生じるものとします。</p>
  </section>

  <section class="privacy-section">
    <h2>第7条（お問い合わせ窓口）</h2>
    <p>個人情報の取り扱いに関するお問い合わせは、以下までご連絡ください。</p>
  </section>

  <div class="privacy-contact">
    <p><span class="privacy-contact-label">【事務所名】</span><?php echo esc_html( get_bloginfo( 'name' ) ); ?></p>
    <p><span class="privacy-contact-label">【担当者】</span>奥野 智士</p>
    <p><span class="privacy-contact-label">【メールアドレス】</span><a href="mailto:<?php echo esc_attr( 'info@andfeel-architect.com' ); ?>"><?php echo esc_html( 'info@andfeel-architect.com' ); ?></a></p>
  </div>
</div>

</main>

<?php andfeel_breadcrumb(); ?>

<?php get_footer(); ?>
