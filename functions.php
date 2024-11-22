<?php
/**
 * Vapaaehtoistoiminta functions
 */

// Require the child theme autoloader.
require_once dirname( __FILE__ ) . '/lib/autoload.php';

// Require the main theme autoloader.
require_once get_template_directory() . '/lib/autoload.php';

// Theme setup
Geniem\Theme\VET::instance();

/**
 * Global helper function to fetch the Setup instance
 *
 * @return Geniem\Theme\VET
 */
function VET() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
    return Geniem\Theme\VET::instance();
}
