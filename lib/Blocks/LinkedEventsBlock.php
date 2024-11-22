<?php
/**
 * Create and register the LinkedEventsBlock
 */

namespace Geniem\Theme\Blocks;

use \Geniem\ACF\Field\DatePicker;
use \Geniem\ACF\Field\Message;
use \Geniem\ACF\Field\Number;
use \Geniem\ACF\Field\Select;
use \Geniem\Theme\Integrations\LinkedEvents;
use \Geniem\Theme\LinkedEventsSettings;

/**
 * LinkedEventsBlock class.
 */
class LinkedEventsBlock extends BaseBlock {

    /**
     * The block name (slug, not shown in admin).
     *
     * @var string
     */
    const NAME = 'linked-events';

    /**
     * Rest namespace.
     *
     * @var string
     */
    const RESTNAMESPACE = 'linkedevents/v1';

    /**
     * Create the block and register it.
     */
    public function __construct() {
        // Define block title.
        $this->title       = 'LinkedEvents';
        $this->prefix      = static::NAME . '_';
        $this->category    = 'common';
        $this->icon        = 'tickets';
        $this->description = 'Näytä LinkedEvents tapahtumia';
        $this->mode        = 'edit';

        parent::__construct();

        $this->hooks();

        \add_action( 'rest_api_init', \Closure::fromCallable( [ $this, 'register_rest_route' ] ) );
        \add_filter( 'nuhe_modify_localized_data', \Closure::fromCallable( [ $this, 'add_events_data_to_localized_array' ] ) );
    }

    /**
     * Hooks.
     */
    public function hooks() : void {
        add_filter(
            'acf/load_field/key=' . $this->prefix . 'keyword',
            \Closure::fromCallable( [ $this, 'fill_keywords' ] )
        );

        add_filter(
            'acf/load_field/key=' . $this->prefix . 'places',
            \Closure::fromCallable( [ $this, 'fill_places' ] )
        );
    }

    /**
     * Create block fields.
     *
     * @return array
     */
    public function fields() : array {
        $strings = [
            'start'    => [
                'label'        => __( 'Start date', 'wp-linked-events' ),
                'instructions' => __( '', 'wp-linked-events' ),
            ],
            'end'      => [
                'label'        => __( 'End date', 'wp-linked-events' ),
                'instructions' => __( '', 'wp-linked-events' ),
            ],
            'keywords' => [
                'label'        => __( 'Keywords', 'wp-linked-events' ),
                'instructions' => __( '', 'wp-linked-events' ),
            ],
            'places'   => [
                'label'        => __( 'Places', 'wp-linked-events' ),
                'instructions' => __( '', 'wp-linked-events' ),
            ],
            'limit'    => [
                'label'        => __( 'Limit', 'wp-linked-events' ),
                'instructions' => '',
            ],
            'event_type'    => [
                'label'        => __( 'Types', 'wp-linked-events' ),
                'instructions' => 'You can select multiple options. Default: Events.',
            ],
        ];

        $key = $this->prefix;

        $start_field = ( new DatePicker( $strings['start']['label'] ) )
            ->set_key( "{$key}start" )
            ->set_name( 'start' )
            ->set_display_format( 'd.m.Y' )
            ->set_return_format( 'Y-m-d' )
            ->set_instructions( $strings['start']['instructions'] );

        $end_field = ( new DatePicker( $strings['end']['label'] ) )
            ->set_key( "{$key}end" )
            ->set_name( 'end' )
            ->set_display_format( 'd.m.Y' )
            ->set_return_format( 'Y-m-d' )
            ->set_instructions( $strings['end']['instructions'] );

        $event_type = ( new Select( $strings['event_type']['label'] ) )
            ->set_key( "{$key}event_type" )
            ->set_name( 'event_type' )
            ->use_ui()
            ->allow_multiple()
            ->set_choices(
                [
                    'General'      => __( 'Events' ),
                    'Course'       => __( 'Courses' ),
                    'Volunteering' => __( 'Volunteering' ),
                ]
            )
            ->set_default_value( 'General' )
            ->set_instructions( $strings['event_type']['instructions'] );

        $keywords_field = ( new Select( $strings['keywords']['label'] ) )
            ->set_key( "{$key}keyword" )
            ->set_name( 'keyword' )
            ->use_ui()
            ->allow_multiple()
            ->use_ajax()
            ->set_instructions( $strings['keywords']['instructions'] );

        $places_field = ( new Select( $strings['places']['label'] ) )
            ->set_key( "{$key}places" )
            ->set_name( 'places' )
            ->use_ui()
            ->allow_multiple()
            ->use_ajax()
            ->set_instructions( $strings['places']['instructions'] );

        $limit_field = ( new Number( $strings['limit']['label'] ) )
            ->set_key( "{$key}limit" )
            ->set_name( 'limit' )
            ->set_instructions( $strings['limit']['instructions'] );

        $block_name_field = new Message( $this->title, 'block_name_field_' . static::NAME, 'block_name_field' );

        return [
            $block_name_field,
            $start_field,
            $end_field,
            $event_type,
            $keywords_field,
            $places_field,
            $limit_field,
        ];
    }

