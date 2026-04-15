<?php
if (! defined('ABSPATH')) {
    exit;
}

get_header();

while (have_posts()) : the_post();
    $current_post_id = get_the_ID();
    $gallery = haritics_get_gallery_urls($current_post_id, '_gallery_urls');

    if ($gallery === [] && has_post_thumbnail()) {
        $gallery[] = get_the_post_thumbnail_url($current_post_id, 'large');
    }

    $summary = haritics_get_meta($current_post_id, '_summary', get_the_excerpt());
    $target_amount = haritics_get_meta($current_post_id, '_target_amount');
    $raised_amount = haritics_get_meta($current_post_id, '_raised_amount');
    $progress = haritics_progress_percent($raised_amount, $target_amount);
    $leader_text = haritics_get_project_leader($current_post_id);
    $leader_list_url = haritics_get_meta($current_post_id, '_leader_list_url');
    $donor_text = haritics_get_project_donor($current_post_id);
    $donor_list_url = haritics_get_meta($current_post_id, '_donor_list_url');
    $location = haritics_get_meta($current_post_id, '_location');
    $accounting_public_url = haritics_get_meta($current_post_id, '_accounting_public_url');
    $related_issues = haritics_get_meta($current_post_id, '_related_issues');
    $status = haritics_get_meta($current_post_id, '_status', __('Đang cập nhật', 'haritics'));

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

    $detail_sections = [
        [
            'id' => 'tong-quan-du-an',
            'title' => __('Tổng quan dự án', 'haritics'),
            'content' => $summary !== '' ? wpautop(esc_html($summary)) : '<p>' . esc_html__('Chưa cập nhật', 'haritics') . '</p>',
        ],
        [
            'id' => 'so-von',
            'title' => __('Số vốn', 'haritics'),
            'content' => '<p>' . esc_html($target_amount !== '' ? haritics_format_money($target_amount) : __('Chưa cập nhật', 'haritics')) . '</p>',
        ],
        [
            'id' => 'lanh-dao',
            'title' => __('Lãnh đạo', 'haritics'),
            'content' => '<p>' . esc_html($leader_text !== '' ? $leader_text : __('Chưa cập nhật', 'haritics')) . '</p>',
            'actions' => $leader_list_url !== '' ? [[
                'label' => __('Xem danh sách', 'haritics'),
                'url' => $leader_list_url,
            ]] : [],
        ],
        [
            'id' => 'nha-hao-tam',
            'title' => __('Nhà hảo tâm', 'haritics'),
            'content' => '<p>' . esc_html($donor_text !== '' ? $donor_text : __('Chưa cập nhật', 'haritics')) . '</p>',
            'actions' => $donor_list_url !== '' ? [[
                'label' => __('Xem danh sách', 'haritics'),
                'url' => $donor_list_url,
            ]] : [],
        ],
        [
            'id' => 'tien-do-hoan-thanh',
            'title' => __('Tiến độ hoàn thành', 'haritics'),
            'content' => '',
            'progress' => true,
        ],
        [
            'id' => 'dia-diem',
            'title' => __('Địa điểm', 'haritics'),
            'content' => '<p>' . esc_html($location !== '' ? $location : __('Chưa cập nhật', 'haritics')) . '</p>',
        ],
        [
            'id' => 'quyet-toan-ke-toan',
            'title' => __('Quyết toán kế toán công khai', 'haritics'),
            'content' => '<p>' . esc_html($accounting_public_url !== '' ? __('Xem bài viết tổng hợp hình ảnh chứng từ, mua bán và ký kết.', 'haritics') : __('Chưa cập nhật', 'haritics')) . '</p>',
            'actions' => $accounting_public_url !== '' ? [[
                'label' => __('Xem bài viết', 'haritics'),
                'url' => $accounting_public_url,
            ]] : [],
        ],
        [
            'id' => 'van-de-lien-quan',
            'title' => __('Các vấn đề liên quan khác', 'haritics'),
            'content' => $related_issues !== '' ? wpautop(esc_html($related_issues)) : '<p>' . esc_html__('Chưa cập nhật', 'haritics') . '</p>',
        ],
    ];
    ?>
    <main class="overflow-hidden">
        <?php haritics_render_breadcrumb(__('Chi tiết dự án', 'haritics')); ?>
        <div class="ul-container ul-section-spacing">
            <div class="row gx-5 gy-4">
                <div class="col-lg-8">
                    <article class="haritics-project-article">
                        <?php if ($gallery !== []) : ?>
                            <div class="ul-project-details-img-slider swiper haritics-project-hero">
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
                        <?php endif; ?>

                        <header class="haritics-project-header">
                            <span class="haritics-project-status"><?php echo esc_html($status); ?></span>
                            <h1 class="ul-event-details-title"><?php the_title(); ?></h1>
                        </header>

                        <?php foreach ($detail_sections as $section) : ?>
                            <section class="haritics-project-section" id="<?php echo esc_attr($section['id']); ?>">
                                <div class="haritics-project-section-head">
                                    <h2 class="ul-event-details-inner-title"><?php echo esc_html($section['title']); ?></h2>
                                    <?php if (! empty($section['actions'])) : ?>
                                        <div class="haritics-project-section-actions">
                                            <?php foreach ($section['actions'] as $action) : ?>
                                                <a href="<?php echo esc_url($action['url']); ?>" class="ul-btn-condition"><?php echo esc_html($action['label']); ?></a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <?php if (! empty($section['progress'])) : ?>
                                    <div class="ul-donation-progress mb-3">
                                        <div class="donation-progress-container ul-progress-container">
                                            <div class="donation-progressbar ul-progressbar" data-ul-progress-value="<?php echo esc_attr((string) $progress); ?>">
                                                <div class="donation-progress-label ul-progress-label"></div>
                                            </div>
                                        </div>
                                        <div class="ul-donation-progress-labels">
                                            <span class="ul-donation-progress-label"><?php echo esc_html(sprintf(__('Đã hoàn thành %d%%', 'haritics'), $progress)); ?></span>
                                            <span class="ul-donation-progress-label"><?php echo esc_html(sprintf(__('Đã huy động %s', 'haritics'), haritics_format_money($raised_amount))); ?></span>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <div class="haritics-content"><?php echo wp_kses_post($section['content']); ?></div>
                                <?php endif; ?>
                            </section>
                        <?php endforeach; ?>

                        <section class="haritics-project-section" id="chi-tiet-noi-dung">
                            <div class="haritics-project-section-head">
                                <h2 class="ul-event-details-inner-title"><?php esc_html_e('Nội dung chi tiết dự án', 'haritics'); ?></h2>
                            </div>
                            <div class="haritics-content"><?php the_content(); ?></div>
                        </section>
                    </article>
                </div>

                <div class="col-lg-4">
                    <aside class="ul-project-details-infos haritics-project-sidebar">
                        <h4 class="ul-project-details-infos-title"><?php esc_html_e('Thông tin nhanh', 'haritics'); ?></h4>
                        <ul class="ul-project-details-infos-list">
                            <li><span class="key"><?php esc_html_e('TRẠNG THÁI', 'haritics'); ?></span>:<span class="value"><?php echo esc_html($status); ?></span></li>
                            <li><span class="key"><?php esc_html_e('SỐ VỐN', 'haritics'); ?></span>:<span class="value"><?php echo esc_html($target_amount !== '' ? haritics_format_money($target_amount) : __('Chưa cập nhật', 'haritics')); ?></span></li>
                            <li><span class="key"><?php esc_html_e('ĐÃ GÂY QUỸ', 'haritics'); ?></span>:<span class="value"><?php echo esc_html($raised_amount !== '' ? haritics_format_money($raised_amount) : __('Chưa cập nhật', 'haritics')); ?></span></li>
                            <li><span class="key"><?php esc_html_e('LÃNH ĐẠO', 'haritics'); ?></span>:<span class="value"><?php echo esc_html($leader_text !== '' ? $leader_text : __('Chưa cập nhật', 'haritics')); ?></span></li>
                            <li><span class="key"><?php esc_html_e('NHÀ HẢO TÂM', 'haritics'); ?></span>:<span class="value"><?php echo esc_html($donor_text !== '' ? $donor_text : __('Chưa cập nhật', 'haritics')); ?></span></li>
                            <li><span class="key"><?php esc_html_e('ĐỊA ĐIỂM', 'haritics'); ?></span>:<span class="value"><?php echo esc_html($location !== '' ? $location : __('Chưa cập nhật', 'haritics')); ?></span></li>
                        </ul>

                        <div class="haritics-project-sidebar-nav">
                            <h5><?php esc_html_e('Mục nội dung', 'haritics'); ?></h5>
                            <ul>
                                <?php foreach ($detail_sections as $section) : ?>
                                    <li><a href="#<?php echo esc_attr($section['id']); ?>"><?php echo esc_html($section['title']); ?></a></li>
                                <?php endforeach; ?>
                                <li><a href="#chi-tiet-noi-dung"><?php esc_html_e('Nội dung chi tiết dự án', 'haritics'); ?></a></li>
                            </ul>
                        </div>

                        <?php haritics_render_social_links(haritics_get_social_links_from_options(), 'ul-footer-socials ul-project-details-infos-shares'); ?>
                    </aside>
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
