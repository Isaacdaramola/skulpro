<?php
	/**
	*
	*/
	class academic_schemes extends skulpro
	{
		const CAN_CREATE = "can_create_academic_schemes";
		const CAN_EDIT  = "can_edit_academic_schemes";
		const CAN_DELETE  = "can_delete_academic_schemes";
		private $name;
		private $academic_level;
		private $academic_subject;
		private $academic_topics;
		private $creation_time;
		private $note;
		private $token;
		protected $t_name = "academic_schemes";

		function __construct()
		{
			$this->db = new sql_datacrud;
			$this->table = isset($_REQUEST['table']) ? addslashes(base64_decode($_REQUEST['table'])) : $this->t_name;
			$this->name = isset($_REQUEST['name']) ? addslashes($_REQUEST['name']) : "";
			$this->academic_topics = isset($_REQUEST['academic_topics']) ? serialize($_REQUEST['academic_topics']) : "";
			$this->academic_level = isset($_REQUEST['academic_level']) ? addslashes($_REQUEST['academic_level']) : "";
			$this->academic_subject = isset($_REQUEST['academic_subject']) ? addslashes($_REQUEST['academic_subject']) : "";
			$this->creation_time = time();
			$this->note = isset($_REQUEST['note']) ? addslashes($_REQUEST['note']) : "";
			$this->token = isset($_REQUEST['token']) ? addslashes($_REQUEST['token']) : "";
		}

		public function delete_data( $token = null ){
			$this->topic_id = addslashes($topic_id);
			$where = get_parameters(array('id' => $this->token ));
			$query = $this->db->delete_data( $this->t_name , $where , true );
			if($query == true):
				parent::success_message("Subtopic has been deleted successfully");
			else:
				parent::error_message("Subtopic failed to be deleted.");
			endif;

		}

		public function if_exists( $code = null , $name = null  ){
			$where = "WHERE token='{$code}' AND name !='{$name}' ";
			return $this->db->get_data( $this->t_name , $where);
		}

		static function module_path(){
      return basename(__DIR__);
    }




		public function insert_data(  ){
			if( access_check( self::CAN_CREATE ) ):
				if(isset($_POST['new-scheme'])):
					extract($_REQUEST);
					$this->note = addslashes($note);
					if( strlen($this->name) < 3 ){
						parent::error_message("Your scheme name lenght must greater than three characters");
						return;
					}
					$form_data = array(
						'name' => $this->name,
						'academic_subject' => $this->academic_subject,
						'academic_level' => $this->academic_level,
						'academic_topics' => $this->academic_topics,
						'note' => $this->note,
						'creation_time' => $this->creation_time,
					);
					$check = self::if_exists($this->name );
					if($check){
						return parent::error_message("Sorry! The subtopic already exist in the database");
					}
					$query = $this->db->insert_data( $this->t_name , $form_data , true );

				endif;
				$name = "";
				$academic_topics = array();
				$academic_level = array();
				$academic_subject = array();
				$note = "";
				$panel_title = " New Scheme Creator";
				$button_icon = "";
				$button_name = "new-scheme";
				$button_value = "Publish";
				$panel_body =  __DIR__ . DS . "form.phtml";
				include BACKEND_PATH . "html/panel.phtml";
			else:
				parent::access_error_message();
			endif;
		}

		public function update_data( $name = null , $note = null , $token = null ){
			if( access_check( self::CAN_CREATE ) ):
				if(isset($_POST['update-subtopic'])):
					extract($_REQUEST);
					if( strlen( $this->name ) < 3 ){
						parent::error_message("Your scheme name lenght must greater than three characters");
						return;
					}
					$form_data = array(
						'name' => $this->name,
						'academic_subject' => $this->academic_subject,
						'academic_level' => $this->academic_level,
						'academic_topics' => $this->academic_topics,
						'note' => $this->note,
					);
					$check = self::if_exists( $this->token , $this->name );
					if($check){
						parent::error_message("Sorry! The subtopic already exist in the database");
						return;
					}
					$where = get_parameters(array("token" => $this->token));

					$query = $this->db->update_data( $this->t_name , $form_data , $where , true );
					if($query == true){
						parent::success_message("Data updated successfully");

					}

					header("location: " . APP_PATH . 'academic_schemes/list' );
					// var_export($query);

				endif;
				$data = self::get_data();

				$name = $data['name'];
				$academic_topics = unserialize($data['academic_topics']);
				$academic_level = $data['academic_level'];
				$academic_subject = $data['academic_subject'];
				$note = $data['note'];
				$panel_title = " Editing  scheme";
				$button_icon = "";
				$button_name = "update-subtopic";
				$button_value = " Update";
				$panel_body =  __DIR__ . DS . "form.phtml";
				include BACKEND_PATH . "html/panel.phtml";
			else:
				parent::access_error_message();
			endif;
		}

		function get_data( $token = null ){
			extract($_REQUEST);
			$where = get_parameters(array('token' => $this->token ));
			return $this->db->get_data( $this->t_name , $this->where );

		}



		public function get_full_data(){
			return $this->db->get_full_data( $this->t_name );
		}








		public static function menu()
		{
			$menu = '<li class="xn-openable">';
				if( access_check( self::CAN_CREATE )  ):
					$menu .=	'<a href="#"><span class="fa fa-user"></span> <span class="xn-text">Academic Scheme</span></a>';
					$menu .=	'<ul>';
						$menu .=	'<li> <a href='.APP_PATH.'academic_schemes/list><span class="fa fa-list"></span> <span class="xn-text">List </span></a></li>';
						$menu .=	'<li> <a href='.APP_PATH.'academic_schemes/new><span class="fa fa-plus"></span> <span class="xn-text">New </span></a></li>';
					$menu	.= '</ul>';
				endif;

			$menu	.= '</li>';
			echo $menu;
		}

	}


?>
