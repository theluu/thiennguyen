<?php
/**
 * Theme options page.
 */

if (! defined('ABSPATH')) {
    exit;
}

function haritics_option_schema(): array
{
    return [
        'header_logo' => 'url',
        'header_cta_text' => 'text',
        'header_cta_url' => 'url',
        'header_button_text' => 'text',
        'header_button_url' => 'url',
        'site_favicon' => 'url',
        'hotline' => 'text',
        'email' => 'text',
        'address' => 'textarea',
        'footer_logo' => 'url',
        'footer_about' => 'textarea',
        'copyright_text' => 'text',
        'social_facebook' => 'url',
        'social_twitter' => 'url',
        'social_instagram' => 'url',
        'social_youtube' => 'url',
        'social_linkedin' => 'url',
        'home_hero_badge' => 'text',
        'home_hero_title' => 'text',
        'home_hero_description' => 'textarea',
        'home_hero_image' => 'url',
        'home_primary_cta_text' => 'text',
        'home_primary_cta_url' => 'url',
        'home_stat_number' => 'text',
        'home_stat_label' => 'text',
        'home_about_badge' => 'text',
        'home_about_title' => 'text',
        'home_about_description' => 'textarea',
        'home_about_image' => 'url',
        'home_projects_heading' => 'text',
        'home_donations_heading' => 'text',
        'home_team_heading' => 'text',
        'home_events_heading' => 'text',
        'contact_intro' => 'textarea',
        'contact_map_iframe' => 'textarea',
        'contact_success_message' => 'text',
        'recaptcha_v3_site_key' => 'text',
        'recaptcha_v3_secret_key' => 'text',
    ];
}

function haritics_register_theme_settings(): void
{
    register_setting(
        'haritics_theme_options_group',
        'haritics_theme_options',
        [
            'sanitize_callback' => 'haritics_sanitize_theme_options',
            'default' => [],
        ]
    );
}
add_action('admin_init', 'haritics_register_theme_settings');

function haritics_sanitize_theme_options(array $input): array
{
    $schema = haritics_option_schema();
    $output = [];

    foreach ($schema as $key => $type) {
        $value = $input[$key] ?? '';

        switch ($type) {
            case 'url':
                $output[$key] = esc_url_raw((string) $value);
                break;
            case 'textarea':
                $output[$key] = wp_kses_post((string) $value);
                break;
            default:
                $output[$key] = sanitize_text_field((string) $value);
                break;
        }
    }

    return $output;
}

function haritics_add_theme_options_page(): void
{
    add_theme_page(
        __('Haritics Settings', 'haritics'),
        __('Haritics Settings', 'haritics'),
        'manage_options',
        'haritics-settings',
        'haritics_render_theme_options_page'
    );
}
add_action('admin_menu', 'haritics_add_theme_options_page');

function haritics_render_option_field(string $key, string $label, string $type = 'text'): void
{
    $value = haritics_get_option($key);
    echo '<tr>';
    echo '<th scope="row"><label for="' . esc_attr($key) . '">' . esc_html($label) . '</label></th>';
    echo '<td>';

    if ($type === 'textarea') {
        echo '<textarea class="large-text" rows="4" id="' . esc_attr($key) . '" name="haritics_theme_options[' . esc_attr($key) . ']">' . esc_textarea($value) . '</textarea>';
    } else {
        echo '<input class="regular-text" type="' . esc_attr($type) . '" id="' . esc_attr($key) . '" name="haritics_theme_options[' . esc_attr($key) . ']" value="' . esc_attr($value) . '">';
    }

    echo '</td>';
    echo '</tr>';
}

function haritics_render_theme_options_page(): void
{
    if (! current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Haritics Settings', 'haritics'); ?></h1>
        <form method="post" action="options.php">
            <?php settings_fields('haritics_theme_options_group'); ?>

            <h2><?php esc_html_e('Header & Footer', 'haritics'); ?></h2>
            <table class="form-table" role="presentation">
                <?php
                haritics_render_option_field('header_logo', 'Header Logo URL', 'url');
                haritics_render_option_field('header_cta_text', 'Header CTA Text');
                haritics_render_option_field('header_cta_url', 'Header CTA URL', 'url');
                haritics_render_option_field('header_button_text', 'Header Button Text');
                haritics_render_option_field('header_button_url', 'Header Button URL', 'url');
                haritics_render_option_field('site_favicon', 'Site Favicon URL', 'url');
                haritics_render_option_field('hotline', 'Hotline');
                haritics_render_option_field('email', 'Email');
                haritics_render_option_field('address', 'Address', 'textarea');
                haritics_render_option_field('footer_logo', 'Footer Logo URL', 'url');
                haritics_render_option_field('footer_about', 'Footer About', 'textarea');
                haritics_render_option_field('copyright_text', 'Copyright Text');
                ?>
            </table>

            <h2><?php esc_html_e('Social Links', 'haritics'); ?></h2>
            <table class="form-table" role="presentation">
                <?php
                haritics_render_option_field('social_facebook', 'Facebook URL', 'url');
                haritics_render_option_field('social_twitter', 'Twitter URL', 'url');
                haritics_render_option_field('social_instagram', 'Instagram URL', 'url');
                haritics_render_option_field('social_youtube', 'YouTube URL', 'url');
                haritics_render_option_field('social_linkedin', 'LinkedIn URL', 'url');
                ?>
            </table>

            <h2><?php esc_html_e('Home Page', 'haritics'); ?></h2>
            <table class="form-table" role="presentation">
                <?php
                haritics_render_option_field('home_hero_badge', 'Hero Badge');
                haritics_render_option_field('home_hero_title', 'Hero Title');
                haritics_render_option_field('home_hero_description', 'Hero Description', 'textarea');
                haritics_render_option_field('home_hero_image', 'Hero Image URL', 'url');
                haritics_render_option_field('home_primary_cta_text', 'Primary CTA Text');
                haritics_render_option_field('home_primary_cta_url', 'Primary CTA URL', 'url');
                haritics_render_option_field('home_stat_number', 'Hero Stat Number');
                haritics_render_option_field('home_stat_label', 'Hero Stat Label');
                haritics_render_option_field('home_about_badge', 'About Badge');
                haritics_render_option_field('home_about_title', 'About Title');
                haritics_render_option_field('home_about_description', 'About Description', 'textarea');
                haritics_render_option_field('home_about_image', 'About Image URL', 'url');
                haritics_render_option_field('home_projects_heading', 'Projects Section Heading');
                haritics_render_option_field('home_donations_heading', 'Donations Section Heading');
                haritics_render_option_field('home_team_heading', 'Team Section Heading');
                haritics_render_option_field('home_events_heading', 'Events Section Heading');
                ?>
            </table>

            <h2><?php esc_html_e('Contact Page', 'haritics'); ?></h2>
            <table class="form-table" role="presentation">
                <?php
                haritics_render_option_field('contact_intro', 'Contact Intro', 'textarea');
                haritics_render_option_field('contact_map_iframe', 'Google Map Iframe', 'textarea');
                haritics_render_option_field('contact_success_message', 'Success Message');
                haritics_render_option_field('recaptcha_v3_site_key', 'reCAPTCHA v3 Site Key');
                haritics_render_option_field('recaptcha_v3_secret_key', 'reCAPTCHA v3 Secret Key');
                ?>
            </table>

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
