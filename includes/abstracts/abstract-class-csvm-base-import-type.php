<?php

abstract class CSVM_Base_Import_Type{
	public static string $waiting_status = 'waiting';
	public static string $in_progress_status = 'in_progress';
	public static string $complete_status = 'complete';

	/**
	 * Checks if the import has been completed
	 *
	 * @since 1.0
	 *
	 * @return bool
	 */
	public function is_complete(): bool
	{
		if( $this->run->status === self::$complete_status ){
			return true;
		}

		return false;
	}
}