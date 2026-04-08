<?php
if (! defined('ABSPATH')) {
    exit;
}

get_header();
while (have_posts()) : the_post();
    $current_post_id = get_the_ID();
    $gallery = haritics_get_gallery_urls(get_the_ID(), '_gallery_urls');
    if ($gallery === [] && has_post_thumbnail()) {
        $gallery[] = get_the_post_thumbnail_url(get_the_ID(), 'large');
    }
    $progress = haritics_progress_percent(haritics_get_meta(get_the_ID(), '_raised_amount'), haritics_get_meta(get_the_ID(), '_target_amount'));
    $project_terms = wp_get_post_terms($current_post_id, 'project_category', ['fields' => 'ids']);
    $related_projects_args = [
        'post_type' => 'project',
        'posts_per_page' => 3,
        'post_status' => 'publish',
        'post__not_in' => [$current_post_id],
    ];
    if (is_array($project_terms) && $project_terms !== []) {
        $related_projects_args['tax_query'] = [[
            'taxonomy' => 'project_category',
            'field' => 'term_id',
            'terms' => $project_terms,
        ]];
    }
    $related_projects = get_posts($related_projects_args);
    ?>
    <main class="overflow-hidden">
        <?php haritics_render_breadcrumb(__('Chi tiết dự án', 'haritics')); ?>
        <div class="ul-container ul-section-spacing">
            <div class="ul-project-details-img-slider swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($gallery as $image) : ?>
                        <div class="swiper-slide"><div><img src="<?php echo esc_url($image); ?>" alt="<?php the_title_attribute(); ?>"></div></div>
                    <?php endforeach; ?>
                </div>
                <div class="ul-project-details-slider-nav ul-slider-nav">
                    <button class="prev"><i class="flaticon-back"></i></button>
                    <button class="next"><i class="flaticon-next"></i></button>
                </div>
            </div>
            <div class="row gx-5 gy-4 flex-column-reverse flex-lg-row">
                <div class="col-md-8">
                    <div class="ul-event-details">
                        <h2 class="ul-event-details-title"><?php the_title(); ?></h2>
                        <p class="ul-event-details-descr"><?php echo esc_html(haritics_get_meta(get_the_ID(), '_summary', get_the_excerpt())); ?></p>
                        <h3 class="ul-event-details-inner-title"><?php esc_html_e('Tổng quan dự án', 'haritics'); ?></h3>
                        <div class="haritics-content"><?php the_content(); ?></div>
                        <h3 class="ul-event-details-inner-title"><?php esc_html_e('Tiến độ hoàn thành', 'haritics'); ?></h3>
                        <div class="ul-donation-progress mb-4">
                            <div class="donation-progress-container ul-progress-container">
                                <div class="donation-progressbar ul-progressbar" data-ul-progress-value="<?php echo esc_attr((string) $progress); ?>">
                                    <div class="donation-progress-label ul-progress-label"></div>
                                </div>
                            </div>
                            <div class="ul-donation-progress-labels">
                                <span class="ul-donation-progress-label"><?php echo esc_html(sprintf(__('Đã hoàn thành %d%%', 'haritics'), $progress)); ?></span>
                                <span class="ul-donation-progress-label"><?php echo esc_html(sprintf(__('Còn lại %d%%', 'haritics'), max(0, 100 - $progress))); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="ul-project-details-infos">
                        <h4 class="ul-project-details-infos-title"><?php esc_html_e('Thông tin nhanh', 'haritics'); ?></h4>
                        <ul class="ul-project-details-infos-list">
                            <li><span class="key"><?php esc_html_e('TRẠNG THÁI', 'haritics'); ?></span>:<span class="value"><?php echo esc_html(haritics_get_meta(get_the_ID(), '_status', __('Đang triển khai', 'haritics'))); ?></span></li>
                            <li><span class="key"><?php esc_html_e('SỐ VỐN', 'haritics'); ?></span>:<span class="value"><?php echo esc_html(haritics_format_money(haritics_get_meta(get_the_ID(), '_target_amount'))); ?></span></li>
                            <li><span class="key"><?php esc_html_e('ĐÃ GÂY QUỸ', 'haritics'); ?></span>:<span class="value"><?php echo esc_html(haritics_format_money(haritics_get_meta(get_the_ID(), '_raised_amount'))); ?></span></li>
                            <li><span class="key"><?php esc_html_e('LÃNH ĐẠO', 'haritics'); ?></span>:<span class="value"><?php echo esc_html(haritics_get_meta(get_the_ID(), '_leader_text', __('Đang cập nhật', 'haritics'))); ?></span></li>
                            <li><span class="key"><?php esc_html_e('NHÀ HẢO TÂM', 'haritics'); ?></span>:<span class="value"><?php echo esc_html(haritics_get_meta(get_the_ID(), '_donor_text', __('Đang cập nhật', 'haritics'))); ?></span></li>
                            <li><span class="key"><?php esc_html_e('ĐỊA ĐIỂM', 'haritics'); ?></span>:<span class="value"><?php echo esc_html(haritics_get_meta(get_the_ID(), '_location')); ?></span></li>
                        </ul>
                        <?php haritics_render_social_links(haritics_get_social_links_from_options(), 'ul-footer-socials ul-project-details-infos-shares'); ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($related_projects !== []) : ?>
            <section class="ul-projects ul-section-spacing">
                <div class="ul-container">
                    <div class="ul-section-heading justify-content-center text-center">
                        <div>
                            <span class="ul-section-sub-title"><?php esc_html_e('Khám phá thêm', 'haritics'); ?></span>
                            <h2 class="ul-section-title"><?php esc_html_e('Dự án liên quan', 'haritics'); ?></h2>
                        </div>
                    </div>
                    <div class="row ul-bs-row row-cols-lg-3 row-cols-md-2 row-cols-1 justify-content-center">
                        <?php foreach ($related_projects as $project) : ?>
                            <div class="col">
                                <article class="ul-project ul-project--sm">
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
        <?php endif; ?>
    </main>
<?php endwhile; get_footer(); ?>
