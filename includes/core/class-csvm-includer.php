<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists( 'CSVM_Includer' ) ) {
	class CSVM_Includer {
		/**
		 * Calls all the sub methods that require the files
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 *
		 */
		public function __construct() {
			$this->require_child_files_once( CSVM_INC_CORE );

			if( is_admin() ){
				$this->require_child_files_once( CSVM_INC_ADMIN );
			}

			$this->require_child_files_once( CSVM_INC_INTERFACES );
			$this->require_child_files_once( CSVM_INC_ABSTRACTS );
			$this->require_child_files_once( CSVM_INC_MODELS );
			$this->require_child_files_once( CSVM_INC_IMPORTS );
		}

		/**
		 * Requires all the PHP files in the given directory
		 *
		 * @since 1.0.0
		 *
		 * @param $dir
		 *
		 * @return void
		 *
		 */
		private function require_child_files_once( $dir ): void
		{
			foreach ( $this->get_files( $dir ) as $file ) {
				require_once $file;
			}
		}

		/**
		 * Loops through a directory and returns an array of PHP files
		 *
		 * @since 1.0.0
		 *
		 * @param $dir
		 *
		 * @return array
		 *
		 */
		private function get_files( $dir ): array
		{
			$dir_object = new \DirectoryIterator( $dir );
			$returnable = array();

			foreach ( $dir_object as $file ) {
				if ( $file->isDot() ) {
					continue;
				}

				if ( $file->isDir() ) {
					$returnable = array_merge( $returnable, $this->get_files( $dir . '/' . $file ) );
				}

				if ( $file->getExtension() != 'php' ) {
					continue;
				}

				$returnable[] = $dir . '/' . $file->getFilename();
			}

			return $returnable;
		}
	}
}