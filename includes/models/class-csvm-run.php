<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists( 'CSVM_Run' ) ){

	/**
	 * @property string $id
	 * @property string $import_id
	 * @property CSVM_Import $import
	 * @property string $file_path
	 * @property string $status
	 * @property string $type
	 * @property numeric $last_row
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
			'id'        => 'string',
			'import_id' => 'required|string',
			'file_path'  => 'required|string',
			'type'      => 'required|string',
			'status'    => 'required|string',
			'last_row'  => 'numeric'
		);

		/**
		 * Retrieve all objects based on status
		 *
		 * @since 1.0
		 *
		 * @param string $status
		 *
		 * @return array
		 */
		public static function get_all_by_status( string $status ): array
		{
			global $wpdb;

			$returnable = array();
			$obj = new self();
			$sql = 'SELECT * FROM ' . $wpdb->prefix . 'options WHERE option_name LIKE "' . $obj->option_prefix . '-%"';
			$results = $wpdb->get_results( $sql );

			if( empty($results) ){
				return $results;
			}

			foreach( $results as $run ){
				$data = unserialize( $run->option_value );

				if( is_string( $data ) ){
					$data = unserialize( $data );
				}

				$run_obj = new self( $data['id'] );

				if( $run_obj->status === $status ){
					$returnable[] = $run_obj;
				}
			}

			return $returnable;
		}

		public function __construct( bool|string $id = false )
		{
			parent::__construct( $id );
		}

		/**
		 * Adds the run ID if it isn't set already
		 *
		 * @since 1.0
		 *
		 * @return bool|self
		 */
		public function save(): bool|self
		{
			$import = new CSVM_Import( $this->import_id );

			if( empty( $this->id ) ){
				$this->id = $this->import_id . '-' . $import->run_count() + 1;
			}

			$import->runs[] = $this->id;
			$import->save();

			return parent::save();
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