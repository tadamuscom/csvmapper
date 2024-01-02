<?php

/**
 * Import model
 *
 * @package csvmapper
 * @author Tadamus <hello@tadamus.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'CSVM_Import' ) ) {

	/**
	 * @property mixed|string           $file_path      required
	 * @property integer|mixed|string   $id             required
	 * @property mixed|string           $file_url       required
	 * @property array|false            $headers        required
	 * @property string                 $type           required
	 * @property array                  $template
	 * @property array                  $ids
	 * @property string                 $table
	 * @property string                 $post_type
	 * @property integer                $total_rows
	 * @property integer                $number_of_rows
	 * @property array                  $runs
	 */
	class CSVM_Import extends CSVM_Base_Model {

		/**
		 * The prefix of the model option
		 *
		 * @since 1.0
		 *
		 * @var string
		 */
		protected string $option_prefix = 'csvm-import';

		/**
		 * The fields of the model
		 *
		 * @since 1.0
		 *
		 * @var array|string[]
		 */
		protected array $fields = array(
			'id'             => 'required|string',
			'file_path'      => 'required|string',
			'file_url'       => 'required|string',
			'headers'        => 'required|array',
			'type'           => 'required|string',
			'template'       => 'string',
			'ids'            => 'array',
			'table'          => 'string',
			'post_type'      => 'string',
			'total_rows'     => 'numeric',
			'number_of_rows' => 'integer',
			'runs'           => 'array',
		);
		/**
		 * Holds the allowed types of import
		 *
		 * @since 1.0
		 *
		 * @var array|string[]
		 */
		public static array $allowed_types = array(
			'Post Meta',
			'User Meta',
			'Custom Table',
			'Posts',
		);

		/**
		 * Constructs a new Import object if the there isn't one already with the same ID
		 *
		 * @since 1.0
		 *
		 * @param string|bool $id
		 *
		 * @return void
		 */
		public function __construct( string|bool $id = false ) {
			parent::__construct( $id );
		}

		/**
		 * Generates a new Import object based on the given file parameters
		 *
		 * @since 1.0
		 *
		 * @param array $file
		 *
		 * @return void
		 */
		public function process( array $file ): void {
			$this->id        = $this->generate_id();
			$this->file_path = $file['file'];
			$this->file_url  = $file['url'];

			$file = fopen( $this->file_path, 'r' );
			$csv  = fgetcsv( $file );
			fclose( $file );

			$this->headers = $csv;
		}

		/**
		 * Takes the IDs string and saves it as array
		 *
		 * @since 1.0
		 *
		 * @param string $ids
		 *
		 * @return void
		 */
		public function set_ids( string $ids ): void {
			$this->ids = array_unique( explode( ',', trim( $ids ) ) );
		}

		/**
		 * Returns the array of headers for that import
		 *
		 * @since 1.0
		 *
		 * @return array
		 */
		public function get_headers(): array {
			return $this->headers;
		}

		/**
		 * Returns a JSON string with the header slugs
		 *
		 * @since 1.0
		 *
		 * @return string
		 */
		public function get_headers_slugs_json(): string {
			$returnable = array();

			foreach ( $this->headers as $header ) {
				$returnable[] = csvm_convert_to_slug( $header );
			}

			return json_encode( $returnable );
		}

		/**
		 * Returns a JSON string with the headers
		 *
		 * @since 1.0
		 *
		 * @return string
		 */
		public function get_headers_json(): string {
			$returnable = array();

			foreach ( $this->headers as $header ) {
				$returnable[] = $header;
			}

			return json_encode( $returnable );
		}

		public function get_db_table_columns( string $table_name ): array {
			global $wpdb;

			$result  = $wpdb->get_results( 'DESCRIBE ' . $wpdb->prefix . $table_name );
			$columns = array();

			foreach ( $result as $column ) {
				if ( $column->Field === 'ID' ) {
					continue;
				}

				$columns[] = $column->Field;
			}

			return $columns;
		}

		/**
		 * Returns the number of existing runs
		 *
		 * @since 1.0
		 *
		 * @return int
		 */
		public function run_count(): int {
			if ( ! empty( $this->runs ) && is_array( $this->runs ) ) {
				return count( $this->runs );
			}

			return 0;
		}

		/**
		 * Generates a unique ID
		 *
		 * @since 1.0
		 *
		 * @param string|bool $prefix
		 *
		 * @return string
		 */
		private function generate_id( string|bool $prefix = false ): string {
			$id = uniqid();

			if ( $prefix ) {
				$id = uniqid( $prefix );
			}

			if ( self::exists( $id ) ) {
				$this->generate_id( $prefix );
			}

			return $id;
		}
	}
}
