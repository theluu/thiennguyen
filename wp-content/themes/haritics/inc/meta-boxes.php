<?php
/**
 * Registers meta boxes.
 */

if (! defined('ABSPATH')) {
    exit;
}

function haritics_meta_fields(): array
{
    return [
        'team' => [
            '_role' => ['label' => 'Chức danh', 'type' => 'text'],
            '_phone' => ['label' => 'Số điện thoại', 'type' => 'text'],
            '_email' => ['label' => 'Email', 'type' => 'email'],
            '_quote' => ['label' => 'Quote / mô tả ngắn', 'type' => 'textarea'],
            '_intro' => ['label' => 'Intro ngắn', 'type' => 'textarea'],
            '_secondary_image' => ['label' => 'Ảnh phụ URL', 'type' => 'url'],
            '_facebook_url' => ['label' => 'Facebook URL', 'type' => 'url'],
            '_twitter_url' => ['label' => 'Twitter URL', 'type' => 'url'],
            '_instagram_url' => ['label' => 'Instagram URL', 'type' => 'url'],
            '_linkedin_url' => ['label' => 'LinkedIn URL', 'type' => 'url'],
            '_skill_one_label' => ['label' => 'Kỹ năng 1 - tên', 'type' => 'text'],
            '_skill_one_value' => ['label' => 'Kỹ năng 1 - %', 'type' => 'number'],
            '_skill_two_label' => ['label' => 'Kỹ năng 2 - tên', 'type' => 'text'],
            '_skill_two_value' => ['label' => 'Kỹ năng 2 - %', 'type' => 'number'],
            '_skill_three_label' => ['label' => 'Kỹ năng 3 - tên', 'type' => 'text'],
            '_skill_three_value' => ['label' => 'Kỹ năng 3 - %', 'type' => 'number'],
        ],
        'project' => [
            '_summary' => ['label' => 'Tổng quan ngắn', 'type' => 'textarea'],
            '_location' => ['label' => 'Địa điểm', 'type' => 'text'],
            '_target_amount' => ['label' => 'Mục tiêu gây quỹ', 'type' => 'number'],
            '_raised_amount' => ['label' => 'Đã gây quỹ', 'type' => 'number'],
            '_start_date' => ['label' => 'Ngày bắt đầu', 'type' => 'date'],
            '_end_date' => ['label' => 'Ngày kết thúc', 'type' => 'date'],
            '_status' => ['label' => 'Trạng thái', 'type' => 'text'],
            '_leader_text' => ['label' => 'Lãnh đạo dự án', 'type' => 'text'],
            '_donor_text' => ['label' => 'Nhà hảo tâm', 'type' => 'text'],
            '_gallery_urls' => ['label' => 'Gallery URLs (mỗi dòng một ảnh)', 'type' => 'textarea'],
        ],
        'donation' => [
            '_badge' => ['label' => 'Nhãn / tag', 'type' => 'text'],
            '_target_amount' => ['label' => 'Mục tiêu gây quỹ', 'type' => 'number'],
            '_raised_amount' => ['label' => 'Đã gây quỹ', 'type' => 'number'],
            '_deadline' => ['label' => 'Hạn đóng góp', 'type' => 'date'],
            '_short_stats' => ['label' => 'Thông tin ngắn', 'type' => 'textarea'],
            '_location' => ['label' => 'Địa điểm', 'type' => 'text'],
            '_gallery_urls' => ['label' => 'Gallery URLs (mỗi dòng một ảnh)', 'type' => 'textarea'],
            '_external_url' => ['label' => 'Link đóng góp bên ngoài', 'type' => 'url'],
        ],
        'event' => [
            '_venue' => ['label' => 'Địa điểm tổ chức', 'type' => 'text'],
            '_event_date' => ['label' => 'Ngày diễn ra', 'type' => 'date'],
            '_event_end_date' => ['label' => 'Ngày kết thúc', 'type' => 'date'],
            '_organizer' => ['label' => 'Đơn vị tổ chức', 'type' => 'text'],
            '_register_url' => ['label' => 'Link đăng ký', 'type' => 'url'],
            '_gallery_urls' => ['label' => 'Gallery URLs (mỗi dòng một ảnh)', 'type' => 'textarea'],
        ],
    ];
}

function haritics_register_meta_boxes(): void
{
    foreach (haritics_meta_fields() as $post_type => $fields) {
        add_meta_box(
            'haritics_' . $post_type . '_meta',
            __('Thông tin mở rộng', 'haritics'),
            'haritics_render_meta_box',
            $post_type,
            'normal',
            'default',
            ['post_type' => $post_type, 'fields' => $fields]
        );
    }
}
add_action('add_meta_boxes', 'haritics_register_meta_boxes');

function haritics_render_meta_box(\WP_Post $post, array $meta_box): void
{
    $fields = $meta_box['args']['fields'] ?? [];
    wp_nonce_field('haritics_save_meta', 'haritics_meta_nonce');

    echo '<table class="form-table" role="presentation">';
    foreach ($fields as $key => $field) {
        $value = get_post_meta($post->ID, $key, true);
        echo '<tr>';
        echo '<th scope="row"><label for="' . esc_attr($key) . '">' . esc_html($field['label']) . '</label></th>';
        echo '<td>';

        if ($field['type'] === 'textarea') {
            echo '<textarea class="large-text" rows="4" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '">' . esc_textarea((string) $value) . '</textarea>';
        } else {
            echo '<input class="regular-text" type="' . esc_attr($field['type']) . '" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="' . esc_attr((string) $value) . '">';
        }

        echo '</td>';
        echo '</tr>';
    }
    echo '</table>';
}

function haritics_save_meta_boxes(int $post_id): void
{
    if (! isset($_POST['haritics_meta_nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['haritics_meta_nonce'])), 'haritics_save_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    $post_type = get_post_type($post_id);
    $definitions = haritics_meta_fields()[$post_type] ?? [];

    if ($definitions === [] || ! current_user_can('edit_post', $post_id)) {
        return;
    }

    foreach ($definitions as $key => $field) {
        $raw = $_POST[$key] ?? '';
        $value = is_string($raw) ? wp_unslash($raw) : '';

        switch ($field['type']) {
            case 'email':
                $sanitized = sanitize_email($value);
                break;
            case 'url':
                $sanitized = esc_url_raw($value);
                break;
            case 'number':
                $sanitized = $value === '' ? '' : (string) (float) $value;
                break;
            case 'textarea':
                $sanitized = wp_kses_post($value);
                break;
            default:
                $sanitized = sanitize_text_field($value);
                break;
        }

        if ($sanitized === '') {
            delete_post_meta($post_id, $key);
        } else {
            update_post_meta($post_id, $key, $sanitized);
        }
    }
}
add_action('save_post', 'haritics_save_meta_boxes');
