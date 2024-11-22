<?php
/**
 * Define the Archive class.
 */

namespace Geniem\Theme\Model;

use \Geniem\Theme\Interfaces\Model;
use \Geniem\Theme\Traits;
use \Geniem\Theme\PostType;
use \Geniem\Theme\Taxonomy;
use \Geniem\Theme\Settings;

/**
 * Archive class.
 */
class Archive implements Model {

    use Traits\Breadcrumbs;
    use Traits\VetList;

    /**
     * This defines the name of this model.
     */
    const NAME = 'Archive';

    /**
     * Add hooks and filters from this model
     *
     * @return void
     */
    public function hooks() : void {
        \add_filter( 'pre_get_posts', \Closure::fromCallable( [ $this, 'modify_query' ] ) );
    }

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
     * Get posts.
     *
     * @return array
     */
    public function get_posts() : array {
        global $wp_query;

        if ( ! $wp_query->have_posts() ) {
            return [];
        }

        $data = [
            'items' => $wp_query->posts,
        ];

        return $this->create_list_data( $data );
    }

    /**
     * Modify query.
     *
     * @param \WP_Query $query The query to modify.
     *
     * @return void
     */
    private function modify_query( \WP_Query $query ) : void {

        // bail early if in admin  or not archive
        if ( is_admin() || ( ! $query->is_main_query() || ! $query->is_archive() ) ) {
            return;
        }

        $queried_object = \get_queried_object();

        if ( ! $queried_object instanceof \WP_Term ) {
            return;
        }

        $taxonomy = $queried_object->taxonomy;

        if ( $taxonomy !== Taxonomy\TypeTax::SLUG && $taxonomy !== Taxonomy\PlaceTagTax::SLUG ) {
            return;
        }

        $query->set( 'post_type', $taxonomy === Taxonomy\TypeTax::SLUG ? PostType\Task::SLUG : PostType\Activity::SLUG );
        $query->set( 'posts_per_page', -1 );
    }

    /**
     * Get taxonomy terms.
     *
     * @return array
     */
    public function get_taxonomy_terms() : array {
        $queried_object = \get_queried_object();

        if ( ! $queried_object instanceof \WP_Term ) {
            return [];
        }

        $current_term     = $queried_object;
        $current_taxonomy = $current_term->taxonomy;
        $current_term_id  = $current_term->term_id;

        $terms = get_terms( [
            'taxonomy' => $current_taxonomy,
        ] );

        if ( empty( $terms ) || ! is_array( $terms ) ) {
            return [];
        }

        return array_map( function( $term ) use ( $current_term_id ) {
            return [
                'url'       => get_term_link( $term ),
                'title'     => $term->name,
                'is_active' => $term->term_id === $current_term_id,
            ];
        }, $terms );
    }

    /**
     * Get taxonomy terms list title.
     *
     * @return string|null
     */
    public function get_taxonomy_terms_title() {
        return Settings::get_setting( 'category_list_title' ) ?: null;
    }
}
