<?php

if( ! class_exists( 'CSVM_Forms' ) ){
    class CSVM_Forms{
        public function __construct()
        {
            add_action( 'admin_post_csvm-file-upload', array( $this, 'upload_form_callback' ) );
			add_action( 'admin_post_csvm-table-mapping', array( $this, 'table_map_callback' ) );
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
				if( $_FILES['csv-upload']['type'] === 'text/csv' ){

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
				}else{
					csvm_redirect( admin_url( 'admin.php?page=csvmapper' ), 'error', __('The only files allowed are CSV files', 'csvmapper' ) );
				}

				return;
			}
		}

		public function table_map_callback(): void
		{
			if( !empty( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'csvm-table-mapping' ) ){
				$fields = array();

				foreach( $_POST as $key => $post ){
					if( str_contains( $key, 'value-' ) ){
						$new_key = substr($key, 6);
						$fields[$new_key] = $post;
					}
				}

				$import = new CSVM_Import($_POST['import_id']);
				$import->template = $fields;
				$import->save();

				csvm_redirect( admin_url( 'admin.php?page=csvmapper' ) . '&step=3&import_id=' . $import->id );
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
    }

    new CSVM_Forms();
}