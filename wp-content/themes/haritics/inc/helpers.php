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

function haritics_get_search_keyword(): string
{
    $filters = haritics_get_search_filters();

    foreach (['s', 'organizer', 'donor', 'location'] as $key) {
        if ($filters[$key] !== '') {
            return $filters[$key];
        }
    }

    return '';
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

function haritics_get_gallery_urls(int $post_id, string $meta_key): array
{
    $raw = trim(haritics_get_meta($post_id, $meta_key));
    if ($raw === '') {
        return [];
    }

    $parts = array_filter(array_map('trim', explode("\n", str_replace(["\r\n", "\r"], "\n", $raw))));

    return array_values(array_filter($parts, static fn ($url) => (bool) filter_var($url, FILTER_VALIDATE_URL)));
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

function haritics_get_projects_by_status(string $status, int $limit = 4): array
{
    $status_aliases = [
        'Đang huy động' => ['Đang huy động', 'Đang kêu gọi'],
        'Đang kêu gọi' => ['Đang kêu gọi', 'Đang huy động'],
        'Sắp triển khai' => ['Sắp triển khai', 'Đang sắp triển khai'],
        'Đang sắp triển khai' => ['Đang sắp triển khai', 'Sắp triển khai'],
    ];

    return get_posts([
        'post_type' => 'project',
        'posts_per_page' => $limit,
        'post_status' => 'publish',
        'meta_query' => [
            [
                'key' => '_status',
                'value' => $status_aliases[$status] ?? [$status],
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
            'title' => __('Dự án đang huy động', 'haritics'),
            'badge' => __('Đang huy động', 'haritics'),
            'statuses' => ['Đang huy động', 'Đang kêu gọi'],
        ],
        [
            'key' => 'featured',
            'title' => __('Dự án tiêu biểu', 'haritics'),
            'badge' => __('Tiêu biểu', 'haritics'),
            'statuses' => ['Tiêu biểu'],
        ],
        [
            'key' => 'implementing',
            'title' => __('Dự án đang triển khai', 'haritics'),
            'badge' => __('Đang triển khai', 'haritics'),
            'statuses' => ['Đang triển khai'],
        ],
        [
            'key' => 'upcoming',
            'title' => __('Dự án đang sắp triển khai', 'haritics'),
            'badge' => __('Sắp triển khai', 'haritics'),
            'statuses' => ['Đang sắp triển khai', 'Sắp triển khai'],
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

        $meta_query[] = [
            'relation' => 'OR',
            [
                'key' => '_leader_text',
                'value' => $filters['organizer'],
                'compare' => 'LIKE',
            ],
            [
                'key' => '_leader_id',
                'value' => $leader_posts,
                'compare' => 'IN',
            ],
        ];
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

        $meta_query[] = [
            'relation' => 'OR',
            [
                'key' => '_donor_text',
                'value' => $filters['donor'],
                'compare' => 'LIKE',
            ],
            [
                'key' => '_donor_id',
                'value' => $donor_posts,
                'compare' => 'IN',
            ],
        ];
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
    $keyword = haritics_get_search_keyword();

    if ($archive_post_type !== '') {
        $query->set('post_type', $archive_post_type);
        $query->set('posts_per_page', $archive_post_type === 'project' ? -1 : 12);

        if ($archive_post_type === 'project' && $has_filters) {
            if ($keyword !== '') {
                $query->set('s', $keyword);
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
    $has_keyword = $keyword !== '';

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

    if ($keyword !== '') {
        $query->set('s', $keyword);
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

        $meta_queries[] = [
            'relation' => 'OR',
            [
                'key' => '_leader_text',
                'value' => $filters['organizer'],
                'compare' => 'LIKE',
            ],
            [
                'key' => '_leader_id',
                'value' => $leader_posts,
                'compare' => 'IN',
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

        $meta_queries[] = [
            'relation' => 'OR',
            [
                'key' => '_donor_text',
                'value' => $filters['donor'],
                'compare' => 'LIKE',
            ],
            [
                'key' => '_donor_id',
                'value' => $donor_posts,
                'compare' => 'IN',
            ],
            [
                'key' => '_badge',
                'value' => $filters['donor'],
                'compare' => 'LIKE',
            ],
        ];
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
