/* ===== ripples-init.js — 背景波紋エフェクト ===== */
(function ($) {
  $(function () {
    'use strict';
    try {
      $('body').ripples({
        dropRadius: 30,
        perturbance: 0.04,
        resolution: 768
      });
    } catch (e) {
      // WebGL非対応ブラウザでは無効化
    }
  });
})(jQuery);
