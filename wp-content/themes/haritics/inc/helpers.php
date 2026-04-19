<?php
/**
 * Theme helpers.
 */

if (! defined('ABSPATH')) {
    exit;
}

function haritics_get_theme_options(): array
{
    $options = get_option('haritics_theme_options', []);

    return is_array($options) ? $options : [];
}

function haritics_get_search_filters(): array
{
    $filters = [
        's' => sanitize_text_field(wp_unslash($_GET['s'] ?? '')),
        'organizer' => sanitize_text_field(wp_unslash($_GET['organizer'] ?? '')),
        'donor' => sanitize_text_field(wp_unslash($_GET['donor'] ?? '')),
        'location' => sanitize_text_field(wp_unslash($_GET['location'] ?? '')),
    ];

    return $filters;
}

function haritics_has_active_search_filters(): bool
{
    foreach (haritics_get_search_filters() as $value) {
        if ($value !== '') {
            return true;
        }
    }

    return false;
}

function haritics_get_search_result_label(): string
{
    $filters = haritics_get_search_filters();
    $parts = [];

    if ($filters['s'] !== '') {
        $parts[] = sprintf(__('Dự án: %s', 'haritics'), $filters['s']);
    }

    if ($filters['organizer'] !== '') {
        $parts[] = sprintf(__('Nhà tổ chức: %s', 'haritics'), $filters['organizer']);
    }

    if ($filters['donor'] !== '') {
        $parts[] = sprintf(__('Mạnh thường quân: %s', 'haritics'), $filters['donor']);
    }

    if ($filters['location'] !== '') {
        $parts[] = sprintf(__('Địa điểm: %s', 'haritics'), $filters['location']);
    }

    return $parts !== [] ? implode(' | ', $parts) : __('Tất cả kết quả', 'haritics');
}

function haritics_get_option(string $key, string $default = ''): string
{
    $options = haritics_get_theme_options();

    if (! array_key_exists($key, $options)) {
        return $default;
    }

    $value = $options[$key];

    return is_string($value) ? $value : $default;
}

function haritics_theme_asset(string $path): string
{
    return get_template_directory_uri() . '/' . ltrim($path, '/');
}

function haritics_route_path(string $key): string
{
    $routes = [
        'team' => 'doi-ngu',
        'project' => 'du-an',
        'donation' => 'manh-thuong-quan',
        'event' => 'hoat-dong',
        'contact' => 'lien-he',
        'project_category' => 'danh-muc-du-an',
        'event_category' => 'danh-muc-hoat-dong',
        'donation_category' => 'danh-muc-manh-thuong-quan',
    ];

    return $routes[$key] ?? $key;
}

function haritics_route_url(string $key): string
{
    return home_url('/' . trim(haritics_route_path($key), '/') . '/');
}

function haritics_image_url(?int $attachment_id, string $size = 'full', string $fallback = ''): string
{
    if ($attachment_id) {
        $url = wp_get_attachment_image_url($attachment_id, $size);
        if ($url) {
            return $url;
        }
    }

    return $fallback;
}

function haritics_format_money($amount): string
{
    if ($amount === '' || $amount === null) {
        return __('Đang cập nhật', 'haritics');
    }

    return number_format((float) $amount, 0, ',', '.') . ' VNĐ';
}

function haritics_translate_skill_label(string $label): string
{
    $map = [
        'Leadership' => __('Lãnh đạo', 'haritics'),
        'Field Coordination' => __('Điều phối hiện trường', 'haritics'),
        'Community Outreach' => __('Kết nối cộng đồng', 'haritics'),
        'Teamwork' => __('Làm việc nhóm', 'haritics'),
        'Planning' => __('Lập kế hoạch', 'haritics'),
        'Partnership' => __('Hợp tác đối tác', 'haritics'),
    ];

    return $map[$label] ?? $label;
}

function haritics_progress_percent($raised, $target): int
{
    $target_value = (float) $target;
    $raised_value = (float) $raised;

    if ($target_value <= 0) {
        return 0;
    }

    return (int) max(0, min(100, round(($raised_value / $target_value) * 100)));
}

function haritics_get_meta(int $post_id, string $key, string $default = ''): string
{
    $value = get_post_meta($post_id, $key, true);

    if (is_scalar($value) && $value !== '') {
        return (string) $value;
    }

    return $default;
}

