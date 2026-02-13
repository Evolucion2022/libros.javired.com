<?php
/**
 * One-time: Activate the Libros Starter Child theme.
 *
 * This mu-plugin runs once to switch the active theme
 * from "libros-starter" to "libros-starter-child".
 * After activation, it sets an option flag so it never runs again.
 */

if (!defined('ABSPATH'))
    exit;

add_action('init', function () {
    // Only run once
    if (get_option('libros_child_theme_activated'))
        return;

    // Only switch if the child theme directory exists
    $child = wp_get_theme('libros-starter-child');
    if (!$child->exists())
        return;

    // Only switch if we're currently on the parent
    $current = get_stylesheet();
    if ($current === 'libros-starter') {
        switch_theme('libros-starter-child');
    }

    // Mark as done (even if we didn't switch — prevents re-running)
    update_option('libros_child_theme_activated', '1');
}, 5);
