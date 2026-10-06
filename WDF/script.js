/**
 * STUDENTHUB - Root Script Bridge
 * Imports and executes master logic from js/main.js
 */

// If main.js is not loaded separately, load it dynamically
if (typeof StudentHub === 'undefined') {
  const script = document.createElement('script');
  script.src = 'js/main.js';
  document.head.appendChild(script);
}
