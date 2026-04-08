<?php
if (! defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main>
    <?php haritics_render_breadcrumb(__('Tin tức', 'haritics')); ?>
    <section class="ul-section-spacing">
        <div class="ul-container">
            <div class="row gy-4">
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <div class="col-12">
                        <article class="ul-project ul-project--sm">
                            <div class="ul-project-img">
                                <?php if (has_post_thumbnail()) : the_post_thumbnail('large'); endif; ?>
                            </div>
                            <div class="ul-project-txt">
                                <div>
                                    <h3 class="ul-project-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <p class="ul-project-descr"><?php echo esc_html(get_the_excerpt()); ?></p>
                                </div>
                                <a href="<?php the_permalink(); ?>" class="ul-project-btn"><i class="flaticon-up-right-arrow"></i></a>
                            </div>
                        </article>
                    </div>
                <?php endwhile; endif; ?>
            </div>
            <?php haritics_render_pagination(); ?>
        </div>
    </section>
</main>
<?php
get_footer();
