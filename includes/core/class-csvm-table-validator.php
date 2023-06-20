<?php

if( ! class_exists('CSVM_Table_Validator') ){
	class CSVM_Table_Validator{
		private array $fields;
		private array $columns;
		private string $error;
		private bool $returnable = false;

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

			$this->validate();
		}

		public function status(): bool
		{
			if( ! $this->returnable ){
				return true;
			}

			return false;
		}

		public function get_error(): string|bool
		{
			if( ! $this->returnable && ! empty( $this->error ) ){
				return $this->error;
			}

			return false;
		}

		private function validate(): void
		{
			foreach( $this->columns as $column ){
				if( $column->Field === 'ID' || $column->Field === 'id' || $column->Field === 'post_type' ){
					continue;
				}

				$this->validate_data_type( $column );
			}
		}

		private function validate_data_type( object $column ): void
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

		private function error( string $error ): void
		{
			$this->returnable = false;
			$this->error = __( $error, 'csvmapper' );
		}
	}
}