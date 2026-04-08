<?php
/**
 * Theme setup and assets.
 */

if (! defined('ABSPATH')) {
    exit;
}

function haritics_setup(): void
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('site-icon');
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'script', 'style']);

    register_nav_menus([
        'primary_menu' => __('Primary Menu', 'haritics'),
        'footer_menu_1' => __('Footer Menu 1', 'haritics'),
        'footer_menu_2' => __('Footer Menu 2', 'haritics'),
    ]);
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
    wp_enqueue_style('haritics-theme', get_stylesheet_uri(), ['haritics-style'], haritics_asset_version('style.css'));

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

function haritics_render_favicon(): void
{
    if (function_exists('has_site_icon') && has_site_icon()) {
        return;
    }

    $favicon_url = haritics_get_option('site_favicon', haritics_theme_asset('assets/img/logo.svg'));

    if ($favicon_url === '') {
        return;
    }

    $mime_type = wp_check_filetype($favicon_url)['type'] ?? 'image/svg+xml';
    ?>
    <link rel="icon" href="<?php echo esc_url($favicon_url); ?>" type="<?php echo esc_attr($mime_type); ?>">
    <link rel="shortcut icon" href="<?php echo esc_url($favicon_url); ?>" type="<?php echo esc_attr($mime_type); ?>">
    <?php
}
add_action('wp_head', 'haritics_render_favicon', 5);

function haritics_migrate_vi_routes(): void
{
    $migration_version = 'vi-routes-1';

    if (get_option('haritics_route_migration_version') === $migration_version) {
        return;
    }

    $route_map = [
        '/projects/' => haritics_route_url('project'),
        '/team/' => haritics_route_url('team'),
        '/donations/' => haritics_route_url('donation'),
        '/events/' => haritics_route_url('event'),
        '/contact/' => haritics_route_url('contact'),
    ];

    $options = get_option('haritics_theme_options', []);
    if (is_array($options)) {
        $option_url_keys = ['header_button_url', 'home_primary_cta_url'];

        foreach ($option_url_keys as $key) {
            if (empty($options[$key]) || ! is_string($options[$key])) {
                continue;
            }

            foreach ($route_map as $old_path => $new_url) {
                if ($options[$key] === home_url($old_path)) {
                    $options[$key] = $new_url;
                }
            }
        }

        if (empty($options['site_favicon'])) {
            $options['site_favicon'] = haritics_theme_asset('assets/img/logo.svg');
        }

        update_option('haritics_theme_options', $options);
    }

    $menu_items = get_posts([
        'post_type' => 'nav_menu_item',
        'post_status' => 'any',
        'numberposts' => -1,
    ]);

    foreach ($menu_items as $item) {
        $url = get_post_meta($item->ID, '_menu_item_url', true);

        if (! is_string($url) || $url === '') {
            continue;
        }

        foreach ($route_map as $old_path => $new_url) {
            if ($url === home_url($old_path)) {
                update_post_meta($item->ID, '_menu_item_url', $new_url);
                break;
            }
        }
    }

    $old_contact_page = get_page_by_path('contact', OBJECT, 'page');
    $new_contact_page = get_page_by_path(haritics_route_path('contact'), OBJECT, 'page');

    if ($old_contact_page instanceof \WP_Post && ! $new_contact_page instanceof \WP_Post) {
        wp_update_post([
            'ID' => $old_contact_page->ID,
            'post_name' => haritics_route_path('contact'),
        ]);
        update_post_meta($old_contact_page->ID, '_wp_page_template', 'page-contact.php');
    } elseif ($new_contact_page instanceof \WP_Post) {
        update_post_meta($new_contact_page->ID, '_wp_page_template', 'page-contact.php');
    }

    update_option('haritics_route_migration_version', $migration_version);
}
add_action('init', 'haritics_migrate_vi_routes', 20);

function haritics_flush_rewrite_once(): void
{
    $rewrite_version = 'vi-routes-1';

    if (get_option('haritics_rewrite_flushed') === $rewrite_version) {
        return;
    }

    flush_rewrite_rules(false);
    update_option('haritics_rewrite_flushed', $rewrite_version);
}
add_action('init', 'haritics_flush_rewrite_once', 99);

function haritics_reset_rewrite_flag(): void
{
    delete_option('haritics_rewrite_flushed');
}
add_action('after_switch_theme', 'haritics_reset_rewrite_flag');