function haritics_get_project_leader(int $project_id, string $default = ''): string
{
    // First try to get leader by ID (new field)
    $leader_id = haritics_get_meta($project_id, '_leader_id');
    if ($leader_id !== '') {
        $leader_post = get_post((int) $leader_id);
        if ($leader_post && $leader_post->post_status === 'publish') {
            return $leader_post->post_title;
        }
    }

    // Fallback to old text field
    return haritics_get_meta($project_id, '_leader_text', $default);
}

function haritics_get_project_donor(int $project_id, string $default = ''): string
{
    // First try to get donor by ID (new field)
    $donor_id = haritics_get_meta($project_id, '_donor_id');
    if ($donor_id !== '') {
        $donor_post = get_post((int) $donor_id);
        if ($donor_post && $donor_post->post_status === 'publish') {
            return $donor_post->post_title;
        }
    }

    // Fallback to old text field
    return haritics_get_meta($project_id, '_donor_text', $default);
}

/**
 * Thẻ dự án (trang chủ / archive) — $type: calling|featured|implementing|upcoming.
 */
function haritics_render_project_card(\WP_Post $project, string $type): void
{
    $target = haritics_get_meta($project->ID, '_target_amount', '0');
    $raised = haritics_get_meta($project->ID, '_raised_amount', '0');
    $progress = haritics_progress_percent($raised, $target);
    $location = haritics_get_meta($project->ID, '_location', get_the_excerpt($project));
    
    // Metadata
    $leader_text = haritics_get_project_leader($project->ID, '');
    $leader_condition = haritics_get_meta($project->ID, '_leader_condition', '#');
    $volunteer_needed = haritics_get_meta($project->ID, '_volunteer_needed', '');
    $volunteer_condition = haritics_get_meta($project->ID, '_volunteer_condition', '#');
    $resources_other = haritics_get_meta($project->ID, '_resources_other', '');
    $resources_detail = haritics_get_meta($project->ID, '_resources_detail', '#');
    $donor_text = haritics_get_project_donor($project->ID, '');
    $donor_list_url = haritics_get_meta($project->ID, '_donor_list_url', '#');
    $leader_list_url = haritics_get_meta($project->ID, '_leader_list_url', '#');

    ?>
    <article class="ul-project-card">
        <div class="ul-project-card-img">
            <?php echo get_the_post_thumbnail($project->ID, 'large', ['alt' => get_the_title($project)]); ?>
            <?php if ($type === 'calling') : ?>
                <a href="<?php echo esc_url(get_permalink($project)); ?>" class="ul-btn-view-detail"><?php esc_html_e('Xem chi tiết', 'haritics'); ?></a>
            <?php endif; ?>
        </div>
        
        <div class="ul-project-card-content">
            <h3 class="ul-project-card-title">
                <a href="<?php echo esc_url(get_permalink($project)); ?>"><?php echo esc_html(get_the_title($project)); ?></a>
            </h3>
            <p class="ul-project-card-location"><?php echo esc_html($location); ?></p>

            <div class="ul-project-progress">
                <div class="ul-progress-container">
                    <div class="ul-progressbar" data-ul-progress-value="<?php echo esc_attr((string) $progress); ?>">
                        <div class="ul-progress-label"></div>
                    </div>
                </div>
                <div class="ul-progress-info">
                    <span class="ul-progress-percent"><?php echo esc_html((string) $progress); ?>%</span>
                    <span class="ul-progress-amount"><?php echo esc_html(haritics_format_money($raised)); ?> / <?php echo esc_html(haritics_format_money($target)); ?></span>
                </div>
            </div>

            <?php
            if ($type === 'calling') :
                $pid = (int) $project->ID;
                $apply_leader_url    = haritics_project_public_form_url($pid, 'haritics-form-apply-leader');
                $apply_volunteer_url = haritics_project_public_form_url($pid, 'haritics-form-apply-volunteer');
                $contribute_url      = haritics_project_public_form_url($pid, 'haritics-form-contribute');
                ?>
                
                <div class="ul-project-meta">
                    <span class="ul-meta-label"><?php esc_html_e('Số vốn cần huy động:', 'haritics'); ?></span>
                    <span class="ul-meta-value font-weight-bold"><?php echo esc_html($target !== '' && $target !== '0' ? haritics_format_money($target) : __('Chưa cập nhật', 'haritics')); ?></span>
                </div>

                <div class="ul-project-meta">
                    <span class="ul-meta-label"><?php esc_html_e('Lãnh đạo dự án:', 'haritics'); ?></span>
                    <div class="ul-meta-buttons-grid">
                        <a href="#popup-leader-cond" 
                        data-apply-url="<?php echo esc_url($apply_leader_url); ?>" 
                        class="ul-btn-condition-outline open-popup-link">
                        <?php esc_html_e('Xem điều kiện', 'haritics'); ?>
                        </a>
                        <a href="<?php echo esc_url($apply_leader_url); ?>" class="ul-btn-apply-solid"><?php esc_html_e('Ứng tuyển', 'haritics'); ?></a>
                    </div>
                </div>

                <div class="ul-project-meta">
                    <span class="ul-meta-label"><?php esc_html_e('Nhân sự cần huy động:', 'haritics'); ?></span>
                    <div class="ul-meta-buttons-grid">
                        <a href="#popup-staff-cond" 
                        data-apply-url="<?php echo esc_url($apply_volunteer_url); ?>" 
                        class="ul-btn-condition-outline open-popup-link">
                        <?php esc_html_e('Xem điều kiện', 'haritics'); ?>
                        </a>
                        <a href="<?php echo esc_url($apply_volunteer_url); ?>" class="ul-btn-apply-solid"><?php esc_html_e('Ứng tuyển', 'haritics'); ?></a>
                    </div>
                </div>

                <div class="ul-project-meta">
                    <span class="ul-meta-label"><?php esc_html_e('Các nguồn lực khác:', 'haritics'); ?></span>
                    <div class="ul-meta-buttons-grid">
                        <a href="<?php echo esc_url(get_permalink($project)); ?>" class="ul-btn-condition-outline"><?php esc_html_e('Xem chi tiết', 'haritics'); ?></a>
                        <a href="<?php echo esc_url($contribute_url); ?>" class="ul-btn-apply-solid"><?php esc_html_e('Muốn đóng góp', 'haritics'); ?></a>
                    </div>
                </div>

                <?php if (!empty($donor_text)) : ?>
                <div class="ul-project-meta">
                    <span class="ul-meta-label"><?php esc_html_e('Nhà hảo tâm:', 'haritics'); ?></span>
                    <span class="ul-meta-value"><?php echo esc_html($donor_text); ?></span>
                </div>
                <?php endif; ?>

            <?php else : ?>
                <?php if (!empty($leader_text)) : ?>
                    <div class="ul-project-meta">
                        <span class="ul-meta-label"><?php esc_html_e('Lãnh đạo dự án:', 'haritics'); ?></span>
                        <span class="ul-meta-value"><?php echo esc_html($leader_text); ?></span>
                        <?php if ($leader_list_url !== '' && $leader_list_url !== '#') : ?>
                            <div class="ul-meta-buttons">
                                <a href="<?php echo esc_url($leader_list_url); ?>" class="ul-btn-condition"><?php esc_html_e('Xem danh sách', 'haritics'); ?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($donor_text)) : ?>
                    <div class="ul-project-meta">
                        <span class="ul-meta-label"><?php esc_html_e('Nhà hảo tâm:', 'haritics'); ?></span>
                        <span class="ul-meta-value"><?php echo esc_html($donor_text); ?></span>
                        <?php if ($donor_list_url !== '' && $donor_list_url !== '#') : ?>
                            <div class="ul-meta-buttons">
                                <a href="<?php echo esc_url($donor_list_url); ?>" class="ul-btn-condition"><?php esc_html_e('Xem danh sách', 'haritics'); ?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <a href="<?php echo esc_url(get_permalink($project)); ?>" class="ul-project-card-btn">
                <i class="flaticon-up-right-arrow"></i>
            </a>
        </div>
    </article>
    <?php
}

