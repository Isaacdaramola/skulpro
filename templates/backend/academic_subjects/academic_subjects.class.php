<?php
	/**
	*
	*/
	class academic_subjects extends skulpro
	{
		const CAN_CREATE = "can_create_academic_subject";
		const CAN_EDIT  = "can_edit_academic_subject";
		const CAN_DELETE  = "can_delete_academic_subject";
		private $name;
		private $academic_subject_teacher;
    private $description;
		private $academic_subject_id;
		private $academic_subject_class;
		private $token;
		private $t_name = "academic_subjects";

    const MODULE_NAME = "academic_subjects";

    public static function access_error_message(){
        parent::error_message(" Sorry, you do not have access right to do this. " );
    }


		final static function adminAccess(){
			if(in_array(self::CAN_CREATE,$_SESSION['user_session_role_capabilities']) &&
				in_array(self::CAN_EDIT,$_SESSION['user_session_role_capabilities']) &&
				in_array(self::CAN_DELETE,$_SESSION['user_session_role_capabilities']) ):;
				return true;
			else:
				return false;
			endif;

		}

		final static function creatorAccess(){
			if(in_array(self::CAN_CREATE,$_SESSION['user_session_role_capabilities']) || self::adminAccess() == true ):
				return true;
			else:
				return false;
			endif;
		}

		final  static function editorAccess(){
			if(in_array(self::CAN_EDIT,$_SESSION['user_session_role_capabilities']) || self::adminAccess() == true ):
				return true;
			else:
				return false;
			endif;
		}

		final static function deleteAccess($code=null){
			if(in_array(self::CAN_DELETE,$_SESSION['user_session_role_capabilities']) || self::adminAccess() == true)
				return true;
		}


		function __construct()
		{
			parent::__construct();
		}

		public function add_academic_subject(){
			extract($_POST);
      $this->name = addslashes($name);
			// check if databse exists, else create interface
			if( $this->db->table_exists( $this->t_name ) == true ):

			elseif( $this->db->table_exists( $this->t_name ) == false ):
				var_export(self::table_template());
				// exit;
				$this->db->query( self::table_template() );
			endif;

			$this->description = addslashes($description);
			$check = $this->db->query("SELECT* FROM academic_subjects WHERE name='$this->name'");
			if($check->num_rows > 0):
				parent::error_message($this->name.' is already in the record. Try anothe Subject name') ;
				return false;
			endif;
			if(self::creatorAccess()):
				$form_data  = array('name' => $this->name , "description" => $this->description );
          $query = $this->db->insert_data( $this->t_name , $form_data , true );
          if($query['status'] == true ):
              parent::success_message($this->name.' has been  added successfully.');
          else:
						parent::error_message($this->db->error) ;
          endif;
			else:
          self::access_error_message();
          return false;
			endif;
		}

		public function update_academic_subject(){
      if(self::editorAccess()):
      	extract($_POST);
				// var_export($_POST);
          $this->name = addslashes($name);
          $this->description = addslashes($description);
					$this->token = addslashes($token);
					$form_data  = array('name' => $this->name , "description" => $this->description );
					$where = "WHERE token='{$this->token}' ";
				$query = $this->db->update_data( $this->t_name , $form_data , $where , true );
				if($query['status'] == true):
					echo '<div class="alert alert-success">'.$name.' has been  updated successfully.</div>';
				else:
					echo '<div class="alert alert-danger">'.$name.' could not be updated.</div>';
					return false;
				endif;
			else:
				self::access_error_message();
			endif;
		}

		public function delete_academic_subject(){
			if(self::deleteAccess()):
				extract( $_REQUEST );
				$this->token = addslashes($token);
				$where = get_parameters( array( 'token' => $this->token ) );
				$query = $this->db->delete_data( $this->t_name , $where , true );
				if($query['status'] == true):
					return parent::success_message("Data deleted successfully");
				else:
					return parent::error_message("Failed to delete file");
				endif;

			else:
				self::access_error_message();
			endif;
		}

		### query to get all the school clsses from the table
		public function get_academic_subjects(){
			$query = $this->db->get_full_data( $this->t_name );
			return $query;
		}

		### query to fetch single class from the record
		public function get_academic_subject($id){
			$this->token =	(int) $id;
			$where = "WHERE id ='$this->token' ";
			return $this->db->get_data( $this->t_name , $where );

		}

		public function get_academic_subject_data($code){
			$query = $this->db->query("SELECT* FROM  academic_subjects WHERE id='$code' ");
			return $query;

		}

		public function get_academic_subject_by_teacher($code=null){
			$query = $this->db->query("SELECT* FROM  academic_subjects WHERE subject_teacher='$code' ");
			if($query->num_rows > 0):
				$result = $query->fetch_array();
				return $result;
			else:
				return array();
			endif;
		}

		public function get_academic_subject_by_class($class){
			$query = $this->db->query("SELECT* FROM  academic_subjects WHERE subject_class='$class' ");
			if($query->num_rows > 0):
				$result = $query->fetch_array();
				return $result;
			else:
				return array();
			endif;
		}


		public function get_academic_subject_by_department($code=null){
			$query = $this->db->query("SELECT* FROM  academic_subjects WHERE academic_department='$code' ");
			if($query->num_rows > 0):
				$result = $query->fetch_array();
				return $result;
			else:
				return array();
			endif;
		}

		function total(){
			return count( self::get_academic_subjects() );
		}


		// public function table_template(){
		// 	$sql = "CREATE academic_subjects (
		// 				id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		// 				name VARCHAR(30) NOT NULL,
		// 				description VARCHAR(30) NOT NULL,
		// 				token VARCHAR(32),
		// 				reg_date TIMESTAMP
		// 			)";
		// 	return $sql;
		// }

		public function table_template(){
			return "CREATE TABLE `academic_subjects` ( `id` INT(17) NOT NULL AUTO_INCREMENT , `name` VARCHAR(50) NOT NULL , `description` TEXT NOT NULL , `token` VARCHAR(32) NOT NULL , `reg_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB ";


		}

		public static function menu(){
      if( access_check( self::CAN_CREATE ) ):
        $menu = '<li class="xn-openable">';
            $menu .= '<a href="#" title="Academic Subjects"><span class="fa fa-list"></span> <span class="xn-text">Academic Subjects</span></a>';
            $menu .= '<ul>';
              $menu .= '<li><a href="'.APP_PATH.'academic_subjects" title="New Teachers weekly report"><span class="fa fa-list"></span> <span class="xn-text"> Subects</span></a></li>';

            $menu .= '</ul>';
        $menu .= '</li>';
        echo $menu;
      else:

      endif;
    }








	}





?>
