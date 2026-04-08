<?php
if (! defined('ABSPATH')) {
    exit;
}

get_header();
while (have_posts()) : the_post();
    $current_post_id = get_the_ID();
    $gallery = haritics_get_gallery_urls(get_the_ID(), '_gallery_urls');
    $event_terms = wp_get_post_terms($current_post_id, 'event_category', ['fields' => 'ids']);
    $related_events_args = [
        'post_type' => 'event',
        'posts_per_page' => 2,
        'post_status' => 'publish',
        'post__not_in' => [$current_post_id],
    ];
    if (is_array($event_terms) && $event_terms !== []) {
        $related_events_args['tax_query'] = [[
            'taxonomy' => 'event_category',
            'field' => 'term_id',
            'terms' => $event_terms,
        ]];
    }
    $related_events = get_posts($related_events_args);
    ?>
    <main>
        <?php haritics_render_breadcrumb(__('Chi tiết sự kiện', 'haritics')); ?>
        <div class="ul-container ul-section-spacing">
            <div class="row gx-0 gy-4 flex-column-reverse flex-lg-row">
                <div class="col-lg-4">
                    <div class="ul-inner-sidebar">
                        <div class="ul-inner-sidebar-widget categories">
                            <h3 class="ul-inner-sidebar-widget-title"><?php esc_html_e('Thông tin sự kiện', 'haritics'); ?></h3>
                            <div class="ul-inner-sidebar-widget-content">
                                <div class="ul-inner-sidebar-categories">
                                    <a href="<?php the_permalink(); ?>"><?php echo esc_html__('Ngày diễn ra:', 'haritics') . ' ' . esc_html(date_i18n(get_option('date_format'), strtotime(haritics_get_meta(get_the_ID(), '_event_date', get_the_date('Y-m-d'))))); ?></a>
                                    <?php if (haritics_get_meta(get_the_ID(), '_venue') !== '') : ?><a href="<?php the_permalink(); ?>"><?php echo esc_html__('Địa điểm:', 'haritics') . ' ' . esc_html(haritics_get_meta(get_the_ID(), '_venue')); ?></a><?php endif; ?>
                                    <?php if (haritics_get_meta(get_the_ID(), '_organizer') !== '') : ?><a href="<?php the_permalink(); ?>"><?php echo esc_html__('Đơn vị tổ chức:', 'haritics') . ' ' . esc_html(haritics_get_meta(get_the_ID(), '_organizer')); ?></a><?php endif; ?>
                                    <?php if (haritics_get_meta(get_the_ID(), '_register_url') !== '') : ?><a href="<?php echo esc_url(haritics_get_meta(get_the_ID(), '_register_url')); ?>"><?php esc_html_e('Đăng ký tham gia', 'haritics'); ?></a><?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="ul-event-details ul-donation-details">
                        <div class="ul-event-details-img"><?php if (has_post_thumbnail()) : the_post_thumbnail('large'); endif; ?></div>
                        <div class="ul-event-details-infos">
                            <div class="ul-event-details-info"><span class="icon"><i class="flaticon-calendar"></i></span><span class="text"><?php echo esc_html(date_i18n(get_option('date_format'), strtotime(haritics_get_meta(get_the_ID(), '_event_date', get_the_date('Y-m-d'))))); ?></span></div>
                            <?php if (haritics_get_meta(get_the_ID(), '_venue') !== '') : ?><div class="ul-event-details-info"><span class="icon"><i class="flaticon-pin"></i></span><span class="text"><?php echo esc_html(haritics_get_meta(get_the_ID(), '_venue')); ?></span></div><?php endif; ?>
                        </div>
                        <h2 class="ul-event-details-title"><?php the_title(); ?></h2>
                        <div class="haritics-content"><?php the_content(); ?></div>
                        <?php if ($gallery !== []) : ?>
                            <div class="row row-cols-md-2 row-cols-1 g-4 mb-4">
                                <?php foreach ($gallery as $image) : ?>
                                    <div class="col"><img src="<?php echo esc_url($image); ?>" alt="<?php the_title_attribute(); ?>"></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($related_events !== []) : ?>
            <section class="ul-section-spacing">
                <div class="ul-container">
                    <div class="ul-section-heading justify-content-center text-center">
                        <div>
                            <span class="ul-section-sub-title"><?php esc_html_e('Hoạt động cộng đồng', 'haritics'); ?></span>
                            <h2 class="ul-section-title"><?php esc_html_e('Sự kiện liên quan', 'haritics'); ?></h2>
                        </div>
                    </div>
                    <div class="ul-events-wrapper">
                        <div class="row ul-bs-row row-cols-lg-2 row-cols-1">
                            <?php foreach ($related_events as $event) : ?>
                                <div class="col">
                                    <article class="ul-event ul-event--inner">
                                        <div class="ul-event-img">
                                            <?php echo get_the_post_thumbnail($event->ID, 'large', ['alt' => get_the_title($event)]); ?>
                                            <?php $event_date = haritics_get_meta($event->ID, '_event_date', get_the_date('Y-m-d', $event)); ?>
                                            <span class="date"><?php echo esc_html(date_i18n('d', strtotime($event_date))); ?> <span><?php echo esc_html(date_i18n('M', strtotime($event_date))); ?></span></span>
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
        <?php endif; ?>
    </main>
<?php endwhile; get_footer(); ?>
