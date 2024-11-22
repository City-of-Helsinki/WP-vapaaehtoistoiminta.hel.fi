<?php
/**
 * Define the Single activity class
 */

namespace Geniem\Theme\Model;

use \Geniem\Theme\Interfaces\Model;
use \Geniem\Theme\Traits;
use \Geniem\Theme\Taxonomy;

/**
 * Single activity class
 */
class SingleActivity implements Model {

    use Traits\FlexibleContent;
    use Traits\Breadcrumbs;

    /**
     * This defines the name of this model.
     */
    const NAME = 'SingleActivity';

    /**
     * Add hooks and filters from this model
     *
     * @return void
     */
    public function hooks() : void {}

    /**
     * Get class name constant
     *
     * @return string Class name constant
     */
    public function get_name() : string {
        return self::NAME;
    }

    /**
     * Add classes to html.
     *
     * @param array $classes Array of classes.
     * @return array Array of classes.
     */
    public function add_classes_to_html( $classes ) : array {
        return $classes;
    }

    /**
     * Get place tags.
     *
     * @return array|null
     */
    public function get_place_tags() {
        global $post;
        $terms = get_the_terms( $post, Taxonomy\PlaceTagTax::SLUG );

        if ( empty( $terms ) || $terms instanceof \WP_Error ) {
            return null;
        }

        return array_map( function( $term ) {
            return [
                'url'   => get_term_link( $term->term_id ),
                'title' => $term->name,
            ];
        }, $terms );

    }
}
