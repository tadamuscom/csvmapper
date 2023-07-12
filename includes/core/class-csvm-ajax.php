<?php

if( ! class_exists( 'CSVM_AJAX' ) ){
	class CSVM_AJAX{
		public function __construct() {
			add_action( 'wp_ajax_csvm_ajax_verification', array( $this, 'verification' ) );
			add_action( 'wp_ajax_csvm_ajax_batch', array( $this, 'batch' ) );
		}

		/**
		 * Check if the import is valid and the front end can communicate with the back end
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		public function verification(): void
		{
			if( wp_doing_ajax() && wp_verify_nonce( $_POST['nonce'], 'csvm-last-step' ) ){
				$import = new CSVM_Import( $_POST['import_id'] );
				$import->number_of_rows = $_POST['number_of_rows'];
				$import->save();

				$run = new CSVM_Run();
				$run->import_id = $import->id;
				$run->file_path = $import->file_path;
				$run->type = 'ajax';
				$run->status = CSVM_Run::$waiting_status;
				$run->last_row = 0;
				$run->save();

				wp_send_json_success( array(
					'run_id' => $run->id,
                    'total_rows' => $import->total_rows
				) );
			}else{
				wp_send_json_error( array(
					'message' => 'Unauthorized request'
				) );
			}
		}

		/**
		 * Handles every batch sent by the front end
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		public function batch(): void
		{
			if( wp_doing_ajax() && wp_verify_nonce( $_POST['nonce'], 'csvm-last-step' ) ){
				$run = new CSVM_Run( $_POST['run'] );
                $import = new CSVM_Import( $run->import_id );

                if( $run->last_row < $import->total_rows ){
                    $first_row = $run->last_row + 1;
                    $last_row = $first_row + $import->number_of_rows;

                    $handler = new CSVM_CSV_Handler( $run );
                    $handler->start( $first_row, $last_row );

                    $run->last_row = $last_row;
                    $run->save();
                }else{
                    $run->set_complete();
                }

				wp_send_json_success();
			}else{
				wp_send_json_error( array(
					'message' => 'Unauthorized request'
				) );
			}
		}
	}

	new CSVM_AJAX();
}