    /**
     * This filters the block ACF data.
     *
     * @param array             $data       Block's ACF data.
     * @param \Geniem\ACF\Block $instance   The block instance.
     * @param array             $block      The original ACF block array.
     * @param string            $content    The HTML content.
     * @param bool              $is_preview A flag that shows if we're in preview.
     * @param int               $post_id    The parent post's ID.
     *
     * @return array The block data.
     */
    public function filter_data( $data, $instance, $block, $content, $is_preview, $post_id ) : array { // phpcs:ignore
        return $this->get_events( $data );
    }

    /**
     * Get events.
     *
     * @param array|mixed $data Data.
     *
     * @return array
     */
    private function get_events( $data ) : array {
        if ( $data === false ) {
            return [];
        }

        $limit_set = empty( $data['limit'] ) ? false : true;

        $settings  = new LinkedEventsSettings();
        $params    = $this->format_params( $data );
        $cache_key = 'wp-linked-events-block-' . md5( json_encode( $params ) );
        $response  = get_transient( $cache_key );

        if ( ! $response ) {
            $response = ( new LinkedEvents\ApiClient() )->get( 'event', $params );

            set_transient( $cache_key, $response, HOUR_IN_SECONDS );
        }

        if ( $response && ! empty( $response->data ) ) {

            $event_settings = [
                'event_page_id' => $settings->get_event_page(),
            ];

            $data['events'] = array_map( function ( $event ) use ( $event_settings ) {
                $event_data = new LinkedEvents\Entities\Event( $event, $event_settings );
                $image      = $event_data->get_primary_image();
                $keywords   = $event_data->get_keywords( 3 );
                $tickets    = $event_data->get_offers();

                if ( ! empty( $image ) ) {
                    $image = (object) [
                        'url' => $image->get_url(),
                        'alt' => $image->get_alt_text(),
                    ];
                }

                if ( ! empty( $keywords ) ) {
                    $keywords = array_map( function( $keyword ) {
                        return $keyword->get_name();
                    }, $keywords );
                }

                if ( ! empty( $tickets ) ) {
                    $tickets = array_map( function( $ticket ) {
                        return (object) [
                            'is_free'      => $ticket->is_free(),
                            'is_free_text' => esc_html__( 'Free', 'nuhe' ),
                            'price'        => $ticket->get_price(),
                        ];
                    }, $tickets );
                }

                return (object) [
                    'image'                 => $image,
                    'keywords'              => $keywords,
                    'permalink'             => $event_data->get_permalink(),
                    'formatted_time_string' => $event_data->get_formatted_time_string(),
                    'name'                  => $event_data->get_name(),
                    'location_string'       => $event_data->get_location_string(),
                    'tickets'               => $tickets,
                ];

            }, $response->data ?? [] );
        }

        $data['load_more_params'] = null;

        if ( ! empty( $response->meta->next ) && ! $limit_set ) {
            $params = parse_url( $response->meta->next );
            $data['load_more_params'] = $params['query'];
            parse_str( $params['query'], $params );

            $data['count'] = intval( $response->meta->count ) - ( intval( $params['page'] ?? 1 ) - 1 ) * intval( $params['page_size'] ?? 1 );
        }

        return $data;
    }
    /**
     * Format params
     *
     * @param array $data Block data.
     *
     * @return array
     */
    public function format_params( array $data ) : array {
        global $post;

        $params = [
            'start'       => empty( $data['start'] )
                ? date( 'Y-m-d' )
                : $data['start'],
            'include'     => 'organizer,location,keywords',
            'super_event' => 'none',
            'sort'        => 'start_time',
            'page_size'   => $data['limit'] ?: 20,
            'page'        => intval( $data['page'] ?? 1 ),
        ];

        if ( ! empty( $data['end'] ) ) {
            $params['end'] = $data['end'];
        }

        if ( ! empty( $data['keyword'] ) ) {
            $params['keyword'] = is_array( $data['keyword'] )
                ? implode( ',', $data['keyword'] )
                : $data['keyword'];
        }

        if ( ! empty( $data['event_type'] ) ) {
            $params['event_type'] = implode( ',', $data['event_type'] );
        }

        if ( ! empty( $data['places'] ) ) {
            if ( is_array( $data['places'] ) ) {
                $data['places'] = implode( ',', $data['places'] );
            }

            $params['location'] = $data['places'];
        }

        return apply_filters(
            'wp_linked_events_event_block_params',
            $params,
            $post->ID ?? null
        );
    }

