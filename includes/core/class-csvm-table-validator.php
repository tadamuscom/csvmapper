<?php

if( ! class_exists('CSVM_Table_Validator') ){
	class CSVM_Table_Validator{
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
		 * @param string $table
		 * @param string $field
		 *
		 * @return string
		 */
		public static function get_default( string $table, string $field ): string
		{
			global $wpdb;

			$table_name = $wpdb->prefix . $table;
			$columns = $wpdb->get_results('DESCRIBE ' . $table_name . ';');

			foreach( $columns as $column ){
				if( $column->Field === $field && ! empty( $column->Default ) ){
					return $column->Default;
				}
			}

			return '';
		}

		public function __construct( array $columns, array $fields )
		{
			$this->fields = $fields;
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
		public function status(): bool
		{
			if( ! $this->returnable ){
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
		public function get_error(): string|bool
		{
			if( ! $this->returnable && ! empty( $this->error ) ){
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
		private function start(): void
		{
			foreach( $this->columns as $column ){
				if( $column->Field === 'ID' || $column->Field === 'id' || $column->Field === 'post_type' ){
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
		 * @param object $column
		 *
		 * @return void
		 */
		private function validate_column( object $column ): void
		{
			$data_types = array(
				'char'          => 'string',
				'varchar'       => 'string',
				'binary'        => 'string',
				'varbinary'     => 'string',
				'tinyblob'      => 'string',
				'tinytext'      => 'string',
				'text'          => 'string',
				'blob'          => 'string',
				'mediumtext'    => 'string',
				'mediumblob'    => 'string',
				'longtext'      => 'string',
				'longblob'      => 'string',
				'enum'          => 'string',
				'set'           => 'string',
				'bit'           => 'numeric',
				'tinyint'       => 'numeric',
				'bool'          => 'numeric',
				'boolean'       => 'numeric',
				'smallint'      => 'numeric',
				'mediumint'     => 'numeric',
				'int'           => 'numeric',
				'integer'       => 'numeric',
				'bigint'        => 'numeric',
				'decimal'       => 'numeric',
				'dec'           => 'numeric',
				'float'          => 'float',
				'double'        => 'float',
				'date'          => 'date',
				'datetime'      => 'date',
				'timestamp'     => 'date',
				'time'          => 'date',
				'year'          => 'date',
			);

			foreach( $data_types as $type => $rules ){
				$column_type = strtolower( $column->Type );

				if( str_starts_with( $column_type, $type ) ){
					if( $column->Null === 'NO' ){
						$rules = $rules . '|required';
					}

					if( str_contains( '(', $column->Type ) && str_contains( ')', $column->Type ) ){
						$limit = ( explode( ')' ,( explode( '(' ,$column->Type ) )[1] ) )[0];
						$rules = $rules . '|min:' . $limit;
					}

					$validator = new CSVM_Validator( $column->Field, $this->fields[$column->Field], $rules );

					if( ! $validator->result() ){
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
		 * @param string $error
		 *
		 * @return void
		 */
		private function error( string $error ): void
		{
			$this->returnable = false;
			$this->error = __( $error, 'csvmapper' );
		}
	}
}