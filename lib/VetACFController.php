<?php
/**
 * This file controls acf functionality.
 */

namespace Geniem\Theme;

use Geniem\ACF\Field;
use Geniem\ACF\Field\FlexibleContent;
use Geniem\Theme\ACF\Layouts;

/**
 * Define the controller class.
 */
class VetACFController {

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {
        add_action( 'acf/init', \Closure::fromCallable( [ $this, 'require_acf_files' ] ) );
        add_filter( 'nuhe_settings_footer_fields', \Closure::fromCallable( [ $this, 'modify_footer_fields' ] ) );
        add_filter( 'nuhe_modify_settings_fields', \Closure::fromCallable( [ $this, 'add_tab_to_settings' ] ), 10, 2 );
        add_filter( 'nuhe_modify_settings_fields', \Closure::fromCallable( [ $this, 'remove_initiative_fallback_image' ] ), 10, 2 );
        add_filter( 'nuhe_acf_color_choices', \Closure::fromCallable( [ $this, 'modify_color_choices' ] ) );
        add_filter( 'nuhe_modify_search_post_types', \Closure::fromCallable( [ $this, 'modify_search_post_types' ] ) );

        new VetACFLayoutsController();
    }

    /**
     * This method loops through all files in the
     * ACF directory and requires them.
     */
    private function require_acf_files() {
        $files = array_diff( scandir( __DIR__ . '/ACF' ), [ '.', '..', 'Fields', 'Layouts' ] );

        // Loop through all files and directories except Fields where we store utility fields.
        array_walk(
            $files,
            function ( $file ) {
                require_once __DIR__ . '/ACF/' . basename( $file );
            }
        );
    }

    /**
     * Modify color choices.
     *
     * @param array $colors Color choices.
     *
     * @return array Array of color choices.
     */
    private function modify_color_choices( array $colors ) : array {
        $colors['black']          = 'Musta';
        $colors['yellow']         = 'Keltainen';
        $colors['yellow-lighter'] = 'Keltainen (vaalea)';
        $colors['green']          = 'Vihreä';
        $colors['green-lighter']  = 'Vihreä (vaalea)';
        $colors['green-dark']     = 'Vihreä (tumma)';
        $colors['pink']           = 'Pinkki';
        $colors['pink-lighter']   = 'Pinkki (vaalea)';
        $colors['grey']           = 'Harmaa';
        $colors['grey-lighter']   = 'Harmaa (vaalea)';
        $colors['near-white']     = 'Melkein valkoinen';
        $colors['blue']           = 'Sininen';
        $colors['blue-bright']    = 'Sininen (kirkas)';
        $colors['blue-light']     = 'Sinnen (vaalea)';
        $colors['turquoise']      = 'Turkoosi';
        $colors['white']          = 'Valkoinen';
        $colors['almost-black']   = 'Melkein musta';

        return $colors;
    }

    /**
     * Modify footer fields.
     *
     * @param array $footer_fields ACF settings footer fields.
     */
    private function modify_footer_fields( array $footer_fields ) : array {
        $footer_strings = [
            'some_links' => [
                'label' => 'Sosiaalisen median linkit',
            ],
            'some_link' => [
                'label' => 'Linkki',
            ],
            'some_source' => [
                'label' => 'Valitse palvelun ikoni',
            ],
        ];

        $key = 'settings';

        // Some links
        $some_links = new Field\Repeater( $footer_strings['some_links']['label'] );
        $some_links->set_key( "${key}_some_links" );
        $some_links->set_name( 'some_links' );

        // Some link
        $some_link = new Field\URL( $footer_strings['some_link']['label'] );
        $some_link->set_key( "${key}_some_link" );
        $some_link->set_name( 'some_link' );
        $some_link->set_required();

        // Some source
        $some_source = new Field\Select( $footer_strings['some_source']['label'] );
        $some_source->set_key( "${key}_some_source" );
        $some_source->set_name( 'some_source' );
        $some_source->set_required();
        $some_source->set_choices( [
            'instagram' => 'Instagram',
            'snapchat'  => 'SnapChat',
            'tiktok'    => 'TikTok',
            'facebook'  => 'Facebook',
            'discord'   => 'Discord',
            'twitter'   => 'Twitter',
            'youtube'   => 'YouTube',
        ] );

        $some_links->add_fields( [
            $some_link,
            $some_source,
        ] );

        $footer_fields[] = $some_links;

        return $footer_fields;
    }

    /**
     * Add tab to settings
     *
     * @param \Geniem\ACF\Group $field_group
     * @param string            $key
     *
     * @return \Geniem\ACF\Group
     */
    private function add_tab_to_settings( \Geniem\ACF\Group $field_group, string $key ) : \Geniem\ACF\Group {
        $strings = [
            'tab'                 => 'Kategorian sivu',
            'category_list_title' => [
                'label'        => 'Kategorialistauksen otsikko',
                'instructions' => 'Voit kirjoittaa tähän otsikon, joka näytetään ennen kategorian sivulla olevaa kategoriapilveä',
            ],
        ];

        $tab = new Field\Tab( $strings['tab'] );
        $tab->set_placement( 'left' );

        $category_list_title = new Field\Text( $strings['category_list_title']['label'] );
        $category_list_title->set_key( "${key}_category_list_title" );
        $category_list_title->set_name( 'category_list_title' );
        $category_list_title->set_instructions( $strings['category_list_title']['instructions'] );

        $tab->add_field( $category_list_title );

        $field_group->add_field( $tab );

        return $field_group;
    }

    /**
     * Remove initiative fallback image
     *
     * @param \Geniem\ACF\Group $field_group
     * @param string            $key
     *
     * @return \Geniem\ACF\Group
     */
    private function remove_initiative_fallback_image( \Geniem\ACF\Group $field_group, string $key ) : \Geniem\ACF\Group {
        $fields = $field_group->get_fields();

        if ( empty( $fields['Yleinen'] ) ) {
            return $field_group;
        }

        $fields['Yleinen']->remove_field( 'default_initiative_image' );

        return $field_group;
    }

    /**
     * Modify search post types
     *
     * @param array $post_types
     *
     * @return array
     */
    private function modify_search_post_types( array $post_types ) : array {
        return [
            PostType\Post::SLUG     => __( 'Articles', 'nuhe' ),
            PostType\Page::SLUG     => __( 'Pages', 'nuhe' ),
            PostType\Activity::SLUG => __( 'Activities', 'vapaaehtoistoiminta' ),
            PostType\Task::SLUG     => __( 'Tasks', 'vapaaehtoistoiminta' ),
        ];
    }
}
