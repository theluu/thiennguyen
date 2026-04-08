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
    'inc/importer.php',
];

foreach ($haritics_includes as $haritics_include) {
    $haritics_path = get_template_directory() . '/' . $haritics_include;

    if (file_exists($haritics_path)) {
        require_once $haritics_path;
    }
}