function haritics_get_gallery_urls(int $post_id, string $meta_key): array
{
    $ids = get_post_meta($post_id, $meta_key, true);

    if (!is_array($ids)) return [];

    return array_map(function ($id) {
        return wp_get_attachment_url($id);
    }, $ids);
}

function haritics_social_icon_class(string $network): string
{
    $map = [
        'facebook' => 'flaticon-facebook',
        'twitter' => 'flaticon-twitter',
        'instagram' => 'flaticon-instagram',
        'youtube' => 'flaticon-youtube',
        'linkedin' => 'flaticon-linkedin-big-logo',
    ];

    return $map[$network] ?? 'flaticon-share';
}

function haritics_get_social_links_from_options(): array
{
    $networks = ['facebook', 'twitter', 'instagram', 'youtube', 'linkedin'];
    $links = [];

    foreach ($networks as $network) {
        $url = haritics_get_option('social_' . $network);
        if ($url !== '') {
            $links[$network] = $url;
        }
    }

    return $links;
}

function haritics_get_post_social_links(int $post_id): array
{
    $networks = ['facebook', 'twitter', 'instagram', 'linkedin'];
    $links = [];

    foreach ($networks as $network) {
        $url = haritics_get_meta($post_id, '_' . $network . '_url');
        if ($url !== '') {
            $links[$network] = $url;
        }
    }

    return $links;
}

