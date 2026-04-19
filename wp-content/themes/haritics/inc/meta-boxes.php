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

            // ✅ UPDATED
            '_gallery_urls' => ['label' => 'Gallery Images', 'type' => 'multi_image'],
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

            // ✅ UPDATED
            '_gallery_urls' => ['label' => 'Gallery Images', 'type' => 'multi_image'],
        ],
        'event' => [
            '_venue' => ['label' => 'Địa điểm tổ chức', 'type' => 'text'],
            '_event_date' => ['label' => 'Ngày diễn ra', 'type' => 'date'],
            '_event_end_date' => ['label' => 'Ngày kết thúc', 'type' => 'date'],
            '_organizer' => ['label' => 'Đơn vị tổ chức', 'type' => 'text'],
            '_register_url' => ['label' => 'Link đăng ký', 'type' => 'url'],

            // ✅ UPDATED
            '_gallery_urls' => ['label' => 'Gallery Images', 'type' => 'multi_image'],
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

/**
 * REGISTER META BOXES
 */
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

/**
 * RENDER
 */
function haritics_render_meta_box(\WP_Post $post, array $meta_box): void
{
    $fields = $meta_box['args']['fields'] ?? [];
    wp_nonce_field('haritics_save_meta', 'haritics_meta_nonce');

    echo '<table class="form-table">';

    foreach ($fields as $key => $field) {
        $value = get_post_meta($post->ID, $key, true);

        echo '<tr>';
        echo '<th><label>' . esc_html($field['label']) . '</label></th>';
        echo '<td>';

        if ($field['type'] === 'textarea') {

            echo '<textarea name="' . esc_attr($key) . '" class="large-text">' . esc_textarea($value) . '</textarea>';

        } elseif ($field['type'] === 'multi_image') {

            $ids = is_array($value) ? $value : [];

            echo '<div class="haritics-images" style="display:flex;gap:10px;flex-wrap:wrap;">';

            foreach ($ids as $id) {
                echo '<div style="position:relative;">';
                echo wp_get_attachment_image($id, 'thumbnail');
                echo '<input type="hidden" name="' . esc_attr($key) . '[]" value="' . esc_attr($id) . '">';
                echo '<button type="button" class="remove-image" style="position:absolute;top:0;right:0;">×</button>';
                echo '</div>';
            }

            echo '</div>';
            echo '<button class="button add-images" data-key="' . esc_attr($key) . '">Chọn ảnh</button>';

        } elseif ($field['type'] === 'select') {

            echo '<select name="' . esc_attr($key) . '">';
            foreach ($field['options'] as $k => $label) {
                echo '<option value="' . esc_attr($k) . '" ' . selected($value, $k, false) . '>' . esc_html($label) . '</option>';
            }
            echo '</select>';

        } elseif ($field['type'] === 'post_select') {

            $posts = get_posts(['post_type' => $field['post_type'], 'numberposts' => -1]);

            echo '<select name="' . esc_attr($key) . '">';
            foreach ($posts as $p) {
                echo '<option value="' . $p->ID . '" ' . selected($value, $p->ID, false) . '>' . esc_html($p->post_title) . '</option>';
            }
            echo '</select>';

        } else {

            echo '<input type="' . esc_attr($field['type']) . '" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '" class="regular-text">';

        }

        echo '</td></tr>';
    }

    echo '</table>';
}

/**
 * SAVE
 */
function haritics_save_meta_boxes(int $post_id): void
{
    if (!isset($_POST['haritics_meta_nonce'])) return;
    if (!wp_verify_nonce($_POST['haritics_meta_nonce'], 'haritics_save_meta')) return;

    $fields = haritics_meta_fields()[get_post_type($post_id)] ?? [];

    foreach ($fields as $key => $field) {

        if ($field['type'] === 'multi_image') {
            if (!empty($_POST[$key]) && is_array($_POST[$key])) {
                update_post_meta($post_id, $key, array_map('intval', $_POST[$key]));
            } else {
                delete_post_meta($post_id, $key);
            }
            continue;
        }

        $value = $_POST[$key] ?? '';
        update_post_meta($post_id, $key, sanitize_text_field($value));
    }
}
add_action('save_post', 'haritics_save_meta_boxes');

/**
 * MEDIA JS
 */
add_action('admin_footer', function () {
?>
<script>
jQuery(function($){
    let frame;

    $(document).on('click', '.add-images', function(e){
        e.preventDefault();

        const btn = $(this);
        const container = btn.prev('.haritics-images');

        frame = wp.media({ multiple:true });

        frame.on('select', function(){
            const files = frame.state().get('selection').toJSON();

            files.forEach(function(f){
                container.append(
                    '<div style="position:relative;">' +
                    '<img src="'+f.sizes.thumbnail.url+'">' +
                    '<input type="hidden" name="'+btn.data('key')+'[]" value="'+f.id+'">' +
                    '<button type="button" class="remove-image" style="position:absolute;top:0;right:0;">×</button>' +
                    '</div>'
                );
            });
        });

        frame.open();
    });

    $(document).on('click', '.remove-image', function(){
        $(this).parent().remove();
    });
});
</script>
<?php
});