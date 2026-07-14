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

  // ── Donate Modal ──
  const donateModal = document.getElementById('donate-modal');
  if (donateModal) {
    const modalBox = donateModal.querySelector('.donate-modal-box');

    const gotoStep = function (step) {
      const form = donateModal.querySelector('.donate-wizard');
      if (!form) return;
      form.dataset.step = String(step);
      const ind = form.querySelector('.donate-step-indicator');
      if (ind) ind.textContent = 'Step ' + step + ' of 2';
      if (modalBox) modalBox.scrollTop = 0;
    };

    // Turn the (server-rendered) Charitable form into a 2-step wizard.
    const initWizard = function () {
      const form = donateModal.querySelector('#charitable-donation-form');
      if (!form || form.classList.contains('donate-wizard')) return;

      const fieldsets = form.querySelectorAll('fieldset.charitable-fieldset');
      const amountFs = fieldsets[0];
      const detailsFs = form.querySelector('#charitable-donor-fields') || fieldsets[1];
      const submitBtn = form.querySelector('[type="submit"][name="donate"], .donate-button');
      if (!amountFs || !detailsFs || !submitBtn) return; // structure changed — leave form as-is

      form.classList.add('donate-wizard');

      // Step indicator at the top of the form.
      const indicator = document.createElement('div');
      indicator.className = 'donate-step-indicator';
      indicator.textContent = 'Step 1 of 2';
      form.insertBefore(indicator, form.firstChild);

      amountFs.classList.add('donate-step-1');
      detailsFs.classList.add('donate-step-2');
      submitBtn.classList.add('donate-step-2-el');
      if (submitBtn.parentElement && submitBtn.parentElement !== form) {
        submitBtn.parentElement.classList.add('donate-step-2-el');
      }

      // "Continue" — advances to step 2 (only shown on step 1).
      const continueBtn = document.createElement('button');
      continueBtn.type = 'button';
      continueBtn.className = 'charitable-button donate-wizard-btn donate-continue donate-step-1-el';
      continueBtn.textContent = 'Continue →';
      amountFs.insertAdjacentElement('afterend', continueBtn);
      continueBtn.addEventListener('click', function () {
        const hasSuggested = !!form.querySelector('.donation-amount.selected, input[name="donation_amount"]:checked');
        const customEl = form.querySelector('input[name="custom_donation_amount"]');
        const hasCustom = customEl && parseFloat(customEl.value) > 0;
        if (!hasSuggested && !hasCustom) {
          indicator.textContent = 'Please choose an amount to continue.';
          indicator.classList.add('donate-step-warn');
          return;
        }
        indicator.classList.remove('donate-step-warn');
        gotoStep(2);
      });

      // "Back" — returns to step 1 (only shown on step 2).
      const backBtn = document.createElement('button');
      backBtn.type = 'button';
      backBtn.className = 'donate-wizard-back donate-step-2-el';
      backBtn.textContent = '← Back';
      detailsFs.insertAdjacentElement('beforebegin', backBtn);
      backBtn.addEventListener('click', function () { gotoStep(1); });

      form.dataset.step = '1';
    };

    const openModal = function () {
      initWizard();
      gotoStep(1);
      donateModal.classList.add('open');
      donateModal.setAttribute('aria-hidden', 'false');
      document.body.classList.add('donate-modal-open');
    };
    const closeModal = function () {
      donateModal.classList.remove('open');
      donateModal.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('donate-modal-open');
    };
    document.querySelectorAll('[data-donate-open]').forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        openModal();
      });
    });
    donateModal.querySelectorAll('[data-donate-close]').forEach(function (btn) {
      btn.addEventListener('click', closeModal);
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && donateModal.classList.contains('open')) {
        closeModal();
      }
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
