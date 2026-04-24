/**
 * Mission Section - Text Highlight on Scroll Animation
 * Word-by-word gradient effect from rgba(0,0,0,0.20) to black
 */

(function() {
  'use strict';

  /**
   * Split text into individual words wrapped in spans
   * Preserves <br><br> tags as line breaks
   */
  function wrapWords(element) {
    const html = element.innerHTML;
    
    // Split by <br><br> to preserve paragraph breaks
    const paragraphs = html.split(/<br\s*\/?>\s*<br\s*\/?>/gi);
    
    const wrappedParagraphs = paragraphs.map(paragraph => {
      // Remove any remaining single <br> tags within paragraph
      const cleanParagraph = paragraph.replace(/<br\s*\/?>/gi, ' ');
      
      // Create a temporary div to extract text
      const temp = document.createElement('div');
      temp.innerHTML = cleanParagraph;
      const text = temp.textContent || temp.innerText || '';
      
      // Split into individual words and wrap each one
      const words = text.trim().split(/\s+/);
      return words
        .map(word => `<span class="highlight-word">${word}</span>`)
        .join(' ');
    });
    
    // Join paragraphs back with <br><br>
    element.innerHTML = wrappedParagraphs.join('<br><br>');
  }

  /**
   * Initialize highlight animation for all text elements
   */
  function initHighlightAnimation() {
    // Find all elements with text-mission-text class
    const textElements = document.querySelectorAll('.text-mission-text');
    
    if (!textElements.length) return;

    // Wrap each word in spans
    textElements.forEach(element => {
      wrapWords(element);
    });

    // Get all word spans
    const words = document.querySelectorAll('.highlight-word');
    
    if (!words.length) return;

    // Intersection Observer options
    const observerOptions = {
      root: null,
      rootMargin: '-10% 0px',
      threshold: buildThresholdArray()
    };

    // Track scroll position to detect direction
    let lastScrollY = window.scrollY;
    let isScrollingUp = false;

    window.addEventListener('scroll', () => {
      const currentScrollY = window.scrollY;
      isScrollingUp = currentScrollY < lastScrollY;
      lastScrollY = currentScrollY;
    }, { passive: true });

    // Create observer
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          // Calculate progress based on how much of the element is visible
          const progress = entry.intersectionRatio;
          console.log(progress);
          
          // Only animate if at least 90% visible
          if (progress >= 0) {
            entry.target.style.setProperty('--highlight-progress', progress);
            
            // Add animated class when fully visible
            if (progress >= 1) {
              entry.target.classList.add('is-highlighted');
            }
          }
        } else {
          // Only reset when scrolling UP (heading to top of page)
          if (isScrollingUp) {
            entry.target.style.setProperty('--highlight-progress', 0);
            entry.target.classList.remove('is-highlighted');
          }
        }
      });
    }, observerOptions);

    // Observe each word with slight stagger
    words.forEach((word, index) => {
      // Add stagger delay as CSS variable
      word.style.setProperty('--word-index', index);
      observer.observe(word);
    });
  }

  /**
   * Build threshold array for smooth animation
   */
  function buildThresholdArray() {
    const thresholds = [];
    for (let i = 0; i <= 100; i++) {
      thresholds.push(i / 100);
    }
    return thresholds;
  }

  /**
   * Initialize on DOM ready
   */
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initHighlightAnimation);
  } else {
    initHighlightAnimation();
  }

  // Re-initialize on dynamic content load (for AJAX/SPA scenarios)
  window.addEventListener('load', initHighlightAnimation);

})();
