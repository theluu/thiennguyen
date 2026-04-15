<?php
if (! defined('ABSPATH')) {
    exit;
}

get_header();

$search_filters = haritics_get_search_filters();
$project_groups = haritics_get_project_status_groups();
$has_results = false;
?>
<main>
    <?php haritics_render_breadcrumb(__('Dự án', 'haritics')); ?>

    <section class="ul-section-spacing pb-0">
        <div class="ul-container">
            <div class="ul-search-filter-box">
                <form action="<?php echo esc_url(get_post_type_archive_link('project') ?: haritics_route_url('project')); ?>" method="get" class="ul-search-filter-form">
                    <div class="row g-3">
                        <div class="col-md-6 col-12">
                            <label class="d-block mb-2" for="filter-s"><?php esc_html_e('Tên dự án', 'haritics'); ?></label>
                            <input type="text" name="s" id="filter-s" placeholder="<?php esc_attr_e('Nhập tên dự án...', 'haritics'); ?>" value="<?php echo esc_attr($search_filters['s']); ?>">
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="d-block mb-2" for="filter-location"><?php esc_html_e('Địa điểm', 'haritics'); ?></label>
                            <input type="text" name="location" id="filter-location" placeholder="<?php esc_attr_e('Ví dụ: Hà Giang, Sơn La...', 'haritics'); ?>" value="<?php echo esc_attr($search_filters['location']); ?>">
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="d-block mb-2" for="filter-organizer"><?php esc_html_e('Nhà tổ chức / Lãnh đạo', 'haritics'); ?></label>
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
                            <a href="<?php echo esc_url(get_post_type_archive_link('project') ?: haritics_route_url('project')); ?>" class="ul-btn ul-btn-secondary"><i class="flaticon-close"></i> <?php esc_html_e('Xóa lọc', 'haritics'); ?></a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="ul-projects ul-section-spacing">
        <div class="ul-container">
            <?php if (haritics_has_active_search_filters()) : ?>
                <div class="ul-search-result-info mb-5">
                    <span><?php esc_html_e('Kết quả tìm kiếm cho:', 'haritics'); ?></span>
                    <strong><?php echo esc_html(haritics_get_search_result_label()); ?></strong>
                </div>
            <?php endif; ?>

            <?php foreach ($project_groups as $group) : ?>
                <?php $projects = haritics_get_projects_for_archive($group, $search_filters); ?>
                <?php if ($projects === []) : ?>
                    <?php continue; ?>
                <?php endif; ?>
                <?php $has_results = true; ?>

                <div class="haritics-archive-group"<?php echo $group['key'] === 'calling' ? ' id="du-an-dang-huy-dong"' : ''; ?>>
                    <div class="ul-section-heading justify-content-between text-center text-md-start">
                        <div class="left">
                            <span class="ul-section-sub-title"><span class="txt"><?php echo esc_html($group['badge']); ?></span></span>
                            <h2 class="ul-section-title"><?php echo esc_html($group['title']); ?></h2>
                        </div>
                    </div>

                    <div class="row ul-bs-row justify-content-center">
                        <?php foreach ($projects as $index => $project) : ?>
                            <?php
                            $location = haritics_get_meta($project->ID, '_location', get_the_excerpt($project));
                            $status = haritics_get_meta($project->ID, '_status');
                            $leader = haritics_get_project_leader($project->ID);
                            $donor = haritics_get_project_donor($project->ID);
                            $target = haritics_get_meta($project->ID, '_target_amount');
                            $leader_condition = haritics_get_meta($project->ID, '_leader_condition');
                            $leader_apply = haritics_get_meta($project->ID, '_leader_apply');
                            $volunteer_needed = haritics_get_meta($project->ID, '_volunteer_needed');
                            $volunteer_condition = haritics_get_meta($project->ID, '_volunteer_condition');
                            $volunteer_apply = haritics_get_meta($project->ID, '_volunteer_apply');
                            $resources_other = haritics_get_meta($project->ID, '_resources_other');
                            $resources_detail = haritics_get_meta($project->ID, '_resources_detail');
                            $resources_donate = haritics_get_meta($project->ID, '_resources_donate');
                            ?>
                            <div class="col-lg-<?php echo $group['key'] === 'calling' ? '6' : ($index % 3 === 0 ? '8' : '4'); ?> col-md-6 col-12">
                                <?php if ($group['key'] === 'calling') : ?>
                                    <article class="ul-project-card haritics-project-card-detailed">
                                        <div class="ul-project-card-img">
                                            <a href="<?php echo esc_url(get_permalink($project)); ?>">
                                                <?php echo get_the_post_thumbnail($project->ID, 'large', ['alt' => get_the_title($project)]); ?>
                                            </a>
                                            <a href="<?php echo esc_url(get_permalink($project)); ?>" class="ul-btn-view-detail"><?php esc_html_e('Xem chi tiết', 'haritics'); ?></a>
                                        </div>
                                        <div class="ul-project-card-content">
                                            <span class="haritics-project-status"><?php echo esc_html($status); ?></span>
                                            <h3 class="ul-project-card-title"><a href="<?php echo esc_url(get_permalink($project)); ?>"><?php echo esc_html(get_the_title($project)); ?></a></h3>
                                            <p class="ul-project-card-location"><?php echo esc_html($location); ?></p>

                                            <?php if ($target !== '') : ?>
                                                <div class="haritics-project-detail-block">
                                                    <span class="haritics-project-detail-label"><?php esc_html_e('Số vốn cần huy động:', 'haritics'); ?></span>
                                                    <span class="haritics-project-detail-value"><?php echo esc_html(haritics_format_money($target)); ?></span>
                                                </div>
                                            <?php endif; ?>

                                            <?php if ($leader !== '') : ?>
                                                <div class="haritics-project-detail-block">
                                                    <span class="haritics-project-detail-label"><?php esc_html_e('Lãnh đạo dự án:', 'haritics'); ?></span>
                                                    <span class="haritics-project-detail-value"><?php echo esc_html($leader); ?></span>
                                                    <div class="haritics-project-detail-actions">
                                                        <?php if ($leader_condition !== '') : ?>
                                                            <a href="<?php echo esc_url($leader_condition); ?>" class="ul-btn-condition"><?php esc_html_e('Xem điều kiện', 'haritics'); ?></a>
                                                        <?php endif; ?>
                                                        <?php if ($leader_apply !== '') : ?>
                                                            <a href="<?php echo esc_url($leader_apply); ?>" class="ul-btn-apply"><?php esc_html_e('Ứng tuyển', 'haritics'); ?></a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <?php if ($volunteer_needed !== '') : ?>
                                                <div class="haritics-project-detail-block">
                                                    <span class="haritics-project-detail-label"><?php esc_html_e('Nhân sự cần huy động:', 'haritics'); ?></span>
                                                    <span class="haritics-project-detail-value"><?php echo esc_html($volunteer_needed); ?></span>
                                                    <div class="haritics-project-detail-actions">
                                                        <?php if ($volunteer_condition !== '') : ?>
                                                            <a href="<?php echo esc_url($volunteer_condition); ?>" class="ul-btn-condition"><?php esc_html_e('Xem điều kiện', 'haritics'); ?></a>
                                                        <?php endif; ?>
                                                        <?php if ($volunteer_apply !== '') : ?>
                                                            <a href="<?php echo esc_url($volunteer_apply); ?>" class="ul-btn-apply"><?php esc_html_e('Ứng tuyển', 'haritics'); ?></a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <?php if ($resources_other !== '') : ?>
                                                <div class="haritics-project-detail-block">
                                                    <span class="haritics-project-detail-label"><?php esc_html_e('Các nguồn lực khác:', 'haritics'); ?></span>
                                                    <span class="haritics-project-detail-value"><?php echo esc_html($resources_other); ?></span>
                                                    <div class="haritics-project-detail-actions">
                                                        <?php if ($resources_detail !== '') : ?>
                                                            <a href="<?php echo esc_url($resources_detail); ?>" class="ul-btn-condition"><?php esc_html_e('Xem chi tiết', 'haritics'); ?></a>
                                                        <?php endif; ?>
                                                        <?php if ($resources_donate !== '') : ?>
                                                            <a href="<?php echo esc_url($resources_donate); ?>" class="ul-btn-apply"><?php esc_html_e('Muốn đóng góp', 'haritics'); ?></a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </article>
                                <?php else : ?>
                                    <article class="ul-project <?php echo $index % 3 === 0 ? '' : 'ul-project--sm'; ?>">
                                        <div class="ul-project-img">
                                            <a href="<?php echo esc_url(get_permalink($project)); ?>">
                                                <?php echo get_the_post_thumbnail($project->ID, 'large', ['alt' => get_the_title($project)]); ?>
                                            </a>
                                        </div>
                                        <div class="ul-project-txt">
                                            <div>
                                                <span class="haritics-project-status"><?php echo esc_html($status); ?></span>
                                                <h3 class="ul-project-title"><a href="<?php echo esc_url(get_permalink($project)); ?>"><?php echo esc_html(get_the_title($project)); ?></a></h3>
                                                <p class="ul-project-descr"><?php echo esc_html($location); ?></p>
                                                <?php if ($leader !== '' || $donor !== '') : ?>
                                                    <div class="haritics-project-meta-list">
                                                        <?php if ($leader !== '') : ?>
                                                            <p><strong><?php esc_html_e('Nhà tổ chức:', 'haritics'); ?></strong> <?php echo esc_html($leader); ?></p>
                                                        <?php endif; ?>
                                                        <?php if ($donor !== '') : ?>
                                                            <p><strong><?php esc_html_e('Mạnh thường quân:', 'haritics'); ?></strong> <?php echo esc_html($donor); ?></p>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <a href="<?php echo esc_url(get_permalink($project)); ?>" class="ul-project-btn"><i class="flaticon-up-right-arrow"></i></a>
                                        </div>
                                    </article>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (! $has_results) : ?>
                <div class="text-center">
                    <p class="ul-section-descr"><?php esc_html_e('Chưa tìm thấy dự án nào phù hợp.', 'haritics'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php get_footer(); ?>
