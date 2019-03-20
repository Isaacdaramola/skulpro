<?php
//require 'dbClass.php';
	$database_obj = new Database();
	

	class operation extends Database
	{
				var $name;
				var $teacher;
				var $department;
				var $class;
				var $condition;
				var $primaryAccess = array('ADMIN','SUPERAMIN','ACCOUNTANT','TEACHER','EVENTMANAGER','AUTHOR');
				var $secondaryAccess = array('PARENT','STAFF','STUDENT');
				
				//private $conn;
					
		public function __construct()
		{
				$database = new Database();
				$db = $database->dbConnection();
				$this->conn = $db;
    	}
	
		public function runQuery($sql)
		{
				$stmt = $this->conn->prepare($sql);
				return $stmt;
		}

			
	
		function getChatbot($file)
		{
			
			$url= HELPERS_PATH.$file;
			$chat = file_get_contents($url);
			echo $chat;
		}

		function listStates()
		{
			$states= array("Abia","Adamawa","Anambra","Akwa Ibom","Bauchi","Bayelsa","Benue","Burno","Cross River",
			"Delta","Ebonyi","Enugun","Edo","Ekiki","Gombe","Imo","Jigawa","Kaduna","Kano",
			"Kastina","Kebbi","Kogi","Kwara","Lagos","Nasarawa","Niger","Ogun","Ondo","OSun",
			"Oyo","Pleteau","Rivers","Sokoto","Taraba","Yobe","Zamfara");
			   return $states;
			
		}

		public function Query($sql)
		{
			$query = $this->conn->query($sql);
			return $query;
		}
		
		public static function maintenance_mode(){
			if(getSetting("maintenance_mode") == true):
				$tem_obj = new templating(TEMPLATES_PATH."system/maintenance_mode.tpl");
				$tem_obj->assign("SITENAME",getSetting("sitename"));
				if(!in_array($_SESSION['userSessionrole'],array("SUPERADMIN","ADMIN")) ):
					echo $tem_obj->show();
					exit();
				endif;	
			endif;		
		}

		public static function debugging_mode(){
			if(getSetting("debug_mode") == true):
				error_reporting(1);
				$tem_obj = new templating(TEMPLATES_PATH."system/maintenance_mode.tpl");
				$tem_obj->assign("SITENAME",getSetting("sitename"));

				if(!in_array($_SESSION['userSessionrole'],array("SUPERADMIN","ADMIN")) ):
					echo $tem_obj->show();
					exit();
				endif;
			else:
				error_reporting(0);	
			endif;		
		}
		
		
		

}

	class logs extends operation
	{
		public function log()
		{
			$stmt = $this->conn->prepare("SELECT* FROM skulpro_log");
			return $stmt;				
		}
	}	
	
?>