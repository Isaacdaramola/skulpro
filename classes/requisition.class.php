<?php

class requisition extends skulpro
{

	protected $annex;
	protected $vendor;
	protected $vendor_location;
	protected $article_of_purchase;
	protected $cost;
	protected $department;
	protected $date;
	protected $principal;
	protected $item;
	protected $price;

	protected $t_name = "requisition";

	protected $user_id;
	protected $token;
	const CAN_MAKE_REQUISITION = "can_make_requisition";
	const CAN_APPROVE_REQUISITION = "can_approve_requisition";



	public function __construct()
	{
		parent::__construct();
	}


	public static function adminAccess(){
		if(in_array(self::CAN_MAKE_REQUISITION,$_SESSION['userSessionrolecapability']) &&
			in_array(self::CAN_APPROVE_REQUISITION,$_SESSION['userSessionrolecapability'])):
			return true;
		else:
			return false;
		endif;
	}

	public static function creatorAccess(){
		if(in_array(self::CAN_MAKE_REQUISITION,$_SESSION['userSessionrolecapability']) || self::adminAccess()):
			return true;
		else:
			return false;
		endif;
	}



	public  function Access($code=null){
		if(in_array(self::CAN_DELETE_INVENTORY,$_SESSION['userSessionrolecapability']) || self::adminAccess()):
			return true;
		else:
			return false;
		endif;
	}

	public static function make_requisition(){
		extract($_POST);
		$form_data  = array('annex' => addslashes($annex) ,
		 										'vendor' => addslashes($vendor) ,
												'vendor_location' => addslashes($vendor_location) ,
												'item' => serialize($item) ,
												'price' => serialize($price) ,
												'department' => addslashes($department) ,
												'date' => addslashes($date) ,
												'user_id' => $_SESSION['user_session']->id ,
												);

		$query = $this->db->insert_data('requisition',$form_data);
		if($query['status'] == true):
			send_mail("kayzenk@gmail.com","Requisiion","Good day boss, I just made a requisiion for Stuffs on the system");
		endif;


	}


	static function creator_f(){
		//	if(isset($_POST['btn-new'])):
		$annex = "";
		$vendor = "";
		$vendor_location = "";
		$article_of_purchase = "";
		$cost = "" ;
		$department = "";
		$date = "";
		$principal = "";
		$btn_name	=	'btn_new_requisition';
		$btn_icon	=	'plus';
		$btn_value	=	'Save';
		$panel_color = 'danger';
		$panel_title	=	'New Requisition';
		$panel_body = TEMPLATES_PATH."widgets/requisition/form.phtml";

		get_header("New Requisition");
		get_sidebar('Requisition');
		if(isset($_POST['btn-new-requisition'])):
			self::make_requisition();
		endif;
		require TEMPLATES_PATH."html/panel.phtml";
		get_footer();
	}

	public function full_data(){
		$data = array();
		$id = $_SESSION['user_session']->id;
		if(!in_array(strtolower($_SESSION['user_session']->user_role),array("admin","principal","burser","accountant"))):
			$query = $this->conn->query("SELECT* FROM $this->t_name  user_id='$id' ");
		else:
			$query = $this->conn->query("SELECT* FROM $this->t_name  ");
		endif;

		if($query->num_rows > 0):
			while ($result = $query->fetch_assoc()) {
				$data[] = $result;
			}
			return $data;
		else:
			return false;
		endif;

	}

	static function list_r(){
		//	if(isset($_POST['btn-new'])):
		$panel_color = 'danger';
		$panel_title	=	'New Requisition';
		$panel_body = TEMPLATES_PATH."widgets/requisition/table.phtml";



		get_header("Requisitions");
			get_sidebar('Requisitions');

		require TEMPLATES_PATH."html/panel.phtml";
		get_footer();
	}

	public function get_requisition(){

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

	public static function menu(){
			$menu = '<li class="xn-openable">';
					$menu .= '<a href="#"><span class="fa fa-edit"></span> <span class="xn-text"> Requisition</span></a>';
					$menu .= '<ul>';
					$menu .= '<li><a href="'.APP_PATH.'requisition/new" title="New Requisition"><span class="fa fa-plus"></span> <span class="xn-text"> New</span></a></li>';
					$menu .= '<li><a href="'.APP_PATH.'requisition/list" title="New Requisition"><span class="fa fa-list"></span> <span class="xn-text"> List</span></a></li>';
					$menu .= '</ul>';
			$menu .= '</li>';
		return $menu;
	}



}
?>
