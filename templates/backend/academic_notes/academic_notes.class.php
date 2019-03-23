<?php
	/**
	*
	*/
	final class academic_notes extends skulpro
	{
		const CAN_CREATE_ACADEMIC_NOTE = 'can_create_academic_note';
		const CAN_DELETE_ACADEMIC_NOTE = 'can_delete_academic_note';
		const CAN_EDIT_ACADEMIC_NOTE = 'can_edit_academic_note';
		const CAN_CHANGE_ACADEMIC_NOTE_STATUS = 'can_change_academic_note_status';

		protected $title;
		protected $description;
		protected $academic_level;
		protected $academic_subject;
		protected $academic_topic;
		protected $file_path;
		protected $id;
		protected $author;
		protected $note_status = "unapproved";
		public $module_name = "academic_notes";
		protected $t_name = "academic_notes";
		protected $token;
		protected $db;

		public static function adminAccess(){
			if(in_array(self::CAN_CREATE_ACADEMIC_NOTE,$_SESSION['user_session_role_capabilities']) &&
				in_array(self::CAN_EDIT_ACADEMIC_NOTE,$_SESSION['user_session_role_capabilities']) &&
				in_array(self::CAN_DELETE_ACADEMIC_NOTE,$_SESSION['user_session_role_capabilities']) ):;
				return true;
			else:
				return false;
			endif;

		}



		function __construct()
		{
			$database = new sql_datacrud;
			$this->db = $database ;
			$this->token = @addslashes( $_REQUEST['token'] );
		}

		public function add_note(){
			extract( $_POST );
			// var_export($_POST);
			$this->title = addslashes($title);
			$this->description = addslashes($description);
			$this->academic_level = (int) $academic_level;
			$this->academic_subject = (int) $academic_subject;
			$this->academic_topic =  isset( $topic ) ? $topic : 0;
			$file = upload( null , NOTES_PATH );

			$form_data = array(
				"title" => $this->title,
				"description" => $this->description,
				"academic_level" => $this->academic_level,
				"academic_subject" => $this->academic_subject,
				"academic_topic" => $this->academic_topic,
				"file_name" => $file['file_name'],
				"file_type" => $file['file_type'],
				"file_size" => $file['file_size'],
			);

			$where = "WHERE token='{$this->token}' ";
			$this->db->insert_data( $this->t_name , $form_data  , $where );
		}


		final public function update_note(){
			$this->title = addslashes($title);
			$this->description = addslashes($description);
			$this->academic_level = (int) $academic_level;
			$this->academic_subject = (int) $academic_subject;
			$this->academic_topic =  isset( $topic ) ? $topic : 0;
			$file = upload( null , NOTES_PATH );

			$form_data = array(
				"title" => $this->title,
				"description" => $this->description,
				"academic_level" => $this->academic_level,
				"academic_subject" => $this->academic_subject,
				"academic_topic" => $this->academic_topic,
				"file_name" => $file['file_name'],
				"file_type" => $file['file_type'],
				"file_size" => $file['file_size'],
			);
			$this->db->update_data( $this->t_name , $form_data  , true );

		}

		public function update_status($code=null,$status=null){
			if(self::statusChangeAccess()):
				$query = $this->conn->query("UPDATE $this->table_name SET note_status='$status' WHERE token='$code' ");
				if($query == true):
					parent::success_message("Note status chaned to $status successfully.");
				else:
					parent::error_message("Failed to change note's status.");
				endif;
			else:
				parent::error_message("Sorry! you do not have the access right to change this not status");
			endif;
		}

		public static function count_note(){

		}

		public static function count_approved_note(){
		}


		public static function count_unapproved_note(){
		}

		public static function count_user_note($author){
		}



		public function get_note_data($code){
		}

		final public function delete_note($id){
		}

		final public function read_note($id){
		}

		final public function get_notes(){
			return $this->db->get_full_data($this->t_name );

		}

		final public function get_setting($name){
		}

		final public function update_setting($name,$value){
		}

		final public function make_setting($name,$value){
		}

		public function get_notes_by_topic($topic){
		}

	}




?>
