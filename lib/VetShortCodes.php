<?php
/**
 * This file controls shortcodes
 */

namespace Geniem\Theme;

/**
 * Define the controller class.
 */
class VetShortCodes {

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {
        $this->add_shortcodes();
    }

    /**
     * This registers all shortcodes.
     *
     * @return void
     */
    private function add_shortcodes() {
        add_shortcode( 'button', \Closure::fromCallable( [ $this, 'add_button_shortcode' ] ) );
    }

    /**
     * Add button shortcode.
     *
     * @param array $atts Array of attributes.
     *
     * @return string|bool
     */
    private function add_button_shortcode( array $atts ) {
        shortcode_atts( [
            'class'      => '',
            'text'       => 'Submit',
            'url'        => '#',
            'aria_label' => '',
        ], $atts );

        ob_start();

        get_template_part( 'partials/link', '', [
            'title'      => $atts['text'],
            'url'        => $atts['url'],
            'icon-start' => 'angle-right',
            'aria-label' => $atts['aria_label'],
        ] );

        return ob_get_clean();
    }
}
