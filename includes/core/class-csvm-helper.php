<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! class_exists( 'CSVM_Helper' ) ){
	class CSVM_Helper{
		/**
		 * If an option exists in the WordPress API it updates it. Or creates a new one if it doesn't exist
		 *
		 * @since 1.0.0
		 *
		 * @param $tag
		 * @param $value
		 *
		 * @return void
		 *
		 */
		public static function add_or_update_option( $tag, $value ): void
		{
			if( get_option( $tag ) ){
				update_option( $tag, $value );
			}

			add_option( $tag, $value );
		}

		/**
		 * Redirects people to a given URL with notifications if needed
		 *
		 * @since 1.0
		 *
		 * @param $url
		 * @param $type
		 * @param $message
		 *
		 * @return bool
		 */
		public static function redirect( $url, $type = null, $message = null ): bool
		{
			if ( $type != null && $message != null ){
				setcookie( 'csvm_redirect_type', $type, time() + 3600, '/' );
				setcookie( 'csvm_redirect_message', $message, time() + 3600, '/' );

				return wp_redirect( $url );
			}

			return wp_redirect( $url );
		}
	}
}

if( ! function_exists( 'add_or_update_option' ) ) {
	function add_or_update_option( $tag, $value ): void
	{
		CSVM_Helper::add_or_update_option( $tag, $value );
	}
}

if( ! function_exists( 'csvm_redirect' ) ){
	function csvm_redirect( $url, $type = null, $message = null ): bool
	{
		return CSVM_Helper::redirect( $url, $type, $message );
	}
}