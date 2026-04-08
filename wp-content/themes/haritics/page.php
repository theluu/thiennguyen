<?php
if (! defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main>
    <?php while (have_posts()) : the_post(); ?>
        <?php haritics_render_breadcrumb(get_the_title()); ?>
        <section class="ul-section-spacing">
            <div class="ul-container">
                <div class="haritics-content">
                    <?php the_content(); ?>
                </div>
            </div>
        </section>
    <?php endwhile; ?>
</main>
<?php
get_footer();
