<?php
	/**
	* 
	*/
	class classes extends skulpro
	{
		protected $class_name;
		protected $class_rep;
		protected $class_rep_assistance;
		protected $class_teacher;
		protected $class_id;
		
		function __construct()
		{
			 $databse = new Database;
            $db = $databse->dbConnection();
            $this->conn = $db;
            return $this->conn;
		}

		public function add_class($class_name,$class_rep,$class_rep_assistance,$class_teacher){
			$this->class_name = addslashes($class_name);
			$this->class_rep = addslashes($class_rep);
			$this->class_rep_assistance = addslashes($class_rep_assistance);
			$this->class_teacher = addslashes($class_teacher);
			$query = $this->conn->query("INSERT INTO classes(class_name,class_rep,class_rep_assistance,class_teacher) VALUES('$this->class_name','$this->class_rep','$this->class_rep_assistance','$this->class_teacher') ");
			if($query == true):
				parent::success_message("Class has been inserted successfully");
			else:
				parent::error_message("Class failed to be inserted.");
			endif;
		}

		public function delete_class($class_id){
			$this->class_id = addslashes($class_id);
			$query = $this->conn->query("DELETE FROM classes WHERE id='$this->class_id' ");
			if($query == true):
				parent::success_message("Class has been deleted successfully");
			else:
				parent::error_message("Class failed to be deleted.");
			endif;

		}

		public function update_class($class_rep,$class_rep_assistance,$class_teacher,$class_id){
			$this->class_rep = addslashes($class_rep);
			$this->class_rep_assistance = addslashes($class_rep_assistance);
			$this->class_teacher = addslashes($class_teacher);
			$this->class_id = addslashes($class_id);
			$query = $this->conn->query("UPDATE classes SET class_rep='$this->class_rep', class_rep_assistance='$this->class_rep_assistance', class_teacher=$this->,class_teacher  WHERE id='$this->class_id' ");
			if($query == true):
				parent::success_message("Class has been updated successfully");
			else:
				parent::error_message("Class failed to be updated.");
			endif;
		}

		public function get_classes(){
			$query = $this->conn->query("SELECT* FROM classes ");
			return $query;
		}

		public function get_classe($class_id){
			$this->class_id = addslashes($class_id);
			$query = $this->conn->query("SELECT* FROM classes WHERE id='$tis->class_id' ");
			return $query;
		}
	}


?>