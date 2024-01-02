<?php
/**
 * General handler of the forms from the admin panel
 *
 * @package csvmapper
 * @author Tadamus <hello@tadamus.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'CSVM_Forms' ) ) {
	/**
	 * General handler of the forms from the admin panel
	 */
	class CSVM_Forms {
		/**
		 * Add the handlers for the forms
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		public function __construct() {
			add_action( 'admin_post_csvm-settings', array( $this, 'settings_form_callback' ) );
			add_action( 'admin_post_csvm-file-upload', array( $this, 'upload_form_callback' ) );
			add_action( 'admin_post_csvm-table-mapping', array( $this, 'table_map_callback' ) );
			add_action( 'admin_post_csvm-meta-mapping', array( $this, 'meta_map_callback' ) );
			add_action( 'admin_post_csvm-last-step', array( $this, 'last_step_callback' ) );
		}

		/**
		 * Callback for the settings form
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		public function settings_form_callback(): void {
			if ( ! empty( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'csvm-settings' ) ) {
				if ( ! empty( $_POST['csvm-enable-cron'] ) ) {
					if ( 'true' === $_POST['csvm-enable-cron'] ) {
						csvm_add_or_update_option( 'csvm_enable_cron_task', 'true' );

						if ( empty( $_POST['csvm-cron-interval-number'] ) || empty( $_POST['csvm-cron-interval-period'] ) ) {
							csvm_redirect( admin_url( 'admin.php?page=csvmapper-settings' ), 'error', __( 'The WP Cron interval cannot be empty', 'csvmapper' ) );

							die;
						}
					} else {
						csvm_add_or_update_option( 'csvm_enable_cron_task', 'false' );
					}
				}

				$interval_number = sanitize_text_field( wp_unslash( $_POST['csvm-cron-interval-number'] ) );
				$interval_period = sanitize_text_field( wp_unslash( $_POST['csvm-cron-interval-period'] ) );

				if ( ! is_numeric( $interval_number ) ) {
					csvm_redirect( admin_url( 'admin.php?page=csvmapper-settings' ), 'error', __( 'The interval number must be a numeric value', 'csvmapper' ) );

					die;
				}

				if ( ! is_string( $interval_period ) ) {
					csvm_redirect( admin_url( 'admin.php?page=csvmapper-settings' ), 'error', __( 'The interval period must be a string', 'csvmapper' ) );

					die;
				}

				csvm_add_or_update_option( 'csvm_cron_interval_number', $interval_number );
				csvm_add_or_update_option( 'csvm_cron_interval_interval', $interval_period );

				$intervals = array(
					'seconds' => 1,
					'minutes' => 60,
					'hours'   => 3600,
					'days'    => 86400,
				);

				csvm_add_or_update_option( 'csvm_cron_interval', $interval_number * $intervals[ $interval_period ] );

				csvm_redirect( admin_url( 'admin.php?page=csvmapper-settings' ), 'success', __( 'Settings saved', 'csvmapper' ) );
				die;
			}
		}

		/**
		 * The callback method for the upload form
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		public function upload_form_callback(): void {
			if ( ! empty( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'csvm-file-upload' ) ) {
				$this->upload_form_validation();

				if ( ! empty( $_POST['csv-import-type'] ) && ! empty( $_FILES['csv-upload'] ) ) {

					$import_type = sanitize_text_field( wp_unslash( $_POST['csv-import-type'] ) );

					$file = wp_handle_upload(
						$_FILES['csv-upload'],
						array(
							'test_form' => false,
							'test_size' => true,
						)
					);

					$import = new CSVM_Import();
					$import->process( $file );
					$import->type = $import_type;

					match ( $import_type ) {
							'post-meta'     => $this->post_meta_import( $import ),
							'user-meta'     => $this->user_meta_import( $import ),
							'custom-table'  => $this->custom_table_import( $import ),
							'posts'         => $this->posts_import( $import )
					};

					$file_obj = new SplFileObject( $import->file_path, 'r' );
					$file_obj->seek( PHP_INT_MAX );

					$import->total_rows = $file_obj->key() - 1;
					$import->save();

					csvm_redirect( admin_url( 'admin.php?page=csvmapper' ) . '&step=2&import_id=' . $import->id );
				}
			}
		}

		/**
		 * Callback for the table map form
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		public function table_map_callback(): void {
			if ( ! empty( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'csvm-table-mapping' ) ) {
				$fields = array();

				if ( empty( $_POST['import_id'] ) ) {
					return;
				}

				$import_id = sanitize_text_field( wp_unslash( $_POST['import_id'] ) );

				foreach ( $_POST as $key => $post ) {
					if ( str_contains( $key, 'value-' ) ) {
						$new_key = substr( $key, 6 );

						if ( empty( $post ) && 0 !== $post ) {
								$post = ' ';
						}

						$fields[ $new_key ] = $post;
					}
				}

				$import = new CSVM_Import( $import_id );

				$this->table_validation( $import, $fields );

				$import->template = $fields;
				$import->save();

				csvm_redirect( admin_url( 'admin.php?page=csvmapper' ) . '&step=3&import_id=' . $import->id );
			}
		}

		/**
		 * Callback for the meta map form
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		public function meta_map_callback(): void {
			if ( ! empty( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'csvm-meta-mapping' ) ) {
				$fields       = array();
				$name_fields  = array();
				$value_fields = array();
				$name_prefix  = 'meta-name-';
				$value_prefix = 'meta-value-';

				if ( empty( $_POST['import_id'] ) ) {
					return;
				}

				$import_id = sanitize_text_field( wp_unslash( $_POST['import_id'] ) );

				foreach ( $_POST as $key => $post ) {
					if ( str_contains( $key, $name_prefix ) ) {
						$new_key                 = substr( $key, strlen( $name_prefix ) );
						$name_fields[ $new_key ] = $post;
					}

					if ( str_contains( $key, $value_prefix ) ) {
						$new_key                  = substr( $key, strlen( $value_prefix ) );
						$value_fields[ $new_key ] = $post;
					}
				}

				foreach ( $name_fields as $key => $name ) {
					if ( empty( $name ) || empty( $value_fields[ $key ] ) ) {
						csvm_redirect( admin_url( 'admin.php?page=csvmapper' ) . '&step=2&import_id=' . sanitize_text_field( wp_unslash( $_POST['import_id'] ) ), 'error', __( 'Please make sure all the fields are filled', 'csvmapper' ) );
					}

					$fields[ $name ] = $value_fields[ $key ];
				}

				$import           = new CSVM_Import( $import_id );
				$import->template = $fields;
				$import->save();

				csvm_redirect( admin_url( 'admin.php?page=csvmapper' ) . '&step=3&import_id=' . $import->id );
			}
		}

		/**
		 * Callback for the last step of the import form
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		public function last_step_callback(): void {
			if ( ! empty( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'csvm-last-step' ) ) {
				if ( empty( $_POST['import_id'] ) ) {
					return;
				}

				$import_id = sanitize_text_field( wp_unslash( $_POST['import_id'] ) );

				$import = new CSVM_Import( $import_id );

				if ( empty( $_POST['csvm-execution-type'] ) ) {
					csvm_redirect( admin_url( 'admin.php?page=csvmapper' ) . '&step=3&import_id=' . $import->id, 'error', __( 'Please select execution type' ) );
				}

				match ( $_POST['csvm-execution-type'] ) {
					'wp-cron' => $this->handle_wp_cron_import( $import ),
					'php' => $this->handle_php_import( $import ),
					'default' => $this->handle_incorrect_import( $import )
				};
			}
		}

		/**
		 * Validates the data of the upload form
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		private function upload_form_validation(): void {
			if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'csvm-file-upload' ) ) {
				return;
			}

			if ( empty( $_FILES['csv-upload']['type'] ) ) {
				return;
			}

			if ( 'text/csv' !== $_FILES['csv-upload']['type'] ) {
				csvm_redirect( admin_url( 'admin.php?page=csvmapper' ), 'error', __( 'Please upload a CSV file', 'csvmapper' ) );
			}

			if ( empty( $_POST['csv-import-type'] ) ) {
				csvm_redirect( admin_url( 'admin.php?page=csvmapper' ), 'error', __( 'There must be an import type', 'csvmapper' ) );
			}

			if ( 'post-meta' === $_POST['csv-import-type'] ) {
				if ( empty( $_POST['csvm-post-ids'] ) ) {
					csvm_redirect( admin_url( 'admin.php?page=csvmapper' ), 'error', __( 'The IDs field must be filled', 'csvmapper' ) );
				}

				$ids = explode( ',', sanitize_text_field( wp_unslash( $_POST['csvm-post-ids'] ) ) );

				foreach ( $ids as $id ) {
					if ( ! get_post( $id ) ) {
						// translators: the id of the post.
						csvm_redirect( admin_url( 'admin.php?page=csvmapper' ), 'error', printf( esc_attr__( 'There is no post with the id of %s', 'csvmapper' ), esc_attr( $id ) ) );
					}
				}
			}

			if ( 'user-meta' === $_POST['csv-import-type'] ) {
				if ( empty( $_POST['csvm-user-ids'] ) ) {
					csvm_redirect( admin_url( 'admin.php?page=csvmapper' ), 'error', __( 'The IDs field must be filled', 'csvmapper' ) );
				}

				$ids = explode( ',', sanitize_text_field( wp_unslash( $_POST['csvm-user-ids'] ) ) );

				foreach ( $ids as $id ) {
					if ( ! get_user_by( 'ID', $id ) ) {
						// translators: the id of the user.
						csvm_redirect( admin_url( 'admin.php?page=csvmapper' ), 'error', printf( esc_attr__( 'There is no user with the id of %s', 'csvmapper' ), esc_attr( $id ) ) );
					}
				}
			}
		}

		/**
		 * Adds the required data to the import object if type is post-meta
		 *
		 * @since 1.0
		 *
		 * @param CSVM_Import $import The import object.
		 *
		 * @return void
		 */
		private function post_meta_import( CSVM_Import $import ): void {
			if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'csvm-file-upload' ) ) {
				return;
			}

			if ( empty( $_POST['csvm-post-ids'] ) ) {
				return;
			}

			$post_ids = sanitize_text_field( wp_unslash( $_POST['csvm-post-ids'] ) );

			$import->set_ids( $post_ids );
		}

		/**
		 * Adds the required data to the import object if type is user-meta
		 *
		 * @since 1.0
		 *
		 * @param CSVM_Import $import The import object.
		 *
		 * @return void
		 */
		private function user_meta_import( CSVM_Import $import ): void {
			if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'csvm-file-upload' ) ) {
				return;
			}

			if ( empty( $_POST['csvm-user-ids'] ) ) {
				return;
			}

			$user_ids = sanitize_text_field( wp_unslash( $_POST['csvm-user-ids'] ) );

			$import->set_ids( $user_ids );
		}

		/**
		 * Adds the required data to the import object if type is custom-table
		 *
		 * @since 1.0
		 *
		 * @param CSVM_Import $import The import object.
		 *
		 * @return void
		 */
		private function custom_table_import( CSVM_Import $import ): void {
			if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'csvm-file-upload' ) ) {
				return;
			}

			if ( empty( $_POST['csvm-custom-table'] ) ) {
				return;
			}

			$custom_table = sanitize_text_field( wp_unslash( $_POST['csvm-custom-table'] ) );

			$import->table = $custom_table;
		}

		/**
		 * Adds the required data to the import object if type is posts
		 *
		 * @since 1.0
		 *
		 * @param CSVM_Import $import The import object.
		 *
		 * @return void
		 */
		private function posts_import( CSVM_Import $import ): void {
			if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'csvm-file-upload' ) ) {
				return;
			}

			if ( empty( $_POST['csvm-post-type'] ) ) {
				return;
			}

			$post_type = sanitize_text_field( wp_unslash( $_POST['csvm-post-type'] ) );

			$import->table     = 'posts';
			$import->post_type = $post_type;
		}

		/**
		 * Handles the execution if the run type is WP Cron
		 *
		 * @since 1.0
		 *
		 * @param CSVM_Import $import The import object.
		 *
		 * @return void
		 */
		private function handle_wp_cron_import( CSVM_Import $import ): void {
			if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'csvm-last-step' ) ) {
				return;
			}

			if ( empty( $_POST['csvm-number-of-rows'] ) ) {
				return;
			}

			$number_of_rows = sanitize_text_field( wp_unslash( $_POST['csvm-number-of-rows'] ) );

			if ( empty( $number_of_rows ) ) {
				csvm_redirect( admin_url( 'admin.php?page=csvmapper' ) . '&step=3&import_id=' . $import->id, 'error', __( 'Please select a number of rows', 'csvmapper' ) );

				die;
			}

			if ( $number_of_rows > 500 ) {
				csvm_redirect( admin_url( 'admin.php?page=csvmapper' ) . '&step=3&import_id=' . $import->id, 'error', __( 'We cannot process more than 500 rows per run', 'csvmapper' ) );

				die;
			}

			if ( $number_of_rows < 1 ) {
				csvm_redirect( admin_url( 'admin.php?page=csvmapper' ) . '&step=3&import_id=' . $import->id, 'error', __( 'We cannot process less than 1 row per run', 'csvmapper' ) );

				die;
			}

			$import->number_of_rows = $number_of_rows;
			$import->save();

			$run            = new CSVM_Run();
			$run->import_id = $import->id;
			$run->file_path = $import->file_path;
			$run->status    = CSVM_Run::$waiting_status;
			$run->type      = 'wp-cron';
			$run->save();

			csvm_redirect( admin_url( 'admin.php?page=csvmapper' ) . '&step=3&import_id=' . $import->id, 'success', __( 'Import added to WP Cron', 'csvmapper' ) );

			die;
		}

		/**
		 * Handles the execution if the run type is PHP
		 *
		 * @since 1.0
		 *
		 * @param CSVM_Import $import The import object.
		 *
		 * @return void
		 */
		private function handle_php_import( CSVM_Import $import ): void {
			$run = new CSVM_PHP_Import( $import );
			$run->execute();

			if ( $run->is_complete() ) {
				csvm_redirect( admin_url( 'admin.php?page=csvmapper' ), 'success', __( 'The import has been completed' ) );
			} else {
				csvm_redirect( admin_url( 'admin.php?page=csvmapper' ) . '&step=3&import_id=' . $import->id, 'error', __( 'There was a problem with the import', 'csvmapper' ) );
			}
		}

		/**
		 * Handles the process if the run type is not correct
		 *
		 * @since 1.0
		 *
		 * @param CSVM_Import $import The import object.
		 *
		 * @return void
		 */
		private function handle_incorrect_import( CSVM_Import $import ): void {
			csvm_redirect( admin_url( 'admin.php?page=csvmapper' ) . '&step=3&import_id=' . $import->id, 'error', __( 'Run type not supported', 'csvmapper' ) );
		}

		/**
		 * Validates the posts table and custom tables based on the SQL schema
		 *
		 * @since 1.0
		 *
		 * @param CSVM_Import $import The import object.
		 * @param array       $fields The fields of the import.
		 *
		 * @return void
		 */
		private function table_validation( CSVM_Import $import, array $fields ): void {
			global $wpdb;

			$table_name = $wpdb->prefix . $import->table;
			$columns    = $wpdb->get_results( $wpdb->prepare( 'DESCRIBE %s;', $table_name ), 'ARRAY_A' );

			$validator = new CSVM_Table_Validator( $columns, $fields );

			if ( $validator->get_error() ) {
				// translators: the error name.
				csvm_redirect( admin_url( 'admin.php?page=csvmapper' ) . '&step=2&import_id=' . $import->id, 'error', printf( esc_attr__( 'There was an error: %s', 'csvmapper' ), esc_html( $validator->get_error() ) ) );
			}
		}
	}

	new CSVM_Forms();
}
