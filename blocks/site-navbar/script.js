/**
 * Site Navbar - Mobile menu toggle functionality.
 *
 * @package BlacklineGuardianFund
 */

(function () {
  'use strict';

  // Wait for DOM to be ready.
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  function init() {
    initMobileMenu();
    initStickyHeader();
  }

  function initMobileMenu() {
    const toggleButton = document.querySelector('[data-mobile-toggle]');
    const closeButton = document.querySelector('[data-mobile-close]');
    const overlay = document.querySelector('[data-mobile-overlay]');

    if (!toggleButton || !closeButton || !overlay) {
      return;
    }

    // Open mobile menu.
    toggleButton.addEventListener('click', function () {
      overlay.classList.add('is-open');
      document.body.classList.add('mobile-menu-open');
      toggleButton.setAttribute('aria-expanded', 'true');
      
      // Focus close button for accessibility.
      closeButton.focus();
    });

    // Close mobile menu.
    closeButton.addEventListener('click', function () {
      closeMobileMenu();
    });

    // Close on backdrop click.
    document.addEventListener('click', function (e) {
      if (
        overlay.classList.contains('is-open') &&
        !overlay.contains(e.target) &&
        !toggleButton.contains(e.target)
      ) {
        closeMobileMenu();
      }
    });

    // Close on Escape key.
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && overlay.classList.contains('is-open')) {
        closeMobileMenu();
      }
    });

    function closeMobileMenu() {
      overlay.classList.remove('is-open');
      document.body.classList.remove('mobile-menu-open');
      toggleButton.setAttribute('aria-expanded', 'false');
      
      // Return focus to toggle button for accessibility.
      toggleButton.focus();
    }
  }

  function initStickyHeader() {
    const header = document.querySelector('.site-navbar');
    
    if (!header) {
      return;
    }

    let lastScroll = 0;
    const scrollThreshold = 200;

    // Handle scroll with throttling for better performance.
    let ticking = false;

    window.addEventListener('scroll', function () {
      lastScroll = window.scrollY || window.pageYOffset;

      if (!ticking) {
        window.requestAnimationFrame(function () {
          updateHeaderState();
          ticking = false;
        });

        ticking = true;
      }
    });

    function updateHeaderState() {
      if (lastScroll > scrollThreshold) {
        header.classList.add('is-floating');
        document.body.classList.add('has-floating-navbar');
      } else {
        header.classList.remove('is-floating');
        document.body.classList.remove('has-floating-navbar');
      }
    }

    // Initial check in case page is loaded scrolled down.
    updateHeaderState();
  }
})();
