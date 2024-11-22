<?php
/**
 * Vet items list partial
 */

if ( empty( $args ) ) {
    return;
}

$grid_columns_class = $args['columns'] !== 1
                    ? " grid-columns grid-columns--{$args['columns']}"
                    : '';
$bg_color           = $args['bg_color'] ?? 'grey';
$anchor_id          = ! empty( $args['anchor_id'] ) ? ' id="' . $args['anchor_id'] . '"' : '';

$layout_classes = [
    'nuhe-layout',
    'nuhe-layout--main-content',
    'nuhe-card-list',
    'nuhe-vet-items',
    'has-bg-color',
    "has-bg-color--{$bg_color}",
    $bg_color === 'grey' ? 'has-koro' : '',
];
$layout_classes = implode( ' ', $layout_classes );

?>

<div class="<?php echo esc_attr( $layout_classes ); ?>"<?php echo wp_kses_post( $anchor_id ); ?>>
        <div class="container">
        <?php if ( ! empty( $args['title'] ) ) : ?>
            <h2 class="nuhe-card-list__title">
                <?php echo esc_html( $args['title'] ); ?>
            </h2>
        <?php endif; ?>

        <?php if ( ! empty( $args['description'] ) ) : ?>
            <div class="nuhe-card-list__description">
                <?php echo wp_kses_post( $args['description'] ); ?>
            </div>
        <?php endif; ?>

        <?php if ( ! empty( $args['items'] ) ) : ?>
            <ul class="nuhe-card-list__items<?php echo esc_attr( $grid_columns_class ); ?>">
                <?php
                foreach ( $args['items'] as $item ) {
                    get_template_part( 'partials/vet-list-item', '', $item );
                }
                ?>
            </ul>
        <?php endif; ?>

        <?php if ( ! empty( $args['link'] ) ) : ?>
            <div class="call-to-action">
                <a
                    href="<?php echo esc_url( $args['link']['url'] ); ?>"
                    class="call-to-action__link"
                    target="<?php echo esc_attr( $args['link']['target'] ); ?>">
                    <span class="call-to-action__text">
                        <?php echo esc_html( $args['link']['title'] ); ?>
                    </span>

                    <?php get_template_part( 'partials/icon', '', [ 'icon' => 'arrow-right' ] ); ?>
                </a>
            </div>
        <?php endif; ?>
        </div>

        <?php if ( $bg_color === 'grey' ) : ?>
            <div class="koro koro--wave koro--grey"></div>
        <?php endif; ?>
</div>
