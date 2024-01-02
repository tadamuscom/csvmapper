<?php
/**
 * Parent of all imports
 *
 * @package csvmapper
 * @author Tadamus <hello@tadamus.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Parent of all imports
 */
abstract class CSVM_Base_Import_Type {

	/**
	 * Checks if the import has been completed
	 *
	 * @since 1.0
	 *
	 * @return bool
	 */
	public function is_complete(): bool {
		if ( CSVM_Run::$complete_status === $this->run->status ) {
			return true;
		}

		return false;
	}
}
