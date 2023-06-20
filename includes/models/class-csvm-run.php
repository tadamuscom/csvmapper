<?php

if( ! class_exists( 'CSVM_Run' ) ){

	/**
	 * @property string $id
	 * @property string $import_id
	 * @property CSVM_Import $import
	 * @property string $file_path
	 * @property string $status
	 * @property string $type
	 */
	class CSVM_Run extends CSVM_Base_Model {
		/**
		 * The waiting status of the model
		 *
		 * @static 1.0
		 *
		 * @var string
		 */
		public static string $waiting_status = 'waiting';

		/**
		 * The in progress status of the model
		 *
		 * @static 1.0
		 *
		 * @var string
		 */
		public static string $in_progress_status = 'in_progress';

		/**
		 * The complete status of the model
		 *
		 * @static 1.0
		 *
		 * @var string
		 */
		public static string $complete_status = 'complete';

		/**
		 * The prefix of the model option
		 *
		 * @since 1.0
		 *
		 * @var string
		 */
		protected string $option_prefix = 'csvm-run';

		/**
		 * The fields of the model
		 *
		 * @since 1.0
		 *
		 * @var array|string[]
		 */
		protected array $fields = array(
			'id'        => 'required|string',
			'import_id' => 'required|string',
			'file_path'  => 'required|string',
			'type'      => 'required|string',
			'status'    => 'required|string'
		);

		public function __construct( bool|string $id = false )
		{
			parent::__construct( $id );
		}

		/**
		 * Changes the status of the run to in progress
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		public function set_in_progress(): void{
			$this->status = CSVM_Run::$in_progress_status;
			$this->save();
		}

		/**
		 * Changes the status of the run to complete
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		public function set_complete(): void{
			$this->status = CSVM_Run::$complete_status;
			$this->save();
		}
	}
}