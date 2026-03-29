<?php
if (! defined('ABSPATH')) {
    exit;
}

$slug = get_post_field('post_name', get_queried_object_id());
$template = $slug ? $slug . '.html' : 'index.html';
$has_source_template = haritics_source_template_exists($template);

haritics_set_current_template($has_source_template ? $template : 'index.html');
get_header();
?>
<main class="overflow-hidden">
    <?php if ($has_source_template) : ?>
        <?php haritics_render_part('main', haritics_get_current_template()); ?>
    <?php else : ?>
    <section class="ul-section-spacing">
        <div class="ul-container">
            <?php
            while (have_posts()) :
                the_post();
                the_content();
            endwhile;
            ?>
        </div>
    </section>
    <?php endif; ?>
</main>
<?php
get_footer();
