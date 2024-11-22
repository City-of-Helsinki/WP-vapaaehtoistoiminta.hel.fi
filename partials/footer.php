<?php
/**
 * Site footer partial
 */

$home_url = ( DPT_PLL_ACTIVE ) ? pll_home_url() : home_url();
?>
<footer class="site-footer has-bg-color<?php echo esc_attr( $args['bg_color_class'] . $args['text_color_class'] ); ?>">
    <div class="koro koro--pulse has-bg-color<?php echo esc_attr( $args['bg_color_class'] ); ?>"></div>
    <div class="site-footer__wrapper">
        <div class="container site-footer__top">
            <a href="<?php echo esc_url( $home_url ); ?>" class="site-logo">
                <img src="<?php echo esc_url( $args['logo'] ); ?>" alt="" role="img" aria-hidden="true">
                <span class="site-logo__title">
                    <?php echo esc_html( $args['service_name'] ); ?>
                </span>
            </a>
        </div>
        <div class="container site-footer__sitemap">
            <?php
            wp_nav_menu( [
                'theme_location' => 'primary_navigation',
                'container'      => false,
                'items_wrap'     => '<ul id="footer-%1$s" class="sitemap">%3$s</ul>',
                'link_before'    => '<span aria-hidden="true" class="hds-icon hds-icon--angle-right hds-icon--size-xs"></span>',
            ] );
            ?>
        </div>
        <div class="container site-footer__links">
            <?php if ( ! empty( $args['fields']['some_links'] ) ) : ?>
                <ul class="site-footer__some-links">
                    <?php foreach ( $args['fields']['some_links'] as $link ) : ?>
                    <li>
                        <a href="<?php echo esc_url( $link['some_link'] ); ?>">
                            <?php
                            get_template_part( 'partials/icon', '', [
                                'icon' => $link['some_source'],
                            ] );
                            ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <ul class="inline-list inline-list--align-end">
            <?php if ( ! empty( $args['fields']['footer_links'] ) ) : ?>
                <?php foreach ( $args['fields']['footer_links'] as $link ) : ?>
                    <li>
                        <?php
                        get_template_part( 'partials/link', '', array_merge(
                            (array) $link, [
                                'classes' => [ 'site-footer__link', 'text-medium' ],
                            ]
                        ) );
                        ?>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
                <li>
                    <?php
                    get_template_part( 'partials/link', '', [
                        'title'   => __( 'Scroll to top', 'nuhe' ),
                        'url'     => '#top',
                        'classes' => [ 'site-footer__link', 'text-medium' ],
                        'icon'    => 'arrow-up',
                    ] );
                    ?>
                </li>
            </ul>
        </div>
        <div class="container site-footer__sub-bar">
            <div class="site-footer__copyright text-sm">
                <?php echo esc_html( $args['fields']['footer_sub_bar']['footer_sub_bar_text'] ); ?>
            </div>
            <div class="site-footer__sub-bar-links">
                <ul class="inline-list inline-list--align-end">
                    <?php if ( ! empty( $args['fields']['footer_sub_bar']['footer_sub_bar_links'] ) ) : ?>
                        <?php foreach ( $args['fields']['footer_sub_bar']['footer_sub_bar_links'] as $link ) : ?>
                            <li>
                                <?php
                                get_template_part( 'partials/link', '', array_merge(
                                    (array) $link, [
                                        'classes' => [ 'site-footer__sub-bar-link', 'text-sm' ],
                                    ]
                                ) );
                                ?>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</footer>
