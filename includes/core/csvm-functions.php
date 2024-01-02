<?php
/**
 * The wrapper functions for the CSVM_Helper
 *
 * @package csvmapper
 * @author Tadamus <hello@tadamus.com>
 */

if ( ! function_exists( 'csvm_add_or_update_option' ) ) {
	/**
	 * Wrapper function for CSVM_Helper::add_or_update_option
	 *
	 * @since 1.0
	 *
	 * @param string $tag The tag name.
	 * @param string $value The value of the option.
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
	 * @param string      $url The url to redirect to.
	 * @param string|null $type The type of the redirect.
	 * @param string|null $message The message.
	 *
	 * @return void
	 */
	function csvm_redirect( string $url, string|null $type = null, string|null $message = null ): void {
		CSVM_Helper::redirect( $url, $type, $message );
	}
}

if ( ! function_exists( 'csvm_convert_to_slug' ) ) {
	/**
	 * Wrapper function for CSVM_Helper::convert_to_slug
	 *
	 * @since 1.0
	 *
	 * @param string $string The string that should be converted to slug.
	 *
	 * @return string
	 */
	function csvm_convert_to_slug( string $string ): string {
		return CSVM_Helper::convert_to_slug( $string );
	}
}
