/* ===== scroll-fade.js — スクロールフェードインアニメーション ===== */
(function () {
  'use strict';

  const targets = document.querySelectorAll('.js-fade-in');
  if (!targets.length) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0,
    rootMargin: '-50% 0px -50% 0px'
  });

  targets.forEach((el) => {
    observer.observe(el);
  });
})();
