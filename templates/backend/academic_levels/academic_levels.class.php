<?php
	/**
	*
	*/
	class academic_levels extends skulpro
	{
		const CAN_CREATE = "can_create_academic_level";
		const CAN_EDIT  = "can_edit_academic_level";
		const CAN_DELETE  = "can_delete_academic_level";
		private $name;
		private $description;
		private $year_tutor;
		const MODULE_NAME = "Academic Levels";
		private $t_name = "academic_levels";
		protected $token;
		// protected $db;





		function __construct()
		{
			parent::__construct();
			$this->name = isset($_REQUEST['name']) ? addslashes( $_REQUEST['name'] ) : "";
			$this->description = isset($_REQUEST['description']) ? addslashes( $_REQUEST['description'] ) : "";
			$this->year_tutor = isset($_REQUEST['year_tutor']) ? addslashes( $_REQUEST['year_tutor'] ) : "";
			$this->token = isset($_REQUEST['token']) ? addslashes( $_REQUEST['token'] ) : "";

		}

		public function get_full_data(){
			return $this->db->get_full_data($this->t_name);
		}

		public function get_academic_level( $id = null ){
			$this->token = addslashes($id);
			$where = "WHERE id='{$this->token}' ";
			return $this->db->get_data( $this->t_name , $where );

		}

		public function get_data( $value='')
		{
			$this->token = isset( $_REQUEST['token'] ) ? addslashes( $_REQUEST['token'] ) : addslashes( $value );
			$where = get_parameters( array( 'token' => $this->token ));
			return $this->db->get_data( $this->t_name , $where );
		}

		public function insert_data(){
			if( !access_check(self::CAN_CREATE)){
				self::access_error_message();
				return;
			}

			$form_data = array(
				'name' => $this->name,
				'description' => $this->description,
			);

			if ( empty( $form_data['name'] ) || strlen( $form_data['name'] ) < 3 ) {
				self::error_message( 'level name can not be empty or lesser than three characters.' );
				return;
				// code...
			}

			return $this->db->insert_data( $this->t_name , $form_data ,  true , true );
		}

		public function update_data(){
			if( !access_check(self::CAN_CREATE)){
				self::access_error_message();
				return;
			}

			$form_data = array(
				'name' => $this->name,
				'description' => $this->description,
			);

			if ( empty( $form_data['name'] ) || strlen( $form_data['name'] ) < 3 ) {
				self::error_message( 'level name can not be empty or lesser than three characters.' );
				return;
				// code...
			}
			$where = get_parameters( array('token' => $this->token) );

			return $this->db->update_data( $this->t_name , $form_data , $where , true );
		}

		public function creator_f(){
			get_header("New Academic Level");
			 if( !access_check(self::CAN_CREATE)){
				 self::access_error_message();
				 return;
			 }

			 if( isset( $_POST['save-name'])){
				 self::insert_data();
			 }

			$form_data['name'] = "";
			$form_data['description'] = "";
			$form_data['button_name'] = "save-name";
			$form_data['button_value'] = "Save";
			$panel_title = "New Academic Level Creator";

			// if the form eixsts , store the path in the variable else , return empty string.
			$panel_body = file_exists( __DIR__ . DS . "form.phtml") ?  __DIR__ . DS . "form.phtml" : ERROR_PATH ;
			require PANEL_PATH;

			get_footer();
		}

		public function editor_f(){
			get_header("Edit Academic Level");
			 if( !access_check(self::CAN_EDIT)){
				 self::access_error_message();
				 return;
			 }

			 if( isset( $_POST['update-name'])){
				 self::update_data();
			 }

			$data = self::get_data(array('token' => $this->token ));

			$form_data['name'] = $data['name'];
			$form_data['description'] = $data['description'];
			$form_data['button_name'] = "update-name";
			$form_data['button_value'] = "Save";
			$panel_title = "Editing Academic Level";

			// if the form eixsts , store the path in the variable else , return empty string.
			$panel_body = file_exists( __DIR__ . DS . "form.phtml") ?  __DIR__ . DS . "form.phtml" : ERROR_PATH ;
			require PANEL_PATH;

			get_footer();
		}

		public function list_t(){
			get_header("Academic Levels");
			 if( !access_check(self::CAN_CREATE)){
				 self::access_error_message();
				 return;
			 }

			 if( isset( $_POST['save-name'])){
				 self::insert_data();
			 }

			// $form_data['name'] = "";
			// $form_data['description'] = "";
			// $form_data['button_name'] = "save-name";
			// $form_data['button_value'] = "Save";
			$panel_title = "Academic Levels";

			// if the form eixsts , store the path in the variable else , return empty string.
			$table_data = self::get_full_data() ;
			$panel_body = __DIR__ . DS . "table.phtml";
			require PANEL_PATH;

			get_footer();
		}


		public function delete_data(){
			$where = get_parameters( array('token' => $this->token ));
			$this->db->delete_data( $this->t_name , $where , true , true );
			header('Location: ' . APP_PATH . basename( __DIR__) );
		}


		public function widgets()
		{
			$int_value = count(self::get_full_data());
			$color = "warning";
			$col = 3;
			$title = "Levels";
			$subtitle = "Academic Levels";
			require WIDGET_PATH;
		}

		static function module_path()
		{
			return basename( __DIR__ );
		}

		public static function menu(){
			// if( access_check(self::CAN_CREATE_SETTING) ):
				$menu = '<li class="xn-openable">';
						$menu .= '<a href="#" title='. self::MODULE_NAME .'><span class="fa fa-cogs"></span> <span class="xn-text">'. self::MODULE_NAME  .'</span></a>';
						$menu .= '<ul>';
							if( access_check(self::CAN_CREATE) ):
								$menu .= '<li><a href="'.APP_PATH . self::module_path() . '/new/ " title="New " '. self::MODULE_NAME  .' "><span class="fa fa-plus"></span> <span class="xn-text"> New </span></a></li>';
							endif;
							$menu .= '<li><a href="'.APP_PATH . self::module_path() .'/list " title="List " '. self::MODULE_NAME  .' "><span class="fa fa-list"></span> <span class="xn-text"> List </span></a></li>';
						$menu .= '</ul>';
				$menu .= '</li>';
				echo $menu;
			// endif;
		}



	}





?>
