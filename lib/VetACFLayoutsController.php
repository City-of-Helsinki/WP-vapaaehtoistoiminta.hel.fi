<?php
/**
 * This file controls acf layouts.
 */

namespace Geniem\Theme;

use Geniem\ACF\Field;
use Geniem\ACF\Field\FlexibleContent;
use Geniem\Theme\ACF\Layouts;
use Geniem\Theme\Traits;
use Geniem\Theme\Model\PageFullWidth;

/**
 * Define the controller class.
 */
class VetACFLayoutsController {

    use Traits\VetList;

    /**
     * Constructor
     */
    public function __construct() {
        add_filter( 'nuhe_acf_flexible_content_fc', \Closure::fromCallable( [ $this, 'modify_layouts' ] ), 10, 2 );
        add_filter( 'nuhe_acf_layout_tax_term_list', \Closure::fromCallable( [ $this, 'layout_tax_term_list' ] ) );
        add_filter( 'nuhe_acf_layout_activity_list', \Closure::fromCallable( [ $this, 'layout_activity_list' ] ) );
        add_filter( 'nuhe_acf_layout_task_list', \Closure::fromCallable( [ $this, 'layout_task_list' ] ) );
        add_filter( 'nuhe_modify_page_modules_rules', \Closure::fromCallable( [ $this, 'modify_page_modules_rules' ] ) );
        add_filter( 'nuhe_modify_page_sidebar_rules', \Closure::fromCallable( [ $this, 'modify_page_sidebar_rules' ] ) );
    }

    /**
     * Modify page modules rules
     *
     * @param array $rules
     *
     * @return array
     */
    private function modify_page_modules_rules( array $rules ) : array {
        $vet_rules = [
            [
                'key'      => 'post_type',
                'value'    => PostType\Activity::SLUG,
                'operator' => '==',
            ],
            [
                'key'      => 'post_type',
                'value'    => PostType\Task::SLUG,
                'operator' => '==',
            ],
        ];

        return array_merge( $rules, $vet_rules );
    }

    /**
     * Modify page sidebar rules
     *
     * @param array $rules
     *
     * @return array
     */
    private function modify_page_sidebar_rules( array $rules ) : array {

        if ( empty( $rules[0]['and'] ) ) {
            return $rules;
        }

        $rules[0]['and'][] = [
            'key'      => 'page_template',
            'value'    => PageFullWidth::TEMPLATE,
            'operator' => '!=',
        ];

        return $rules;
    }

    /**
     * Modify layouts.
     *
     * @param FlexibleContent $flexible_content
     *
     * @param string $key
     *
     * @return FlexibleContent
     */
    private function modify_layouts( FlexibleContent $flexible_content, string $key ) : FlexibleContent {
        $flexible_content->add_layout( new Layouts\TaxonomyTermList( $key ) );
        $flexible_content->add_layout( new Layouts\ActivityList( $key ) );
        $flexible_content->add_layout( new Layouts\TaskList( $key ) );

        $layoyts = $flexible_content->get_layouts();

        if ( ! empty( $layoyts['initiative_list'] ) ) {
            $flexible_content->remove_layout( 'initiative_list' );
        }

        return $flexible_content;
    }

    /**
     * Layout tax term list
     *
     * @param array $layout
     *
     * @return array
     */
    private function layout_tax_term_list( array $layout ) : array {
        $layout['terms'] = [];

        if ( empty( $layout ) || empty( $layout['taxonomy'] ) ) {
            return $layout;
        }

        $terms = get_terms( [
            'taxonomy' => $layout['taxonomy'],
        ] );

        if ( empty( $terms ) || ! is_array( $terms ) ) {
            return $layout;
        };

        $layout['terms'] = array_map( function( $term ) {
            return [
                'url'   => get_term_link( $term ),
                'title' => $term->name,
            ];
        }, $terms );

        return $layout;
    }

    /**
     * Layout activity list
     *
     * @param array $layout
     *
     * @return array
     */
    private function layout_activity_list( array $layout ) : array {
        return $this->create_list_data( $layout );
    }

    /**
     * Layout task list
     *
     * @param array $layout
     *
     * @return array
     */
    private function layout_task_list( array $layout ) : array {
        return $this->create_list_data( $layout );
    }
}
