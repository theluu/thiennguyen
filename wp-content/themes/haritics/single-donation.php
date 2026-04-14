<?php
if (! defined('ABSPATH')) {
    exit;
}

get_header();

while (have_posts()) : the_post();
    $current_post_id = get_the_ID();
    $gallery = haritics_get_gallery_urls($current_post_id, '_gallery_urls');
    $donation_terms = wp_get_post_terms($current_post_id, 'donation_category', ['fields' => 'ids']);
    $related_donations_args = [
        'post_type' => 'donation',
        'posts_per_page' => 4,
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

    $info_groups = [
        __('Thông tin cơ bản', 'haritics') => [
            __('Loại', 'haritics') => haritics_get_meta($current_post_id, '_donor_type'),
            __('Ngày sinh', 'haritics') => haritics_get_meta($current_post_id, '_birth_date'),
            __('Mã số định danh', 'haritics') => haritics_get_meta($current_post_id, '_identifier_code'),
        ],
        __('Thông tin liên hệ', 'haritics') => [
            __('Số điện thoại', 'haritics') => haritics_get_meta($current_post_id, '_phone'),
            __('Email', 'haritics') => haritics_get_meta($current_post_id, '_email'),
            __('Địa chỉ', 'haritics') => haritics_get_meta($current_post_id, '_address'),
            __('Kênh liên lạc ưu tiên', 'haritics') => haritics_get_meta($current_post_id, '_preferred_contact'),
        ],
        __('Thông tin đóng góp', 'haritics') => [
            __('Loại đóng góp', 'haritics') => haritics_get_meta($current_post_id, '_contribution_type'),
            __('Số tiền / Giá trị quy đổi', 'haritics') => haritics_get_meta($current_post_id, '_contribution_value') !== '' ? haritics_format_money(haritics_get_meta($current_post_id, '_contribution_value')) : '',
            __('Ngày đóng góp', 'haritics') => haritics_get_meta($current_post_id, '_contribution_date'),
            __('Hình thức', 'haritics') => haritics_get_meta($current_post_id, '_contribution_method'),
            __('Chiến dịch / chương trình liên quan', 'haritics') => haritics_get_meta($current_post_id, '_campaign_related'),
        ],
        __('Lịch sử & hành vi', 'haritics') => [
            __('Lịch sử các lần đóng góp', 'haritics') => haritics_get_meta($current_post_id, '_donation_history'),
            __('Tổng giá trị đã đóng góp', 'haritics') => haritics_get_meta($current_post_id, '_total_contributed') !== '' ? haritics_format_money(haritics_get_meta($current_post_id, '_total_contributed')) : '',
            __('Tần suất đóng góp', 'haritics') => haritics_get_meta($current_post_id, '_contribution_frequency'),
            __('Chiến dịch quan tâm', 'haritics') => haritics_get_meta($current_post_id, '_campaign_interest'),
        ],
        __('Ghi chú & cá nhân hóa', 'haritics') => [
            __('Ghi chú riêng', 'haritics') => haritics_get_meta($current_post_id, '_private_notes'),
            __('Ẩn danh', 'haritics') => haritics_get_meta($current_post_id, '_is_anonymous') === '1' ? __('Có', 'haritics') : __('Không', 'haritics'),
            __('Đồng ý nhận thông tin', 'haritics') => haritics_get_meta($current_post_id, '_marketing_opt_in') === '1' ? __('Có', 'haritics') : __('Không', 'haritics'),
        ],
    ];
    ?>
    <main>
        <?php haritics_render_breadcrumb(__('Thông tin mạnh thường quân', 'haritics')); ?>
        <div class="ul-container ul-section-spacing">
            <div class="row gx-4 gy-4 flex-column-reverse flex-lg-row">
                <div class="col-lg-4">
                    <aside class="ul-project-details-infos haritics-project-sidebar">
                        <h4 class="ul-project-details-infos-title"><?php esc_html_e('Tóm tắt hồ sơ', 'haritics'); ?></h4>
                        <ul class="ul-project-details-infos-list">
                            <li><span class="key"><?php esc_html_e('LOẠI', 'haritics'); ?></span>:<span class="value"><?php echo esc_html(haritics_get_meta($current_post_id, '_donor_type', __('Chưa cập nhật', 'haritics'))); ?></span></li>
                            <li><span class="key"><?php esc_html_e('LIÊN HỆ', 'haritics'); ?></span>:<span class="value"><?php echo esc_html(haritics_get_meta($current_post_id, '_preferred_contact', __('Chưa cập nhật', 'haritics'))); ?></span></li>
                            <li><span class="key"><?php esc_html_e('ĐÓNG GÓP', 'haritics'); ?></span>:<span class="value"><?php echo esc_html(haritics_get_meta($current_post_id, '_contribution_type', __('Chưa cập nhật', 'haritics'))); ?></span></li>
                            <li><span class="key"><?php esc_html_e('GIÁ TRỊ', 'haritics'); ?></span>:<span class="value"><?php echo esc_html(haritics_get_meta($current_post_id, '_contribution_value') !== '' ? haritics_format_money(haritics_get_meta($current_post_id, '_contribution_value')) : __('Chưa cập nhật', 'haritics')); ?></span></li>
                            <li><span class="key"><?php esc_html_e('CHIẾN DỊCH', 'haritics'); ?></span>:<span class="value"><?php echo esc_html(haritics_get_meta($current_post_id, '_campaign_related', __('Chưa cập nhật', 'haritics'))); ?></span></li>
                        </ul>
                    </aside>
                </div>
                <div class="col-lg-8">
                    <article class="haritics-project-article">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="ul-donation-details-img"><?php the_post_thumbnail('large'); ?></div>
                        <?php endif; ?>
                        <div class="haritics-project-header">
                            <span class="haritics-project-status"><?php echo esc_html(haritics_get_meta($current_post_id, '_donor_type', __('Mạnh thường quân', 'haritics'))); ?></span>
                            <h1 class="ul-event-details-title"><?php the_title(); ?></h1>
                            <?php if (get_the_excerpt() !== '') : ?>
                                <p class="ul-event-details-descr"><?php echo esc_html(get_the_excerpt()); ?></p>
                            <?php endif; ?>
                        </div>

                        <?php foreach ($info_groups as $group_title => $items) : ?>
                            <section class="haritics-project-section">
                                <div class="haritics-project-section-head">
                                    <h2 class="ul-event-details-inner-title"><?php echo esc_html($group_title); ?></h2>
                                </div>
                                <div class="haritics-donor-profile-grid">
                                    <?php foreach ($items as $label => $value) : ?>
                                        <div class="haritics-donor-profile-item">
                                            <span class="haritics-project-detail-label"><?php echo esc_html($label); ?></span>
                                            <div class="haritics-content">
                                                <p><?php echo esc_html($value !== '' ? $value : __('Chưa cập nhật', 'haritics')); ?></p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </section>
                        <?php endforeach; ?>

                        <?php if ($gallery !== []) : ?>
                            <section class="haritics-project-section">
                                <div class="haritics-project-section-head">
                                    <h2 class="ul-event-details-inner-title"><?php esc_html_e('Hồ sơ minh họa', 'haritics'); ?></h2>
                                </div>
                                <div class="row row-cols-md-2 row-cols-1 g-4">
                                    <?php foreach ($gallery as $image) : ?>
                                        <div class="col"><img src="<?php echo esc_url($image); ?>" alt="<?php the_title_attribute(); ?>"></div>
                                    <?php endforeach; ?>
                                </div>
                            </section>
                        <?php endif; ?>

                        <section class="haritics-project-section">
                            <div class="haritics-project-section-head">
                                <h2 class="ul-event-details-inner-title"><?php esc_html_e('Nội dung chi tiết', 'haritics'); ?></h2>
                            </div>
                            <div class="haritics-content"><?php the_content(); ?></div>
                        </section>
                    </article>
                </div>
            </div>
        </div>

        <?php if ($related_donations !== []) : ?>
            <section class="ul-team ul-inner-team ul-section-spacing">
                <div class="ul-container">
                    <div class="ul-section-heading justify-content-center text-center">
                        <div>
                            <h2 class="ul-section-title"><?php esc_html_e('Mạnh thường quân liên quan', 'haritics'); ?></h2>
                        </div>
                    </div>
                    <div class="row row-cols-md-4 row-cols-sm-3 row-cols-2 row-cols-xxs-1 ul-team-row justify-content-center">
                        <?php foreach ($related_donations as $donation) : ?>
                            <div class="col">
                                <article class="ul-team-member haritics-donor-member">
                                    <div class="ul-team-member-img">
                                        <?php echo get_the_post_thumbnail($donation->ID, 'large', ['alt' => get_the_title($donation)]); ?>
                                    </div>
                                    <div class="ul-team-member-info">
                                        <h3 class="ul-team-member-name"><a href="<?php echo esc_url(get_permalink($donation)); ?>"><?php echo esc_html(get_the_title($donation)); ?></a></h3>
                                        <p class="ul-team-member-designation"><?php echo esc_html(haritics_get_meta($donation->ID, '_donor_type', __('Mạnh thường quân', 'haritics'))); ?></p>
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
