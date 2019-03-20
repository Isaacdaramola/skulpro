<?php
 /**
 *
 */

 class Database
 {
 	  const DB_HOST = "localhost";
  	const DB_NAME = "ace_college";
  	const DB_USER = "root";
  	const DB_PASS = "";
    const DB_PREFIX = "";
  	protected $conn;



   	function dbConnection()
   	{
      try {
          $this->conn = null;
          $host = self::DB_HOST;
          $db_name = self::DB_NAME;
          $db_user = self::DB_USER;
          $db_password = self::DB_PASS;
      		$this->conn = new mysqli("{$host}" , "{$db_user}" , "{$db_password}" , "{$db_name}" );
          // var_export( $this->conn);
      		return $this->conn;

      } catch ( PDOException $e) {
        $page_info['page_title'] = "Database Error";
        $page_info['page_body'] = "<i><h1>Error: Application is unable to connect to the Database server</i></h1>";
        die( $page_info['page_body'] . $e->getMessage());
        // include BACKEND_PATH . "html/page.phtml";
        exit();
      }


   	}


 }




?>
