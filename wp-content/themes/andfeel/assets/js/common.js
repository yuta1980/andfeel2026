/* ===== common.js — ハンバーガーメニュー ===== */
(function () {
  'use strict';

  const hamburger = document.getElementById('hamburger');
  const nav = document.querySelector('.header-nav');
  if (!hamburger || !nav) return;
  const navLinks = nav.querySelectorAll('a');

  hamburger.addEventListener('click', () => {
    const isOpen = hamburger.classList.toggle('is-open');
    nav.classList.toggle('is-open');
    hamburger.setAttribute('aria-expanded', String(isOpen));
  });

  navLinks.forEach(link => {
    link.addEventListener('click', () => {
      hamburger.classList.remove('is-open');
      nav.classList.remove('is-open');
      hamburger.setAttribute('aria-expanded', 'false');
    });
  });

  /* --- メニュー外クリックで閉じる --- */
  document.addEventListener('click', (e) => {
    if (!hamburger.classList.contains('is-open')) return;
    if (hamburger.contains(e.target) || nav.contains(e.target)) return;

    hamburger.classList.remove('is-open');
    nav.classList.remove('is-open');
    hamburger.setAttribute('aria-expanded', 'false');
  });

  /* --- ESCキーで閉じる --- */
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && hamburger.classList.contains('is-open')) {
      hamburger.classList.remove('is-open');
      nav.classList.remove('is-open');
      hamburger.setAttribute('aria-expanded', 'false');
    }
  });
})();
