<?php
if (! defined('ABSPATH')) {
    exit;
}

get_header();

$search_filters = haritics_get_search_filters();
?>
<main>
    <?php haritics_render_breadcrumb(__('Kết quả tìm kiếm', 'haritics')); ?>

    <!-- Search Filter Section -->
    <section class="ul-section-spacing pb-0">
        <div class="ul-container">
            <div class="ul-search-filter-box">
                <form action="<?php echo esc_url(home_url('/')); ?>" method="get" class="ul-search-filter-form">
                    <div class="row g-3">
                        <div class="col-md-6 col-12">
                            <label class="d-block mb-2" for="filter-s"><?php esc_html_e('Từ khóa', 'haritics'); ?></label>
                            <input type="text" name="s" id="filter-s" placeholder="<?php esc_attr_e('Nhập từ khóa tìm kiếm...', 'haritics'); ?>" value="<?php echo esc_attr($search_filters['s']); ?>">
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="d-block mb-2" for="filter-location"><?php esc_html_e('Địa điểm', 'haritics'); ?></label>
                            <input type="text" name="location" id="filter-location" placeholder="<?php esc_attr_e('Ví dụ: Hà Giang, Sơn La...', 'haritics'); ?>" value="<?php echo esc_attr($search_filters['location']); ?>">
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="d-block mb-2" for="filter-organizer"><?php esc_html_e('Nhà tổ chức', 'haritics'); ?></label>
                            <input type="text" name="organizer" id="filter-organizer" placeholder="<?php esc_attr_e('Nhập tên nhà tổ chức...', 'haritics'); ?>" value="<?php echo esc_attr($search_filters['organizer']); ?>">
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="d-block mb-2" for="filter-donor"><?php esc_html_e('Mạnh thường quân', 'haritics'); ?></label>
                            <input type="text" name="donor" id="filter-donor" placeholder="<?php esc_attr_e('Nhập tên mạnh thường quân...', 'haritics'); ?>" value="<?php echo esc_attr($search_filters['donor']); ?>">
                        </div>
                    </div>
                    <div class="ul-search-filter-actions">
                        <button type="submit" class="ul-btn"><i class="flaticon-search"></i> <?php esc_html_e('Tìm kiếm', 'haritics'); ?></button>
                        <?php if (haritics_has_active_search_filters()) : ?>
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="ul-btn ul-btn-secondary"><i class="flaticon-close"></i> <?php esc_html_e('Xóa lọc', 'haritics'); ?></a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="ul-section-spacing">
        <div class="ul-container">
            <div class="ul-section-heading justify-content-center text-center">
                <div>
                    <span class="ul-section-sub-title"><?php esc_html_e('Kết quả tìm kiếm', 'haritics'); ?></span>
                    <h2 class="ul-section-title"><?php echo esc_html(haritics_get_search_result_label()); ?></h2>
                </div>
            </div>

            <?php if (have_posts()) : ?>
                <div class="row gy-4">
                    <?php while (have_posts()) : the_post();
                        $post_type = get_post_type();
                        $event_date = haritics_get_meta(get_the_ID(), '_event_date', get_the_date('Y-m-d'));
                        ?>
                        <div class="col-12">
                            <?php if ($post_type === 'donation') : ?>
                                <!-- Donation Card -->
                                <article class="ul-donation">
                                    <div class="ul-donation-img">
                                        <?php if (has_post_thumbnail()) : the_post_thumbnail('large', ['alt' => get_the_title()]); endif; ?>
                                        <span class="tag"><?php echo esc_html(haritics_get_meta(get_the_ID(), '_badge', __('Ủng hộ', 'haritics'))); ?></span>
                                    </div>
                                    <div class="ul-donation-txt">
                                        <?php
                                        $target = haritics_get_meta(get_the_ID(), '_target_amount', '0');
                                        $raised = haritics_get_meta(get_the_ID(), '_raised_amount', '0');
                                        $progress = haritics_progress_percent($raised, $target);
                                        ?>
                                        <div class="ul-donation-progress">
                                            <div class="donation-progress-container ul-progress-container">
                                                <div class="donation-progressbar ul-progressbar" data-ul-progress-value="<?php echo esc_attr((string) $progress); ?>">
                                                    <div class="donation-progress-label ul-progress-label"></div>
                                                </div>
                                            </div>
                                            <div class="ul-donation-progress-labels">
                                                <span class="ul-donation-progress-label"><?php echo esc_html__('Đã huy động:', 'haritics') . ' ' . esc_html(haritics_format_money($raised)); ?></span>
                                                <span class="ul-donation-progress-label"><?php echo esc_html__('Mục tiêu:', 'haritics') . ' ' . esc_html(haritics_format_money($target)); ?></span>
                                            </div>
                                        </div>
                                        <a href="<?php the_permalink(); ?>" class="ul-donation-title"><?php the_title(); ?></a>
                                        <p class="ul-donation-descr"><?php echo esc_html(get_the_excerpt()); ?></p>
                                        <a href="<?php the_permalink(); ?>" class="ul-donation-btn"><?php esc_html_e('Ủng hộ ngay', 'haritics'); ?> <i class="flaticon-up-right-arrow"></i></a>
                                    </div>
                                </article>
                            <?php elseif ($post_type === 'team') : ?>
                                <!-- Team Member Card -->
                                <article class="ul-team-member-card">
                                    <div class="ul-team-member-card-img">
                                        <?php if (has_post_thumbnail()) : the_post_thumbnail('medium', ['alt' => get_the_title()]); endif; ?>
                                    </div>
                                    <div class="ul-team-member-card-info">
                                        <span class="ul-section-sub-title"><?php echo esc_html(get_post_type_object('team')->labels->singular_name ?? 'Đội ngũ'); ?></span>
                                        <h3 class="ul-team-member-card-name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                        <p class="ul-team-member-card-role"><?php echo esc_html(haritics_get_meta(get_the_ID(), '_role', '')); ?></p>
                                        <p class="ul-team-member-card-descr"><?php echo esc_html(get_the_excerpt()); ?></p>
                                        <a href="<?php the_permalink(); ?>" class="ul-btn"><?php esc_html_e('Xem chi tiết', 'haritics'); ?> <i class="flaticon-up-right-arrow"></i></a>
                                    </div>
                                </article>
                            <?php elseif ($post_type === 'event') : ?>
                                <!-- Event Card -->
                                <article class="ul-event">
                                    <div class="ul-event-img">
                                        <?php if (has_post_thumbnail()) : the_post_thumbnail('large', ['alt' => get_the_title()]); endif; ?>
                                        <span class="date"><?php echo esc_html(date_i18n('d', strtotime($event_date))); ?> <span><?php echo esc_html(date_i18n('M', strtotime($event_date))); ?></span></span>
                                    </div>
                                    <div class="ul-event-txt">
                                        <h3 class="ul-event-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                        <p class="ul-event-descr"><?php echo esc_html(get_the_excerpt()); ?></p>
                                        <div class="ul-event-info">
                                            <span class="ul-event-info-title"><?php esc_html_e('Địa điểm', 'haritics'); ?></span>
                                            <p class="ul-event-info-descr"><?php echo esc_html(haritics_get_meta(get_the_ID(), '_venue', '')); ?></p>
                                        </div>
                                        <a href="<?php the_permalink(); ?>" class="ul-btn"><i class="flaticon-fast-forward-double-right-arrows-symbol"></i> <?php esc_html_e('Xem chi tiết', 'haritics'); ?></a>
                                    </div>
                                </article>
                            <?php else : ?>
                                <!-- Project Card (default) -->
                                <article class="ul-project ul-project--sm">
                                    <div class="ul-project-img">
                                        <?php if (has_post_thumbnail()) : the_post_thumbnail('large', ['alt' => get_the_title()]); endif; ?>
                                    </div>
                                    <div class="ul-project-txt">
                                        <div>
                                            <span class="ul-section-sub-title"><?php echo esc_html(get_post_type_object(get_post_type())->labels->singular_name ?? get_post_type()); ?></span>
                                            <h3 class="ul-project-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                            <p class="ul-project-descr"><?php echo esc_html(get_the_excerpt() ?: haritics_get_meta(get_the_ID(), '_location', haritics_get_meta(get_the_ID(), '_venue', ''))); ?></p>
                                        </div>
                                        <a href="<?php the_permalink(); ?>" class="ul-project-btn"><i class="flaticon-up-right-arrow"></i></a>
                                    </div>
                                </article>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                </div>
                <?php haritics_render_pagination(); ?>
            <?php else : ?>
                <div class="text-center">
                    <p class="ul-section-descr"><?php esc_html_e('Chưa tìm thấy kết quả phù hợp với bộ lọc hiện tại.', 'haritics'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php get_footer(); ?>
