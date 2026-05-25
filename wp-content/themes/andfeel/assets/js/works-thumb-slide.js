/* ===== works-thumb-slide.js — 一覧サムネイル自動フェードスライド ===== */
(function () {
  'use strict';

  var INTERVAL = 4500;         // 切り替え間隔（ms）
  var STAGGER  = 800;          // カードごとのずらし（ms）

  var cards = document.querySelectorAll('.works-item-photo');

  cards.forEach(function (card, cardIndex) {
    var slides = card.querySelectorAll('.works-thumb-slide');
    if (slides.length < 2) return;       // 1枚以下ならスライド不要

    var current = 0;
    var total   = slides.length;

    function next() {
      slides[current].classList.remove('is-active');
      current = (current + 1) % total;
      slides[current].classList.add('is-active');
    }

    // カードごとに開始タイミングをずらす
    setTimeout(function () {
      setInterval(next, INTERVAL);
    }, cardIndex * STAGGER);
  });
})();