function haritics_render_social_links(array $links, string $wrapper_class = 'ul-footer-socials'): void
{
    if ($links === []) {
        return;
    }

    echo '<div class="' . esc_attr($wrapper_class) . '">';
    foreach ($links as $network => $url) {
        echo '<a href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer"><i class="' . esc_attr(haritics_social_icon_class((string) $network)) . '"></i></a>';
    }
    echo '</div>';
}

function haritics_build_menu_tree(array $items, int $parent_id = 0): array
{
    $branch = [];

    foreach ($items as $item) {
        if ((int) $item->menu_item_parent !== $parent_id) {
            continue;
        }

        $item->children = haritics_build_menu_tree($items, (int) $item->ID);
        $branch[] = $item;
    }

    return $branch;
}

function haritics_get_fallback_menu_items(): array
{
    $items = [
        ['title' => __('Trang chủ', 'haritics'), 'url' => home_url('/')],
        ['title' => __('Dự án', 'haritics'), 'url' => get_post_type_archive_link('project') ?: haritics_route_url('project')],
        ['title' => __('Nhà tổ chức', 'haritics'), 'url' => get_post_type_archive_link('team') ?: haritics_route_url('team')],
        ['title' => __('Mạnh thường quân', 'haritics'), 'url' => get_post_type_archive_link('donation') ?: haritics_route_url('donation')],
        ['title' => __('Hoạt động', 'haritics'), 'url' => get_post_type_archive_link('event') ?: haritics_route_url('event')],
        ['title' => __('Liên hệ', 'haritics'), 'url' => haritics_route_url('contact')],
    ];

    return $items;
}

function haritics_render_primary_menu(string $location = 'primary_menu'): void
{
    $locations = get_nav_menu_locations();

    if (! empty($locations[$location])) {
        $menu_items = wp_get_nav_menu_items($locations[$location]);
        if (is_array($menu_items) && $menu_items !== []) {
            $tree = haritics_build_menu_tree($menu_items);
            echo '<nav class="ul-header-nav">';
            haritics_render_menu_nodes($tree);
            echo '</nav>';
            return;
        }
    }

    echo '<nav class="ul-header-nav">';
    foreach (haritics_get_fallback_menu_items() as $item) {
        echo '<a href="' . esc_url($item['url']) . '">' . esc_html($item['title']) . '</a>';
    }
    echo '</nav>';
}

function haritics_render_menu_nodes(array $nodes): void
{
    foreach ($nodes as $node) {
        $title = esc_html($node->title);
        $url = esc_url($node->url ?: '#');

        if (! empty($node->children)) {
            echo '<div class="has-sub-menu">';
            echo '<a href="' . $url . '">' . $title . '</a>';
            echo '<div class="ul-header-submenu"><ul>';
            foreach ($node->children as $child) {
                echo '<li><a href="' . esc_url($child->url ?: '#') . '">' . esc_html($child->title) . '</a></li>';
            }
            echo '</ul></div>';
            echo '</div>';
        } else {
            echo '<a href="' . $url . '">' . $title . '</a>';
        }
    }
}

function haritics_render_footer_menu(string $location, array $fallback): void
{
    $locations = get_nav_menu_locations();

    if (! empty($locations[$location])) {
        wp_nav_menu([
            'theme_location' => $location,
            'container' => false,
            'menu_class' => '',
            'items_wrap' => '%3$s',
            'depth' => 1,
            'fallback_cb' => false,
            'link_before' => '',
            'link_after' => '',
        ]);
        return;
    }

    foreach ($fallback as $item) {
        echo '<a href="' . esc_url($item['url']) . '">' . esc_html($item['title']) . '</a>';
    }
}

