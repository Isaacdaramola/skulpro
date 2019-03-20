<?php

class inventory extends skulpro
{
	var $item_name;
	var $item_quantity;
	var $item_entry_date;
	var $item_source;
	var $item_category;
	protected $user_id;
	protected $token;
	const CAN_CREATE_INVENTORY = "can_create_inventory";
	const CAN_EDIT_INVENTORY = "can_edit_inventory";
	const CAN_DELETE_INVENTORY = "can_delete_inventory";



	public function __construct()
	{
		// $database = new Database();
		// $db = $database->dbConnection();
		// $this->conn = $db;
		parent::__construct();
	}


	public static function adminAccess(){
		if(in_array(self::CAN_CREATE_INVENTORY,$_SESSION['userSessionrolecapability']) &&
			in_array(self::CAN_EDIT_INVENTORY,$_SESSION['userSessionrolecapability']) &&
			in_array(self::CAN_DELETE_INVENTORY,$_SESSION['userSessionrolecapability'])):
			return true;
		else:
			return false;
		endif;
	}

	public static function creatorAccess(){
		if(in_array(self::CAN_CREATE_INVENTORY,$_SESSION['userSessionrolecapability']) || self::adminAccess()):
			return true;
		else:
			return false;
		endif;
	}


	// public static function userAccess(){
	// 	if(in_array(self::CAN_USE_LIBRARY,$_SESSION['userSessionrolecapability']) || self::adminAccess()):
	// 		return true;
	// 	else:
	// 		return false;
	// 	endif;
	// }


	public static function editorAccess(){
		if(in_array(self::CAN_EDIT_INVENTORY,$_SESSION['userSessionrolecapability']) || self::adminAccess()):
			return true;
		else:
			return false;
		endif;
	}


	public  function deleteAccess($code=null){
		if(in_array(self::CAN_DELETE_INVENTORY,$_SESSION['userSessionrolecapability']) || self::adminAccess()):
			return true;
		else:
			return false;
		endif;
	}


	static function creator_f(){
		//	if(isset($_POST['btn-new'])):
		$item_name = '';
		$item_quantity = '';
		$item_entry_date	=	'';
		$item_source	= '';
		$item_category	= '';
		$btn_name	=	'btn_new_inventory';
		$btn_icon	=	'plus';
		$btn_value	=	'Save';
		$panel_color = 'danger';
		$panel_title	=	'New Inventory';
		$panel_body = TEMPLATES_PATH."inventory/form.phtml";

		get_header("Inventory");
		get_sidebar('Inventory');
		require TEMPLATES_PATH."html/panel.phtml";
		get_footer();

	}

	static function editor_f(){
		$item_name = '';
		$item_quantity = '';
		$item_entry_date	=	'';
		$item_source	= '';
		$item_category	= '';
		$btn_name	=	'';
		$btn_icon	=	'';
		$btn_value	=	'';
		get_file();
	}

	static function delete_f(){

	}



}
?>
