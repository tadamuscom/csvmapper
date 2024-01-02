<?php
/**
 * Small functionalities that could not have their own classes
 *
 * @package csvmapper
 * @author Tadamus <hello@tadamus.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'CSVM_Helper' ) ) {
	/**
	 * Small functionalities that could not have their own classes
	 */
	class CSVM_Helper {
		/**
		 * If an option exists in the WordPress API it updates it. Or creates a new one if it doesn't exist
		 *
		 * @since 1.0
		 *
		 * @param string $tag The tag name.
		 * @param string $value The value for the option.
		 *
		 * @return void
		 */
		public static function add_or_update_option( string $tag, string $value ): void {
			if ( get_option( $tag ) ) {
				update_option( $tag, $value );
			}

			add_option( $tag, $value );
		}

		/**
		 * Redirects people to a given URL with notifications if needed
		 *
		 * @since 1.0
		 *
		 * @param string      $url The URL to redirect to.
		 * @param string|null $type The type of the redirect.
		 * @param string|null $message The message.
		 *
		 * @return void
		 */
		public static function redirect( string $url, string|null $type = null, string|null $message = null ): void {
			if ( null !== $type && null !== $message ) {
				setcookie( 'csvm_redirect_type', $type, time() + 3600, '/' );
				setcookie( 'csvm_redirect_message', $message, time() + 3600, '/' );

				wp_redirect( $url );
				die();
			}

			wp_redirect( $url );
			die();
		}

		/**
		 * Converts the given string to lowercase slug with no whitespace
		 *
		 * @since 1.0
		 *
		 * @param string $string The string that should be converted to slug.
		 *
		 * @return string
		 */
		public static function convert_to_slug( string $string ): string {
			$string = strtolower( $string );

			return str_replace( ' ', '-', $string );
		}
	}
}
