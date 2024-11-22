<?php
/**
 * TaskList ACF-layout
 */

namespace Geniem\Theme\ACF\Layouts;

use Geniem\ACF\Field;
use Geniem\Theme\PostType;

/**
 * Define the TaskList class.
 */
class TaskList extends Field\Flexible\Layout {

    /**
     * Layout key
     */
    const KEY = '_task_list';

    /**
     * Create the layout
     *
     * @param string $key Key from the flexible content.
     */
    public function __construct( $key ) {
        $label = 'Tehtävien listaus';
        $key   = $key . self::KEY;
        $name  = 'task_list';

        parent::__construct( $label, $key, $name );

        $this->add_layout_fields();
    }

    /**
     * Add layout fields
     *
     * @return void
     */
    private function add_layout_fields() : void {
        $key = $this->key;

        $strings = [
            'bg_color' => [
                'label' => 'Taustaväri',
            ],
            'title' => [
                'label' => 'Otsikko',
            ],
            'description' => [
                'label' => 'Kuvausteksti',
            ],
            'items' => [
                'label' => 'Lisää / poista tehtäviä',
            ],
            'link' => [
                'title'        => 'Linkki',
                'instructions' => 'Vapaavalintainen linkki tehtävien jälkeen',
            ],
            'anchor_link_text' => [
                'title'        => 'Ankkurilinkki',
                'instructions' => 'Jos haluat osioon johtavan linkin olevan muu kuin osion otsikko on, laita tähän haluamasi linkkiteksti.',
            ],
        ];

        $bg_color_field = new Field\Select( $strings['bg_color']['label'] );
        $bg_color_field->set_key( "${key}_bg_color" );
        $bg_color_field->set_name( 'bg_color' );
        $colors = [
            'white' => 'Valkoinen',
            'grey'  => 'Harmaa',
        ];
        $colors = apply_filters( 'nuhe_acf_color_choices', $colors );
        $bg_color_field->set_choices( $colors );
        $bg_color_field->set_default_value( 'white' );
        $this->add_field( $bg_color_field );

        $title_field = new Field\Text( $strings['title']['label'] );
        $title_field->set_key( "${key}_title" );
        $title_field->set_name( 'title' );
        $title_field->redipress_include_search();
        $this->add_field( $title_field );

        // Anchor link
        $anchor_link_text = new Field\Text( $strings['anchor_link_text']['title'] );
        $anchor_link_text->set_key( "${key}_anchor_link_text" );
        $anchor_link_text->set_name( 'anchor_link_text' );
        $anchor_link_text->set_instructions( $strings['anchor_link_text']['instructions'] );
        $this->add_field( $anchor_link_text );

        $description_field = new Field\TextArea( $strings['description']['label'] );
        $description_field->set_key( "${key}_description" );
        $description_field->set_name( 'description' );
        $description_field->set_new_lines();
        $description_field->redipress_include_search();
        $this->add_field( $description_field );

        $items_field = new Field\Relationship( $strings['items']['label'] );
        $items_field->set_key( "${key}_items" );
        $items_field->set_post_types( [ PostType\Task::SLUG ] );
        $items_field->set_name( 'items' );
        $items_field->set_min( 1 );
        $items_field->set_max( 16 );
        $items_field->set_filters( [ 'search' ] );
        $this->add_field( $items_field );

        $link_label = $strings['link']['title'];
        $link_field = new Field\Link( $link_label );
        $link_field->set_key( "${key}_link" );
        $link_field->set_name( 'link' );
        $link_field->set_instructions( $strings['link']['instructions'] );
        $this->add_field( $link_field );
    }
}
