<?php
/**
 * This file controls Post type functionality.
 */

namespace Geniem\Theme;

/**
 * Define the controller class.
 */
class VetPostTypeController {

    /**
     * The post type class instances
     *
     * @var \Geniem\Theme\Interfaces\PostType[]
     */
    private $classes = [];

    /**
     * Get a single class instance from Theme Controller
     *
     * @param string|null $class Class name to get.
     * @return \Geniem\Theme\Interfaces\PostType|null
     */
    public function get_class( ?string $class ) : ?Interfaces\PostType {
        return $this->classes[ $class ] ?? null;
    }

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {
        add_action( 'init', \Closure::fromCallable( [ $this, 'register_cpts' ] ) );
        add_filter( 'nuhe_cpts', \Closure::fromCallable( [ $this, 'modify_cpts' ] ) );
    }

    /**
     * This registers all custom post types.
     *
     * @return void
     */
    private function register_cpts() {

        $instances = array_map(
            function( $field_class ) {

                $field_class = basename( $field_class, '.' . pathinfo( $field_class )['extension'] );
                $class_name  = __NAMESPACE__ . '\PostType\\' . $field_class;

                // Bail early if the class does not exist for some reason
                if ( ! \class_exists( $class_name ) ) {
                    return null;
                }

                return new $class_name();
            },
            array_diff( scandir( __DIR__ . '/PostType' ), [ '.', '..' ] )
        );

        foreach ( $instances as $instance ) {
            if ( $instance instanceof Interfaces\PostType ) {
                $instance->hooks();

                $this->classes[ $instance::SLUG ] = $instance;
            }
        }
    }

    /**
     * Remove cpts
     *
     * @param array $instances CTP instances.
     */
    private function modify_cpts( array $instances ) : array {
        $cpts_to_remove = [
            PostType\HighSchool::class            => 0,
            PostType\Instructor::class            => 0,
            PostType\VocationalSchool::class      => 0,
            PostType\YouthCenter::class           => 0,
            PostType\YouthCouncilElections::class => 0,
            PostType\Initiative::class            => 0,
        ];

        return array_filter( $instances, function( $instance ) use ( $cpts_to_remove ) {
            return ! array_key_exists( get_class( $instance ), $cpts_to_remove );
        } );
    }
}
