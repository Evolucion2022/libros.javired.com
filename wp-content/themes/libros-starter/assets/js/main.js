/**
 * Libros Starter Theme — Main JavaScript
 *
 * - Scroll reveal (IntersectionObserver)
 * - Smooth scroll for anchor links
 * - CTA tracking (Google Analytics + Facebook Pixel)
 */

"use strict";

/* === SCROLL REVEAL === */
function initScrollReveal() {
    const reveals = document.querySelectorAll('.reveal');
    if (!reveals.length) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -60px 0px'
    });

    reveals.forEach(el => observer.observe(el));
}

/* === SMOOTH SCROLL === */
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', (e) => {
            const href = e.currentTarget.getAttribute('href');
            if (!href || href === '#') return;
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
}

/* === CTA TRACKING === */
function initCtaTracking() {
    const ctas = document.querySelectorAll('[id^="cta-"]');
    ctas.forEach(cta => {
        cta.addEventListener('click', () => {
            sessionStorage.setItem('cta_clicked', 'true');
            trackEvent('cta_click', cta.id);
        });
    });
}

function trackEvent(action, label) {
    if (typeof window.gtag === 'function') {
        window.gtag('event', action, {
            event_category: 'landing',
            event_label: label
        });
    }
    if (typeof window.fbq === 'function') {
        window.fbq('trackCustom', action, { label });
    }
}

/* === INIT === */
document.addEventListener('DOMContentLoaded', () => {
    initScrollReveal();
    initSmoothScroll();
    initCtaTracking();
});
