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
            '_status' => ['label' => 'Trạng thái', 'type' => 'select', 'options' => [
                'Dang-huy-dong' => 'Đang kêu gọi nguồn lực',
                'Tieu-bieu' => 'Tiêu biểu',
                'Dang-trien-khai' => 'Đang triển khai',
                'Dang-sap-trien-khai' => 'Đang sắp triển khai',
            ]],
            '_leader_id' => ['label' => 'Lãnh đạo dự án', 'type' => 'post_select', 'post_type' => 'team'],
            '_leader_condition' => ['label' => 'Link xem điều kiện lãnh đạo', 'type' => 'url'],
            '_leader_apply' => ['label' => 'Link ứng tuyển lãnh đạo', 'type' => 'url'],
            '_volunteer_needed' => ['label' => 'Nhân sự cần huy động', 'type' => 'text'],
            '_volunteer_condition' => ['label' => 'Link xem điều kiện tình nguyện', 'type' => 'url'],
            '_volunteer_apply' => ['label' => 'Link ứng tuyển tình nguyện', 'type' => 'url'],
            '_resources_other' => ['label' => 'Các nguồn lực khác', 'type' => 'textarea'],
            '_resources_detail' => ['label' => 'Link xem chi tiết nguồn lực', 'type' => 'url'],
            '_resources_donate' => ['label' => 'Link muốn đóng góp', 'type' => 'url'],
            '_donor_id' => ['label' => 'Nhà hảo tâm', 'type' => 'post_select', 'post_type' => 'donation'],
            '_leader_list_url' => ['label' => 'Link xem danh sách lãnh đạo', 'type' => 'url'],
            '_donor_list_url' => ['label' => 'Link xem danh sách nhà hảo tâm', 'type' => 'url'],
            '_accounting_public_url' => ['label' => 'Link bài viết quyết toán công khai', 'type' => 'url'],
            '_related_issues' => ['label' => 'Các vấn đề liên quan khác', 'type' => 'textarea'],
            '_gallery_urls' => ['label' => 'Gallery URLs (mỗi dòng một ảnh)', 'type' => 'textarea'],
        ],
        'donation' => [
            '_donor_type' => ['label' => 'Loại mạnh thường quân', 'type' => 'select', 'options' => [
                'Ca nhan' => 'Cá nhân',
                'Doanh nghiep' => 'Doanh nghiệp',
            ]],
            '_birth_date' => ['label' => 'Ngày sinh', 'type' => 'date'],
            '_identifier_code' => ['label' => 'Mã số định danh (CMND/CCCD/MST)', 'type' => 'text'],
            '_phone' => ['label' => 'Số điện thoại', 'type' => 'text'],
            '_email' => ['label' => 'Email', 'type' => 'email'],
            '_address' => ['label' => 'Địa chỉ', 'type' => 'textarea'],
            '_preferred_contact' => ['label' => 'Kênh liên lạc ưu tiên', 'type' => 'select', 'options' => [
                'Zalo' => 'Zalo',
                'Email' => 'Email',
                'Phone' => 'Phone',
            ]],
            '_contribution_type' => ['label' => 'Loại đóng góp', 'type' => 'select', 'options' => [
                'Tien' => 'Tiền',
                'Hien vat' => 'Hiện vật',
                'Dich vu' => 'Dịch vụ',
            ]],
            '_contribution_value' => ['label' => 'Số tiền / Giá trị quy đổi', 'type' => 'number'],
            '_contribution_date' => ['label' => 'Ngày đóng góp', 'type' => 'date'],
            '_contribution_method' => ['label' => 'Hình thức đóng góp', 'type' => 'select', 'options' => [
                'Chuyen khoan' => 'Chuyển khoản',
                'Tien mat' => 'Tiền mặt',
                'Vi dien tu' => 'Ví điện tử',
            ]],
            '_campaign_related' => ['label' => 'Chiến dịch / chương trình liên quan', 'type' => 'text'],
            '_donation_history' => ['label' => 'Lịch sử các lần đóng góp', 'type' => 'textarea'],
            '_total_contributed' => ['label' => 'Tổng giá trị đã đóng góp', 'type' => 'number'],
            '_contribution_frequency' => ['label' => 'Tần suất đóng góp', 'type' => 'text'],
            '_campaign_interest' => ['label' => 'Chiến dịch quan tâm', 'type' => 'textarea'],
            '_private_notes' => ['label' => 'Ghi chú riêng', 'type' => 'textarea'],
            '_is_anonymous' => ['label' => 'Ẩn danh', 'type' => 'select', 'options' => [
                '0' => 'Không',
                '1' => 'Có',
            ]],
            '_marketing_opt_in' => ['label' => 'Đồng ý nhận thông tin', 'type' => 'select', 'options' => [
                '0' => 'Không',
                '1' => 'Có',
            ]],
            '_gallery_urls' => ['label' => 'Gallery URLs (mỗi dòng một ảnh)', 'type' => 'textarea'],
        ],
        'event' => [
            '_venue' => ['label' => 'Địa điểm tổ chức', 'type' => 'text'],
            '_event_date' => ['label' => 'Ngày diễn ra', 'type' => 'date'],
            '_event_end_date' => ['label' => 'Ngày kết thúc', 'type' => 'date'],
            '_organizer' => ['label' => 'Đơn vị tổ chức', 'type' => 'text'],
            '_register_url' => ['label' => 'Link đăng ký', 'type' => 'url'],
            '_gallery_urls' => ['label' => 'Gallery URLs (mỗi dòng một ảnh)', 'type' => 'textarea'],
        ],
        'haritics_apply' => [
            '_project_id' => ['label' => 'ID dự án', 'type' => 'number'],
            '_apply_role' => ['label' => 'Vị trí ứng tuyển', 'type' => 'select', 'options' => [
                'leader' => 'Lãnh đạo dự án',
                'volunteer' => 'Nhân sự / tình nguyện',
            ]],
            '_applicant_name' => ['label' => 'Họ tên', 'type' => 'text'],
            '_applicant_email' => ['label' => 'Email', 'type' => 'email'],
            '_applicant_phone' => ['label' => 'Điện thoại', 'type' => 'text'],
        ],
        'haritics_contribute' => [
            '_project_id' => ['label' => 'ID dự án', 'type' => 'number'],
            '_applicant_name' => ['label' => 'Họ tên', 'type' => 'text'],
            '_applicant_email' => ['label' => 'Email', 'type' => 'email'],
            '_applicant_phone' => ['label' => 'Điện thoại', 'type' => 'text'],
            '_contribution_note' => ['label' => 'Nội dung / hình thức muốn đóng góp', 'type' => 'textarea'],
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
        } elseif ($field['type'] === 'select') {
            $options = $field['options'] ?? [];
            echo '<select class="regular-text" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '">';
            echo '<option value="">' . esc_html__('Chọn', 'haritics') . '</option>';
            foreach ($options as $option_value => $option_label) {
                echo '<option value="' . esc_attr((string) $option_value) . '"' . selected((string) $value, (string) $option_value, false) . '>' . esc_html((string) $option_label) . '</option>';
            }
            echo '</select>';
        } elseif ($field['type'] === 'post_select') {
            $post_type = $field['post_type'] ?? 'post';
            $posts = get_posts([
                'post_type' => $post_type,
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'orderby' => 'title',
                'order' => 'ASC',
            ]);
            echo '<select class="regular-text" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '">';
            echo '<option value="">' . esc_html__('Chọn', 'haritics') . '</option>';
            foreach ($posts as $post_item) {
                echo '<option value="' . esc_attr((string) $post_item->ID) . '"' . selected($value, (string) $post_item->ID, false) . '>' . esc_html($post_item->post_title) . '</option>';
            }
            echo '</select>';
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
            case 'select':
            case 'post_select':
                $sanitized = sanitize_text_field($value);
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
