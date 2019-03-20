<?php
	/**
	* 
	*/
	class academic_terms extends skulpro
	{
		
		
		function __construct()
		{
			 $databse = new Database;
            $db = $databse->dbConnection();
            $this->conn = $db;
            return $this->conn;
		}

        public function get_academic_terms(){
            $query = $this->conn->query("SELECT* FROM academic_terms");
            //$result = $query->fetch_object();
            return $query;
        }
		
	}


?>