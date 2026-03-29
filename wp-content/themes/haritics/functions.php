<?php
/**
 * Theme bootstrap for Haritics.
 */

if (! defined('ABSPATH')) {
    exit;
}

function haritics_setup(): void
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'script', 'style']);
}
add_action('after_setup_theme', 'haritics_setup');

function haritics_asset_version(string $relative_path): ?string
{
    $file = get_template_directory() . '/' . ltrim($relative_path, '/');

    return file_exists($file) ? (string) filemtime($file) : null;
}

function haritics_enqueue_assets(): void
{
    $theme_uri = get_template_directory_uri();

    wp_enqueue_style('haritics-icons', $theme_uri . '/assets/icon/flaticon_charitics.css', [], haritics_asset_version('assets/icon/flaticon_charitics.css'));
    wp_enqueue_style('haritics-bootstrap', $theme_uri . '/assets/vendor/bootstrap/bootstrap.min.css', [], haritics_asset_version('assets/vendor/bootstrap/bootstrap.min.css'));
    wp_enqueue_style('haritics-splide', $theme_uri . '/assets/vendor/splide/splide.min.css', [], haritics_asset_version('assets/vendor/splide/splide.min.css'));
    wp_enqueue_style('haritics-swiper', $theme_uri . '/assets/vendor/swiper/swiper-bundle.min.css', [], haritics_asset_version('assets/vendor/swiper/swiper-bundle.min.css'));
    wp_enqueue_style('haritics-slimselect', $theme_uri . '/assets/vendor/slim-select/slimselect.css', [], haritics_asset_version('assets/vendor/slim-select/slimselect.css'));
    wp_enqueue_style('haritics-animate', $theme_uri . '/assets/vendor/animate-wow/animate.min.css', [], haritics_asset_version('assets/vendor/animate-wow/animate.min.css'));
    wp_enqueue_style('haritics-flatpickr', $theme_uri . '/assets/vendor/flatpickr/flatpickr.min.css', [], haritics_asset_version('assets/vendor/flatpickr/flatpickr.min.css'));
    wp_enqueue_style('haritics-style', $theme_uri . '/assets/css/style.css', [], haritics_asset_version('assets/css/style.css'));

    wp_enqueue_script('haritics-bootstrap', $theme_uri . '/assets/vendor/bootstrap/bootstrap.bundle.min.js', [], haritics_asset_version('assets/vendor/bootstrap/bootstrap.bundle.min.js'), true);
    wp_enqueue_script('haritics-splide', $theme_uri . '/assets/vendor/splide/splide.min.js', [], haritics_asset_version('assets/vendor/splide/splide.min.js'), true);
    wp_enqueue_script('haritics-splide-auto-scroll', $theme_uri . '/assets/vendor/splide/splide-extension-auto-scroll.min.js', ['haritics-splide'], haritics_asset_version('assets/vendor/splide/splide-extension-auto-scroll.min.js'), true);
    wp_enqueue_script('haritics-swiper', $theme_uri . '/assets/vendor/swiper/swiper-bundle.min.js', [], haritics_asset_version('assets/vendor/swiper/swiper-bundle.min.js'), true);
    wp_enqueue_script('haritics-slimselect', $theme_uri . '/assets/vendor/slim-select/slimselect.min.js', [], haritics_asset_version('assets/vendor/slim-select/slimselect.min.js'), true);
    wp_enqueue_script('haritics-wow', $theme_uri . '/assets/vendor/animate-wow/wow.min.js', [], haritics_asset_version('assets/vendor/animate-wow/wow.min.js'), true);
    wp_enqueue_script('haritics-splittype', $theme_uri . '/assets/vendor/splittype/index.min.js', [], haritics_asset_version('assets/vendor/splittype/index.min.js'), true);
    wp_enqueue_script('haritics-mixitup', $theme_uri . '/assets/vendor/mixitup/mixitup.min.js', [], haritics_asset_version('assets/vendor/mixitup/mixitup.min.js'), true);
    wp_enqueue_script('haritics-fslightbox', $theme_uri . '/assets/vendor/fslightbox/fslightbox.js', [], haritics_asset_version('assets/vendor/fslightbox/fslightbox.js'), true);
    wp_enqueue_script('haritics-flatpickr', $theme_uri . '/assets/vendor/flatpickr/flatpickr.js', [], haritics_asset_version('assets/vendor/flatpickr/flatpickr.js'), true);
    wp_enqueue_script('haritics-main', $theme_uri . '/assets/js/main.js', ['jquery'], haritics_asset_version('assets/js/main.js'), true);
    wp_enqueue_script('haritics-tab', $theme_uri . '/assets/js/tab.js', ['haritics-main'], haritics_asset_version('assets/js/tab.js'), true);
    wp_enqueue_script('haritics-accordion', $theme_uri . '/assets/js/accordion.js', ['haritics-main'], haritics_asset_version('assets/js/accordion.js'), true);
    wp_enqueue_script('haritics-progressbar', $theme_uri . '/assets/js/progressbar.js', ['haritics-main'], haritics_asset_version('assets/js/progressbar.js'), true);
    wp_enqueue_script('haritics-donate-form', $theme_uri . '/assets/js/donate-form.js', ['haritics-main'], haritics_asset_version('assets/js/donate-form.js'), true);
}
add_action('wp_enqueue_scripts', 'haritics_enqueue_assets');

