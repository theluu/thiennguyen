<?php
if (! defined('ABSPATH')) {
    exit;
}

$header_logo = haritics_get_option('header_logo', haritics_theme_asset('assets/img/logo.svg'));
$header_cta_text = haritics_get_option('header_cta_text', __('Liên hệ tư vấn', 'haritics'));
$header_cta_url = haritics_get_option('header_cta_url', 'tel:' . preg_replace('/\s+/', '', haritics_get_option('hotline', '19001234')));
$header_button_text = haritics_get_option('header_button_text', __('Xem dự án', 'haritics'));
$header_button_url = haritics_get_option('header_button_url', get_post_type_archive_link('project') ?: haritics_route_url('project'));
$haritics_project_search_url = get_post_type_archive_link('project') ?: haritics_route_url('project');
$social_links = haritics_get_social_links_from_options();
$search_filters = haritics_get_search_filters();
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="preloader" id="preloader">
    <div class="loader"></div>
</div>

<div class="ul-sidebar">
    <div class="ul-sidebar-header">
        <div class="ul-sidebar-header-logo">
            <a href="<?php echo esc_url(home_url('/')); ?>">
                <img src="<?php echo esc_url($header_logo); ?>" alt="<?php bloginfo('name'); ?>" class="logo">
            </a>
        </div>
        <button class="ul-sidebar-closer"><i class="flaticon-close"></i></button>
    </div>

    <div class="ul-sidebar-header-nav-wrapper d-block d-lg-none"></div>

    <div class="ul-sidebar-footer">
        <span class="ul-sidebar-footer-title"><?php esc_html_e('Kết nối cùng chúng tôi', 'haritics'); ?></span>
        <?php haritics_render_social_links($social_links, 'ul-sidebar-footer-social'); ?>
    </div>
</div>

<div class="ul-search-form-wrapper flex-grow-1 flex-shrink-0">
    <button class="ul-search-closer"><i class="flaticon-close"></i></button>
    <form action="<?php echo esc_url($haritics_project_search_url); ?>" class="ul-search-form ul-search-form--advanced" method="get">
        <div class="ul-search-form-inner">
            <div class="ul-search-form-heading">
                <span class="ul-section-sub-title text-white"><?php esc_html_e('Tìm kiếm nhanh', 'haritics'); ?></span>
                <h3 class="ul-section-title text-white"><?php esc_html_e('Lọc nội dung ngay từ thanh search', 'haritics'); ?></h3>
                <p class="text-white mb-0"><?php esc_html_e('Chọn tiêu chí phù hợp để tìm theo dự án, nhà tổ chức, mạnh thường quân hoặc địa điểm tỉnh/xã.', 'haritics'); ?></p>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="text-white d-block mb-2" for="search-project"><?php esc_html_e('Dự án', 'haritics'); ?></label>
                    <input type="search" name="s" id="search-project" placeholder="<?php esc_attr_e('Nhập tên dự án', 'haritics'); ?>" value="<?php echo esc_attr($search_filters['s']); ?>">
                </div>
                <div class="col-md-6">
                    <label class="text-white d-block mb-2" for="search-organizer"><?php esc_html_e('Nhà tổ chức', 'haritics'); ?></label>
                    <input type="search" name="organizer" id="search-organizer" placeholder="<?php esc_attr_e('Nhập tên đơn vị tổ chức', 'haritics'); ?>" value="<?php echo esc_attr($search_filters['organizer']); ?>">
                </div>
                <div class="col-md-6">
                    <label class="text-white d-block mb-2" for="search-donor"><?php esc_html_e('Mạnh thường quân', 'haritics'); ?></label>
                    <input type="search" name="donor" id="search-donor" placeholder="<?php esc_attr_e('Nhập tên mạnh thường quân', 'haritics'); ?>" value="<?php echo esc_attr($search_filters['donor']); ?>">
                </div>
                <div class="col-md-6">
                    <label class="text-white d-block mb-2" for="search-location"><?php esc_html_e('Địa điểm tỉnh/xã', 'haritics'); ?></label>
                    <input type="search" name="location" id="search-location" placeholder="<?php esc_attr_e('Ví dụ: Hà Giang, Tả Sử Choóng', 'haritics'); ?>" value="<?php echo esc_attr($search_filters['location']); ?>">
                </div>
            </div>

            <div class="ul-search-form-actions">
                <button type="submit"><span class="icon"><i class="flaticon-search"></i></span> <?php esc_html_e('Tìm kiếm', 'haritics'); ?></button>
            </div>
        </div>
    </form>
</div>

<header class="ul-header">
    <div class="ul-header-bottom to-be-sticky">
        <div class="ul-header-bottom-wrapper ul-header-container">
            <div class="logo-container">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="d-inline-block">
                    <img src="<?php echo esc_url($header_logo); ?>" alt="<?php bloginfo('name'); ?>" class="logo">
                </a>
            </div>

            <div class="ul-header-nav-wrapper">
                <div class="to-go-to-sidebar-in-mobile">
                    <?php haritics_render_primary_menu(); ?>
                </div>
            </div>

            <div class="ul-header-actions">
                <button class="ul-header-search-opener"><i class="flaticon-search"></i></button>
                <a href="<?php echo esc_url($header_cta_url); ?>" class="ul-header-cta d-none d-xl-inline-flex"><?php echo esc_html($header_cta_text); ?></a>
                <a href="<?php echo esc_url($header_button_url); ?>" class="ul-btn d-sm-inline-flex d-none"><i class="flaticon-fast-forward-double-right-arrows-symbol"></i> <?php echo esc_html($header_button_text); ?></a>
                <button class="ul-header-sidebar-opener d-lg-none d-inline-flex"><i class="flaticon-menu"></i></button>
            </div>
        </div>
    </div>
</header>
