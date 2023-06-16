<?php

if( ! class_exists( 'CSVM_PHP_Import' ) ){
	class CSVM_PHP_Import extends CSVM_Base_Import_Type implements CSVM_Import_Type {
		public CSVM_Import $import;
		public CSVM_Run $run;

		public function __construct( CSVM_Import $import ) {
			$this->import = $import;

			$run = new CSVM_Run();

			$run->id = $import->id . '-' . $import->run_count() + 1;
			$run->import_id = $import->id;
			$run->file_path = $import->file_path;
			$run->status = self::$waiting_status;

			$run->save();

			$this->run = $run;
		}

		public function execute(): void
		{
			// TODO: Implement execute() method.
		}
	}
}