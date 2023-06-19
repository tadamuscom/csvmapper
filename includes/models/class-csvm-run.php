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
		protected string $option_prefix = 'csvm-run';
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
	}
}