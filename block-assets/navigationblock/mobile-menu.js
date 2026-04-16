/**
 * Navigation block — mobile menu toggle.
 *
 * @package CustomTheme
 */

(function () {
  'use strict';

  var button = document.getElementById('mobile-menu-button');
  var menu = document.getElementById('mobile-menu');

  if (button && menu) {
    button.addEventListener('click', function () {
      var isExpanded = button.getAttribute('aria-expanded') === 'true';
      button.setAttribute('aria-expanded', !isExpanded);
      menu.classList.toggle('hidden');
    });
  }
}());
