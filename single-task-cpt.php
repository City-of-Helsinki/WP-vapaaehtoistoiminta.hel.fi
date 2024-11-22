<?php
/**
 * Single task template
 */

use Geniem\Theme\Model\SingleTask;

get_header();
global $post;
$model         = new SingleTask();
$breadcrumbs   = $model->get_breadcrumbs();
$custom_fields = $model->get_task_custom_fields();
?>
<div id="main-content" class="container page-grid<?php echo ! empty( $breadcrumbs ) ? ' page-grid--has-breadcrumbs' : ''; ?> ">
    <main class="page-grid__content blocks">
        <?php
        if ( ! empty( $breadcrumbs ) ) {
            \get_template_part( 'partials/breadcrumbs', '', [
                'breadcrumbs' => $breadcrumbs,
            ] );
        }

        the_post();

        // Featured image
        if ( \has_post_thumbnail() ) {
            \get_template_part( 'partials/featured-image' );
        }

        // H1 heading
        \get_template_part( 'partials/heading', '', [
            'level' => 'h1',
            'class' => 'page-heading',
        ] );
        ?>

        <div class="single-task-fields">
            <?php foreach ( $custom_fields as $custom_field ) : ?>
                <?php if ( ! empty( $custom_field['label'] ) && ! empty( $custom_field['value'] ) ) : ?>
                    <p class="single-task-field single-task-field--label"><?php echo esc_html( $custom_field['label'] . ':' ); ?></p>
                <?php endif; ?>
                <?php if ( ! empty( $custom_field['value'] ) ) : ?>
                    <p class="single-task-field single-task-field--value"><?php echo wp_kses_post( $custom_field['value'] ); ?></p>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </main>
</div>
<aside class="page-footer" aria-label="<?php esc_attr_e( 'More related content', 'nuhe' ); ?>">
<?php $model->content(); ?>
</aside>
<div class="container post-taxonomies-list-wrapper">
    <?php
    get_template_part( 'partials/links', '', [
        'list_classes' => [ 'child-menu-items', 'button-links' ],
        'links'        => $model->get_type_terms(),
        'type'         => 'button',
        'classes'      => [ 'outlined', 'border-black' ],
        'icon-end'     => 'arrow-right',
    ] );
    ?>
</div>
<?php

get_footer();
