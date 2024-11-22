<?php
/**
 * This file controls search functionality.
 */

namespace Geniem\Theme;

use Geniem\Theme\PostType;

/**
 * Define the controller class.
 */
class VetSearchController {

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {
        add_filter( 'nuhe_modify_search_results_slugs', \Closure::fromCallable( [ $this, 'modify_search_results_slugs' ] ) );
    }

    /**
     * Modify search results slugs
     *
     * @param array $slugs
     *
     * @return array
     */
    private function modify_search_results_slugs( array $slugs ) : array {
        return [
            PostType\Page::SLUG => [
                _x( 'page', 'page in search results', 'nuhe' ),
                _x( 'pages', 'pages in search results', 'nuhe' ),
            ],
            PostType\Post::SLUG => [
                _x( 'article', 'article in search results', 'nuhe' ),
                _x( 'articles', 'articles in search results', 'nuhe' ),
            ],
            PostType\Task::SLUG => [
                _x( 'task', 'task in search results', 'vapaaehtoistoiminta' ),
                _x( 'tasks', 'tasks in search results', 'vapaaehtoistoiminta' ),
            ],
            PostType\Activity::SLUG => [
                _x( 'activity', 'activity in search results', 'vapaaehtoistoiminta' ),
                _x( 'activities', 'activities in search results', 'vapaaehtoistoiminta' ),
            ],
            'all_search_results' => [
                _x( 'search result', 'search result', 'nuhe' ),
                _x( 'search results', 'search results', 'nuhe' ),
            ],
        ];
    }
}
