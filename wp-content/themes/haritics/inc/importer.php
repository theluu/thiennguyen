<?php
/**
 * Demo importer for Haritics theme.
 */

if (! defined('ABSPATH')) {
    exit;
}

function haritics_register_importer_page(): void
{
    add_management_page(
        __('Haritics Import Demo', 'haritics'),
        __('Haritics Import Demo', 'haritics'),
        'manage_options',
        'haritics-import-demo',
        'haritics_render_importer_page'
    );
}
add_action('admin_menu', 'haritics_register_importer_page');

function haritics_render_importer_page(): void
{
    if (! current_user_can('manage_options')) {
        return;
    }

    $status = sanitize_text_field(wp_unslash($_GET['haritics_import'] ?? ''));
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Haritics Demo Import', 'haritics'); ?></h1>
        <p><?php esc_html_e('Import dữ liệu demo dựa trên nội dung FE của theme Haritics: options, pages, menus, team, project, donation, event và ảnh đi kèm.', 'haritics'); ?></p>

        <?php if ($status === 'success') : ?>
            <div class="notice notice-success"><p><?php esc_html_e('Đã import dữ liệu demo thành công.', 'haritics'); ?></p></div>
        <?php elseif ($status === 'failed') : ?>
            <div class="notice notice-error"><p><?php esc_html_e('Import thất bại. Vui lòng xem debug log hoặc thử lại.', 'haritics'); ?></p></div>
        <?php endif; ?>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="haritics_run_demo_import">
            <?php wp_nonce_field('haritics_run_demo_import', 'haritics_import_nonce'); ?>
            <?php submit_button(__('Run Demo Import', 'haritics')); ?>
        </form>
    </div>
    <?php
}

