/**
 * Shop Page — JavaScript
 * Dark/Light toggle, mobile drawer, scroll effects, category filters.
 *
 * @package libros-starter-child
 */

"use strict";

(function () {
    /* ─────────────────────────────────────
       THEME TOGGLE (Dark / Light)
       ───────────────────────────────────── */
    const STORAGE_KEY = "libros-theme";

    function getPreferredTheme() {
        const stored = localStorage.getItem(STORAGE_KEY);
        if (stored) return stored;
        return window.matchMedia("(prefers-color-scheme: dark)").matches
            ? "dark"
            : "light";
    }

    function applyTheme(theme) {
        document.documentElement.setAttribute("data-theme", theme);
        localStorage.setItem(STORAGE_KEY, theme);
        updateToggleIcons(theme);
    }

    function updateToggleIcons(theme) {
        const isDark = theme === "dark";
        document.querySelectorAll(".theme-toggle").forEach(function (toggle) {
            var knobSun = toggle.querySelector(".theme-toggle__knob-sun");
            var knobMoon = toggle.querySelector(".theme-toggle__knob-moon");
            if (knobSun) knobSun.style.display = isDark ? "none" : "block";
            if (knobMoon) knobMoon.style.display = isDark ? "block" : "none";
        });
    }

    function toggleTheme() {
        var current = document.documentElement.getAttribute("data-theme") || "light";
        applyTheme(current === "dark" ? "light" : "dark");
    }

    // Apply theme immediately (before DOMContentLoaded to prevent flash)
    applyTheme(getPreferredTheme());

    /* ─────────────────────────────────────
       DOM READY
       ───────────────────────────────────── */
    document.addEventListener("DOMContentLoaded", function () {
        // Re-apply to make sure icons are set
        updateToggleIcons(getPreferredTheme());

        // Theme toggle buttons
        var themeToggle = document.getElementById("themeToggle");
        var themeToggleMobile = document.getElementById("themeToggleMobile");

        if (themeToggle) {
            themeToggle.addEventListener("click", toggleTheme);
        }
        if (themeToggleMobile) {
            themeToggleMobile.addEventListener("click", toggleTheme);
        }

        /* ─────────────────────────────────────
           MOBILE DRAWER
           ───────────────────────────────────── */
        var hamburger = document.getElementById("shopHamburger");
        var drawer = document.getElementById("shopDrawer");
        var drawerOverlay = document.getElementById("shopDrawerOverlay");
        var drawerClose = document.getElementById("shopDrawerClose");

        function openDrawer() {
            if (drawer) drawer.classList.add("is-open");
            if (drawerOverlay) drawerOverlay.classList.add("is-open");
            document.body.style.overflow = "hidden";
        }

        function closeDrawer() {
            if (drawer) drawer.classList.remove("is-open");
            if (drawerOverlay) drawerOverlay.classList.remove("is-open");
            document.body.style.overflow = "";
        }

        if (hamburger) hamburger.addEventListener("click", openDrawer);
        if (drawerClose) drawerClose.addEventListener("click", closeDrawer);
        if (drawerOverlay) drawerOverlay.addEventListener("click", closeDrawer);

        // Close on Escape key
        document.addEventListener("keydown", function (e) {
            if (e.key === "Escape") closeDrawer();
        });

        /* ─────────────────────────────────────
           HEADER SCROLL EFFECT
           ───────────────────────────────────── */
        var header = document.getElementById("shopHeader");
        var lastScroll = 0;

        if (header) {
            window.addEventListener(
                "scroll",
                function () {
                    var currentScroll = window.scrollY;
                    if (currentScroll > 20) {
                        header.classList.add("is-scrolled");
                    } else {
                        header.classList.remove("is-scrolled");
                    }
                    lastScroll = currentScroll;
                },
                { passive: true }
            );
        }

        /* ─────────────────────────────────────
           CATEGORY DROPDOWN (Desktop)
           ───────────────────────────────────── */
        var dropdowns = document.querySelectorAll(".shop-nav-dropdown");
        dropdowns.forEach(function (dropdown) {
            var trigger = dropdown.querySelector(".shop-nav-dropdown__trigger");
            if (trigger) {
                trigger.addEventListener("click", function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropdown.classList.toggle("is-open");
                });
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener("click", function (e) {
            dropdowns.forEach(function (dropdown) {
                if (!dropdown.contains(e.target)) {
                    dropdown.classList.remove("is-open");
                }
            });
        });

        /* ─────────────────────────────────────
           PRODUCT CARD REVEAL ANIMATION
           ───────────────────────────────────── */
        var cards = document.querySelectorAll(".shop-card");
        if (cards.length && "IntersectionObserver" in window) {
            // Add initial hidden state
            cards.forEach(function (card, index) {
                card.style.opacity = "0";
                card.style.transform = "translateY(24px)";
                card.style.transition =
                    "opacity 0.5s ease " +
                    index * 0.04 +
                    "s, transform 0.5s ease " +
                    index * 0.04 +
                    "s";
            });

            var observer = new IntersectionObserver(
                function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            entry.target.style.opacity = "1";
                            entry.target.style.transform = "translateY(0)";
                            observer.unobserve(entry.target);
                        }
                    });
                },
                { threshold: 0.05, rootMargin: "0px 0px -40px 0px" }
            );

            cards.forEach(function (card) {
                observer.observe(card);
            });
        }

        /* ─────────────────────────────────────
           SEARCH FOCUS ENHANCEMENT
           ───────────────────────────────────── */
        var searchInput = document.querySelector(".shop-hero__search input");
        if (searchInput) {
            searchInput.addEventListener("focus", function () {
                this.closest(".shop-hero__search").classList.add("is-focused");
            });
            searchInput.addEventListener("blur", function () {
                this.closest(".shop-hero__search").classList.remove("is-focused");
            });
        }
    });
})();
