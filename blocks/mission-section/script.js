/**
 * Mission Section - Text Highlight on Scroll Animation
 * Word-by-word gradient effect from rgba(0,0,0,0.20) to black
 */

(function() {
  'use strict';

  /**
   * Split text into individual words wrapped in spans
   * Preserves all HTML elements and formatting (em, strong, a, etc.)
   */
  function wrapWords(element) {
    /**
     * Recursively process DOM nodes, wrapping only text nodes
     */
    function processNode(node) {
      if (node.nodeType === Node.TEXT_NODE) {
        // Text node - split into words and wrap each
        const text = node.textContent;
        
        // Skip if empty or only whitespace
        if (!text.trim()) return;
        
        // Split into words while preserving leading/trailing spaces
        const leadingSpace = text.match(/^\s+/)?.[0] || '';
        const trailingSpace = text.match(/\s+$/)?.[0] || '';
        const trimmedText = text.trim();
        
        if (!trimmedText) return;
        
        const words = trimmedText.split(/\s+/);
        const fragment = document.createDocumentFragment();
        
        // Add leading space if present
        if (leadingSpace) {
          fragment.appendChild(document.createTextNode(leadingSpace));
        }
        
        // Wrap each word
        words.forEach((word, index) => {
          if (word) {
            const span = document.createElement('span');
            span.className = 'highlight-word';
            span.textContent = word;
            fragment.appendChild(span);
            
            // Add space between words (except last word)
            if (index < words.length - 1) {
              fragment.appendChild(document.createTextNode(' '));
            }
          }
        });
        
        // Add trailing space if present
        if (trailingSpace) {
          fragment.appendChild(document.createTextNode(trailingSpace));
        }
        
        // Replace the text node with wrapped words
        node.parentNode.replaceChild(fragment, node);
      } else if (node.nodeType === Node.ELEMENT_NODE) {
        // Element node - recursively process children
        // Convert to array to avoid live NodeList issues during manipulation
        const childNodes = Array.from(node.childNodes);
        childNodes.forEach(child => processNode(child));
      }
    }
    
    processNode(element);
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
