<?php
/**
 * Trait for VetList
 */

namespace Geniem\Theme\Traits;

use Geniem\Theme\PostType;

/**
 * Trait VetList
 *
 * @package Geniem\Theme\Traits
 */
trait VetList {

    use FeaturedImage;

    /**
     * Create list data
     *
     * @param array $data
     *
     * @return array
     */
    private function create_list_data( array $data ) : array {
        if ( empty( $data ) ) {
            return [];
        }

        $data['columns']        = $this->get_columns_count( $data['items'] );
        $columns_less_than_four = $data['columns'] < 4;
        $image_size             = $data['columns'] === 1 ? 'fullhd' : 'large';
        $data['anchor_id']      = $this->generate_anchor_id( $data['anchor_link_text'] ?? '', $data['title'] ?? '' );

        $data['items'] = array_map( function( $item ) use ( $columns_less_than_four, $image_size ) {
            $id       = $item->ID;
            $subtitle = $item->post_type === PostType\Activity::SLUG ? get_field( 'place_subtitle', $id ) : get_field( 'subtitle', $id );
            return (object) [
                'post_title'       => $item->post_title,
                'color_theme'      => 'color-theme-1',
                'image'            => $this->get_featured_image( $image_size, $id ),
                'subtitle'         => $subtitle ?: '',
                'link'             => get_permalink( $id ),
                'background_class' => $columns_less_than_four && ! \has_post_thumbnail( $id )
                                    ? ' has-bg-color color-theme-1'
                                    : '',
            ];
        }, $data['items'] );

        return $data;
    }

    /**
     * Get columns count.
     *
     * @param array $items Item to count.
     * @return int
     */
    private function get_columns_count( $items ) : int {
        $item_count = count( $items );
        return $item_count > 3 ? 4 : $item_count;
    }

    /**
     * Generate anchor id.
     *
     * @param string $anchor_link_text
     * @param string $title
     * @return string
     */
    private function generate_anchor_id( $anchor_link_text, $title ) : string {
        if ( empty( $anchor_link_text ) && empty( $title ) ) {
            return '';
        }

        $anchor_text = $title;
        $anchor_text = $anchor_link_text ?: $anchor_text;

        return \sanitize_title( $anchor_text );
    }
}
