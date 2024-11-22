<?php
/**
 * Define the Single task class
 */

namespace Geniem\Theme\Model;

use \Geniem\Theme\Interfaces\Model;
use \Geniem\Theme\Traits;
use \Geniem\Theme\Taxonomy;

/**
 * Single task class
 */
class SingleTask implements Model {

    use Traits\FlexibleContent;
    use Traits\Breadcrumbs;

    /**
     * This defines the name of this model.
     */
    const NAME = 'SingleTask';

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
     * Get task custom fields.
     *
     * @return array
     */
    public function get_task_custom_fields() : array {
        return [
            [
                'value' => get_field( 'description' ) ?: null,
            ],
            [
                'label' => __( 'Time', 'vapaaehtoistoiminta' ),
                'value' => get_field( 'time' ) ?: null,
            ],
            [
                'label' => __( 'Location', 'nuhe' ),
                'value' => get_field( 'location' ) ?: null,
            ],
            [
                'label' => __( 'Requirements', 'vapaaehtoistoiminta' ),
                'value' => get_field( 'requirements' ) ?: null,
            ],
            [
                'label' => __( 'Organizer', 'vapaaehtoistoiminta' ),
                'value' => get_field( 'organizer' ) ?: null,
            ],
            [
                'label' => __( 'Additional information', 'vapaaehtoistoiminta' ),
                'value' => get_field( 'additional_information' ) ?: null,
            ],
        ];
    }

    /**
     * Get type terms.
     *
     * @return array|null
     */
    public function get_type_terms() {
        global $post;
        $terms = get_the_terms( $post, Taxonomy\TypeTax::SLUG );

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