function haritics_get_source_file(string $template = 'index.html'): string
{
    $template = basename($template);
    $file = get_template_directory() . '/source/' . $template;

    if (! file_exists($file)) {
        $file = get_template_directory() . '/source/index.html';
    }

    return $file;
}

function haritics_set_current_template(string $template = 'index.html'): void
{
    $GLOBALS['haritics_current_template'] = basename($template);
}

function haritics_get_current_template(): string
{
    return isset($GLOBALS['haritics_current_template']) ? basename((string) $GLOBALS['haritics_current_template']) : 'index.html';
}

function haritics_source_template_exists(string $template): bool
{
    return file_exists(get_template_directory() . '/source/' . basename($template));
}

function haritics_get_html_parts(string $template = 'index.html'): array
{
    static $cache = [];

    $template = basename($template);

    if (isset($cache[$template])) {
        return $cache[$template];
    }

    $html = file_get_contents(haritics_get_source_file($template));

    preg_match('#<body>(.*)<main\b[^>]*>#s', $html, $before_main);
    preg_match('#<main\b[^>]*>(.*)</main>#s', $html, $main);
    preg_match('#</main>(.*)</body>#s', $html, $after_main);

    $cache[$template] = [
        'before_main' => $before_main[1] ?? '',
        'main' => $main[1] ?? '',
        'after_main' => $after_main[1] ?? '',
    ];

    return $cache[$template];
}

function haritics_transform_markup(string $markup, bool $strip_scripts = false): string
{
    $theme_uri = esc_url(get_template_directory_uri());
    $home = home_url('/');

    $markup = str_replace('src="assets/', 'src="' . $theme_uri . '/assets/', $markup);
    $markup = str_replace('href="assets/', 'href="' . $theme_uri . '/assets/', $markup);
    $markup = preg_replace_callback(
        '#(href|action)="([a-z0-9-]+)\.html(?:\#([^"]*))?"#i',
        static function (array $matches) use ($home): string {
            $attribute = $matches[1];
            $slug = strtolower($matches[2]);
            $anchor = isset($matches[3]) ? '#' . $matches[3] : '';
            $url = $slug === 'index' ? $home : home_url('/' . $slug . '/');

            return sprintf('%s="%s%s"', $attribute, esc_url($url), $anchor);
        },
        $markup
    );
    $markup = str_replace('href="#', 'href="' . esc_url($home) . '#', $markup);
    $markup = str_replace('action="#', 'action="' . esc_url($home) . '#', $markup);

    if ($strip_scripts) {
        $markup = preg_replace('#<script\b[^>]*>.*?</script>#is', '', $markup);
    }

    return $markup;
}

function haritics_render_part(string $part, string $template = 'index.html', bool $strip_scripts = false): void
{
    $parts = haritics_get_html_parts($template);

    if (! isset($parts[$part])) {
        return;
    }

    echo haritics_transform_markup($parts[$part], $strip_scripts); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
