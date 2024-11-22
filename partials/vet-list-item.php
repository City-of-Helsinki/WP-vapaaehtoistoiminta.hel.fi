<?php
/**
 * Vet list item partial
 */

$args = wp_parse_args( $args );

$white_bg_class = empty( $args['background_class'] )
                ? " has-bg-color has-bg-color--white {$args['color_theme']} has-bg-image"
                : " {$args['color_theme']}";
?>

<li class="card-list-item grid-columns__column<?php echo esc_attr( $args['background_class'] ); ?>">
    <div class="card-list-item__inner-container<?php echo esc_attr( $args['background_class'] ); ?>">
        <?php if ( empty( $args['background_class'] ) ) : ?>
            <figure class="img-container">
                <?php
                echo wp_get_attachment_image( $args['image']['id'], 'large' );
                ?>
            </figure>
        <?php endif; ?>

        <div class="card-list-item__content has-dash<?php echo esc_attr( $white_bg_class ); ?>">
            <h3 class="card-list-item-title">
                <a
                    href="<?php echo esc_url( $args['link'] ); ?>"
                    class="card-list-item-title__link"
                >
                    <?php echo esc_html( $args['post_title'] ); ?>
                </a>
            </h3>

            <?php
            if ( ! empty( $args['subtitle'] ) ) {
                echo wp_kses_post( apply_filters( 'the_content', $args['subtitle'] ) );
            }
            ?>
        </div>
    </div>
</li>
