<?php
if (! defined('ABSPATH')) {
    exit;
}

get_header();

$hero_badge = haritics_get_option('home_hero_badge', __('Chung tay vì cộng đồng', 'haritics'));
$hero_title = haritics_get_option('home_hero_title', __('Kết nối nguồn lực cho những dự án thiện nguyện thiết thực', 'haritics'));
$hero_description = haritics_get_option('home_hero_description', __('Nền tảng giúp kết nối nhà tổ chức, mạnh thường quân và các nguồn lực xã hội để cùng tạo ra những dự án minh bạch, bền vững và phù hợp với nhu cầu thực tế tại địa phương.', 'haritics'));
$hero_image = haritics_get_option('home_hero_image', haritics_theme_asset('assets/img/banner-img.png'));
$hero_cta_text = haritics_get_option('home_primary_cta_text', __('Ủng hộ ngay', 'haritics'));
$hero_cta_url = haritics_get_option('home_primary_cta_url', get_post_type_archive_link('donation') ?: haritics_route_url('donation'));
$stat_number = haritics_get_option('home_stat_number', '2M+');
$stat_label = haritics_get_option('home_stat_label', __('Mạnh thường quân đang đồng hành', 'haritics'));
$about_badge = haritics_get_option('home_about_badge', __('Về chúng tôi', 'haritics'));
$about_title = haritics_get_option('home_about_title', __('Lan tỏa tinh thần sẻ chia để cộng đồng cùng phát triển', 'haritics'));
$about_description = haritics_get_option('home_about_description', __('Thiện Nguyện hướng tới việc kết nối đúng người, đúng dự án và đúng nguồn lực. Chúng tôi mong muốn mỗi đóng góp đều được sử dụng hiệu quả, công khai và tạo ra giá trị lâu dài cho cộng đồng thụ hưởng.', 'haritics'));
$about_image = haritics_get_option('home_about_image', haritics_theme_asset('assets/img/about-img.png'));
$projects = haritics_get_home_posts('project', 4);
$donations = haritics_get_home_posts('donation', 4);
$team_members = haritics_get_home_posts('team', 4);
$events = haritics_get_home_posts('event', 3);
?>
<main class="overflow-hidden">
    <section class="ul-banner">
        <div class="ul-banner-container">
            <div class="row gy-4 row-cols-lg-2 row-cols-1 align-items-center flex-column-reverse flex-lg-row">
                <div class="col">
                    <div class="ul-banner-txt">
                        <span class="ul-banner-sub-title ul-section-sub-title"><?php echo esc_html($hero_badge); ?></span>
                        <h1 class="ul-banner-title"><?php echo esc_html($hero_title); ?></h1>
                        <p class="ul-banner-descr"><?php echo esc_html($hero_description); ?></p>
                        <div class="ul-banner-btns">
                            <a href="<?php echo esc_url($hero_cta_url); ?>" class="ul-btn"><i class="flaticon-fast-forward-double-right-arrows-symbol"></i> <?php echo esc_html($hero_cta_text); ?></a>
                            <div class="ul-banner-stat">
                                <div class="imgs"><span class="number"><?php echo esc_html($stat_number); ?></span></div>
                                <span class="txt"><?php echo esc_html($stat_label); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col align-self-start">
                    <div class="ul-banner-img">
                        <div class="img-wrapper">
                            <img src="<?php echo esc_url($hero_image); ?>" alt="<?php echo esc_attr($hero_title); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ul-about ul-section-spacing">
        <div class="ul-container">
            <div class="row row-cols-md-2 row-cols-1 align-items-center gy-4 ul-about-row">
                <div class="col">
                    <div class="ul-about-imgs">
                        <div class="img-wrapper"><img src="<?php echo esc_url($about_image); ?>" alt="<?php echo esc_attr($about_title); ?>"></div>
                    </div>
                </div>
                <div class="col">
                    <div class="ul-about-txt">
                        <span class="ul-section-sub-title ul-section-sub-title--2"><?php echo esc_html($about_badge); ?></span>
                        <h2 class="ul-section-title"><?php echo esc_html($about_title); ?></h2>
                        <p class="ul-section-descr"><?php echo esc_html($about_description); ?></p>
                        <div class="ul-about-bottom">
                            <a href="<?php echo esc_url(haritics_route_url('contact')); ?>" class="ul-btn"><i class="flaticon-fast-forward-double-right-arrows-symbol"></i> <?php esc_html_e('Liên hệ với chúng tôi', 'haritics'); ?></a>
                            <div class="ul-about-call">
                                <div class="icon"><i class="flaticon-telephone-call"></i></div>
                                <div class="txt">
                                    <span class="call-title"><?php esc_html_e('Liên hệ hỗ trợ', 'haritics'); ?></span>
                                    <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', haritics_get_option('hotline', '19001234'))); ?>"><?php echo esc_html(haritics_get_option('hotline', '1900 1234')); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ul-donations ul-section-spacing overflow-hidden">
        <div class="ul-container">
            <div class="ul-section-heading ul-donations-heading justify-content-between text-center">
                <div class="left">
                    <span class="ul-section-sub-title"><span class="txt"><?php esc_html_e('Dự án nổi bật', 'haritics'); ?></span></span>
                    <h2 class="ul-section-title"><?php echo esc_html(haritics_get_option('home_projects_heading', __('Những dự án đang triển khai', 'haritics'))); ?></h2>
                </div>
            </div>
            <div class="row ul-bs-row justify-content-center">
                <?php foreach ($projects as $index => $project) : ?>
                    <div class="col-lg-<?php echo $index % 2 === 0 ? '8' : '4'; ?> col-md-6 col-10 col-xxs-12">
                        <article class="ul-project <?php echo $index % 2 === 0 ? '' : 'ul-project--sm'; ?>">
                            <div class="ul-project-img">
                                <?php echo get_the_post_thumbnail($project->ID, 'large', ['alt' => get_the_title($project)]); ?>
                            </div>
                            <div class="ul-project-txt">
                                <div>
                                    <h3 class="ul-project-title"><a href="<?php echo esc_url(get_permalink($project)); ?>"><?php echo esc_html(get_the_title($project)); ?></a></h3>
                                    <p class="ul-project-descr"><?php echo esc_html(haritics_get_meta($project->ID, '_location', get_the_excerpt($project))); ?></p>
                                </div>
                                <a href="<?php echo esc_url(get_permalink($project)); ?>" class="ul-project-btn"><i class="flaticon-up-right-arrow"></i></a>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="ul-section-spacing overflow-hidden">
        <div class="ul-container">
            <div class="ul-section-heading justify-content-center text-center">
                <div>
                    <span class="ul-section-sub-title"><?php esc_html_e('Đóng góp cộng đồng', 'haritics'); ?></span>
                    <h2 class="ul-section-title"><?php echo esc_html(haritics_get_option('home_donations_heading', __('Những chương trình đang cần thêm nguồn lực cộng đồng', 'haritics'))); ?></h2>
                </div>
            </div>
            <div class="row ul-bs-row row-cols-xl-4 row-cols-md-2 row-cols-1">
                <?php foreach ($donations as $donation) : $progress = haritics_progress_percent(haritics_get_meta($donation->ID, '_raised_amount'), haritics_get_meta($donation->ID, '_target_amount')); ?>
                    <div class="col">
                        <article class="ul-donation ul-donation--inner">
                            <div class="ul-donation-img">
                                <?php echo get_the_post_thumbnail($donation->ID, 'large', ['alt' => get_the_title($donation)]); ?>
                                <span class="tag"><?php echo esc_html(haritics_get_meta($donation->ID, '_badge', __('Ủng hộ', 'haritics'))); ?></span>
                            </div>
                            <div class="ul-donation-txt">
                                <div class="ul-donation-progress">
                                    <div class="donation-progress-container ul-progress-container">
                                        <div class="donation-progressbar ul-progressbar" data-ul-progress-value="<?php echo esc_attr((string) $progress); ?>">
                                            <div class="donation-progress-label ul-progress-label"></div>
                                        </div>
                                    </div>
                                    <div class="ul-donation-progress-labels">
                                        <span class="ul-donation-progress-label"><?php echo esc_html__('Đã huy động:', 'haritics') . ' ' . esc_html(haritics_format_money(haritics_get_meta($donation->ID, '_raised_amount'))); ?></span>
                                        <span class="ul-donation-progress-label"><?php echo esc_html__('Mục tiêu:', 'haritics') . ' ' . esc_html(haritics_format_money(haritics_get_meta($donation->ID, '_target_amount'))); ?></span>
                                    </div>
                                </div>
                                <a href="<?php echo esc_url(get_permalink($donation)); ?>" class="ul-donation-title"><?php echo esc_html(get_the_title($donation)); ?></a>
                                <p class="ul-donation-descr"><?php echo esc_html(get_the_excerpt($donation)); ?></p>
                                <a href="<?php echo esc_url(get_permalink($donation)); ?>" class="ul-donation-btn"><?php esc_html_e('Ủng hộ ngay', 'haritics'); ?> <i class="flaticon-up-right-arrow"></i></a>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="ul-team ul-inner-team ul-section-spacing">
        <div class="ul-container">
            <div class="ul-section-heading justify-content-center text-center">
                <div>
                    <span class="ul-section-sub-title"><?php esc_html_e('Đội ngũ đồng hành', 'haritics'); ?></span>
                    <h2 class="ul-section-title"><?php echo esc_html(haritics_get_option('home_team_heading', __('Những cá nhân đang trực tiếp triển khai chương trình', 'haritics'))); ?></h2>
                </div>
            </div>
            <div class="row row-cols-md-4 row-cols-sm-2 row-cols-1 ul-team-row justify-content-center">
                <?php foreach ($team_members as $member) : ?>
                    <div class="col">
                        <article class="ul-team-member">
                            <div class="ul-team-member-img">
                                <?php echo get_the_post_thumbnail($member->ID, 'large', ['alt' => get_the_title($member)]); ?>
                                <?php haritics_render_social_links(haritics_get_post_social_links($member->ID), 'ul-team-member-socials'); ?>
                            </div>
                            <div class="ul-team-member-info">
                                <h3 class="ul-team-member-name"><a href="<?php echo esc_url(get_permalink($member)); ?>"><?php echo esc_html(get_the_title($member)); ?></a></h3>
                                <p class="ul-team-member-designation"><?php echo esc_html(haritics_get_meta($member->ID, '_role')); ?></p>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="ul-section-spacing">
        <div class="ul-container">
            <div class="ul-section-heading justify-content-center text-center">
                <div>
                    <span class="ul-section-sub-title"><?php esc_html_e('Hoạt động cộng đồng', 'haritics'); ?></span>
                    <h2 class="ul-section-title"><?php echo esc_html(haritics_get_option('home_events_heading', __('Các sự kiện và hoạt động sắp diễn ra', 'haritics'))); ?></h2>
                </div>
            </div>
            <div class="ul-events-wrapper">
                <div class="row ul-bs-row row-cols-lg-2 row-cols-1">
                    <?php foreach ($events as $event) : ?>
                        <div class="col">
                            <article class="ul-event ul-event--inner">
                                <div class="ul-event-img">
                                    <?php echo get_the_post_thumbnail($event->ID, 'large', ['alt' => get_the_title($event)]); ?>
                                    <span class="date"><?php echo esc_html(date_i18n('d', strtotime(haritics_get_meta($event->ID, '_event_date', get_the_date('Y-m-d', $event))))); ?> <span><?php echo esc_html(date_i18n('M', strtotime(haritics_get_meta($event->ID, '_event_date', get_the_date('Y-m-d', $event))))); ?></span></span>
                                </div>
                                <div class="ul-event-txt">
                                    <h3 class="ul-event-title"><a href="<?php echo esc_url(get_permalink($event)); ?>"><?php echo esc_html(get_the_title($event)); ?></a></h3>
                                    <p class="ul-event-descr"><?php echo esc_html(get_the_excerpt($event)); ?></p>
                                    <div class="ul-event-info">
                                        <span class="ul-event-info-title"><?php esc_html_e('Địa điểm', 'haritics'); ?></span>
                                        <p class="ul-event-info-descr"><?php echo esc_html(haritics_get_meta($event->ID, '_venue')); ?></p>
                                    </div>
                                    <a href="<?php echo esc_url(get_permalink($event)); ?>" class="ul-btn"><i class="flaticon-fast-forward-double-right-arrows-symbol"></i> <?php esc_html_e('Xem chi tiết', 'haritics'); ?></a>
                                </div>
                            </article>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>