function haritics_handle_demo_import(): void
{
    if (! current_user_can('manage_options')) {
        wp_die(esc_html__('Unauthorized.', 'haritics'));
    }

    if (! isset($_POST['haritics_import_nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['haritics_import_nonce'])), 'haritics_run_demo_import')) {
        wp_die(esc_html__('Invalid request.', 'haritics'));
    }

    try {
        haritics_run_demo_import();
        wp_safe_redirect(add_query_arg('haritics_import', 'success', admin_url('tools.php?page=haritics-import-demo')));
        exit;
    } catch (\Throwable $e) {
        error_log('Haritics importer failed: ' . $e->getMessage()); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
        wp_safe_redirect(add_query_arg('haritics_import', 'failed', admin_url('tools.php?page=haritics-import-demo')));
        exit;
    }
}
add_action('admin_post_haritics_run_demo_import', 'haritics_handle_demo_import');

function haritics_run_demo_import(): void
{
    haritics_import_options();
    haritics_import_pages();
    haritics_import_taxonomies();
    haritics_import_posts();
    haritics_import_menus();
    update_option('haritics_demo_imported_at', current_time('mysql'));
}

function haritics_import_options(): void
{
    $options = [
        'header_logo' => haritics_theme_asset('assets/img/logo.svg'),
        'header_cta_text' => '1900 1234',
        'header_cta_url' => 'tel:19001234',
        'header_button_text' => 'Xem dự án',
        'header_button_url' => haritics_route_url('project'),
        'site_favicon' => haritics_theme_asset('assets/img/logo.svg'),
        'hotline' => '1900 1234',
        'email' => 'lienhe@thiennguyen.vn',
        'address' => 'Tầng 5, Tòa nhà Cộng đồng, Hà Nội',
        'footer_logo' => haritics_theme_asset('assets/img/logo-white.svg'),
        'footer_about' => 'Nền tảng kết nối dự án cộng đồng với nguồn lực xã hội, hướng tới một Việt Nam tự lực, minh bạch và hùng cường.',
        'copyright_text' => '© ' . date('Y') . ' Thiện Nguyện. Bảo lưu mọi quyền.',
        'social_facebook' => 'https://facebook.com/',
        'social_twitter' => 'https://twitter.com/',
        'social_instagram' => 'https://instagram.com/',
        'social_youtube' => 'https://youtube.com/',
        'social_linkedin' => 'https://linkedin.com/',
        'home_hero_badge' => 'Chung tay vì cộng đồng',
        'home_hero_title' => 'Kết nối nguồn lực cho những dự án thiện nguyện thiết thực',
        'home_hero_description' => 'Nền tảng giúp kết nối nhà tổ chức, mạnh thường quân và các nguồn lực xã hội để cùng tạo ra những dự án minh bạch, bền vững và phù hợp với nhu cầu thực tế tại địa phương.',
        'home_hero_image' => haritics_theme_asset('assets/img/banner-img.png'),
        'home_primary_cta_text' => 'Ủng hộ ngay',
        'home_primary_cta_url' => haritics_route_url('donation'),
        'home_stat_number' => '2M+',
        'home_stat_label' => 'Mạnh thường quân đang đồng hành',
        'home_about_badge' => 'Về chúng tôi',
        'home_about_title' => 'Lan tỏa tinh thần sẻ chia để cộng đồng cùng phát triển',
        'home_about_description' => 'Thiện Nguyện hướng tới việc kết nối đúng người, đúng dự án và đúng nguồn lực. Chúng tôi mong muốn mỗi đóng góp đều được sử dụng hiệu quả, công khai và tạo ra giá trị lâu dài cho cộng đồng thụ hưởng.',
        'home_about_image' => haritics_theme_asset('assets/img/about-img.png'),
        'home_projects_heading' => 'Những dự án đang triển khai',
        'home_donations_heading' => 'Những chương trình đang cần thêm nguồn lực cộng đồng',
        'home_team_heading' => 'Những cá nhân đang trực tiếp triển khai chương trình',
        'home_events_heading' => 'Các sự kiện và hoạt động sắp diễn ra',
        'contact_intro' => 'Chúng tôi luôn sẵn sàng lắng nghe nhu cầu đồng hành, tài trợ, hợp tác triển khai dự án và các đề xuất từ cộng đồng.',
        'contact_success_message' => 'Cảm ơn bạn. Chúng tôi đã nhận được thông tin và sẽ phản hồi sớm.',
        'contact_map_iframe' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.0762457458724!2d105.83415957596991!3d21.03045648764147!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135abbd41f7a72f%3A0xc17ad1ad2d4d4e60!2zSOG7kyBIb8OgbiBLaeG6v20!5e0!3m2!1svi!2s!4v1710000000000!5m2!1svi!2s" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
    ];

    update_option('haritics_theme_options', $options);
}

function haritics_import_pages(): void
{
    $home_id = haritics_upsert_page([
        'post_title' => 'Trang chủ',
        'post_name' => 'home',
        'post_content' => 'Trang chủ được render bởi front-page.php.',
    ]);

    $contact_id = haritics_upsert_page([
        'post_title' => 'Liên hệ',
        'post_name' => haritics_route_path('contact'),
        'post_content' => 'Trang liên hệ được render bởi page-contact.php.',
    ]);

    update_option('show_on_front', 'page');
    update_option('page_on_front', $home_id);

    if ($contact_id) {
        update_post_meta($contact_id, '_wp_page_template', 'page-contact.php');
    }
}

function haritics_upsert_page(array $data): int
{
    $existing = get_page_by_path($data['post_name'], OBJECT, 'page');

    $postarr = [
        'post_type' => 'page',
        'post_status' => 'publish',
        'post_title' => $data['post_title'],
        'post_name' => $data['post_name'],
        'post_content' => $data['post_content'],
    ];

    if ($existing instanceof \WP_Post) {
        $postarr['ID'] = $existing->ID;
    }

    return (int) wp_insert_post($postarr);
}

function haritics_import_taxonomies(): void
{
    $terms = [
        'project_category' => ['Giáo dục cộng đồng', 'Dinh dưỡng học đường', 'Y tế vùng cao'],
        'donation_category' => ['Dinh dưỡng', 'Y tế', 'Giáo dục', 'Sinh kế'],
        'event_category' => ['Gây quỹ', 'Khảo sát thực địa', 'Đào tạo cộng đồng'],
    ];

    foreach ($terms as $taxonomy => $labels) {
        foreach ($labels as $label) {
            if (! term_exists($label, $taxonomy)) {
                wp_insert_term($label, $taxonomy);
            }
        }
    }
}

function haritics_import_posts(): void
{
    foreach (haritics_demo_dataset() as $post_type => $entries) {
        foreach ($entries as $entry) {
            haritics_upsert_demo_post($post_type, $entry);
        }
    }
}

function haritics_upsert_demo_post(string $post_type, array $entry): int
{
    $existing = get_page_by_path($entry['slug'], OBJECT, $post_type);

    $postarr = [
        'post_type' => $post_type,
        'post_status' => 'publish',
        'post_title' => $entry['title'],
        'post_name' => $entry['slug'],
        'post_excerpt' => $entry['excerpt'] ?? '',
        'post_content' => $entry['content'] ?? '',
    ];

    if ($existing instanceof \WP_Post) {
        $postarr['ID'] = $existing->ID;
    }

    $post_id = (int) wp_insert_post($postarr);

    if ($post_id <= 0) {
        return 0;
    }

    if (! empty($entry['taxonomies']) && is_array($entry['taxonomies'])) {
        foreach ($entry['taxonomies'] as $taxonomy => $terms) {
            wp_set_object_terms($post_id, $terms, $taxonomy, false);
        }
    }

    if (! empty($entry['meta']) && is_array($entry['meta'])) {
        foreach ($entry['meta'] as $meta_key => $meta_value) {
            update_post_meta($post_id, $meta_key, $meta_value);
        }
    }

    if (! empty($entry['image'])) {
        $attachment_id = haritics_import_local_image($entry['image'], $entry['title']);
        if ($attachment_id) {
            set_post_thumbnail($post_id, $attachment_id);
        }
    }

    return $post_id;
}

function haritics_import_local_image(string $relative_path, string $title = ''): int
{
    $map = get_option('haritics_imported_media_map', []);
    $map = is_array($map) ? $map : [];

    if (! empty($map[$relative_path]) && get_post((int) $map[$relative_path])) {
        return (int) $map[$relative_path];
    }

    $source = get_template_directory() . '/' . ltrim($relative_path, '/');
    if (! file_exists($source)) {
        return 0;
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';

    $uploads = wp_upload_dir();
    if (! empty($uploads['error'])) {
        return 0;
    }

    $filename = wp_unique_filename($uploads['path'], basename($source));
    $destination = trailingslashit($uploads['path']) . $filename;

    if (! copy($source, $destination)) {
        return 0;
    }

    $filetype = wp_check_filetype($filename, null);
    $attachment = [
        'post_mime_type' => $filetype['type'] ?? 'image/jpeg',
        'post_title' => $title !== '' ? $title : preg_replace('/\.[^.]+$/', '', basename($filename)),
        'post_content' => '',
        'post_status' => 'inherit',
    ];

    $attachment_id = wp_insert_attachment($attachment, $destination);
    if (! $attachment_id || is_wp_error($attachment_id)) {
        return 0;
    }

    $metadata = wp_generate_attachment_metadata($attachment_id, $destination);
    if (! is_wp_error($metadata)) {
        wp_update_attachment_metadata($attachment_id, $metadata);
    }

    $map[$relative_path] = (int) $attachment_id;
    update_option('haritics_imported_media_map', $map);

    return (int) $attachment_id;
}

function haritics_import_menus(): void
{
    $locations = get_theme_mod('nav_menu_locations', []);

    $primary = haritics_upsert_menu('Primary Menu', [
        ['title' => 'Trang chủ', 'url' => home_url('/')],
        ['title' => 'Dự án', 'url' => haritics_route_url('project')],
        ['title' => 'Nhà tổ chức', 'url' => haritics_route_url('team')],
        ['title' => 'Mạnh thường quân', 'url' => haritics_route_url('donation')],
        ['title' => 'Hoạt động', 'url' => haritics_route_url('event')],
        ['title' => 'Liên hệ', 'url' => haritics_route_url('contact')],
    ]);
    $footer_one = haritics_upsert_menu('Footer Menu 1', [
        ['title' => 'Trang chủ', 'url' => home_url('/')],
        ['title' => 'Dự án', 'url' => haritics_route_url('project')],
        ['title' => 'Mạnh thường quân', 'url' => haritics_route_url('donation')],
        ['title' => 'Hoạt động', 'url' => haritics_route_url('event')],
        ['title' => 'Liên hệ', 'url' => haritics_route_url('contact')],
    ]);
    $footer_two = haritics_upsert_menu('Footer Menu 2', [
        ['title' => 'Đội ngũ tổ chức', 'url' => haritics_route_url('team')],
        ['title' => 'Dự án nổi bật', 'url' => haritics_route_url('project')],
        ['title' => 'Điều khoản sử dụng', 'url' => '#'],
        ['title' => 'Chính sách bảo mật', 'url' => '#'],
    ]);

    $locations['primary_menu'] = $primary;
    $locations['footer_menu_1'] = $footer_one;
    $locations['footer_menu_2'] = $footer_two;
    set_theme_mod('nav_menu_locations', $locations);
}

function haritics_upsert_menu(string $menu_name, array $items): int
{
    $menu = wp_get_nav_menu_object($menu_name);
    $menu_id = $menu ? (int) $menu->term_id : (int) wp_create_nav_menu($menu_name);

    if ($menu_id <= 0) {
        return 0;
    }

    $existing_items = wp_get_nav_menu_items($menu_id);
    if (is_array($existing_items)) {
        foreach ($existing_items as $existing_item) {
            wp_delete_post((int) $existing_item->ID, true);
        }
    }

    foreach ($items as $item) {
        wp_update_nav_menu_item($menu_id, 0, [
            'menu-item-title' => $item['title'],
            'menu-item-url' => $item['url'],
            'menu-item-status' => 'publish',
        ]);
    }

    return $menu_id;
}

function haritics_demo_dataset(): array
{
    return [
        'team' => [
            [
                'slug' => 'nguyen-thu-ha',
                'title' => 'Nguyễn Thu Hà',
                'excerpt' => 'Điều phối chiến lược dự án, kết nối đối tác và giám sát tiến độ triển khai tại địa phương.',
                'content' => '<p>Nguyễn Thu Hà phụ trách kết nối các nguồn lực chiến lược, làm việc với chính quyền địa phương và giám sát tiến độ của từng hạng mục trọng điểm. Chị đồng hành cùng đội ngũ hiện trường từ giai đoạn khảo sát, huy động tài trợ đến lúc nghiệm thu và báo cáo minh bạch.</p><p>Trọng tâm công việc của Hà là đảm bảo các dự án đi đúng nhu cầu thực tế của cộng đồng, có KPI rõ ràng, tiến độ minh bạch và đầu ra có thể đo lường.</p>',
                'image' => 'assets/img/member-1.jpg',
                'meta' => [
                    '_role' => 'Giám đốc điều phối',
                    '_phone' => '0912 345 678',
                    '_email' => 'ha.nguyen@thiennguyen.vn',
                    '_quote' => 'Kết nối đúng nguồn lực là bước đầu để mỗi dự án tạo ra tác động lâu dài.',
                    '_facebook_url' => 'https://facebook.com/',
                    '_twitter_url' => 'https://twitter.com/',
                    '_instagram_url' => 'https://instagram.com/',
                    '_linkedin_url' => 'https://linkedin.com/',
                    '_skill_one_label' => 'Leadership',
                    '_skill_one_value' => '92',
                    '_skill_two_label' => 'Partnership',
                    '_skill_two_value' => '88',
                    '_skill_three_label' => 'Planning',
                    '_skill_three_value' => '90',
                ],
            ],
            [
                'slug' => 'tran-minh-quan',
                'title' => 'Trần Minh Quân',
                'excerpt' => 'Phụ trách vận hành hiện trường, điều phối hậu cần và kết nối đội ngũ tình nguyện viên.',
                'content' => '<p>Trần Minh Quân trực tiếp bám địa bàn, lên kế hoạch hậu cần cho các chuyến đi thực địa, điều phối tình nguyện viên và phối hợp cùng ban tổ chức địa phương để đảm bảo an toàn, tiến độ và hiệu quả triển khai.</p>',
                'image' => 'assets/img/member-2.jpg',
                'meta' => [
                    '_role' => 'Trưởng nhóm hiện trường',
                    '_phone' => '0903 456 789',
                    '_email' => 'quan.tran@thiennguyen.vn',
                    '_quote' => 'Một dự án tốt là dự án không chỉ khởi động tốt mà còn hoàn thành trọn vẹn.',
                    '_facebook_url' => 'https://facebook.com/',
                    '_instagram_url' => 'https://instagram.com/',
                    '_linkedin_url' => 'https://linkedin.com/',
                    '_skill_one_label' => 'Field Ops',
                    '_skill_one_value' => '91',
                    '_skill_two_label' => 'Teamwork',
                    '_skill_two_value' => '86',
                    '_skill_three_label' => 'Execution',
                    '_skill_three_value' => '89',
                ],
            ],
            [
                'slug' => 'le-thanh-tam',
                'title' => 'Lê Thanh Tâm',
                'excerpt' => 'Đồng hành trong công tác truyền thông, kể lại hành trình dự án và minh bạch kết quả.',
                'content' => '<p>Lê Thanh Tâm phụ trách nội dung và truyền thông cộng đồng, xây dựng tuyến bài cập nhật tiến độ, ghi nhận câu chuyện người thật việc thật và công khai kết quả từng giai đoạn để tăng niềm tin với cộng đồng đồng hành.</p>',
                'image' => 'assets/img/member-3.jpg',
                'meta' => [
                    '_role' => 'Phụ trách truyền thông',
                    '_phone' => '0987 654 321',
                    '_email' => 'tam.le@thiennguyen.vn',
                    '_quote' => 'Truyền thông không chỉ để lan tỏa, mà còn để minh bạch và kết nối.',
                    '_facebook_url' => 'https://facebook.com/',
                    '_instagram_url' => 'https://instagram.com/',
                    '_linkedin_url' => 'https://linkedin.com/',
                    '_skill_one_label' => 'Storytelling',
                    '_skill_one_value' => '90',
                    '_skill_two_label' => 'Content',
                    '_skill_two_value' => '87',
                    '_skill_three_label' => 'Community',
                    '_skill_three_value' => '84',
                ],
            ],
            [
                'slug' => 'pham-duc-long',
                'title' => 'Phạm Đức Long',
                'excerpt' => 'Phụ trách tài chính dự án và công khai chứng từ theo từng giai đoạn triển khai.',
                'content' => '<p>Phạm Đức Long xây dựng quy trình kiểm soát tài chính, chuẩn hóa chứng từ và phối hợp cùng các đối tác để mọi dòng tiền trong dự án đều được theo dõi, công khai và báo cáo rõ ràng tới cộng đồng.</p>',
                'image' => 'assets/img/member-4.jpg',
                'meta' => [
                    '_role' => 'Phụ trách tài chính',
                    '_phone' => '0918 111 222',
                    '_email' => 'long.pham@thiennguyen.vn',
                    '_quote' => 'Minh bạch là nền tảng của niềm tin và sự đồng hành dài hạn.',
                    '_facebook_url' => 'https://facebook.com/',
                    '_linkedin_url' => 'https://linkedin.com/',
                    '_skill_one_label' => 'Budgeting',
                    '_skill_one_value' => '93',
                    '_skill_two_label' => 'Compliance',
                    '_skill_two_value' => '90',
                    '_skill_three_label' => 'Reporting',
                    '_skill_three_value' => '88',
                ],
            ],
        ],
        'project' => [
            [
                'slug' => 'xay-diem-truong-va-khu-noi-tru-vung-cao',
                'title' => 'Dự án xây dựng điểm trường và khu nội trú cho học sinh vùng cao',
                'excerpt' => 'Hoàng Su Phì, Hà Giang',
                'content' => '<p>Dự án hướng đến việc xây dựng một điểm trường kiên cố, khu nội trú an toàn và không gian sinh hoạt học tập cho học sinh tại xã vùng cao còn nhiều khó khăn. Đây là dự án trọng điểm cần sự phối hợp giữa đội ngũ lãnh đạo dự án, nhà hảo tâm, đối tác địa phương và các nhóm tình nguyện chuyên môn.</p><p>Dự án tập trung vào ba hợp phần chính: hoàn thiện hạ tầng trường học, trang bị nội thất và đồ dùng học tập, đồng thời đào tạo đội ngũ vận hành địa phương nhằm duy trì hiệu quả lâu dài.</p>',
                'image' => 'assets/img/project-details-img-1.jpg',
                'taxonomies' => [
                    'project_category' => ['Giáo dục cộng đồng'],
                ],
                'meta' => [
                    '_summary' => 'Dự án trọng điểm nâng cấp điểm trường và khu nội trú an toàn cho học sinh vùng cao.',
                    '_location' => 'Xã Tả Sử Choóng, huyện Hoàng Su Phì, tỉnh Hà Giang',
                    '_target_amount' => '3200000000',
                    '_raised_amount' => '1856000000',
                    '_start_date' => '2025-05-01',
                    '_end_date' => '2025-11-30',
                    '_status' => 'Đang triển khai',
                    '_leader_text' => 'Nguyễn Thu Hà và Trần Minh Quân',
                    '_donor_text' => '58 mạnh thường quân và 6 đối tác vận chuyển',
                    '_gallery_urls' => haritics_theme_asset('assets/img/project-details-img-1.jpg') . "\n" . haritics_theme_asset('assets/img/project-1.jpg') . "\n" . haritics_theme_asset('assets/img/project-2.jpg'),
                ],
            ],
            [
                'slug' => 'bep-an-ban-tru-cho-tre-em-vung-sau',
                'title' => 'Bếp ăn bán trú cho trẻ em vùng sâu',
                'excerpt' => 'Mù Cang Chải, Yên Bái',
                'content' => '<p>Dự án cải tạo khu bếp, bổ sung thiết bị nấu ăn và xây dựng quy trình vận hành bếp ăn an toàn cho các điểm trường vùng sâu, giúp học sinh có bữa ăn đủ dinh dưỡng và giảm tỷ lệ bỏ học vì điều kiện sinh hoạt khó khăn.</p>',
                'image' => 'assets/img/project-2.jpg',
                'taxonomies' => [
                    'project_category' => ['Dinh dưỡng học đường'],
                ],
                'meta' => [
                    '_summary' => 'Nâng cấp bếp ăn bán trú và hỗ trợ dinh dưỡng định kỳ cho học sinh.',
                    '_location' => 'Mù Cang Chải, Yên Bái',
                    '_target_amount' => '980000000',
                    '_raised_amount' => '510000000',
                    '_start_date' => '2025-06-15',
                    '_end_date' => '2025-10-30',
                    '_status' => 'Đang kêu gọi',
                    '_leader_text' => 'Trần Minh Quân',
                    '_donor_text' => '24 mạnh thường quân và nhóm thiện nguyện khu vực Hà Nội',
                    '_gallery_urls' => haritics_theme_asset('assets/img/project-2.jpg') . "\n" . haritics_theme_asset('assets/img/project-3.jpg'),
                ],
            ],
            [
                'slug' => 'tram-y-te-luu-dong-cho-ban-lang-xa-xoi',
                'title' => 'Trạm y tế lưu động cho bản làng xa xôi',
                'excerpt' => 'Mường Nhé, Điện Biên',
                'content' => '<p>Dự án gây quỹ thiết bị y tế cơ bản, hỗ trợ xe lưu động và phối hợp với đội ngũ bác sĩ tình nguyện để tổ chức khám định kỳ cho người dân tại những khu vực khó tiếp cận.</p>',
                'image' => 'assets/img/project-3.jpg',
                'taxonomies' => [
                    'project_category' => ['Y tế vùng cao'],
                ],
                'meta' => [
                    '_summary' => 'Huy động thiết bị và kinh phí tổ chức khám lưu động định kỳ cho người dân vùng xa.',
                    '_location' => 'Mường Nhé, Điện Biên',
                    '_target_amount' => '1450000000',
                    '_raised_amount' => '680000000',
                    '_start_date' => '2025-07-01',
                    '_end_date' => '2025-12-15',
                    '_status' => 'Đang kêu gọi',
                    '_leader_text' => 'Nguyễn Thu Hà',
                    '_donor_text' => '16 đối tác y tế và 31 mạnh thường quân',
                    '_gallery_urls' => haritics_theme_asset('assets/img/project-3.jpg') . "\n" . haritics_theme_asset('assets/img/project-4.jpg'),
                ],
            ],
            [
                'slug' => 'thu-vien-va-hoc-bong-cho-hoc-sinh-hieu-hoc',
                'title' => 'Thư viện và học bổng cho học sinh hiếu học',
                'excerpt' => 'Bắc Hà, Lào Cai',
                'content' => '<p>Dự án gây quỹ xây dựng thư viện nhỏ tại trường, bổ sung sách, góc đọc và học bổng cho học sinh hiếu học có hoàn cảnh khó khăn nhằm tăng động lực đến lớp lâu dài.</p>',
                'image' => 'assets/img/project-4.jpg',
                'taxonomies' => [
                    'project_category' => ['Giáo dục cộng đồng'],
                ],
                'meta' => [
                    '_summary' => 'Xây dựng thư viện nhỏ và cấp học bổng cho học sinh vùng cao.',
                    '_location' => 'Bắc Hà, Lào Cai',
                    '_target_amount' => '620000000',
                    '_raised_amount' => '390000000',
                    '_start_date' => '2025-08-01',
                    '_end_date' => '2025-11-20',
                    '_status' => 'Đang triển khai',
                    '_leader_text' => 'Lê Thanh Tâm',
                    '_donor_text' => '12 tổ chức giáo dục và 40 mạnh thường quân',
                    '_gallery_urls' => haritics_theme_asset('assets/img/project-4.jpg') . "\n" . haritics_theme_asset('assets/img/about-img.png'),
                ],
            ],
        ],
        'donation' => [
            [
                'slug' => 'bua-an-du-chat-cho-tre-em-vung-kho-khan',
                'title' => 'Bữa ăn đủ chất cho trẻ em vùng khó khăn',
                'excerpt' => 'Chương trình cung cấp thực phẩm, bếp ăn và hỗ trợ dinh dưỡng định kỳ cho trẻ em tại các điểm trường vùng sâu.',
                'content' => '<p>Chương trình tập trung hỗ trợ dinh dưỡng học đường thông qua việc nâng cấp bếp ăn, bổ sung thực phẩm thiết yếu và triển khai bữa ăn định kỳ cho học sinh tại các điểm trường vùng sâu.</p><p>Nguồn đóng góp sẽ được sử dụng cho thực phẩm, thiết bị bếp và chi phí vận chuyển đến điểm trường.</p>',
                'image' => 'assets/img/donation-1.jpg',
                'taxonomies' => [
                    'donation_category' => ['Dinh dưỡng'],
                ],
                'meta' => [
                    '_badge' => 'Dinh dưỡng',
                    '_target_amount' => '30000000',
                    '_raised_amount' => '25000000',
                    '_deadline' => '2025-09-30',
                    '_short_stats' => 'Ưu tiên cho học sinh tiểu học ở các điểm trường khó khăn.',
                    '_location' => 'Hoàng Su Phì, Hà Giang',
                    '_gallery_urls' => haritics_theme_asset('assets/img/donation-details-img.jpg') . "\n" . haritics_theme_asset('assets/img/donation-details-inner-1.jpg') . "\n" . haritics_theme_asset('assets/img/donation-details-inner-2.jpg'),
                    '_external_url' => home_url('/' . haritics_route_path('donation') . '/bua-an-du-chat-cho-tre-em-vung-kho-khan/'),
                ],
            ],
            [
                'slug' => 'kham-suc-khoe-dinh-ky-cho-nguoi-dan-vung-cao',
                'title' => 'Khám sức khỏe định kỳ cho người dân vùng cao',
                'excerpt' => 'Huy động kinh phí, vật tư y tế và đội ngũ tình nguyện viên để tổ chức các đợt khám lưu động tại địa bàn khó tiếp cận.',
                'content' => '<p>Chương trình huy động kinh phí, vật tư y tế và đội ngũ bác sĩ tình nguyện để tổ chức khám lưu động cho người dân vùng cao, ưu tiên người già, trẻ nhỏ và phụ nữ mang thai.</p>',
                'image' => 'assets/img/donation-2.jpg',
                'taxonomies' => [
                    'donation_category' => ['Y tế'],
                ],
                'meta' => [
                    '_badge' => 'Y tế',
                    '_target_amount' => '30000000',
                    '_raised_amount' => '25000000',
                    '_deadline' => '2025-10-10',
                    '_short_stats' => 'Kêu gọi thêm vật tư y tế và chi phí logistics.',
                    '_location' => 'Mường Nhé, Điện Biên',
                    '_gallery_urls' => haritics_theme_asset('assets/img/donation-2.jpg') . "\n" . haritics_theme_asset('assets/img/donation-details-inner-1.jpg'),
                    '_external_url' => home_url('/' . haritics_route_path('donation') . '/kham-suc-khoe-dinh-ky-cho-nguoi-dan-vung-cao/'),
                ],
            ],
            [
                'slug' => 'tu-sach-va-hoc-bong-cho-hoc-sinh-hieu-hoc',
                'title' => 'Tủ sách và học bổng cho học sinh hiếu học',
                'excerpt' => 'Gây quỹ cho sách, học bổng và không gian học tập an toàn để tiếp sức cho học sinh có hoàn cảnh khó khăn.',
                'content' => '<p>Khoản đóng góp được dùng cho sách, học bổng, góc đọc và các bộ dụng cụ học tập nhằm tiếp sức lâu dài cho học sinh hiếu học có hoàn cảnh khó khăn.</p>',
                'image' => 'assets/img/donation-3.jpg',
                'taxonomies' => [
                    'donation_category' => ['Giáo dục'],
                ],
                'meta' => [
                    '_badge' => 'Giáo dục',
                    '_target_amount' => '30000000',
                    '_raised_amount' => '15000000',
                    '_deadline' => '2025-11-15',
                    '_short_stats' => 'Ưu tiên trường bán trú, học sinh lớp cuối cấp.',
                    '_location' => 'Bắc Hà, Lào Cai',
                    '_gallery_urls' => haritics_theme_asset('assets/img/donation-3.jpg') . "\n" . haritics_theme_asset('assets/img/blog-1.jpg'),
                    '_external_url' => home_url('/' . haritics_route_path('donation') . '/tu-sach-va-hoc-bong-cho-hoc-sinh-hieu-hoc/'),
                ],
            ],
            [
                'slug' => 'ho-tro-sinh-ke-ben-vung-cho-ho-gia-dinh-yeu-the',
                'title' => 'Hỗ trợ sinh kế bền vững cho hộ gia đình yếu thế',
                'excerpt' => 'Đồng hành với các hộ dân thông qua hỗ trợ giống, công cụ sản xuất và hướng dẫn mô hình kinh tế phù hợp.',
                'content' => '<p>Chương trình hỗ trợ sinh kế nhằm giúp các hộ gia đình yếu thế có thể tự tạo thu nhập ổn định hơn thông qua con giống, vật tư sản xuất và hướng dẫn mô hình kinh tế tại chỗ.</p>',
                'image' => 'assets/img/donation-4.jpg',
                'taxonomies' => [
                    'donation_category' => ['Sinh kế'],
                ],
                'meta' => [
                    '_badge' => 'Sinh kế',
                    '_target_amount' => '30000000',
                    '_raised_amount' => '19200000',
                    '_deadline' => '2025-12-01',
                    '_short_stats' => 'Ưu tiên hộ gia đình nuôi con nhỏ và mất sức lao động chính.',
                    '_location' => 'Sơn La',
                    '_gallery_urls' => haritics_theme_asset('assets/img/donation-4.jpg') . "\n" . haritics_theme_asset('assets/img/blog-2.jpg'),
                    '_external_url' => home_url('/' . haritics_route_path('donation') . '/ho-tro-sinh-ke-ben-vung-cho-ho-gia-dinh-yeu-the/'),
                ],
            ],
        ],
        'event' => [
            [
                'slug' => 'ngay-hoi-gay-quy-xay-diem-truong-vung-cao',
                'title' => 'Ngày hội gây quỹ xây điểm trường vùng cao',
                'excerpt' => 'Sự kiện kết nối cộng đồng, doanh nghiệp và nhà hảo tâm nhằm huy động nguồn lực cho dự án trường học vùng cao.',
                'content' => '<p>Sự kiện tập trung kết nối cộng đồng, doanh nghiệp và các nhà hảo tâm để cùng huy động nguồn lực cho dự án xây điểm trường vùng cao. Chương trình có phiên chia sẻ về nhu cầu thực tế, hoạt động gây quỹ và khu trưng bày minh chứng dự án.</p>',
                'image' => 'assets/img/event-img.jpg',
                'taxonomies' => [
                    'event_category' => ['Gây quỹ'],
                ],
                'meta' => [
                    '_venue' => 'Hà Nội',
                    '_event_date' => '2025-07-29',
                    '_event_end_date' => '2025-07-29',
                    '_organizer' => 'Thiện Nguyện',
                    '_register_url' => home_url('/' . haritics_route_path('event') . '/ngay-hoi-gay-quy-xay-diem-truong-vung-cao/'),
                    '_gallery_urls' => haritics_theme_asset('assets/img/event-details.jpg') . "\n" . haritics_theme_asset('assets/img/blog-b-1.jpg'),
                ],
            ],
            [
                'slug' => 'hanh-trinh-khao-sat-diem-truong-hoang-su-phi',
                'title' => 'Hành trình khảo sát điểm trường Hoàng Su Phì',
                'excerpt' => 'Đoàn khảo sát thực địa cùng đối tác địa phương và đội ngũ kỹ thuật trước khi triển khai xây dựng.',
                'content' => '<p>Hoạt động khảo sát được tổ chức với sự tham gia của đội ngũ kỹ thuật, ban điều phối dự án và đại diện địa phương để đánh giá hiện trạng, xác định nhu cầu và thống nhất kế hoạch thi công từng giai đoạn.</p>',
                'image' => 'assets/img/blog-b-1.jpg',
                'taxonomies' => [
                    'event_category' => ['Khảo sát thực địa'],
                ],
                'meta' => [
                    '_venue' => 'Hoàng Su Phì, Hà Giang',
                    '_event_date' => '2025-08-12',
                    '_event_end_date' => '2025-08-14',
                    '_organizer' => 'Ban điều phối dự án',
                    '_register_url' => home_url('/' . haritics_route_path('event') . '/hanh-trinh-khao-sat-diem-truong-hoang-su-phi/'),
                    '_gallery_urls' => haritics_theme_asset('assets/img/blog-b-1.jpg') . "\n" . haritics_theme_asset('assets/img/project-1.jpg'),
                ],
            ],
            [
                'slug' => 'tap-huan-minh-bach-tai-chinh-cho-doi-ngu-du-an',
                'title' => 'Tập huấn minh bạch tài chính cho đội ngũ dự án',
                'excerpt' => 'Buổi đào tạo về quy trình công khai chứng từ, báo cáo và phối hợp vận hành dữ liệu minh bạch.',
                'content' => '<p>Buổi tập huấn giúp đội ngũ dự án chuẩn hóa quy trình công khai chứng từ, báo cáo tiến độ, kiểm soát tài chính và phối hợp với đối tác để dữ liệu luôn rõ ràng, nhất quán và dễ kiểm chứng.</p>',
                'image' => 'assets/img/blog-b-2.jpg',
                'taxonomies' => [
                    'event_category' => ['Đào tạo cộng đồng'],
                ],
                'meta' => [
                    '_venue' => 'TP. Hồ Chí Minh',
                    '_event_date' => '2025-09-05',
                    '_event_end_date' => '2025-09-05',
                    '_organizer' => 'Phạm Đức Long',
                    '_register_url' => home_url('/' . haritics_route_path('event') . '/tap-huan-minh-bach-tai-chinh-cho-doi-ngu-du-an/'),
                    '_gallery_urls' => haritics_theme_asset('assets/img/blog-b-2.jpg') . "\n" . haritics_theme_asset('assets/img/blog-3.jpg'),
                ],
            ],
            [
                'slug' => 'ket-noi-tinh-nguyen-vien-mua-dong-vung-cao',
                'title' => 'Kết nối tình nguyện viên mùa đông vùng cao',
                'excerpt' => 'Hoạt động tuyển chọn và kết nối tình nguyện viên cho các dự án mùa đông tại điểm trường xa.',
                'content' => '<p>Chương trình tuyển chọn tình nguyện viên hậu cần, truyền thông và điều phối hiện trường cho chuỗi hoạt động mùa đông vùng cao, đồng thời tập huấn kỹ năng làm việc với cộng đồng và an toàn di chuyển.</p>',
                'image' => 'assets/img/blog-3.jpg',
                'taxonomies' => [
                    'event_category' => ['Gây quỹ'],
                ],
                'meta' => [
                    '_venue' => 'Online & Hà Nội',
                    '_event_date' => '2025-10-18',
                    '_event_end_date' => '2025-10-18',
                    '_organizer' => 'Trần Minh Quân',
                    '_register_url' => home_url('/' . haritics_route_path('event') . '/ket-noi-tinh-nguyen-vien-mua-dong-vung-cao/'),
                    '_gallery_urls' => haritics_theme_asset('assets/img/blog-3.jpg') . "\n" . haritics_theme_asset('assets/img/volunteer-bg-1.jpg'),
                ],
            ],
        ],
    ];
}
