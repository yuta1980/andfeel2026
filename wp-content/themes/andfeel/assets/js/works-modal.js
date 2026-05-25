/* ===== works-modal.js — スライドショー & ライトボックス ===== */
(function () {
  'use strict';

  /* ---------- スライドショー ---------- */
  var slideshow = document.getElementById('worksSlideshow');
  if (!slideshow) return;

  var track    = slideshow.querySelector('.works-slideshow-track');
  var slides   = slideshow.querySelectorAll('.works-slide');
  var btnPrev  = slideshow.querySelector('.works-slideshow-prev');
  var btnNext  = slideshow.querySelector('.works-slideshow-next');
  var dots     = slideshow.querySelectorAll('.works-slideshow-dot');
  var total    = slides.length;
  var current  = 0;

  // 全画像URLを配列化（モーダル用）
  var urls = [];
  var alts = [];
  slides.forEach(function (slide) {
    var img = slide.querySelector('img');
    urls.push(img.getAttribute('data-src') || img.currentSrc || img.src);
    alts.push(img.alt);
  });

  function goTo(index) {
    if (index < 0) index = total - 1;
    if (index >= total) index = 0;
    current = index;
    track.style.transform = 'translateX(-' + (current * 100 / total) + '%)';
    dots.forEach(function (dot, i) {
      dot.classList.toggle('is-active', i === current);
    });
  }

  if (btnPrev) btnPrev.addEventListener('click', function (e) { e.stopPropagation(); goTo(current - 1); });
  if (btnNext) btnNext.addEventListener('click', function (e) { e.stopPropagation(); goTo(current + 1); });

  dots.forEach(function (dot) {
    dot.addEventListener('click', function () {
      goTo(parseInt(this.dataset.index, 10));
    });
  });

  /* --- タッチスワイプ --- */
  var startX = 0;
  var startY = 0;
  var isDragging = false;

  slideshow.addEventListener('touchstart', function (e) {
    startX = e.touches[0].clientX;
    startY = e.touches[0].clientY;
    isDragging = true;
  }, { passive: true });

  slideshow.addEventListener('touchend', function (e) {
    if (!isDragging) return;
    isDragging = false;
    var dx = e.changedTouches[0].clientX - startX;
    var dy = e.changedTouches[0].clientY - startY;
    if (Math.abs(dx) > Math.abs(dy) && Math.abs(dx) > 40) {
      if (dx < 0) goTo(current + 1);
      else goTo(current - 1);
    }
  }, { passive: true });

  /* --- キーボード操作 --- */
  document.addEventListener('keydown', function (e) {
    // モーダルが開いているときはスライドショー操作しない
    var modal = document.getElementById('worksModal');
    if (modal && modal.classList.contains('is-open')) return;
    if (e.key === 'ArrowLeft') goTo(current - 1);
    if (e.key === 'ArrowRight') goTo(current + 1);
  });

  /* ---------- モーダル / ライトボックス ---------- */
  var modal    = document.getElementById('worksModal');
  if (!modal) return;

  var overlay  = modal.querySelector('.works-modal-overlay');
  var modalImg = modal.querySelector('.works-modal-image');
  var mClose   = modal.querySelector('.works-modal-close');
  var mPrev    = modal.querySelector('.works-modal-prev');
  var mNext    = modal.querySelector('.works-modal-next');
  var mIndex   = 0;

  function openModal(index) {
    mIndex = index;
    showModalImage();
    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }

  function closeModal() {
    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  function showModalImage() {
    modalImg.src = urls[mIndex];
    modalImg.alt = alts[mIndex];
    mPrev.style.visibility = mIndex === 0 ? 'hidden' : '';
    mNext.style.visibility = mIndex === urls.length - 1 ? 'hidden' : '';
  }

  // スライド画像クリック → モーダル表示
  slides.forEach(function (slide) {
    slide.addEventListener('click', function () {
      openModal(parseInt(this.dataset.index, 10));
    });
  });

  mClose.addEventListener('click', closeModal);
  overlay.addEventListener('click', closeModal);
  mPrev.addEventListener('click', function () { if (mIndex > 0) { mIndex--; showModalImage(); } });
  mNext.addEventListener('click', function () { if (mIndex < urls.length - 1) { mIndex++; showModalImage(); } });

  document.addEventListener('keydown', function (e) {
    if (!modal.classList.contains('is-open')) return;
    if (e.key === 'Escape') closeModal();
    if (e.key === 'ArrowLeft' && mIndex > 0) { mIndex--; showModalImage(); }
    if (e.key === 'ArrowRight' && mIndex < urls.length - 1) { mIndex++; showModalImage(); }
  });
})();
