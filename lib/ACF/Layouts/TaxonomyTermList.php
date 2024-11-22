<?php
/**
 * TaxonomyTermList ACF-layout
 */

namespace Geniem\Theme\ACF\Layouts;

use Geniem\ACF\Field;
use Geniem\Theme\Taxonomy;

/**
 * Define the TaxonomyTermList class.
 */
class TaxonomyTermList extends Field\Flexible\Layout {

    /**
     * Layout key
     */
    const KEY = '_tax_term_list';

    /**
     * Create the layout
     *
     * @param string $key Key from the flexible content.
     */
    public function __construct( $key ) {
        $label = 'Kategorialistaus';
        $key   = $key . self::KEY;
        $name  = 'tax_term_list';

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
            'title' => [
                'label' => 'Otsikko',
            ],
            'description' => [
                'label' => 'Kuvausteksti',
            ],
            'taxonomy' => [
                'label'   => 'Valitse listattavat kategoriat',
                'choices' => [
                    Taxonomy\PlaceTagTax::SLUG => 'Toiminnan paikat',
                    Taxonomy\TypeTax::SLUG     => 'Tehtävien tyypit',
                ],
            ],
        ];

        $title_field = new Field\Text( $strings['title']['label'] );
        $title_field->set_key( "${key}_title" );
        $title_field->set_name( 'title' );
        $this->add_field( $title_field );

        $description_field = new Field\TextArea( $strings['description']['label'] );
        $description_field->set_key( "${key}_description" );
        $description_field->set_name( 'description' );
        $description_field->set_new_lines();
        $this->add_field( $description_field );

        $taxonomy_field = new Field\Radio( $strings['taxonomy']['label'] );
        $taxonomy_field->set_key( "${key}_taxonomy" );
        $taxonomy_field->set_name( 'taxonomy' );
        $taxonomy_field->set_choices( $strings['taxonomy']['choices'] );
        $this->add_field( $taxonomy_field );
    }

}
