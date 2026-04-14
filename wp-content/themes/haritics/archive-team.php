<?php
if (! defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main>
    <?php haritics_render_breadcrumb(__('Đội ngũ', 'haritics')); ?>
    <section class="ul-team ul-inner-team ul-section-spacing">
        <div class="ul-container">
            <div class="row row-cols-md-4 row-cols-sm-3 row-cols-2 row-cols-xxs-1 ul-team-row justify-content-center">
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <div class="col">
                        <article class="ul-team-member">
                            <div class="ul-team-member-img">
                                <?php if (has_post_thumbnail()) : the_post_thumbnail('large'); endif; ?>
                                <?php haritics_render_social_links(haritics_get_post_social_links(get_the_ID()), 'ul-team-member-socials'); ?>
                            </div>
                            <div class="ul-team-member-info">
                                <h3 class="ul-team-member-name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <p class="ul-team-member-designation"><?php echo esc_html(haritics_get_meta(get_the_ID(), '_role')); ?></p>
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
