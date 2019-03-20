<?php
	/**
	*
	*/
	class academic_department_subjects extends skulpro
	{
		const CAN_CREATE_ACADEMIC_DEPARTMENT_SUBJECT = "can_create_academic_department_subject";
		const CAN_EDIT_ACADEMIC_DEPARTMENT_SUBJECT  = "can_edit_academic_department_subject";
		const CAN_DELETE_ACADEMIC_DEPARTMENT_SUBJECT  = "can_delete_academic_department_subject";
		private $academic_department_id;
		private $academic_subject_id;
		private $id;
		private $table_name = "academic_department_subjects";


		final static function adminAccess(){
			if(in_array(self::CAN_CREATE_ACADEMIC_DEPARTMENT_SUBJECT,$_SESSION['user_session_role_capabilities']) &&
				in_array(self::CAN_EDIT_ACADEMIC_DEPARTMENT_SUBJECT,$_SESSION['user_session_role_capabilities']) &&
				in_array(self::CAN_DELETE_ACADEMIC_DEPARTMENT_SUBJECT,$_SESSION['user_session_role_capabilities']) ):
				return true;
			else:
				return false;
			endif;

		}

		final static function creatorAccess(){
			if(in_array(self::CAN_CREATE_ACADEMIC_DEPARTMENT_SUBJECT,$_SESSION['user_session_role_capabilities']) || self::adminAccess() == true ):
				return true;
			else:
				return false;
			endif;
		}

		final  static function editorAccess(){
			if(in_array(self::CAN_EDIT_ACADEMIC_DEPARTMENT_SUBJECT,$_SESSION['user_session_role_capabilities']) || self::adminAccess() == true ):
				return true;
			else:
				return false;
			endif;
		}

		final function deleteAccess($code=null){
			if(in_array(self::CAN_DELETE_ACADEMIC_DEPARTMENT_SUBJECT,$_SESSION['user_session_role_capabilities']) || self::adminAccess() == true)
				return true;
		}


		function __construct()
		{
			$database = new Database();
			$db = $database->dbConnection();
			$this->conn = $db;
		}

		public function get_academic_department_subjects($id){
			$this->id =(int) $id;
			$query = $this->conn->query("SELECT* FROM $this->table_name WHERE departments='$this->id' ");
			return $query;
		}

		public function department_subject_check($department,$subject){
			$query = $this->conn->query("SELECT* FROM $this->table_name WHERE departments='$department' AND subjects='$subject' LIMIT 1");
			if($query->num_rows > 0):
				return true;
			else:
				return false;
			endif;
		}

		public function add_academic_department_subjects(){
			extract($_POST);
			$this->academic_department_id = (int) $department ;
			$this->academic_subject_id = (int) $subject;
			if(self::department_subject_check($this->academic_department_id,$this->academic_subject_id) == false):
				$query = $this->conn->query("INSERT INTO $this->table_name(departments,subjects) VALUES('$this->academic_department_id','$this->academic_subject_id') ");
				if($query == true):
					parent::success_message("Department Subject Created successfully.");
				else:
					parent::error_message("Department Subject failed creating. ".$this->conn->error);
				endif;
			else:
				parent::error_message("Department Subject is already in the database.");
			endif;
		}

		public function delete_academic_department_subjects(){
			extract($_POST);
			$this->id = (int) $code ;
				$query = $this->conn->query("DELETE FROM $this->table_name WHERE id='$this->id' ");
				if($query == true):
					parent::success_message("Deleted successfully.");
				else:
					parent::error_message("Failed to delete. ".$this->conn->error);
				endif;
		}



	}





?>
