<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

abstract class CSVM_Base_Import_Type{
	/**
	 * Checks if the import has been completed
	 *
	 * @since 1.0
	 *
	 * @return bool
	 */
	public function is_complete(): bool
	{
		if( $this->run->status === CSVM_Run::$complete_status ){
			return true;
		}

		return false;
	}
}