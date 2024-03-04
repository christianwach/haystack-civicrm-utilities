<?php
/**
 * Autoloader class.
 *
 * Handles autoloading functionality.
 *
 * @package Haystack_CU
 * @since 1.0.0
 */

namespace Haystack_CU;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Autoloader class.
 *
 * A class that handles autoloading functionality.
 *
 * @since 1.0.0
 */
class Autoloader {

	/**
	 * Namespace.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $namespace;

	/**
	 * Plugin source path.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $source_path;

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param string $namespace The plugin namespace.
	 * @param string $source_path The plugin source path.
	 */
	public function __construct( $namespace, $source_path ) {
		$this->namespace   = $namespace;
		$this->source_path = $source_path;
		$this->register_autoloader();
	}

	/**
	 * Registers the autoloader.
	 *
	 * @since 1.0.0
	 *
	 * @return bool Whether the autoloader has been registered or not.
	 */
	private function register_autoloader() {
		return spl_autoload_register( [ $this, 'autoload' ] );
	}

	/**
	 * Loads classes on request.
	 *
	 * @since 1.0.0
	 *
	 * @param string $class The class to load.
	 */
	private function autoload( $class ) {

		// Deconstruct the namespaced class name.
		$class_parts = explode( '\\', $class );

		// Ditch the first entry, which is our namespace.
		$namespace = array_shift( $class_parts );

		// Bail if not our namespace.
		if ( $this->namespace !== $namespace ) {
			return;
		}

		// What remains needs to be lowercase.
		$class_parts = array_map( 'strtolower', $class_parts );

		// The final entry is our class name.
		$class_name = str_replace( '_', '-', array_pop( $class_parts ) );

		// Class parts now contains the path to the file.
		if ( empty( $class_parts ) ) {
			$path = 'class-' . $class_name . '.php';
		} else {

			// The "component" is the enclosing directory name.
			$component = array_pop( $class_parts );

			// What remains is the actual path.
			$class_path = str_replace( '_', '-', implode( DIRECTORY_SEPARATOR, $class_parts ) );

			// Build filename.
			$filename = 'class-' . $component . '-' . $class_name . '.php';

			// Join class path.
			$path = implode( DIRECTORY_SEPARATOR, [ $class_path, $component, $filename ] );

		}

		// Require file.
		$path = $this->source_path . DIRECTORY_SEPARATOR . $path;
		if ( file_exists( $path ) ) {
			require $path;
		}

	}

}
