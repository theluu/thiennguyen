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

/**
 * Thêm Meta Box cho Trang Chủ để sửa Banner và About Us
 */
add_action('add_meta_boxes', 'haritics_home_meta_boxes');
function haritics_home_meta_boxes() {
    $post_id = isset($_GET['post']) ? $_GET['post'] : (isset($_POST['post_ID']) ? $_POST['post_ID'] : 0);
    // Chỉ hiện nếu là trang được chọn làm Trang Chủ
    if ($post_id == get_option('page_on_front')) {
        add_meta_box('home_settings', 'Cấu hình Nội dung Trang Chủ', 'haritics_home_settings_callback', 'page', 'normal', 'high');
    }
}

function haritics_home_settings_callback($post) {
    // Lấy dữ liệu cũ
    $hero_image_id = get_post_meta($post->ID, '_hero_image_id', true);
    $about_image_id = get_post_meta($post->ID, '_about_image_id', true);
    
    // Tạo Nonce để bảo mật
    wp_nonce_field('haritics_save_home_meta', 'haritics_home_meta_nonce');

    // CSS đơn giản cho Admin
    echo '<style>.hr-admin-row { margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px; } .hr-admin-row label { font-weight: bold; display: block; margin-bottom: 5px; } .hr-img-preview { background: #f0f0f0; border: 1px solid #ccc; margin-bottom: 10px; max-width: 200px; min-height: 100px; display: flex; align-items: center; justify-content: center; overflow: hidden; } .hr-img-preview img { max-width: 100%; height: auto; }</style>';

    // --- CẤU HÌNH BANNER ---
    echo '<h3>1. Cấu hình Header Banner</h3>';
    haritics_render_meta_field($post->ID, '_hero_badge', 'Badge (Chung tay vì cộng đồng)');
    haritics_render_meta_field($post->ID, '_hero_title', 'Tiêu đề Banner');
    haritics_render_meta_field($post->ID, '_hero_description', 'Mô tả Banner', 'textarea');
    haritics_render_image_uploader($post->ID, '_hero_image_id', 'Ảnh Banner', $hero_image_id);

    echo '<hr>';

    // --- CẤU HÌNH ABOUT US ---
    echo '<h3>2. Cấu hình About Us</h3>';
    haritics_render_meta_field($post->ID, '_about_title', 'Tiêu đề About Us');
    haritics_render_meta_field($post->ID, '_about_description', 'Nội dung About Us', 'textarea');
    haritics_render_image_uploader($post->ID, '_about_image_id', 'Ảnh About Us', $about_image_id);
}

// Hàm render trường input
function haritics_render_meta_field($post_id, $key, $label, $type = 'text') {
    $value = get_post_meta($post_id, $key, true);
    echo '<div class="hr-admin-row"><label>' . esc_html($label) . '</label>';
    if ($type === 'textarea') {
        echo '<textarea name="' . esc_attr($key) . '" style="width:100%; height:80px;">' . esc_textarea($value) . '</textarea>';
    } else {
        echo '<input type="text" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '" style="width:100%">';
    }
    echo '</div>';
}

// Hàm render trình upload ảnh
function haritics_render_image_uploader($post_id, $key, $label, $image_id) {
    $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
    ?>
    <div class="hr-admin-row">
        <label><?php echo esc_html($label); ?></label>
        <div class="hr-img-preview" id="preview-<?php echo esc_attr($key); ?>">
            <?php if ($image_url): ?>
                <img src="<?php echo esc_url($image_url); ?>" />
            <?php else: ?>
                <span style="color:#999">Chưa chọn ảnh</span>
            <?php endif; ?>
        </div>
        <input type="hidden" name="<?php echo esc_attr($key); ?>" id="input-<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($image_id); ?>">
        <button type="button" class="button hr-upload-btn" data-target="<?php echo esc_attr($key); ?>">Chọn ảnh từ thư viện</button>
        <button type="button" class="button hr-remove-btn" data-target="<?php echo esc_attr($key); ?>" style="color:red">Xóa ảnh</button>
    </div>
    <?php
}

// Lưu dữ liệu
add_action('save_post', 'haritics_save_home_settings');
function haritics_save_home_settings($post_id) {
    if (!isset($_POST['haritics_home_meta_nonce']) || !wp_verify_nonce($_POST['haritics_home_meta_nonce'], 'haritics_save_home_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    $fields = ['_hero_badge', '_hero_title', '_hero_description', '_hero_image_id', '_about_title', '_about_description', '_about_image_id'];
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
}

add_action('admin_footer', 'haritics_home_admin_js');
function haritics_home_admin_js() {
    ?>
    <script>
    jQuery(document).ready(function($) {
        $('.hr-upload-btn').click(function(e) {
            e.preventDefault();
            var target = $(this).data('target');
            var custom_uploader = wp.media({
                title: 'Chọn ảnh',
                button: { text: 'Sử dụng ảnh này' },
                multiple: false
            }).on('select', function() {
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                $('#preview-' + target).html('<img src="' + attachment.url + '" />');
                $('#input-' + target).val(attachment.id);
            }).open();
        });

        $('.hr-remove-btn').click(function(e) {
            e.preventDefault();
            var target = $(this).data('target');
            $('#preview-' + target).html('<span style="color:#999">Chưa chọn ảnh</span>');
            $('#input-' + target).val('');
        });
    });
    </script>
    <?php
}

// Đảm bảo nạp thư viện media của WP
add_action('admin_enqueue_scripts', function() {
    wp_enqueue_media();
});