function haritics_render_breadcrumb(string $title): void
{
    ?>
    <section class="ul-breadcrumb ul-section-spacing">
        <div class="ul-container">
            <h2 class="ul-breadcrumb-title"><?php echo esc_html($title); ?></h2>
            <ul class="ul-breadcrumb-nav">
                <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Trang chủ', 'haritics'); ?></a></li>
                <li><span class="separator"><i class="flaticon-right"></i></span></li>
                <li><?php echo esc_html($title); ?></li>
            </ul>
        </div>
    </section>
    <?php
}

function haritics_render_pagination(): void
{
    $links = paginate_links([
        'type' => 'array',
        'prev_text' => '<i class="flaticon-back"></i>',
        'next_text' => '<i class="flaticon-next"></i>',
    ]);

    if (! is_array($links) || $links === []) {
        return;
    }

    echo '<div class="ul-pagination">';
    foreach ($links as $link) {
        echo wp_kses_post($link);
    }
    echo '</div>';
}

function haritics_get_home_posts(string $post_type, int $limit = 3): array
{
    return get_posts([
        'post_type' => $post_type,
        'posts_per_page' => $limit,
        'post_status' => 'publish',
    ]);
}

/**
 * Giá trị _status lưu trong DB có thể là slug (meta box select) hoặc nhãn cũ (import).
 * Trả về nhãn tiếng Việt để hiển thị.
 */
function haritics_get_project_status_label(string $stored): string
{
    $slug_to_label = [
        'Dang-huy-dong' => __('Đang kêu gọi nguồn lực', 'haritics'),
        'Tieu-bieu' => __('Tiêu biểu', 'haritics'),
        'Dang-trien-khai' => __('Đang triển khai', 'haritics'),
        'Dang-sap-trien-khai' => __('Đang sắp triển khai', 'haritics'),
    ];

    if (isset($slug_to_label[$stored])) {
        return $slug_to_label[$stored];
    }

    $legacy_calling = [
        'Đang huy động' => __('Đang kêu gọi nguồn lực', 'haritics'),
        'Đang kêu gọi' => __('Đang kêu gọi nguồn lực', 'haritics'),
    ];

    if (isset($legacy_calling[$stored])) {
        return $legacy_calling[$stored];
    }

    return $stored;
}

/**
 * Khóa loại thẻ dự án (calling|featured|implementing|upcoming) theo meta _status.
 */
function haritics_get_project_card_type_for_post(int $post_id): string
{
    $stored = haritics_get_meta($post_id, '_status', '');
    $map = [
        'Đang huy động' => 'calling',
        'Đang kêu gọi' => 'calling',
        'Dang-huy-dong' => 'calling',
        'Tiêu biểu' => 'featured',
        'Tieu-bieu' => 'featured',
        'Đang triển khai' => 'implementing',
        'Dang-trien-khai' => 'implementing',
        'Sắp triển khai' => 'upcoming',
        'Đang sắp triển khai' => 'upcoming',
        'Dang-sap-trien-khai' => 'upcoming',
    ];

    return $map[$stored] ?? 'implementing';
}

/**
 * Tất cả giá trị meta _status tương đương một nhóm (theo 4 option trong meta box + dữ liệu cũ).
 *
 * @return list<string>
 */
function haritics_project_status_meta_values_for_group(string $group_key): array
{
    $groups = [
        'calling' => ['Dang-huy-dong', 'Đang huy động', 'Đang kêu gọi'],
        'featured' => ['Tieu-bieu', 'Tiêu biểu'],
        'implementing' => ['Dang-trien-khai', 'Đang triển khai'],
        'upcoming' => ['Dang-sap-trien-khai', 'Đang sắp triển khai', 'Sắp triển khai'],
    ];

    return $groups[$group_key] ?? [];
}

