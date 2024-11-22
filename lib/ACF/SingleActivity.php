<?php
/**
 * Fields for PostType\Activity
 *
 * @package Geniem\Theme\ACF
 */

namespace Geniem\Theme\ACF;

use Geniem\ACF\Field;
use \Geniem\ACF\Group;
use \Geniem\ACF\RuleGroup;
use \Geniem\Theme\PostType;

/**
 * Class Activity
 *
 * @package Geniem\Theme\ACF
 */
class Activity {

    /**
     * CPT key.
     */
    const CPT_KEY = 'fg_activity';

    /**
     * Activity constructor.
     */
    public function __construct() {
        add_action( 'init', [ $this, 'register_cpt_fields' ] );
    }

    /**
     * Register fields
     */
    public function register_cpt_fields() {
        $field_group = new Group( 'Toiminnan lisäkentät' );
        $key         = self::CPT_KEY;

        $field_group->set_key( $key );

        $rule_group = new RuleGroup();
        $rule_group->add_rule( 'post_type', '==', PostType\Activity::SLUG );
        $field_group->add_rule_group( $rule_group );

        $field_group->set_position( 'normal' );

        $strings = [
            'place_subtitle' => [
                'label' => 'Subtitle',
            ],
        ];

        $place_subtitle = new Field\Text( $strings['place_subtitle']['label'] );
        $place_subtitle->set_key( "${key}_place_subtitle" );
        $place_subtitle->set_name( 'place_subtitle' );
        $place_subtitle->redipress_include_search();

        $field_group->add_fields( [
            $place_subtitle,
        ] );

        $field_group->register();
    }
}

new Activity();
