<?php
	class system_templates extends skulpro
	{
		const CAN_CREATE = "can_create_system_template";
		const CAN_DELETE = "can_delete_system_template";
		const CAN_EDIT = "can_edit_system_template";
		const CAN_USE = "can_use_system_template";
		public $module_name = "system_templates";
		protected $subject;
		protected $content;
		protected $id;
		protected $type;
		protected $table_name = "system_templates";
		public   	$db;
		private   $author;


	function __construct()
	{
		parent::__construct();
		$this->subject 	= isset( $_REQUEST['subject'] ) ? addslashes( $_REQUEST['subject'] ) : "" ;
		$this->content 	= isset( $_REQUEST['content'] ) ? addslashes( $_REQUEST['content'] ) : "" ;
		$this->type 		= isset( $_REQUEST['type'] ) ? addslashes( $_REQUEST['type']) : "" ;
		$this->author 	= get_user()['id'] ;
	}



	public function add_template(){
		if( access_check( self::CAN_CREATE ) ):
			extract($_POST);
			$this->title = addslashes($title);
			$this->content = addslashes($content);
			$this->type = addslashes($type);
			$this->author = $_SESSION['user_session']->id;
			$query = $this->db->query("INSERT
				INTO
				$this->table_name(title,content,type,author)
				VALUES('$this->title','$this->content','$this->type','$this->author') ");
			if($query == true):
				parent::success_message("System template created successfully");
			else:
				parent::error_message("Failed to created system template ". $this->db->error);
			endif;
		else:
			parent::error_message("You do not have the access to update this template");
		endif;

	}

	public function update_template(){
		if(self::editorAccess()):
			extract($_POST);
			$this->title = addslashes($title);
			$this->content = addslashes($content);
			$this->type = addslashes($type);
			$this->id = (int) base64_decode($token);
			$query = $this->db->query("UPDATE
				$this->table_name SET
				title = '$this->title',
				content = '$this->content',
				type = '$this->type'
				WHERE
				id = '$this->id'
				");
			if($query == true):
				parent::success_message("System template updated successfully");
			else:
				parent::error_message("Failed to update system template ". $this->db->error);
			endif;
		else:
			parent::error_message("You do not have the access to update this template");
		endif;


	}


	public function get_template( array $parameters = null ){
		$where  = get_parameters( $parameters );
		return $this->db->get_data( $this->table_name , $where );

	}


	public function get_data( array $parameters = null ){
		$where  = get_parameters( $parameters );
		return $this->db->get_full_data( $this->table_name , $where );
	}


	public function get_sms(){
		$where = " WHERE type='sms' ";
		return $this->db->get_full_data( $this->table_name , $where );
	}

}


?>
