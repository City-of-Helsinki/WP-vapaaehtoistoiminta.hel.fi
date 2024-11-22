<?php
/**
 * Tax term list partial
 */

$args = wp_parse_args( $args );

$description_modifier = ! empty( $args['description'] ) ? ' nuhe-tag-cloud--has-description' : '';

?>

<div class="nuhe-layout nuhe-layout--main-content nuhe-tag-cloud<?php echo esc_attr( $description_modifier ); ?>">
    <div class="container">
        <?php if ( ! empty( $args['title'] ) ) : ?>
            <h2 class="nuhe-tag-cloud__title">
                <?php echo esc_html( $args['title'] ); ?>
            </h2>
        <?php endif; ?>

        <?php if ( ! empty( $args['description'] ) ) : ?>
            <div class="nuhe-tag-cloud__description">
                <?php echo wp_kses_post( $args['description'] ); ?>
            </div>
        <?php endif; ?>

        <?php
        get_template_part( 'partials/links', '', [
            'list_classes' => [ 'button-links', 'taxonomy-terms' ],
            'links'        => $args['terms'],
            'type'         => 'button',
            'classes'      => [ 'outlined', 'border-black' ],
            'attributes'   => [
                'aria-label' => __( 'Filter results', 'nuhe' ),
            ],
        ] );
        ?>
    </div>
</div>