function haritics_get_projects_by_status(string $status, int $limit = 4): array
{
    $status_to_group = [
        'Đang huy động' => 'calling',
        'Đang kêu gọi' => 'calling',
        'Dang-huy-dong' => 'calling',
        'Tiêu biểu' => 'featured',
        'Tieu-bieu' => 'featured',
        'Đang triển khai' => 'implementing',
        'Dang-trien-khai' => 'implementing',
        'Sắp triển khai' => 'upcoming',
        'Đang sắp triển khai' => 'upcoming',
        'Dang-sap-trien-khai' => 'upcoming',
    ];

    $group = $status_to_group[$status] ?? null;
    $values = $group !== null
        ? haritics_project_status_meta_values_for_group($group)
        : [$status];

    return get_posts([
        'post_type' => 'project',
        'posts_per_page' => $limit,
        'post_status' => 'publish',
        'meta_query' => [
            [
                'key' => '_status',
                'value' => $values,
                'compare' => 'IN',
            ],
        ],
    ]);
}

function haritics_get_project_status_groups(): array
{
    return [
        [
            'key' => 'calling',
            'title' => __('Dự án đang kêu gọi nguồn lực', 'haritics'),
            'badge' => __('Đang kêu gọi nguồn lực', 'haritics'),
            'statuses' => haritics_project_status_meta_values_for_group('calling'),
        ],
        [
            'key' => 'featured',
            'title' => __('Dự án tiêu biểu', 'haritics'),
            'badge' => __('Tiêu biểu', 'haritics'),
            'statuses' => haritics_project_status_meta_values_for_group('featured'),
        ],
        [
            'key' => 'implementing',
            'title' => __('Dự án đang triển khai', 'haritics'),
            'badge' => __('Đang triển khai', 'haritics'),
            'statuses' => haritics_project_status_meta_values_for_group('implementing'),
        ],
        [
            'key' => 'upcoming',
            'title' => __('Dự án đang sắp triển khai', 'haritics'),
            'badge' => __('Đang sắp triển khai', 'haritics'),
            'statuses' => haritics_project_status_meta_values_for_group('upcoming'),
        ],
    ];
}

function haritics_build_project_filter_meta_query(array $filters): array
{
    $meta_query = ['relation' => 'AND'];

    // organizer filter - search in leader_text OR in leader_id (post title)
    if (! empty($filters['organizer'])) {
        $leader_posts = get_posts([
            'post_type' => 'team',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            's' => $filters['organizer'],
            'fields' => 'ids',
        ]);

        $organizer_clause = [
            'relation' => 'OR',
            [
                'key' => '_leader_text',
                'value' => $filters['organizer'],
                'compare' => 'LIKE',
            ],
        ];
        if ($leader_posts !== []) {
            $organizer_clause[] = [
                'key' => '_leader_id',
                'value' => $leader_posts,
                'compare' => 'IN',
            ];
        }
        $meta_query[] = $organizer_clause;
    }

    // donor filter - search in donor_text OR in donor_id (post title)
    if (! empty($filters['donor'])) {
        $donor_posts = get_posts([
            'post_type' => 'donation',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            's' => $filters['donor'],
            'fields' => 'ids',
        ]);

        $donor_clause = [
            'relation' => 'OR',
            [
                'key' => '_donor_text',
                'value' => $filters['donor'],
                'compare' => 'LIKE',
            ],
        ];
        if ($donor_posts !== []) {
            $donor_clause[] = [
                'key' => '_donor_id',
                'value' => $donor_posts,
                'compare' => 'IN',
            ];
        }
        $meta_query[] = $donor_clause;
    }

    if (! empty($filters['location'])) {
        $meta_query[] = [
            'key' => '_location',
            'value' => $filters['location'],
            'compare' => 'LIKE',
        ];
    }

    return count($meta_query) > 1 ? $meta_query : [];
}

function haritics_get_projects_for_archive(array $group, array $filters = []): array
{
    $query_args = [
        'post_type' => 'project',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
        'meta_query' => [
            'relation' => 'AND',
            [
                'key' => '_status',
                'value' => $group['statuses'] ?? [],
                'compare' => 'IN',
            ],
        ],
    ];

    if (! empty($filters['s'])) {
        $query_args['s'] = $filters['s'];
    }

    $filter_meta_query = haritics_build_project_filter_meta_query($filters);
    if ($filter_meta_query !== []) {
        foreach (array_slice($filter_meta_query, 1) as $meta_clause) {
            $query_args['meta_query'][] = $meta_clause;
        }
    }

    return get_posts($query_args);
}

