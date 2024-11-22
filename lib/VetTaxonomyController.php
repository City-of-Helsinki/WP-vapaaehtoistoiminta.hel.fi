<?php
/**
 * This file controls taxonomy functionality.
 */

namespace Geniem\Theme;

/**
 * Define the controller class.
 */
class VetTaxonomyController {

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {
        add_filter( 'nuhe_type_tax_associated_post_types', \Closure::fromCallable( [ $this, 'associate_type_tax' ] ) );
        add_filter( 'nuhe_place_tag_tax_associated_post_types', \Closure::fromCallable( [ $this, 'associate_place_tag_tax' ] ) );
    }

    /**
     * Associate type tax
     *
     * @param array $post_types
     *
     * @return array
     */
    private function associate_type_tax( array $post_types ) : array {
        return array_merge( $post_types, [ \Geniem\Theme\PostType\Task::SLUG ] );
    }

    /**
     * Associate place tag tax
     *
     * @param array $post_types
     *
     * @return array
     */
    private function associate_place_tag_tax( array $post_types ) : array {
        return array_merge( $post_types, [ \Geniem\Theme\PostType\Activity::SLUG ] );
    }
}
