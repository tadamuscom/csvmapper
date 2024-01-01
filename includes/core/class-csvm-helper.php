<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'CSVM_Helper' ) ) {
	class CSVM_Helper {

		/**
		 * If an option exists in the WordPress API it updates it. Or creates a new one if it doesn't exist
		 *
		 * @since 1.0
		 *
		 * @param string $tag
		 * @param string $value
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
		 * @param string      $url
		 * @param string|null $type
		 * @param string|null $message
		 *
		 * @return void
		 */
		public static function redirect( string $url, string|null $type = null, string|null $message = null ): void {
			if ( $type != null && $message != null ) {
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
		 * @param string $string
		 *
		 * @return string
		 */
		public static function convert_to_slug( string $string ): string {
			$string = strtolower( $string );

			return str_replace( ' ', '-', $string );
		}
	}
}

if ( ! function_exists( 'csvm_add_or_update_option' ) ) {
	/**
	 * Wrapper function for CSVM_Helper::add_or_update_option
	 *
	 * @since 1.0
	 *
	 * @param string $tag
	 * @param string $value
	 *
	 * @return void
	 */
	function csvm_add_or_update_option( string $tag, string $value ): void {
		CSVM_Helper::add_or_update_option( $tag, $value );
	}
}

if ( ! function_exists( 'csvm_redirect' ) ) {
	/**
	 * Wrapper function for CSVM_Helper::redirect
	 *
	 * @since 1.0
	 *
	 * @param string      $url
	 * @param string|null $type
	 * @param string|null $message
	 *
	 * @return bool
	 */
	function csvm_redirect( string $url, string|null $type = null, string|null $message = null ): bool {
		return CSVM_Helper::redirect( $url, $type, $message );
	}
}

if ( ! function_exists( 'csvm_convert_to_slug' ) ) {
	/**
	 * Wrapper function for CSVM_Helper::convert_to_slug
	 *
	 * @since 1.0
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	function csvm_convert_to_slug( string $string ): string {
		return CSVM_Helper::convert_to_slug( $string );
	}
}
