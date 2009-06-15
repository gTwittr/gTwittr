<?php

	class PersistanceService {
		
		private $dbFilename;
		private $db = null;
		
		private static $instance = null;
		
		public static function getInstance() {
			if (self::$instance == null) {
				self::$instance = new PersistanceService();
			}
			return self::$instance;
		}
		
		public function __construct() {
			$this->dbFilename = "data/geWittrData.sqlite";
			
			$this->db = new SQLiteDatabase($this->dbFilename);

			if( $this->db->lastError() ) {
				die("Error: couldn't open or create database!");
			}
		}
		
		public function getTokens($sessionId = '') {
			
			if ( ! $this->db ) {
				die("Error: no database connection!");
			}
			
			$this->createTokenTableIfNotExists();

			$query = "SELECT * FROM userTwitterAuth;";
	 
			if ( strlen($sessionId) > 0  ) {
				$query = "SELECT * FROM userTwitterAuth WHERE sessionId = '$sessionId';";	
			}
			
			$result = $this->db->query($query);
	
			$resArray = array();

			while($authToken = $result->fetch(SQLITE_ASSOC)){
				array_push($resArray, $authToken);
			}
			
			$json = json_encode($resArray);
			
			// not generally needed as PHP will destroy the connection
			unset($db);
			
			return $json;
		}
		
		public function setToken($twitterId, $token, $secret) {
			if ( ! $this->db ) {
				die("Error: no database connection!");
			}
			
			$this->createTokenTableIfNotExists();
			
			$checkQuery = "SELECT * FROM userTwitterAuth WHERE twitterId = '$twitterId';";
			
			$result = $this->db->query($checkQuery);
			
			$sessionId = uniqid();
			
			$query = "INSERT INTO userTwitterAuth (sessionId, twitterId, token, secret) VALUES ('$sessionId', $twitterId, '$token', '$secret')";
			if ( $result->numRows() > 0 ) {
				$query = "UPDATE userTwitterAuth SET sessionId='$sessionId' token='$token', secret='$secret' WHERE twitterId=$twitterId;";
			}
			
			$this->db->query($query);
			
			return $sessionId;
		}
		
		private function checkTableExists($tableName) {
			$tableExists = false;

			$tables = $this->db->query("SELECT * FROM sqlite_master WHERE type='table';");
			while( $row = $tables->fetch(SQLITE_ASSOC) ) {
				if ( strcmp( $row['tbl_name'], $tableName) == 0 ) {
					$tableExists = true;
				}
			}

			return $tableExists;
		}
		
		private function createTokenTableIfNotExists() {
			if ( ! $this->checkTableExists('userTwitterAuth') ) {
				$this->db->query("BEGIN;
				        CREATE TABLE userTwitterAuth (sessionId TEXT PRIMARY KEY, twitterId INTEGER, token TEXT, secret TEXT);
				        COMMIT;");
				//TESTING: insert some test data
				$this->db->query("BEGIN;
				        INSERT INTO userTwitterAuth (sessionId, twitterId, token, secret) VALUES('" . uniqid() . "', 2087924463, 'AABBCCDDEEFFGGHHII', 'JJKKLLMMNNOO');
				        INSERT INTO userTwitterAuth (sessionId, twitterId, token, secret) VALUES('" . uniqid() . "', 2087286689, 'PPQQRRSSTTUUVVWWXX', 'YYZZAABBCCDD');
				        COMMIT;");
			}
		}
		
	}

?>