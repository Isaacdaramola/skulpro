<?php
class student extends skulpro{

private $course_taken = array();
private $role = "student";
private $department;
private $subject_taken;
private $admitted_date;
private $qualification;
private $class_supervising;
private $work_experience;
private $children = array();
public $user_id;
private $tax_pin;
private $next_of_kin;
protected $first_name;
protected $middle_name;
protected $last_name;
protected $gender;
protected $entry_level;
protected $password;
protected $user_privilege;

protected $age;
protected $blood_group;
protected $weight;
protected $height;
protected $profile_picture;
protected $token;
protected $user_type ="student";


protected $t_name = "students_metadata";

const CAN_CREATE_STUDENT = "can_create_student";
const CAN_EDIT_STUDENT  = "can_edit_student";
const CAN_DELETE_STUDENT  = "can_delete_student";
const CAN_DISABLE_STUDENT  = "can_disable_student";
const CAN_ENABLE_STUDENT  = "can_enable_student";
const CAN_VIEW_STUDENT  = "can_view_student";

	public function create_user(){
		if(self::creatorAccess()):
			extract($_POST);
			$this->user_name = strReplace(addslashes($user_name));
			$this->password = md5($password);
			$this->first_name = addslashes($first_name) ;
			$this->middle_name = addslashes($middle_name);
			$this->last_name = addslashes($last_name);
			// $this->user_email = addslashes($user_email);
			// $this->user_phone_one = addslashes($user_phone_one);
			// $this->user_phone_two = addslashes($user_phone_two);
			// $this->user_fax_address = addslashes($user_fax_address);
			// $this->user_house_address = addslashes($user_house_address);
			// $this->user_role = addslashes($user_role);
			// $this->user_privilege = serialize($user_privilege);
			$this->user_profile_picture = addslashes($profile_picture);
			$this->user_facebook_link = addslashes($facebook_link);
			$this->user_twitter_link = addslashes($twitter_link);
			$this->user_instagram_link = addslashes($instagram_link);
			$this->user_whatsapp = addslashes($whatsapp);

			// Check if user name exists
			if(self::is_user_name_exists($this->user_name) == true):
				self::error_message("This username ($user_name) is already in user by another user.");
				return false;
			endif;

			// Try to check if password is 8 character long else return false
			if(self::check_password_strength($user_password) == false):
				self::error_message("Password is too week. Try something stronger");
				return false;
			endif;



			$form_data  = array('user_first_name' => $this->first_name,
			'user_middle_name' => $this->middle_name,
			 'user_last_name' => $this->last_name,
			 'user_password' => $this->password,
			 'user_role' => $this->user_role,
			 'user_name' => $this->user_name,
			 'user_type' => $this->user_type,
		 );
		 $database = new sql_datacrud;
		 $query = $database->insert_data('users',$form_data);

		self::set_token($query['last_id'],"users");

		$student_data = array('user_id' => $query['last_id'],
			'academic_entry_level' => addslashes($academic_entry_level),
			'height' => addslashes($height) ,
			'weight' => addslashes($weight),
		);
		var_dump($student_data);

				$query = $database->insert_data('students_metadata',$student_data);

				if($query == true):
					self::success_message("User Account Created Successfully!");
				else:
					self::error_message("Sorry, system could not create user account. If problem persist, contact ICT department.".$this->conn->error);
				endif;
		else:
			self::access_error_message();
		endif;
	}

	public function _student_creator(){
		$panel_title = "New Student";
		$gender = "";
		$user_name = "";
		$academic_entry_level = "";
		$first_name = "";
		$middle_name = "";
		$last_name = "";
		$email = "";
		$weight = "";
		$height = "";
		$complexion = "";
		$fax_address = "";
		$phone_one = "";
		$phone_two = "";
		$user_role = "";
		$user_exca = array();
		$facebook_link = "";
		$twitter_link = "";
		$instagram_link = "";
		$whatsapp_link = "";
		$linkedin_link = "";
		$house_address = "";
		$button_name = "btn_new_student";
		$button_value = "Create User";
		$button_icon = "plus";
		extract($_POST);

		get_header("New Student Account");
		get_sidebar("$panel_title");
		if(isset($_POST['btn_new_student'])):
			self::create_user();
		endif;
		require TEMPLATES_PATH."user/student/form.phtml";
		get_footer();
	}

	public function add_student_metadata($user_id){
		$this->user_id = (int) $user_id;
		$query = $this->conn->query("INSERT  INTO students_metadata(user_id)  VALUES('$this->user_id') ");
	}

	public function  update_student_metadata(){

	}

	public function get_students(){
		$query = $this->db->get_full_data( "users", "WHERE user_role='student' " );
		return $query;
	}
	public function get_student_metadata( $token=null ){
		$query =  $this->db->get_full_data( "students_metadata", "WHERE user_id='{$token}' " );
		return $query;
	}

	public static function menu(){
		//if(in_array(self::CAN_CREATE_STUDENT,$_SESSION['user_session_role_capabilities'])):
			$menu = '<li>';
			$menu .=	'<a href="#"><span class="fa fa-users"></span> <span class="xn-text"> STUDENTS</span></a>';
			$menu .=	'<ul>';
			if(in_array(self::CAN_CREATE_STUDENT,$_SESSION['user_session_role_capabilities'])):
				$menu .=	'<li>';
				$menu .=	'<a href='.APP_PATH.'sms><span class="fa fa-plus"></span> <span class="xn-text">Send SMS</span></a>';
				$menu .=	'</li>';
			endif;
			$menu	.= '</ul>';
			$menu	.= '</li>';
		//endif;
		return $menu;
	}

}

?>
