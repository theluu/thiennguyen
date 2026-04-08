<?php
if (! defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main>
    <?php haritics_render_breadcrumb(__('Danh sách ủng hộ', 'haritics')); ?>
    <section class="ul-section-spacing overflow-hidden">
        <div class="ul-container">
            <div class="row ul-bs-row row-cols-xl-4 row-cols-md-3 row-cols-2 row-cols-xxs-1">
                <?php if (have_posts()) : while (have_posts()) : the_post(); $progress = haritics_progress_percent(haritics_get_meta(get_the_ID(), '_raised_amount'), haritics_get_meta(get_the_ID(), '_target_amount')); ?>
                    <div class="col">
                        <article class="ul-donation ul-donation--inner">
                            <div class="ul-donation-img">
                                <?php if (has_post_thumbnail()) : the_post_thumbnail('large'); endif; ?>
                                <span class="tag"><?php echo esc_html(haritics_get_meta(get_the_ID(), '_badge', __('Ủng hộ', 'haritics'))); ?></span>
                            </div>
                            <div class="ul-donation-txt">
                                <div class="ul-donation-progress">
                                    <div class="donation-progress-container ul-progress-container">
                                        <div class="donation-progressbar ul-progressbar" data-ul-progress-value="<?php echo esc_attr((string) $progress); ?>">
                                            <div class="donation-progress-label ul-progress-label"></div>
                                        </div>
                                    </div>
                                    <div class="ul-donation-progress-labels">
                                        <span class="ul-donation-progress-label"><?php echo esc_html__('Đã huy động:', 'haritics') . ' ' . esc_html(haritics_format_money(haritics_get_meta(get_the_ID(), '_raised_amount'))); ?></span>
                                        <span class="ul-donation-progress-label"><?php echo esc_html__('Mục tiêu:', 'haritics') . ' ' . esc_html(haritics_format_money(haritics_get_meta(get_the_ID(), '_target_amount'))); ?></span>
                                    </div>
                                </div>
                                <a href="<?php the_permalink(); ?>" class="ul-donation-title"><?php the_title(); ?></a>
                                <p class="ul-donation-descr"><?php echo esc_html(get_the_excerpt()); ?></p>
                                <a href="<?php the_permalink(); ?>" class="ul-donation-btn"><?php esc_html_e('Ủng hộ ngay', 'haritics'); ?> <i class="flaticon-up-right-arrow"></i></a>
                            </div>
                        </article>
                    </div>
                <?php endwhile; endif; ?>
            </div>
            <?php haritics_render_pagination(); ?>
        </div>
    </section>
</main>
<?php get_footer(); ?>
