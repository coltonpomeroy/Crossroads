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
});
