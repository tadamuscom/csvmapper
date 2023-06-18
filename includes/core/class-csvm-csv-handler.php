<?php

if( ! class_exists( 'CSVM_CSV_Handler' ) ){
	class CSVM_CSV_Handler{
		private CSVM_Run $run;
		private CSVM_Import $import;

		public function __construct( CSVM_Run $run ) {
			$this->run = $run;
			echo '<pre>';
			print_r($run->import);
			echo '</pre>';
			die();
			$this->import = $run->import;
		}

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

		private function complete_run( $file ): void
		{
			$row = fgetcsv($file);

			while ( $row ) {
				print_r( $row );
			}
		}
	}
}