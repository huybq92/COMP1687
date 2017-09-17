<?php
	class CustomMySQLConnection {
		// MySQL server's information
		private $host = 'localhost';
		private $user = 'comp1687';
		private $pass = '123.abc';
		private $db   = 'test';

		public $encrypt_key     = 'testkey'; // key phrase for AES encryption in DB
		private $sql_connection = ''; // Initialize an empty string variable before assign it to be the type of sql connection

		// Function to open a connection to MySQL server
		function openConnection() {
			$this->sql_connection = mysqli_connect($this->host,$this->user,$this->pass,$this->db);
		}

		// Function to close SQL connection
		function closeConnection() {
			mysqli_close($this->sql_connection);
		}

		// Function to execute SELECT statement in SQL and return the result
		public function executeSELECT($query_statement) {
			// Firstly, open a new connection
			$this->openConnection();

			// Then execute SQL statement
			$result_set = mysqli_query($this->sql_connection,$query_statement);

			// Finally close connection and return result
			$this->closeConnection();
			return $result_set;
		}

		// Function to execute INSERT, UPDATE, DELETE and other statements that do NOT return values
		// CRUD is acronym of Create, Read, Update and Delete ^_^
		public function executeCRUD($query_statement) {
			// Firstly, open a new connection
			$this->openConnection();

			// Then execute the SQL statement
			mysqli_query($this->sql_connection, $query_statement) or die(mysqli_error($this->sql_connection));

			// Finally close connection
			$this->closeConnection();
		}

		// Function to fix escape string
		public function fixEscapeString($string) {
			$this->openConnection();
			$fixed_string = mysqli_real_escape_string($this->sql_connection, $string);
			$this->closeConnection;
			return $fixed_string;
		}
	}

	// Create an instance for above class
	$connection = new CustomMySQLConnection();
?>