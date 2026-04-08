<?php
if (! defined('ABSPATH')) {
    exit;
}

get_header();
while (have_posts()) : the_post();
    $current_post_id = get_the_ID();
    $progress = haritics_progress_percent(haritics_get_meta(get_the_ID(), '_raised_amount'), haritics_get_meta(get_the_ID(), '_target_amount'));
    $gallery = haritics_get_gallery_urls(get_the_ID(), '_gallery_urls');
    $external_url = haritics_get_meta(get_the_ID(), '_external_url', get_permalink());
    $donation_terms = wp_get_post_terms($current_post_id, 'donation_category', ['fields' => 'ids']);
    $related_donations_args = [
        'post_type' => 'donation',
        'posts_per_page' => 3,
        'post_status' => 'publish',
        'post__not_in' => [$current_post_id],
    ];
    if (is_array($donation_terms) && $donation_terms !== []) {
        $related_donations_args['tax_query'] = [[
            'taxonomy' => 'donation_category',
            'field' => 'term_id',
            'terms' => $donation_terms,
        ]];
    }
    $related_donations = get_posts($related_donations_args);
    ?>
    <main>
        <?php haritics_render_breadcrumb(__('Chi tiết chương trình', 'haritics')); ?>
        <div class="ul-container ul-section-spacing">
            <div class="row gx-0 gy-4 flex-column-reverse flex-lg-row">
                <div class="col-lg-4">
                    <div class="ul-inner-sidebar">
                        <div class="ul-inner-sidebar-widget categories">
                            <h3 class="ul-inner-sidebar-widget-title"><?php esc_html_e('Thông tin nhanh', 'haritics'); ?></h3>
                            <div class="ul-inner-sidebar-widget-content">
                                <div class="ul-inner-sidebar-categories">
                                    <a href="<?php echo esc_url(get_post_type_archive_link('donation') ?: haritics_route_url('donation')); ?>"><?php esc_html_e('Xem tất cả chương trình', 'haritics'); ?></a>
                                    <?php if (haritics_get_meta(get_the_ID(), '_deadline') !== '') : ?><a href="<?php the_permalink(); ?>"><?php echo esc_html__('Hạn đóng góp:', 'haritics') . ' ' . esc_html(date_i18n(get_option('date_format'), strtotime(haritics_get_meta(get_the_ID(), '_deadline')))); ?></a><?php endif; ?>
                                    <?php if (haritics_get_meta(get_the_ID(), '_location') !== '') : ?><a href="<?php the_permalink(); ?>"><?php echo esc_html__('Địa điểm:', 'haritics') . ' ' . esc_html(haritics_get_meta(get_the_ID(), '_location')); ?></a><?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="ul-donation-details">
                        <div class="ul-donation-details-img"><?php if (has_post_thumbnail()) : the_post_thumbnail('large'); endif; ?></div>
                        <h2 class="ul-donation-details-raised"><?php echo esc_html(haritics_format_money(haritics_get_meta(get_the_ID(), '_raised_amount'))); ?> <span class="target"><?php echo esc_html(sprintf(__('trong tổng %s đã huy động', 'haritics'), haritics_format_money(haritics_get_meta(get_the_ID(), '_target_amount')))); ?></span></h2>
                        <div class="ul-donation-progress ul-donation-progress-2">
                            <div class="donation-progress-container ul-progress-container">
                                <div class="donation-progressbar ul-progressbar" data-ul-progress-value="<?php echo esc_attr((string) $progress); ?>">
                                    <div class="donation-progress-label ul-progress-label"></div>
                                </div>
                            </div>
                        </div>
                        <div class="haritics-content"><?php the_content(); ?></div>
                        <?php if ($gallery !== []) : ?>
                            <div class="row row-cols-md-2 row-cols-1 g-4 mb-4">
                                <?php foreach ($gallery as $image) : ?>
                                    <div class="col"><img src="<?php echo esc_url($image); ?>" alt="<?php the_title_attribute(); ?>"></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <a href="<?php echo esc_url($external_url); ?>" class="ul-btn"><i class="flaticon-fast-forward-double-right-arrows-symbol"></i> <?php esc_html_e('Ủng hộ ngay', 'haritics'); ?></a>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($related_donations !== []) : ?>
            <section class="ul-section-spacing overflow-hidden">
                <div class="ul-container">
                    <div class="ul-section-heading justify-content-center text-center">
                        <div>
                            <span class="ul-section-sub-title"><?php esc_html_e('Đồng hành cùng cộng đồng', 'haritics'); ?></span>
                            <h2 class="ul-section-title"><?php esc_html_e('Mạnh thường quân liên quan', 'haritics'); ?></h2>
                        </div>
                    </div>
                    <div class="row ul-bs-row row-cols-xl-3 row-cols-md-2 row-cols-1">
                        <?php foreach ($related_donations as $donation) : $related_progress = haritics_progress_percent(haritics_get_meta($donation->ID, '_raised_amount'), haritics_get_meta($donation->ID, '_target_amount')); ?>
                            <div class="col">
                                <article class="ul-donation ul-donation--inner">
                                    <div class="ul-donation-img">
                                        <?php echo get_the_post_thumbnail($donation->ID, 'large', ['alt' => get_the_title($donation)]); ?>
                                        <span class="tag"><?php echo esc_html(haritics_get_meta($donation->ID, '_badge', __('Ủng hộ', 'haritics'))); ?></span>
                                    </div>
                                    <div class="ul-donation-txt">
                                        <div class="ul-donation-progress">
                                            <div class="donation-progress-container ul-progress-container">
                                                <div class="donation-progressbar ul-progressbar" data-ul-progress-value="<?php echo esc_attr((string) $related_progress); ?>">
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
        <?php endif; ?>
    </main>
<?php endwhile; get_footer(); ?>
