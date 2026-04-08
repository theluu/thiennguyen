<?php
if (! defined('ABSPATH')) {
    exit;
}

$footer_logo = haritics_get_option('footer_logo', haritics_theme_asset('assets/img/logo-white.svg'));
$footer_about = haritics_get_option('footer_about', __('Theo dõi tiến độ dự án, chứng từ công khai và các cập nhật cộng đồng ngay trên nền tảng Thiện Nguyện.', 'haritics'));
$hotline = haritics_get_option('hotline', '1900 1234');
$email = haritics_get_option('email', 'lienhe@thiennguyen.vn');
$address = haritics_get_option('address', __('Tầng 5, Tòa nhà Cộng đồng, Hà Nội', 'haritics'));
$copyright = haritics_get_option('copyright_text', '© ' . date('Y') . ' ' . get_bloginfo('name'));
$social_links = haritics_get_social_links_from_options();
$footer_fallback_1 = haritics_get_fallback_menu_items();
$footer_fallback_2 = [
    ['title' => __('Liên hệ', 'haritics'), 'url' => haritics_route_url('contact')],
    ['title' => __('Dự án nổi bật', 'haritics'), 'url' => get_post_type_archive_link('project') ?: haritics_route_url('project')],
    ['title' => __('Hoạt động', 'haritics'), 'url' => get_post_type_archive_link('event') ?: haritics_route_url('event')],
];
?>
<footer class="ul-footer">
    <div class="ul-footer-top">
        <div class="ul-footer-container">
            <div class="ul-footer-top-contact-infos">
                <div class="ul-footer-top-contact-info">
                    <div class="ul-footer-top-contact-info-icon"><div class="ul-footer-top-contact-info-icon-inner"><i class="flaticon-pin"></i></div></div>
                    <div class="ul-footer-top-contact-info-txt">
                        <span class="ul-footer-top-contact-info-label"><?php esc_html_e('Địa chỉ', 'haritics'); ?></span>
                        <h5 class="ul-footer-top-contact-info-address"><?php echo esc_html($address); ?></h5>
                    </div>
                </div>
                <div class="ul-footer-top-contact-info">
                    <div class="ul-footer-top-contact-info-icon"><div class="ul-footer-top-contact-info-icon-inner"><i class="flaticon-email"></i></div></div>
                    <div class="ul-footer-top-contact-info-txt">
                        <span class="ul-footer-top-contact-info-label"><?php esc_html_e('Email', 'haritics'); ?></span>
                        <h5 class="ul-footer-top-contact-info-address"><a href="mailto:<?php echo antispambot($email); ?>"><?php echo esc_html($email); ?></a></h5>
                    </div>
                </div>
                <div class="ul-footer-top-contact-info">
                    <div class="ul-footer-top-contact-info-icon"><div class="ul-footer-top-contact-info-icon-inner"><i class="flaticon-telephone-call-1"></i></div></div>
                    <div class="ul-footer-top-contact-info-txt">
                        <span class="ul-footer-top-contact-info-label"><?php esc_html_e('Hotline', 'haritics'); ?></span>
                        <h5 class="ul-footer-top-contact-info-address"><a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $hotline)); ?>"><?php echo esc_html($hotline); ?></a></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ul-footer-middle">
        <div class="ul-footer-container">
            <div class="ul-footer-middle-wrapper wow animate__fadeInUp">
                <div class="ul-footer-about">
                    <a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo esc_url($footer_logo); ?>" alt="<?php bloginfo('name'); ?>" class="logo"></a>
                    <p class="ul-footer-about-txt"><?php echo esc_html($footer_about); ?></p>
                    <?php haritics_render_social_links($social_links, 'ul-footer-socials'); ?>
                </div>

                <div class="ul-footer-widget">
                    <h3 class="ul-footer-widget-title"><?php esc_html_e('Liên kết nhanh', 'haritics'); ?></h3>
                    <div class="ul-footer-widget-links">
                        <?php haritics_render_footer_menu('footer_menu_1', $footer_fallback_1); ?>
                    </div>
                </div>

                <div class="ul-footer-widget">
                    <h3 class="ul-footer-widget-title"><?php esc_html_e('Khám phá thêm', 'haritics'); ?></h3>
                    <div class="ul-footer-widget-links">
                        <?php haritics_render_footer_menu('footer_menu_2', $footer_fallback_2); ?>
                    </div>
                </div>

                <div class="ul-footer-widget">
                    <h3 class="ul-footer-widget-title"><?php esc_html_e('Liên hệ', 'haritics'); ?></h3>
                    <div class="ul-footer-widget-links">
                        <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $hotline)); ?>"><?php echo esc_html($hotline); ?></a>
                        <a href="mailto:<?php echo antispambot($email); ?>"><?php echo esc_html($email); ?></a>
                        <span class="haritics-footer-address"><?php echo esc_html($address); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ul-footer-bottom">
        <div class="ul-footer-container">
            <p class="mb-0"><?php echo esc_html($copyright); ?></p>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
