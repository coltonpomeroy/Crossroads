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

  // ── Donation amount picker (Donate page) ──
  // Each amount tile carries the Stripe Payment Link it should open. We only
  // pick a destination here — Stripe collects the card, name, and email.
  const donateWidget = document.querySelector('.donate-widget');

  if (donateWidget) {
    const freqButtons = donateWidget.querySelectorAll('.donate-freq-btn');
    const panels = donateWidget.querySelectorAll('.donate-panel');
    const submit = donateWidget.querySelector('.donate-submit');
    let selected = null;

    const clearSelection = function () {
      donateWidget.querySelectorAll('.donate-amount[aria-pressed="true"]').forEach(function (btn) {
        btn.setAttribute('aria-pressed', 'false');
      });
      selected = null;
      submit.disabled = true;
      submit.textContent = 'Choose an amount';
    };

    // Highlight a tile and turn the CTA into a confirmation of what's selected.
    donateWidget.querySelectorAll('.donate-amount').forEach(function (btn) {
      btn.addEventListener('click', function () {
        clearSelection();
        btn.setAttribute('aria-pressed', 'true');
        selected = btn;
        submit.disabled = false;

        if (btn.dataset.custom) {
          submit.textContent = 'Continue to checkout →';
        } else if (btn.dataset.monthly) {
          submit.textContent = 'Donate $' + btn.dataset.amount + '/month →';
        } else {
          submit.textContent = 'Donate $' + btn.dataset.amount + ' →';
        }
      });
    });

    // Switching frequency swaps panels and resets the choice, so a one-time
    // selection can never be submitted against a monthly link.
    freqButtons.forEach(function (btn) {
      btn.addEventListener('click', function () {
        const freq = btn.dataset.freq;
        if (donateWidget.dataset.freq === freq) return;

        donateWidget.dataset.freq = freq;
        clearSelection();

        freqButtons.forEach(function (other) {
          const active = other === btn;
          other.classList.toggle('is-active', active);
          other.setAttribute('aria-pressed', active ? 'true' : 'false');
        });
        panels.forEach(function (panel) {
          panel.hidden = panel.dataset.panel !== freq;
        });
      });
    });

    submit.addEventListener('click', function () {
      if (!selected || !selected.dataset.url) return;
      submit.disabled = true;
      submit.textContent = 'Taking you to checkout…';
      window.location.href = selected.dataset.url;
    });
  }

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