    /**
     * Fill field choices from settings or from API.
     *
     * @param array $field ACF field.
     *
     * @return array
     */
    public function fill_keywords( array $field ) : array {
        $keywords = ( new LinkedEventsSettings() )->get_event_keywords();

        if ( empty( $keywords ) ) {
            return ( new LinkedEventsSettings() )->fill_event_keyword_group_keywords_choices( $field );
        }

        $choices = [];

        foreach ( $keywords as $value ) {
            $id_list             = implode( ',', $value['event_keyword_group_keywords'] );
            $choices[ $id_list ] = $value['event_keyword_group_text'];
        }

        $field['choices'] = $choices;

        return $field;
    }

    /**
     * Fill field places choices from settings or from API.
     *
     * @param array $field ACF field.
     *
     * @return array
     */
    public function fill_places( array $field ) : array {
        $settings = new LinkedEventsSettings();
        $choices  = $settings->get_places_from_options();

        if ( empty( $choices ) ) {
            return $field;
        }

        $field['choices'] = $choices;

        return $field;
    }

    /**
     * Register rest route
     *
     * @return void
     */
    public function register_rest_route() : void {
        register_rest_route(
            self::RESTNAMESPACE,
            'events',
            [
                'methods'             => 'POST',
                'callback'            => \Closure::fromCallable( [ $this, 'get_linked_events' ] ),
                'permission_callback' => '__return_true',
            ]
        );
    }

    /**
     * Get linked events.
     *
     * @param \WP_REST_Request $request 
     *
     * @return string|void
     */
    private function get_linked_events( \WP_REST_Request $request ) {
        if ( empty( $request->get_param( 'loadMoreParams' ) ) ) {
            return;
        }

        parse_str( $request->get_param( 'loadMoreParams' ), $parts );

        return $this->get_events( $parts );

    }

    /**
     * Add events data to localized array.
     *
     * @param array $localized_data
     *
     * @return array
     */
    private function add_events_data_to_localized_array( $localized_data ) : array {
        $localized_data['restApiUrl'] = get_rest_url( null, self::RESTNAMESPACE . '/events' );

        return $localized_data;
    }
}
