<?php
/**
 * PageVetEvents template
 *
 * Template Name: Vapaaehtoistoiminta täysleveä
 */

get_header();

$model       = ModelController()->get_class( 'Page' );
$breadcrumbs = $model->get_breadcrumbs();
?>
<div id="main-content" class="container container--vet-fullwidth page-grid<?php echo ! empty( $breadcrumbs ) ? ' page-grid--has-breadcrumbs' : ''; ?> ">
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

        \get_template_part( 'partials/blocks' );

		?>
    </main>
</div>
<aside class="page-footer" aria-label="<?php esc_attr_e( 'More related content', 'nuhe' ); ?>">
<?php $model->content(); ?>
</aside>
<?php

get_footer();
