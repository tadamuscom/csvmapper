<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! interface_exists('CSVM_Base_Import_Type' ) ){
	interface CSVM_Import_Type{
		/**
		 * Execution mechanism of each import
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		public function execute(): void;
	}
}