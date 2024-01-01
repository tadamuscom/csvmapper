<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'CSVM_Cron' ) ) {
	class CSVM_Cron {

		public function __construct() {
			if ( get_option( 'csvm_enable_cron_task' ) === 'true' ) {
				add_action( 'csvm_import_lookout', array( $this, 'lookout_callback' ) );
				add_filter( 'cron_schedules', array( $this, 'cron_schedules' ) );

				if ( ! wp_next_scheduled( 'csvm_import_lookout' ) ) {
					wp_schedule_event( time(), 'csvm_cron_interval', 'csvm_import_lookout' );
				}
			}
		}

		/**
		 * Execute on WP Cron callback
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		public function lookout_callback(): void {
			$active_runs  = CSVM_Run::get_all_by_status( CSVM_Run::$in_progress_status );
			$waiting_runs = CSVM_Run::get_all_by_status( CSVM_Run::$waiting_status );

			$runs = array_merge( $active_runs, $waiting_runs );

			if ( ! empty( $runs ) ) {
				foreach ( $runs as $run ) {
					$import   = new CSVM_Import( $run->import_id );
					$last_row = $run->last_row;

					if ( empty( $run->last_row ) ) {
						$last_row = 0;
					}

					$this->run_batch( $run, $import, $last_row );

					if ( $run->status === CSVM_Run::$waiting_status ) {
						$run->set_in_progress();
					}
				}
			}
		}

		/**
		 * Register custom schedules
		 *
		 * @since 1.0
		 *
		 * @param array $schedules
		 *
		 * @return array
		 */
		public function cron_schedules( array $schedules ): array {
			$schedules['csvm_cron_interval'] = array(
				'interval' => get_option( 'csvm_cron_interval' ),
				'display'  => esc_html__( 'CSVMapper Custom Cron Interval' ),
				'csvmapper',
			);

			return $schedules;
		}

		/**
		 * Execute on each batch
		 *
		 * @since 1.0
		 *
		 * @param CSVM_Run    $run
		 * @param CSVM_Import $import
		 * @param $last_row
		 *
		 * @return void
		 */
		private function run_batch( CSVM_Run $run, CSVM_Import $import, $last_row ): void {
			if ( $last_row < $import->total_rows ) {
				$first_row = $run->last_row + 1;
				$last_row  = $first_row + $import->number_of_rows;

				$handler = new CSVM_CSV_Handler( $run );
				$handler->start( $first_row, $last_row );

				$run->last_row = $last_row;
				$run->save();
			}

			if ( $last_row >= $import->total_rows ) {
				$run->set_complete();
			}
		}
	}

	new CSVM_Cron();
}
