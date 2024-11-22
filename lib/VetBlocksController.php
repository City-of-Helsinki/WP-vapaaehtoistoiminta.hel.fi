<?php
/**
 * This class handles the registration of Gutenberg blocks.
 */

namespace Geniem\Theme;

/**
 * Class Blocks.
 */
class VetBlocksController implements Interfaces\Controller {

    /**
     * Initialize the class' variables and add methods
     * to the correct action hooks.
     *
     * @return void
     */
    public function hooks() : void {

        // Modify allowed block types.
        \add_filter( 'modify_allowed_block_types', \Closure::fromCallable( [ $this, 'allowed_block_types' ] ) );

        // Require ACF blocks
        \add_action( 'acf/init', \Closure::fromCallable( [ $this, 'require_block_files' ] ) );

    }

    /**
     * This method loops through all files in the
     * Blocks directory and requires them.
     */
    private function require_block_files() : void {
        $files = array_diff( scandir( __DIR__ . '/Blocks' ), [ '.', '..', 'BaseBlock.php' ] );

        // Require
        array_walk(
        // Loop through all files and directories
            $files,
            function ( $block ) {
                $block_class_name = str_replace( '.php', '', $block );
                $class_name       = __NAMESPACE__ . "\\Blocks\\{$block_class_name}";

                if ( class_exists( $class_name ) ) {
                    new $class_name();
                }
            }
        );
    }

    /**
     * Set the allowed block types.
     *
     * @param array $allowed_blocks Array of blocks.
     *
     * @return array
     */
    private function allowed_block_types( $allowed_blocks ) {
        $blocks = [
            'acf/linked-events' => [],
        ];

        return array_merge( $allowed_blocks, $blocks );
    }
}
