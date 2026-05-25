/* ===== works-filter.js — フィルター & ページネーション ===== */
(function () {
  'use strict';

  const PER_PAGE = 4;
  let currentPage = 1;

  const tabs = document.querySelectorAll('.filter-tab');
  const items = document.querySelectorAll('.works-item');
  const pagination = document.querySelector('.pagination');

  // 初期カテゴリー: 最初のタブの data-category（スラッグ）を使用
  let currentCategory = tabs.length ? tabs[0].dataset.category : '';

  const getFilteredItems = () => {
    const filtered = [];
    items.forEach((item) => {
      const cats = item.dataset.category.split(' ');
      if (cats.includes(currentCategory)) {
        filtered.push(item);
      }
    });
    return filtered;
  };

  const render = () => {
    const filtered = getFilteredItems();
    const totalPages = Math.ceil(filtered.length / PER_PAGE);
    if (currentPage > totalPages) currentPage = totalPages || 1;

    const start = (currentPage - 1) * PER_PAGE;
    const end = start + PER_PAGE;

    // 全アイテムを非表示にしてから、該当ページのみ表示
    items.forEach((item) => {
      item.style.display = 'none';
    });

    filtered.forEach((item, i) => {
      if (i >= start && i < end) {
        item.style.display = '';
      }
    });

    // ページネーション更新
    renderPagination(totalPages);
  };

  const renderPagination = (totalPages) => {
    if (!pagination) return;

    const paginationLines = pagination.querySelector('.pagination-lines');
    if (!paginationLines) return;

    // ページネーション再構築
    let html = '<div class="pagination-line" aria-hidden="true"></div>';
    html += '<div class="pagination-nums">';
    for (let i = 1; i <= totalPages; i++) {
      if (i === currentPage) {
        html += '<span class="current" aria-current="page">' + i + '</span>';
      } else {
        html += '<a href="#" data-page="' + i + '">' + i + '</a>';
      }
    }
    html += '</div>';
    html += '<div class="pagination-line" aria-hidden="true"></div>';

    if (currentPage < totalPages) {
      html += '<a href="#" class="pagination-arrow" data-page="' + (currentPage + 1) + '" aria-label="次のページ">';
      html += '<svg viewBox="0 0 20 8" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">';
      html += '<path d="M0 4H18M18 4L14.5 1M18 4L14.5 7" stroke="#000" stroke-width="1"/>';
      html += '</svg></a>';
    }

    paginationLines.innerHTML = html;

    // ページネーションのクリックイベント
    paginationLines.querySelectorAll('[data-page]').forEach((link) => {
      link.addEventListener('click', function (e) {
        e.preventDefault();
        currentPage = parseInt(this.dataset.page, 10);
        render();
      });
    });

    // 1ページ以下なら非表示
    pagination.style.display = totalPages <= 1 ? 'none' : '';
  };

  // タブ切り替え
  tabs.forEach((tab) => {
    tab.addEventListener('click', function () {
      tabs.forEach((t) => { t.classList.remove('is-active'); });
      this.classList.add('is-active');
      currentCategory = this.dataset.category;
      currentPage = 1;
      render();
    });
  });

  // 初期表示
  render();

  // --- スクロールで線アニメーション発火 ---
  const lineObserver = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        lineObserver.unobserve(entry.target);
      }
    });
  }, { rootMargin: '0px 0px -10% 0px' });

  items.forEach(function (item) {
    lineObserver.observe(item);
  });
})();