function haritics_apply_advanced_search(\WP_Query $query): void
{
    if (is_admin() || ! $query->is_main_query()) {
        return;
    }

    $has_filters = haritics_has_active_search_filters();
    $archive_post_type = $query->is_post_type_archive() ? (string) $query->get('post_type') : '';

    if (! $has_filters && $archive_post_type === '') {
        return;
    }

    $filters = haritics_get_search_filters();

    if ($archive_post_type !== '') {
        $query->set('post_type', $archive_post_type);
        $query->set('posts_per_page', $archive_post_type === 'project' ? -1 : 12);

        if ($archive_post_type === 'project' && $has_filters) {
            if ($filters['s'] !== '') {
                $query->set('s', $filters['s']);
            }

            $meta_query = haritics_build_project_filter_meta_query($filters);
            if ($meta_query !== []) {
                $query->set('meta_query', $meta_query);
            }
        }

        return;
    }

    if (! $has_filters) {
        return;
    }

    if ($query->is_home() && (string) get_option('show_on_front') === 'page') {
        $query->set('page_id', 0);
        $query->is_home = false;
        $query->is_page = false;
        $query->is_singular = false;
    }

    $post_types = [];
    $has_organizer = $filters['organizer'] !== '';
    $has_donor = $filters['donor'] !== '';
    $has_location = $filters['location'] !== '';

    $post_types[] = 'project';

    if ($has_organizer) {
        $post_types[] = 'team';
    }

    if ($has_donor) {
        $post_types[] = 'donation';
    }

    if ($has_location) {
        $post_types[] = 'donation';
        $post_types[] = 'event';
    }

    $post_types = array_unique($post_types);

    if (! empty($post_types)) {
        $query->set('post_type', $post_types);
    }

    $query->set('posts_per_page', 12);

    if ($filters['s'] !== '') {
        $query->set('s', $filters['s']);
    }

    $meta_queries = [];

    // organizer filter - search in team posts OR project meta fields
    if ($filters['organizer'] !== '') {
        $leader_posts = get_posts([
            'post_type' => 'team',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            's' => $filters['organizer'],
            'fields' => 'ids',
        ]);

        $organizer_block = [
            'relation' => 'OR',
            [
                'key' => '_leader_text',
                'value' => $filters['organizer'],
                'compare' => 'LIKE',
            ],
            [
                'key' => '_organizer',
                'value' => $filters['organizer'],
                'compare' => 'LIKE',
            ],
            [
                'key' => '_role',
                'value' => $filters['organizer'],
                'compare' => 'LIKE',
            ],
        ];
        if ($leader_posts !== []) {
            $organizer_block[] = [
                'key' => '_leader_id',
                'value' => $leader_posts,
                'compare' => 'IN',
            ];
        }
        $meta_queries[] = $organizer_block;
    }

    // donor filter - search in donation posts OR project meta fields
    if ($filters['donor'] !== '') {
        $donor_posts = get_posts([
            'post_type' => 'donation',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            's' => $filters['donor'],
            'fields' => 'ids',
        ]);

        $donor_block = [
            'relation' => 'OR',
            [
                'key' => '_donor_text',
                'value' => $filters['donor'],
                'compare' => 'LIKE',
            ],
            [
                'key' => '_badge',
                'value' => $filters['donor'],
                'compare' => 'LIKE',
            ],
        ];
        if ($donor_posts !== []) {
            $donor_block[] = [
                'key' => '_donor_id',
                'value' => $donor_posts,
                'compare' => 'IN',
            ];
        }
        $meta_queries[] = $donor_block;
    }

    if ($filters['location'] !== '') {
        $meta_queries[] = [
            'relation' => 'OR',
            [
                'key' => '_location',
                'value' => $filters['location'],
                'compare' => 'LIKE',
            ],
            [
                'key' => '_venue',
                'value' => $filters['location'],
                'compare' => 'LIKE',
            ],
        ];
    }

    if (! empty($meta_queries)) {
        if (count($meta_queries) > 1) {
            $meta_query = ['relation' => 'AND'];
            foreach ($meta_queries as $mq) {
                $meta_query[] = $mq;
            }
            $query->set('meta_query', $meta_query);
        } else {
            $query->set('meta_query', $meta_queries[0]);
        }
    }

    if ($has_filters) {
        $query->is_search = true;
    }
}
add_action('pre_get_posts', 'haritics_apply_advanced_search');
