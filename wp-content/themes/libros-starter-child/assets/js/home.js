/**
 * Home Page — JavaScript
 * Dark/Light toggle (shared with shop), scroll reveal, smooth scroll.
 *
 * @package libros-starter-child
 */

"use strict";

(function () {
    /* ─────────────────────────────────────
       THEME TOGGLE (shared localStorage key with shop)
       ───────────────────────────────────── */
    var STORAGE_KEY = "libros-theme";

    function getPreferredTheme() {
        var stored = localStorage.getItem(STORAGE_KEY);
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
        var isDark = theme === "dark";
        document.querySelectorAll(".theme-toggle").forEach(function (toggle) {
            var knobSun = toggle.querySelector(".theme-toggle__knob-sun");
            var knobMoon = toggle.querySelector(".theme-toggle__knob-moon");
            if (knobSun) knobSun.style.display = isDark ? "none" : "block";
            if (knobMoon) knobMoon.style.display = isDark ? "block" : "none";
        });
    }

    function toggleTheme() {
        var current =
            document.documentElement.getAttribute("data-theme") || "light";
        applyTheme(current === "dark" ? "light" : "dark");
    }

    // Apply immediately
    applyTheme(getPreferredTheme());

    /* ─────────────────────────────────────
       DOM READY
       ───────────────────────────────────── */
    document.addEventListener("DOMContentLoaded", function () {
        updateToggleIcons(getPreferredTheme());

        // Theme toggle buttons
        var themeToggle = document.getElementById("themeToggle");
        var themeToggleMobile = document.getElementById("themeToggleMobile");
        if (themeToggle) themeToggle.addEventListener("click", toggleTheme);
        if (themeToggleMobile)
            themeToggleMobile.addEventListener("click", toggleTheme);

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

        document.addEventListener("keydown", function (e) {
            if (e.key === "Escape") closeDrawer();
        });

        /* ─────────────────────────────────────
           HEADER SCROLL EFFECT
           ───────────────────────────────────── */
        var header = document.getElementById("shopHeader");
        if (header) {
            window.addEventListener(
                "scroll",
                function () {
                    if (window.scrollY > 20) {
                        header.classList.add("is-scrolled");
                    } else {
                        header.classList.remove("is-scrolled");
                    }
                },
                { passive: true }
            );
        }

        /* ─────────────────────────────────────
           CATEGORY DROPDOWN
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
        document.addEventListener("click", function (e) {
            dropdowns.forEach(function (dropdown) {
                if (!dropdown.contains(e.target)) {
                    dropdown.classList.remove("is-open");
                }
            });
        });

        /* ─────────────────────────────────────
           SCROLL REVEAL ANIMATION
           ───────────────────────────────────── */
        var revealElements = document.querySelectorAll(".home-reveal");
        if (revealElements.length && "IntersectionObserver" in window) {
            var revealObserver = new IntersectionObserver(
                function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add("is-visible");
                            revealObserver.unobserve(entry.target);
                        }
                    });
                },
                { threshold: 0.08, rootMargin: "0px 0px -30px 0px" }
            );

            revealElements.forEach(function (el) {
                revealObserver.observe(el);
            });
        }

        /* ─────────────────────────────────────
           SMOOTH SCROLL for #anchor links
           ───────────────────────────────────── */
        document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
            anchor.addEventListener("click", function (e) {
                var targetId = this.getAttribute("href");
                if (targetId === "#") return;
                var target = document.querySelector(targetId);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: "smooth", block: "start" });
                }
            });
        });
    });
})();
