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

    <?php if (haritics_has_active_search_filters()) : ?>
        <section class="ul-section-spacing pb-0">
            <div class="ul-container">
                <div class="ul-search-result-info mb-5">
                    <span><?php esc_html_e('Kết quả tìm kiếm cho:', 'haritics'); ?></span>
                    <strong><?php echo esc_html(haritics_get_search_result_label()); ?></strong>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php foreach ($project_groups as $group) : ?>
        <?php $projects = haritics_get_projects_for_archive($group, $search_filters); ?>
        <?php if ($projects === []) : ?>
            <?php continue; ?>
        <?php endif; ?>
        <?php $has_results = true; ?>

        <section class="ul-projects-timeline ul-section-spacing overflow-hidden haritics-archive-group"<?php echo $group['key'] === 'calling' ? ' id="du-an-keu-goi-nguon-luc"' : ''; ?>>
            <div class="ul-container">
                <div class="ul-section-heading justify-content-between text-center">
                    <div class="left">
                        <h2 class="ul-section-title"><?php echo esc_html($group['title']); ?></h2>
                    </div>
                </div>
                <div class="row ul-bs-row row-cols-xl-4 row-cols-lg-4 row-cols-md-2 row-cols-1 justify-content-center">
                    <?php foreach ($projects as $project) : ?>
                        <div class="col">
                            <?php haritics_render_project_card($project, $group['key']); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endforeach; ?>

    <?php if (! $has_results) : ?>
        <section class="ul-section-spacing">
            <div class="ul-container">
                <div class="text-center">
                    <p class="ul-section-descr"><?php esc_html_e('Chưa tìm thấy dự án nào phù hợp.', 'haritics'); ?></p>
                </div>
            </div>
        </section>
    <?php endif; ?>
</main>
<?php get_footer(); ?>
