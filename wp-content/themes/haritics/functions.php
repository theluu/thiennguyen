<?php
/**
 * Theme bootstrap for Haritics.
 */

if (! defined('ABSPATH')) {
    exit;
}

$haritics_includes = [
    'inc/setup.php',
    'inc/helpers.php',
    'inc/options.php',
    'inc/content-types.php',
    'inc/meta-boxes.php',
    'inc/contact-form.php',
    'inc/project-forms.php',
    'inc/importer.php',
];

foreach ($haritics_includes as $haritics_include) {
    $haritics_path = get_template_directory() . '/' . $haritics_include;

    if (file_exists($haritics_path)) {
        require_once $haritics_path;
    }
}

/**
 * Tích hợp SweetAlert2 vào theme
 */
function haritics_enqueue_scripts() {
    // CDN CSS
    wp_enqueue_style('sweetalert2-css', 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css', array(), '11.0');

    // CDN JS
    wp_enqueue_script('sweetalert2-js', 'https://cdn.jsdelivr.net/npm/sweetalert2@11', array(), '11.0', true);
}
add_action('wp_enqueue_scripts', 'haritics_enqueue_scripts');