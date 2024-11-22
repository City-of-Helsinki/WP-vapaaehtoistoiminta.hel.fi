<?php
/**
 * Main site header partial.
 */

use Geniem\Theme\Settings;

/**
 * Search model
 *
 * @var $model Search
 */
$search   = ModelController()->get_class( 'Search' );
$home_url = ( DPT_PLL_ACTIVE ) ? pll_home_url() : home_url();
?>

<header class="site-header" id="top">
    <a href="#main-content" class="u-skip-to-content">
        <?= __('Skip to content', 'vapaaehtoistoiminta'); ?>
    </a>
    <div class="container container--full-small">
        <a href="<?php echo esc_url( $home_url ); ?>" class="site-logo">
            <img src="<?php echo esc_url( $args['logo'] ); ?>" alt="" role="img" aria-hidden="true">
            <span class="site-logo__title">
                <?php echo esc_html( $args['service_name'] ); ?>
            </span>
        </a>

        <?php if ( ! Settings::get_setting( 'hide_search' ) ) : ?>
            <div class="site-header__desktopsearch dropdown dropdown--search" data-dropdown>
                <?php
                get_template_part( 'partials/button', '', [
                    'label'      => __( 'Search', 'nuhe' ),
                    'icon-start' => 'search',
                    'classes'    => [ 'supplementary', 'theme-black' ],
                    'attributes' => [
                        'aria-expanded'         => 'false',
                        'data-dropdown-trigger' => true,
                    ],
                ] );
                ?>
                <div class="site-header__search-wrapper dropdown__content dropdown__content--search">
                    <form role="search" action="<?php echo esc_url( $search->get_search_action() ); ?>" method="GET">
                        <?php
                        get_template_part( 'partials/searchfield', '', [
                            'placeholder' => __( 'Search', 'nuhe' ),
                            'id'          => 'main-search-desktop',
                            'hide_label'  => true,
                            'name'        => 's',
                        ] );
                        ?>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <?php if ( ! Settings::get_setting( 'hide_lang_menu' ) ) : ?>
            <?php if ( ! empty( $args['languages'] ) ) : ?>
                <div class="site-header__desktoplang dropdown" data-dropdown role="region"
                    aria-label="<?php esc_attr_e( 'Change language', 'vapaaehtoistoiminta' ); ?>">
                    <?php
                    get_template_part( 'partials/button', '', [
                        'label'      => $args['languages']['current']['slug'],
                        'icon-end'   => 'angle-down',
                        'classes'    => [ 'supplementary', 'theme-black' ],
                        'attributes' => [
                            'aria-expanded'         => 'false',
                            'data-dropdown-trigger' => true,
                            'aria-label'            =>  __( 'Switch language', 'vapaaehtoistoiminta' ),
                        ],
                    ] );
                    ?>

                    <div class="dropdown__content">
                        <ul class="dropdown__menu">
                            <?php foreach ( $args['languages']['all'] as $lang ) : ?>
                                <li>
                                    <a class="hds-button hds-button--small hds-button--supplementary hds-button--theme-black"
                                        href="<?php echo esc_url( $lang['url'] ); ?>"
                                        aria-label="<?= esc_attr( sprintf( __( 'Switch language to %s', 'vapaaehtoistoiminta' ), $lang['name'] ) ); ?>"
                                        <?php if ( $lang['current_lang'] ) : ?>aria-current="true" <?php endif; ?>>
                                        <span class="hds-button__label"><?php echo esc_html( $lang['name'] ); ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ( ! empty( $args['facebook_link'] ) ) : ?>
            <div class="site-header__some-icon site-header__some-icon--desktop">
                <a href="<?php echo esc_url( $args['facebook_link']['some_link'] ); ?>" target="_blank">
                    <span class="screen-reader-text"><?= __( 'Go to Vapaaehtoistoiminta Facebook page', 'vapaaehtoistoiminta' ); ?></span>
                    <?php
                    get_template_part( 'partials/icon', '', [
                        'icon' => $args['facebook_link']['some_source'],
                    ] );
                    ?>
                </a>
            </div>
        <?php endif; ?>

        <div class="site-header__menutoggle">
            <?php
            get_template_part( 'partials/button', '', [
                'label'      => '&nbsp;',
                'icon-end'   => 'menu-hamburger',
                'classes'    => [ 'supplementary', 'theme-black' ],
                'attributes' => [
                    'aria-expanded' => 'false',
                    'aria-label'    => __( 'Menu', 'nuhe' ),
                    'id'            => 'js-menu-toggle',
                ],
            ] );
            ?>
        </div>

    </div>

    <div class="site-header__navwrapper" id="js-menu-wrapper">
        <?php if ( ! Settings::get_setting( 'hide_search' ) ) : ?>
            <div class="site-header__search-wrapper site-header__search-wrapper--mobile">
                <form role="search" action="<?php echo esc_url( $search->get_search_action() ); ?>" method="GET">
                    <?php
                    get_template_part( 'partials/searchfield', '', [
                        'placeholder' => __( 'Search', 'nuhe' ),
                        'id'          => 'main-search-mobile',
                        'hide_label'  => true,
                        'name'        => 's',
                    ] );
                    ?>
                </form>
            </div>
        <?php endif; ?>

        <?php
        get_template_part( 'partials/menu', '', [
            'label'   => __( 'Primary', 'nuhe' ),
            'classes' => [ 'site-header__menu' ],
        ] );
        ?>

        <?php if ( ! Settings::get_setting( 'hide_lang_menu' ) ) : ?>
            <?php if ( ! empty( $args['languages'] ) ) : ?>
                <div class="site-header__mobilelang" role="region"
                    aria-label="<?php esc_attr_e( 'Change language', 'nuhe' ); ?>">
                    <ul class="site-header__mobilelanglist">
                        <?php foreach ( $args['languages']['all'] as $lang ) : ?>
                            <li>
                                <a class="hds-button hds-button--small hds-button--supplementary hds-button--theme-black"
                                    href="<?php echo esc_url( $lang['url'] ); ?>"
                                    <?php if ( $lang['current_lang'] ) : ?>aria-current="true" <?php endif; ?>>
                                    <span class="hds-button__label"><?php echo esc_html( $lang['name'] ); ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ( ! empty( $args['facebook_link'] ) ) : ?>
            <div class="site-header__some-icon site-header__some-icon--mobile">
                <a href="<?php echo esc_url( $args['facebook_link']['some_link'] ); ?>" target="_blank">
                    <?php
                    get_template_part( 'partials/icon', '', [
                        'icon' => $args['facebook_link']['some_source'],
                    ] );
                    ?>
                </a>
            </div>
        <?php endif; ?>

    </div>
</header>
