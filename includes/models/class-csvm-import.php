<?php
/**
 * Import model
 *
 * @package csvmapper
 * @author Tadamus <hello@tadamus.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'CSVM_Import' ) ) {

	/**
	 * Import model
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
		 * ID
		 *
		 * @var string
		 */
		public string $id;

		/**
		 * File Path
		 *
		 * @var string
		 */
		public string $file_path;

		/**
		 * File URL
		 *
		 * @var string
		 */
		public string $file_url;

		/**
		 * Headers
		 *
		 * @var array
		 */
		public array $headers;

		/**
		 * Type
		 *
		 * @var string
		 */
		public string $type;

		/**
		 * Target IDs
		 *
		 * @var array
		 */
		public array $ids;

		/**
		 * Table
		 *
		 * @var string
		 */
		public string $table;

		/**
		 * Post Type
		 *
		 * @var string
		 */
		public string $post_type;

		/**
		 * Total Rows
		 *
		 * @var mixed
		 */
		public mixed $total_rows;

		/**
		 * Number of Rows
		 *
		 * @var int
		 */
		public int $number_of_rows;

		/**
		 * Runs
		 *
		 * @var array
		 */
		public array $runs;

		/**
		 * Template
		 *
		 * @var mixed
		 */
		public mixed $template;

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
		 * @param string|bool $id The ID of the import.
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
		 * @param array $file The CSV file.
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
		 * @param string $ids The IDs.
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

		/**
		 * Ge the columns of the database table
		 *
		 * @since 1.0
		 *
		 * @param string $table_name The name of the table.
		 *
		 * @return array
		 */
		public function get_db_table_columns( string $table_name ): array {
			global $wpdb;

			$result  = $wpdb->get_results( $wpdb->prepare( 'DESCRIBE %i;', $wpdb->prefix . $table_name ), 'ARRAY_A' );
			$columns = array();

			foreach ( $result as $column ) {
				if ( 'ID' === $column['Field'] ) {
					continue;
				}

				$columns[] = $column['Field'];
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
		 * @param string|bool $prefix The prefix for the ids.
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
