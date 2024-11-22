<?php
/**
 * Fields for PostType\Task
 *
 * @package Geniem\Theme\ACF
 */

namespace Geniem\Theme\ACF;

use Geniem\ACF\Field;
use \Geniem\ACF\Group;
use \Geniem\ACF\RuleGroup;
use \Geniem\Theme\PostType;

/**
 * Class Task
 *
 * @package Geniem\Theme\ACF
 */
class Task {

    /**
     * CPT key.
     */
    const CPT_KEY = 'fg_task';

    /**
     * Task constructor.
     */
    public function __construct() {
        add_action( 'init', [ $this, 'register_cpt_fields' ] );
    }

    /**
     * Register fields
     */
    public function register_cpt_fields() {
        $field_group = new Group( 'Tehtävän lisäkentät' );
        $key         = self::CPT_KEY;

        $field_group->set_key( $key );

        $rule_group = new RuleGroup();
        $rule_group->add_rule( 'post_type', '==', PostType\Task::SLUG );
        $field_group->add_rule_group( $rule_group );

        $field_group->set_position( 'normal' );

        $strings = [
            'subtitle' => [
                'label' => 'Subtitle',
            ],
            'description' => [
                'label' => 'Description',
            ],
            'time' => [
                'label' => 'Time',
            ],
            'location' => [
                'label' => 'Location',
            ],
            'requirements' => [
                'label' => 'Requirements',
            ],
            'organizer' => [
                'label' => 'Organizer',
            ],
            'additional_information' => [
                'label' => 'Additional information',
            ],
        ];

        $subtitle = new Field\Text( $strings['subtitle']['label'] );
        $subtitle->set_key( "${key}_subtitle" );
        $subtitle->set_name( 'subtitle' );
        $subtitle->redipress_include_search();

        $description = new Field\Wysiwyg( $strings['description']['label'] );
        $description->set_key( "${key}_description" );
        $description->set_name( 'description' );

        $time = new Field\Text( $strings['time']['label'] );
        $time->set_key( "${key}_time" );
        $time->set_name( 'time' );

        $location = new Field\Wysiwyg( $strings['location']['label'] );
        $location->set_key( "${key}_location" );
        $location->set_name( 'location' );

        $requirements = new Field\TextArea( $strings['requirements']['label'] );
        $requirements->set_key( "${key}_requirements" );
        $requirements->set_name( 'requirements' );

        $organizer = new Field\Text( $strings['organizer']['label'] );
        $organizer->set_key( "${key}_organizer" );
        $organizer->set_name( 'organizer' );

        $additional_information = new Field\Wysiwyg( $strings['additional_information']['label'] );
        $additional_information->set_key( "${key}_additional_information" );
        $additional_information->set_name( 'additional_information' );

        $field_group->add_fields( [
            $subtitle,
            $description,
            $time,
            $location,
            $requirements,
            $organizer,
            $additional_information,
        ] );

        $field_group->register();
    }
}

new Task();
