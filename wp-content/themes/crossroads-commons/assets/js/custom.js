/**
 * Crossroads Commons — Custom JavaScript
 */

document.addEventListener('DOMContentLoaded', function () {
  // ── Hamburger Menu Toggle ──
  const hamburger = document.querySelector('.hamburger');
  const mobileMenu = document.querySelector('.mobile-menu');

  if (hamburger && mobileMenu) {
    hamburger.addEventListener('click', function () {
      hamburger.classList.toggle('open');
      mobileMenu.classList.toggle('open');
    });
  }

  // ── FAQ Accordion Toggle ──
  const faqQuestions = document.querySelectorAll('.faq-question');

  faqQuestions.forEach(function (question) {
    question.addEventListener('click', function () {
      this.parentElement.classList.toggle('open');
    });
  });

  // ── Video Section: play when scrolled into view, pause when out ──
  const visionVideo = document.querySelector('.video-section video');
  if (visionVideo && 'IntersectionObserver' in window) {
    const observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          visionVideo.play().catch(function () {});
        } else {
          visionVideo.pause();
        }
      });
    }, { threshold: 0.5 });
    observer.observe(visionVideo);
  }
});
