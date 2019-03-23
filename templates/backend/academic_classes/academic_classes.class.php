<?php
	/**
	*
	*/
	class academic_classes extends skulpro
	{
		const CAN_CREATE = "can_create_academic_class";
		const CAN_EDIT = "can_edit_academic_class";
		const CAN_DELETE  = "can_delete_academic_class";
		private $class_name;
		private $class_teacher;
		private $class_rep;
		private $class_rep_assistance;
		private $academic_level;
		private $academic_department;
		private $t_name = "academic_classes";

    private $token;
		const MODULE_NAME = "Academic Classes";





		function __construct()
		{
			$this->class_name = isset($_REQUEST['class_name']) ? addslashes($_REQUEST['class_name'] ) : "" ;
			$this->class_teacher = isset($_REQUEST['class_teacher']) ? addslashes($_REQUEST['class_teacher'] ) : "" ;
			$this->class_rep = isset($_REQUEST['class_rep']) ? addslashes($_REQUEST['class_rep'] ) : "" ;
			$this->class_rep_assistance = isset($_REQUEST['class_rep_assistance']) ? addslashes($_REQUEST['class_rep_assistance'] ) : "" ;
			$this->academic_level = isset($_REQUEST['academic_level']) ? addslashes($_REQUEST['academic_level'] ) : "" ;
			$this->academic_department = isset($_REQUEST['academic_department']) ? addslashes($_REQUEST['academic_department'] ) : "" ;
			$this->token = isset($_REQUEST['token']) ? addslashes($_REQUEST['token'] ) : "" ;
			parent::__construct();
		}

		public function creator(){
			get_header( "Create Academic Class" );
			if( access_check(self::CAN_CREATE) ):

				$form_data['class_name'] = "";
				$user_obj = new user;
				$parameters['user_type'] = "teaching-staff";
				$form_data['teachers'] = $user_obj->get_system_users( $parameters );
				$students = $user_obj->get_system_users( array( 'user_type' => "student") );
				$form_data['class_teacher'] = "";
				$form_data['class_rep'] = "";
				$form_data['class_rep_assistance'] = "";
				$form_data['academic_level'] = "";
				$form_data['class_department'] = "";
				$form_data['button_name'] = "btn-new-class";
				$form_data['button_value'] = "Save";


				$panel_body = __DIR__ . DS . "form.phtml";
				$panel_title = " New Academic Class";
				require BACKEND_PATH . "html/panel.phtml";
			else:
				parent::access_error_message();
				return ;
			endif;

			get_footer();
		}

		### query to get all the school clsses from the table
		public function get_academic_classes(){
			return $this->db->get_full_data("academic_classes");
		}


		### query to fetch single class from the record
		public function get_academic_class($id){
			$this->academic_class_code =(int) $id;
			$query = $this->db->query("SELECT* FROM academic_classes WHERE  id='$this->academic_class_code' ");
			$result = $query->fetch_array();
			return $result;
		}

		public function delete_academic_class($code=null){
			if(self::deleteAccess()):
				$this->academic_class_code = addslashes($code);
				$query = $this->db->query("DELETE FROM academic_classes WHERE  token='$this->academic_class_code' ");
				return $query;
			else:
				self::error_message();
			endif;
		}

		public function get_academic_class_data($code){
			//$this->academic_class_code = (int) addslashes($code);
			$query = $this->db->query("SELECT* FROM academic_classes WHERE  code='$code' ");
			$result = $query->fetch_object();
			return $result;

		}


		public function update_academic_class($academic_class_name,$academic_class_teacher,$academic_class_rep,$academic_class_rep_assistance,$academic_class_teacher_assistance,$academic_class_code){
			if(self::editorAccess()):
				$this->academic_class_name = addslashes($academic_class_name);
				$this->academic_class_rep = addslashes($academic_class_rep);
				$this->academic_class_rep_assistance = addslashes($academic_class_rep_assistance);
				$this->academic_class_teacher = addslashes($academic_class_teacher);
				$this->academic_class_teacher_assistance = addslashes($academic_class_teacher_assistance);
				$this->academic_class_code = (int) $academic_class_code;
				$query = $this->db->query("UPDATE academic_classes
				SET
				class_teacher = '$this->academic_class_teacher',
				class_teacher_assistance = '$this->academic_class_teacher_assistance',
				class_rep = '$this->academic_class_rep',
				class_rep_assistance = '$this->academic_class_rep_assistance'
				WHERE
				code='$this->academic_class_code'  ");
				if($query == true):
					echo '<div class="alert alert-success">'.$academic_class_name.' has been  updated successfully.</div>';
				else:
					echo '<div class="alert alert-danger">'.$academic_class_name.' could not be updated.</div>';
					return false;
				endif;
			else:
				parent::error_message("Sorry! You do not have the access right to perform this operation ");
				return false;
			endif;


		}
	}





?>
