<?php
if (! defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main>
    <?php haritics_render_breadcrumb(__('Hoạt động', 'haritics')); ?>
    <section class="ul-section-spacing">
        <div class="ul-container">
            <div class="ul-events-wrapper">
                <div class="row ul-bs-row row-cols-lg-2 row-cols-1">
                    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                        <div class="col">
                            <article class="ul-event ul-event--inner">
                                <div class="ul-event-img">
                                    <?php if (has_post_thumbnail()) : the_post_thumbnail('large'); endif; ?>
                                    <?php $date = haritics_get_meta(get_the_ID(), '_event_date', get_the_date('Y-m-d')); ?>
                                    <span class="date"><?php echo esc_html(date_i18n('d', strtotime($date))); ?> <span><?php echo esc_html(date_i18n('M', strtotime($date))); ?></span></span>
                                </div>
                                <div class="ul-event-txt">
                                    <h3 class="ul-event-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <p class="ul-event-descr"><?php echo esc_html(get_the_excerpt()); ?></p>
                                    <div class="ul-event-info">
                                        <span class="ul-event-info-title"><?php esc_html_e('Địa điểm', 'haritics'); ?></span>
                                        <p class="ul-event-info-descr"><?php echo esc_html(haritics_get_meta(get_the_ID(), '_venue')); ?></p>
                                    </div>
                                    <a href="<?php the_permalink(); ?>" class="ul-btn"><i class="flaticon-fast-forward-double-right-arrows-symbol"></i> <?php esc_html_e('Xem chi tiết', 'haritics'); ?></a>
                                </div>
                            </article>
                        </div>
                    <?php endwhile; endif; ?>
                </div>
            </div>
            <?php haritics_render_pagination(); ?>
        </div>
    </section>
</main>
<?php get_footer(); ?>
