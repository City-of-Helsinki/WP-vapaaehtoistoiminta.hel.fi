<?php
/**
 * Archive template
 */

use Geniem\Theme\Model\Archive;

get_header();

$model                = new Archive();
$breadcrumbs          = $model->get_breadcrumbs();
$taxonomy_terms_title = $model->get_taxonomy_terms_title();
?>
<main id="main-content" class="archive-main">
    <div class="container">
        <?php
        if ( ! empty( $breadcrumbs ) ) {
            \get_template_part( 'partials/breadcrumbs', '', [
                'breadcrumbs' => $breadcrumbs,
            ] );
        }

        // H1 heading
        \get_template_part( 'partials/heading', '', [
            'level' => 'h1',
            'class' => 'page-heading',
            'title' => get_queried_object()->name,
        ] );
        ?>

        <?php if ( ! empty( get_queried_object()->description ) ) : ?>
        <div class="term-description">
            <p>
                <?php echo wp_kses_post( get_queried_object()->description ); ?>
            </p>
        </div>
        <?php endif; ?>
    </div>

    <?php get_template_part( 'partials/vet-items-list', '', $model->get_posts() ); ?>

    <div class="container">

    <?php if ( ! empty( $taxonomy_terms_title ) ) : ?>
        <h2><?php echo esc_html( $taxonomy_terms_title ); ?></h2>
    <?php endif; ?>

    <?php
    get_template_part( 'partials/links', '', [
        'list_classes' => [ 'button-links', 'taxonomy-terms' ],
        'links'        => $model->get_taxonomy_terms(),
        'type'         => 'button',
        'classes'      => [ 'outlined', 'border-black' ],
        'attributes'   => [
            'aria-label' => __( 'Filter results', 'nuhe' ),
        ],
    ] );
    ?>

    </div>
</main>
<?php

get_footer();
