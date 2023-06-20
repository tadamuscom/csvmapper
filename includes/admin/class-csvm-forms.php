<?php

if( ! class_exists( 'CSVM_Forms' ) ){
    class CSVM_Forms{
        public function __construct()
        {
            add_action( 'admin_post_csvm-file-upload', array( $this, 'upload_form_callback' ) );
			add_action( 'admin_post_csvm-table-mapping', array( $this, 'table_map_callback' ) );
	        add_action( 'admin_post_csvm-meta-mapping', array( $this, 'meta_map_callback' ) );
			add_action( 'admin_post_csvm-last-step', array( $this, 'last_step_callback' ) );
        }

	    /**
	     * The callback method for the upload form
	     *
	     * @since 1.0
	     *
	     * @return void
	     */
	    public function upload_form_callback(): void
	    {
			if( !empty( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'csvm-file-upload' ) ){
				$this->upload_form_validation();

				$file = wp_handle_upload( $_FILES['csv-upload'], array(
					'test_form' => false,
					'test_size' => true,
				) );

				$import = new CSVM_Import();
				$import->process( $file );
				$import->type = $_POST['csv-import-type'];

				match ( $_POST['csv-import-type'] ){
					'post-meta'     => $this->post_meta_import ($import ),
					'user-meta'     => $this->user_meta_import( $import ),
					'custom-table'  => $this->custom_table_import( $import ),
					'posts'         => $this->posts_import( $import )
				};

				$import->save();

				csvm_redirect( admin_url( 'admin.php?page=csvmapper' ) . '&step=2&import_id=' . $import->id );
			}
		}

	    /**
	     * Callback for the table map form
	     *
	     * @since 1.0
	     *
	     * @return void
	     */
		public function table_map_callback(): void
		{
			if( !empty( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'csvm-table-mapping' ) ){
				$fields = array();

				foreach( $_POST as $key => $post ){
					if( str_contains( $key, 'value-' ) ){
						$new_key = substr($key, 6);

						if( empty( $post ) && $post != 0 ){
							$post = ' ';
						}

						$fields[$new_key] = $post;
					}
				}

				$import = new CSVM_Import($_POST['import_id']);

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
	    public function meta_map_callback(): void
	    {
		    if( !empty( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'csvm-meta-mapping' ) ){
				$fields = array();
			    $name_fields = array();
				$value_fields = array();
				$name_prefix = 'meta-name-';
				$value_prefix = 'meta-value-';

			    foreach( $_POST as $key => $post ){
				    if( str_contains( $key, $name_prefix ) ){
					    $new_key = substr( $key, strlen( $name_prefix ) );
					    $name_fields[ $new_key ] = $post;
				    }

				    if( str_contains( $key, $value_prefix ) ){
					    $new_key = substr( $key, strlen( $value_prefix ) );
					    $value_fields[ $new_key ] = $post;
				    }
			    }

				foreach( $name_fields as $key => $name ){
					if( empty( $name ) || empty( $value_fields[ $key ] ) ){
						csvm_redirect( admin_url( 'admin.php?page=csvmapper' ) . '&step=2&import_id=' . $_POST[ 'import_id' ], 'error' , __( 'Please make sure all the fields are filled', 'csvmapper' ) );
					}

					$fields[ $name ] = $value_fields[ $key ];
				}

			    $import = new CSVM_Import( $_POST[ 'import_id' ] );
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
		public function last_step_callback(): void
		{
			if( !empty( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'csvm-last-step' ) ){
				$import = new CSVM_Import( $_POST['import_id'] );

				if( empty( $_POST['csvm-execution-type'] ) ){
					csvm_redirect( admin_url( 'admin.php?page=csvmapper' ) . '&step=3&import_id=' . $import->id, 'error', __( 'Please select execution type' ) );
				}

				match ( $_POST['csvm-execution-type'] ){
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
		private function upload_form_validation(): void
		{
			if( $_FILES['csv-upload']['type'] !== 'text/csv' ) {
				csvm_redirect( admin_url( 'admin.php?page=csvmapper' ), 'error', __('Please upload a CSV file', 'csvmapper' ) );
			}

			if( empty( $_POST['csv-import-type'] ) ){
				csvm_redirect( admin_url( 'admin.php?page=csvmapper' ), 'error', __('There must be an import type', 'csvmapper' ) );
			}

			if( $_POST['csv-import-type'] === 'post-meta' ){
				if( empty( $_POST['csvm-post-ids'] ) ){
					csvm_redirect( admin_url( 'admin.php?page=csvmapper' ), 'error', __('The IDs field must be filled', 'csvmapper' ) );
				}

				$ids = explode( ',', $_POST['csvm-post-ids'] );

				foreach( $ids as $id ){
					if ( ! get_post( $id ) ) {
						csvm_redirect( admin_url( 'admin.php?page=csvmapper' ), 'error', __( 'There is no post with the id of ' . $id, 'csvmapper' ) );
					}
				}
			}

			if( $_POST['csv-import-type'] === 'user-meta' ){
				if( empty( $_POST['csvm-user-ids'] ) ){
					csvm_redirect( admin_url( 'admin.php?page=csvmapper' ), 'error', __('The IDs field must be filled', 'csvmapper' ) );
				}

				$ids = explode( ',', $_POST['csvm-user-ids'] );

				foreach( $ids as $id ){
					if ( ! get_user_by( 'ID', $id ) ) {
						csvm_redirect( admin_url( 'admin.php?page=csvmapper' ), 'error', __( 'There is no user with the id of ' . $id, 'csvmapper' ) );
					}
				}
			}
		}

	    /**
	     * Adds the required data to the import object if type is post-meta
	     *
	     * @since 1.0
	     *
	     * @param CSVM_Import $import
	     *
	     * @return void
	     */
		private function post_meta_import( CSVM_Import $import ): void
		{
			$import->set_ids( $_POST['csvm-post-ids'] );
		}

	    /**
	     * Adds the required data to the import object if type is user-meta
	     *
	     * @since 1.0
	     *
	     * @param CSVM_Import $import
	     *
	     * @return void
	     */
	    private function user_meta_import( CSVM_Import $import ): void
	    {
		    $import->set_ids( $_POST['csvm-user-ids'] );
	    }

	    /**
	     * Adds the required data to the import object if type is custom-table
	     *
	     * @since 1.0
	     *
	     * @param CSVM_Import $import
	     *
	     * @return void
	     */
		private function custom_table_import( CSVM_Import $import ): void
		{
			$import->table = $_POST['csvm-custom-table'];
		}

	    /**
	     * Adds the required data to the import object if type is posts
	     *
	     * @since 1.0
	     *
	     * @param CSVM_Import $import
	     *
	     * @return void
	     */
		private function posts_import( CSVM_Import $import ): void
		{
			$import->table = 'posts';
			$import->post_type = $_POST['csvm-post-type'];
		}

	    /**
	     * Handles the execution if the run type is WP Cron
	     *
	     * @since 1.0
	     *
	     * @param CSVM_Import $import
	     *
	     * @return void
	     */
		private function handle_wp_cron_import( CSVM_Import $import ): void
		{
			if( empty( $_POST['csvm-number-of-rows'] ) ){
				csvm_redirect( admin_url( 'admin.php?page=csvmapper' ) . '&step=3&import_id=' . $import->id, 'error', __( 'Please select a number of rows', 'csvmapper' ) );
			}

			$number_of_rows = $_POST['csvm-number-of-rows'];

			return;
		}

	    /**
	     * Handles the execution if the run type is PHP
	     *
	     * @since 1.0
	     *
	     * @param CSVM_Import $import
	     *
	     * @return void
	     */
	    private function handle_php_import( CSVM_Import $import ): void
	    {
			$run = new CSVM_PHP_Import( $import );
		    $run->execute();

			if( $run->is_complete() ){
				csvm_redirect( admin_url( 'admin.php?page=csvmapper' ), 'success', __( 'The import has been completed' ) );
			}else{
				csvm_redirect( admin_url( 'admin.php?page=csvmapper' ) . '&step=3&import_id=' . $import->id, 'error', __( 'There was a problem with the import', 'csvmapper' ) );
			}
	    }

	    /**
	     * Handles the process if the run type is not correct
	     *
	     * @since 1.0
	     *
	     * @param CSVM_Import $import
	     *
	     * @return void
	     */
	    private function handle_incorrect_import( CSVM_Import $import ): void
	    {
		    csvm_redirect( admin_url( 'admin.php?page=csvmapper' ) . '&step=3&import_id=' . $import->id, 'error', __( 'Run type not supported', 'csvmapper' ) );
	    }

		private function table_validation( CSVM_Import $import, array $fields ): void
		{
			global $wpdb;

			$table_name = $wpdb->prefix . $import->table;
			$columns = $wpdb->get_results('DESCRIBE ' . $table_name . ';');

			$validator = new CSVM_Table_Validator( $columns, $fields );

			if( $validator->get_error() ){
				csvm_redirect( admin_url( 'admin.php?page=csvmapper' ) . '&step=2&import_id=' . $import->id, 'error', __( $validator->get_error(), 'csvmapper' ) );
			}
		}
    }

    new CSVM_Forms();
}