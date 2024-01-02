<?php
/**
 * Validate the table columns from the MySQL database
 *
 * @package csvmapper
 * @author Tadamus <hello@tadamus.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'CSVM_Table_Validator' ) ) {
	/**
	 * Validate the table columns from the MySQL database
	 */
	class CSVM_Table_Validator {
		/**
		 * The submitted fields
		 *
		 * @since 1.0
		 *
		 * @var array
		 */
		private array $fields;

		/**
		 * The columns of the table
		 *
		 * @since 1.0
		 *
		 * @var array
		 */
		private array $columns;

		/**
		 * The error name
		 *
		 * @since 1.0
		 *
		 * @var string
		 */
		private string $error;

		/**
		 * The status property of the validation
		 *
		 * @since 1.0
		 *
		 * @var bool
		 */
		private bool $returnable = false;

		/**
		 * Retrieve the default value of a column if it has a default
		 *
		 * @since 1.0
		 *
		 * @param string $table The name of the database table.
		 * @param string $field The name of the field.
		 *
		 * @return string
		 */
		public static function get_default( string $table, string $field ): string {
			global $wpdb;

			$table_name = $wpdb->prefix . $table;
			$columns    = $wpdb->get_results( $wpdb->prepare( 'DESCRIBE %i;', $table_name ), 'ARRAY_A' );

			foreach ( $columns as $column ) {
				if ( $column['Field'] === $field && ! empty( $column['Default'] ) ) {
					return $column['Default'];
				}
			}

			return '';
		}

		/**
		 * Validate the table
		 *
		 * @since 1.0
		 *
		 * @param array $columns The columns of the table.
		 * @param array $fields The fields.
		 * @return void
		 */
		public function __construct( array $columns, array $fields ) {
			$this->fields  = $fields;
			$this->columns = $columns;

			$this->start();
		}

		/**
		 * Returns the status of the validation
		 *
		 * @since 1.0
		 *
		 * @return bool
		 */
		public function status(): bool {
			if ( ! $this->returnable ) {
				return true;
			}

			return false;
		}

		/**
		 * Retrieve the error name
		 *
		 * @since 1.0
		 *
		 * @return string|bool
		 */
		public function get_error(): string|bool {
			if ( ! $this->returnable && ! empty( $this->error ) ) {
				return $this->error;
			}

			return false;
		}

		/**
		 * Starts the validation process
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		private function start(): void {
			foreach ( $this->columns as $column ) {
				if ( 'ID' === $column['Field'] || 'id' === $column['Field'] || 'post_type' === $column['Field'] ) {
					continue;
				}

				$this->validate_column( $column );
			}
		}

		/**
		 * Validates independent columns
		 *
		 * @since 1.0
		 *
		 * @param array $column The column name.
		 *
		 * @return void
		 */
		private function validate_column( array $column ): void {
			$data_types = array(
				'char'       => 'string',
				'varchar'    => 'string',
				'binary'     => 'string',
				'varbinary'  => 'string',
				'tinyblob'   => 'string',
				'tinytext'   => 'string',
				'text'       => 'string',
				'blob'       => 'string',
				'mediumtext' => 'string',
				'mediumblob' => 'string',
				'longtext'   => 'string',
				'longblob'   => 'string',
				'enum'       => 'string',
				'set'        => 'string',
				'bit'        => 'numeric',
				'tinyint'    => 'numeric',
				'bool'       => 'numeric',
				'boolean'    => 'numeric',
				'smallint'   => 'numeric',
				'mediumint'  => 'numeric',
				'int'        => 'numeric',
				'integer'    => 'numeric',
				'bigint'     => 'numeric',
				'decimal'    => 'numeric',
				'dec'        => 'numeric',
				'float'      => 'float',
				'double'     => 'float',
				'date'       => 'date',
				'datetime'   => 'date',
				'timestamp'  => 'date',
				'time'       => 'date',
				'year'       => 'date',
			);

			foreach ( $data_types as $type => $rules ) {
				$column_type = strtolower( $column['Type'] );

				if ( str_starts_with( $column_type, $type ) ) {
					if ( 'NO' === $column['Null'] ) {
						$rules = $rules . '|required';
					}

					if ( str_contains( '(', $column['Type'] ) && str_contains( ')', $column['Type'] ) ) {
						$limit = ( explode( ')', ( explode( '(', $column['Type'] ) )[1] ) )[0];
						$rules = $rules . '|min:' . $limit;
					}

					$validator = new CSVM_Validator( $column['Field'], $this->fields[ $column['Field'] ], $rules );

					if ( ! $validator->result() ) {
						$this->error( $validator->get_error() );
						break;
					}
				}
			}
		}

		/**
		 * Sets the error if the validation fails
		 *
		 * @since 1.0
		 *
		 * @param string $error The error name.
		 *
		 * @return void
		 */
		private function error( string $error ): void {
			$this->returnable = false;
			// translators: The error name.
			$this->error = printf( esc_html__( 'Error: %s', 'csvmapper' ), esc_html( $error ) );
		}
	}
}
