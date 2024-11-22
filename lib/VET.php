<?php
/**
 * VET theme functionalities
 */

namespace Geniem\Theme;

use Geniem\Theme\Settings;
use Geniem\Theme\Taxonomy;

/**
 * Class VET
 *
 * This class sets up the child theme functionalities.
 */
class VET {

    /**
     * The controller instance
     *
     * @var self|null
     */
    private static $instance = null;

    /**
     * The class instances
     *
     * @var array
     */
    private $classes = [];

    /**
     * Get the VET
     *
     * @return VET
     */
    public static function instance() {
        if ( ! static::$instance ) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->hooks();
        $this->init_classes();
    }

    /**
     * Hooks
     *
     * @return void
     */
    private function hooks() : void {
        add_action( 'init', \Closure::fromCallable( [ new VetShortCodes(), 'hooks' ] ) );
        add_action( 'wp_enqueue_scripts', \Closure::fromCallable( [ $this, 'enqueue_assets' ] ) );
        add_action( 'after_setup_theme', \Closure::fromCallable( [ $this, 'load_child_theme_textdomain' ] ) );
        add_filter( 'nuhe_add_cpts_to_polylang', \Closure::fromCallable( [ $this, 'add_cpts_to_polylang' ] ) );
        add_filter( 'pll_get_taxonomies', \Closure::fromCallable( [ $this, 'add_taxs_to_polylang' ] ), 10, 2 );
        add_filter( 'nuhe_modify_instances_array', \Closure::fromCallable( [ $this, 'add_models' ] ) );
        add_filter( 'nuhe_constants_redipress_taxonomies', \Closure::fromCallable( [ $this, 'add_taxonomies_to_redipress' ] ) );
    }

    /**
     * Run setup for vet theme functionality.
     *
     * @return void
     */
    private function init_classes() {

        $this->classes = [
            // This controls CPT functionality in the theme.
            'PostTypeController'   => new VetPostTypeController(),

            // This controls taxonomy functionality in the theme.
            'TaxonomyController'   => new VetTaxonomyController(),

            // This controls the ACF functionality in the theme.
            'ACFController'        => new VetACFController(),

            'SearchController'     => new VetSearchController(),

            'BlocksController'     => new VetBlocksController(),

            'LinkedEventsSettings' => new LinkedEventsSettings(),
        ];

        // Loop through the classes and run hooks methods of all controllers.
        array_walk( $this->classes, function ( $instance ) {
            $instance->hooks();
        } );
    }

    /**
     * Enqueue assets.
     *
     * @return void
     */
    private function enqueue_assets() : void {
        $theme_file_uri    = get_theme_file_uri();
        $assets_dir        = '/assets/dist/';
        $main_css          = 'main.css';
        $main_js           = 'main.js';
        $main_css_mod_time = self::get_theme_asset_mod_time( $assets_dir . $main_css );
        $main_js_mod_time  = self::get_theme_asset_mod_time( $assets_dir . $main_js );

        wp_enqueue_style( 'child-theme-style', $theme_file_uri . $assets_dir . $main_css,
            [ 'theme-css' ],
            $main_css_mod_time
        );

        wp_enqueue_script( 'child-theme-js', $theme_file_uri . $assets_dir . $main_js,
            [ 'theme-js' ],
            $main_js_mod_time,
            true
        );
    }

    /**
     * Get theme asset mod time
     *
     * @param string $path_end
     *
     * @return string 
     */
    private static function get_theme_asset_mod_time( $path_end = '' ) {
        $theme     = wp_get_theme();
        $file_path = get_theme_file_path();

        return file_exists( $file_path . $path_end )
            ? filemtime( $file_path . $path_end )
            : $theme->get( 'Version' );
    }

    /**
     * Get facebook link.
     *
     * @return array|null
     */
    public function get_facebook_link() {
        $some_links = Settings::get_setting( 'footer' )['some_links'];

        if ( empty( $some_links ) ) {
            return null;
        }

        $facebook_link = null;

        foreach ( $some_links as $link ) {
            if ( $link['some_source'] === 'facebook' ) {
                $facebook_link = $link;
                break;
            }
        }

        return $facebook_link;
    }

    /**
     * Load child theme textdomain.
     *
     * @return void
     */
    private function load_child_theme_textdomain() : void {
        load_child_theme_textdomain( 'vapaaehtoistoiminta', \get_stylesheet_directory() . '/lang' );
    }

    /**
     * Add cpts to polylang.
     *
     * @return array Array of post types.
     */
    private function add_cpts_to_polylang( array $post_types ) : array {
        $vet_post_types = [
            PostType\Activity::SLUG,
            PostType\Task::SLUG,
        ];

        return array_merge( $post_types, $vet_post_types );
    }

    /**
     * This adds the taxonomies that are not public to Polylang translation.
     *
     * @param array   $tax_types   The taxonomy type array.
     * @param boolean $is_settings A not used boolean flag to see if we're in settings.
     *
     * @return array The modified tax_types -array.
     */
    private function add_taxs_to_polylang( $tax_types, $is_settings ) {
        $tax_types[ Taxonomy\PlaceTagTax::SLUG ] = Taxonomy\PlaceTagTax::SLUG;
        $tax_types[ Taxonomy\TypeTax::SLUG ]     = Taxonomy\TypeTax::SLUG;

        return $tax_types;
    }

    /**
     * Add models.
     *
     * @param array $instances
     *
     * @return array
     */
    private function add_models( array $instances ) : array {
        $child_instances = array_map(
            function( $field_class ) {

                $field_class = basename( $field_class, '.' . pathinfo( $field_class )['extension'] );
                $class_name  = __NAMESPACE__ . '\Model\\' . $field_class;

                // Bail early if the class does not exist for some reason
                if ( ! \class_exists( $class_name ) ) {
                    return null;
                }

                return new $class_name();
            },
            array_diff( scandir( __DIR__ . '/Model' ), [ '.', '..' ] )
        );

        return array_merge( $instances, $child_instances );
    }

    /**
     * Add taxonomies to redipress
     *
     * @param array $taxonomies
     *
     * @return array
     */
    private function add_taxonomies_to_redipress( array $taxonomies ) : array {
        return array_merge( $taxonomies, [
            Taxonomy\TypeTax::SLUG,
            Taxonomy\PlaceTagTax::SLUG,
        ] );
    }

    /**
     * Update imported activities post content.
     * This is most likely used only once and can be removed after once executed.
     *
     * @example wp eval 'Geniem\Theme\VET::update_imported_activities_content();' --url=vapaaehtoistoiminta.nuorten-helsinki.test
     *
     * @return void
     */
    public static function update_imported_activities_content() : void {
        $activities = new \WP_Query( [
            'post_type'      => PostType\Activity::SLUG,
            'posts_per_page' => -1,
        ] );

        foreach ( $activities->posts as $activity ) {
            $post_meta = get_post_meta( $activity->ID ) ?: [];
            $content   = '';

            foreach ( $post_meta as $key => $meta ) {
                if ( strpos( $key, 'columns_' ) !== false && strpos( $meta[0], 'field_columns' ) === false ) {
                    $content .= $meta[0] . '<br>';
                }
            }

            wp_update_post( [
                'ID'           => $activity->ID,
                'post_content' => $content,
            ] );
        }
    }
}
