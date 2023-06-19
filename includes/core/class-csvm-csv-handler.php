<?php

if( ! class_exists( 'CSVM_CSV_Handler' ) ){
	class CSVM_CSV_Handler{
		private CSVM_Run $run;
		private CSVM_Import $import;

		public function __construct( CSVM_Run $run ) {
			$this->run = $run;
			$this->import = new CSVM_Import( $run->import_id );
		}

		/**
		 * Starts the CSV process
		 *
		 * @since 1.0
		 *
		 * @param string|bool $start
		 * @param string|bool $limit
		 *
		 * @return void
		 */
		public function start( string|bool $start = false, string|bool $limit = false ): void
		{
			$file = fopen( $this->import->file_path, 'r' );

			if( ! $file ){
				wp_die( __('CSV File couldn\'t be open', 'csvmapper') );
			}

			if( ! $start && ! $limit ){
				$this->complete_run( $file );
			}

			fclose($file);
			die();
		}

		/**
		 * Runs through the whole file and triggers the creation actions
		 *
		 * @since 1.0
		 *
		 * @param $file
		 *
		 * @return void
		 */
		private function complete_run( $file ): void
		{

			echo '<pre>';
			while ( ( $row = fgetcsv( $file ) ) !== false ) {
				print_r( $row );
			}
			echo '</pre>';
		}
	}
}