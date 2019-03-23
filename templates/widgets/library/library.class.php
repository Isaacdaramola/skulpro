<?php

class library extends skulpro
{
	private $name;
	private $access;
	private $title;
	private $token;
	private $desc;
	private $class;
	private $size;
	public $db;
	private $author;
	private $t_name = "library";
	public $module_name = "library";
	const CAN_CREATE = "can_create_library";
	const CAN_EDIT = "can_edit_library";
	const CAN_DELETE = "can_delete_library";
	const CAN_DELETE_OTHERS_LIBRARY = "can_delete_others_library";
	const CAN_USE = "can_use_library";


	public function __construct( $name = null , $title = null , $desc = null , $class = null )
	{
		parent::__construct();
		$this->name = isset( $_REQUEST['name'] ) ? addslashes( $_REQUEST['name'] ) : "";
		$this->title = isset( $_REQUEST['title'] ) ? addslashes( $_REQUEST['title'] ) : "";
		$this->desc =  isset( $_REQUEST['desc'] ) ? addslashes( $_REQUEST['desc'] ) : "";
		$this->class =   isset( $_REQUEST['class'] ) ? addslashes( $_REQUEST['class'] ) : "";
		$this->token =   isset( $_REQUEST['token'] ) ? addslashes( $_REQUEST['token'] ) : "";
		$this->author = $_SESSION['user_session']->id;
	}


	public static function adminAccess(){
		if(in_array(self::CAN_CREATE,$_SESSION['userSessionrolecapability']) &&
			in_array(self::CAN_EDIT,$_SESSION['userSessionrolecapability']) &&
			in_array(self::CAN_DELETE,$_SESSION['userSessionrolecapability']) &&
			in_array(self::CAN_DELETE_OTHERS,$_SESSION['userSessionrolecapability']) &&
			in_array(self::CAN_USE,$_SESSION['userSessionrolecapability'])):
			return true;
		else:
			return false;
		endif;
	}

	public static function creatorAccess(){
		if(in_array(self::CAN_CREATE_LIBRARY,$_SESSION['userSessionrolecapability']) || self::adminAccess()):
			return true;
		else:
			return false;
		endif;
	}


	private function _get_author($code){
		$query = $this->conn->query("SELECT author FROM skulpro_library WHERE code='$code' ");
		$author = $query->fetch_object();
		$this->author = $author;
		return $this->author;

	}


	public function getprimaryAccess()
	{
		return $this->primaryAccess;
	}

	public function getsecondaryAccess()
	{
		return $this->secondaryAccess;
	}





	function getthisBook($col,$code)
	{
		$query = $this->conn->query("SELECT* FROM
		skulPRO_library
		WHERE code='$code'");
		while($row = mysqli_fetch_array($query))
		{
		return $row[$col];
		}
	}

	public function getLibrary()
	{
		$userRole = $_SESSION['userSessionrole'];
		$userCode = $_SESSION['userSessioncode'];
		$userClass = $_SESSION['userSessionclass'];

		switch ($userRole) {
			case 'SUSERADMIN':
			case 'ADMIN':
			case 'TEACHER':
			case 'ACCOUNTANT':
			case 'STAFF':
				$query = $this->conn->query("SELECT* FROM skulPRO_library");
				return $query;
				# code...
				break;

			case 'PARENT':
				$query = $this->conn->query("SELECT* FROM skulPRO_library WHERE dept='general' ");
				return $query;
				break;

			case 'STUDENT':
				$query = $this->conn->query("SELECT* FROM skulPRO_library WHERE dept='general' AND dept='$userClass'  ");
				return $query;
				break;

			default:
				# code...
				break;
		}

	}


	public function downloadBook()
	{
		if( isset( $_GET['action'] ) && $_GET['action']=='download' && !empty( $_GET['token'] ) )
		{
			$code = (int) $_GET['token'];
			$category = 'School Library Asset';
			$downloadAgent = $_SERVER['HTTP_USER_AGENT'].'<br>';
			$user = $_SESSION['userSessionname'];
			$name = self::getthisBook('name',$code);
			$path = LIBRARY_PATH.$name;
			$setting = new operation;
			$download = new download();
			$stmt = $this->conn->prepare("UPDATE skulPRO_library SET downloadCount=downloadCount+1 WHERE code='$code'");
			$stmt->execute();
			if($stmt==TRUE)
			{
				$download->downloadAsset( $path,$category,$downloadAgent,$user );
			}
		}
	}

	public function get_full_data()
	{
		return $this->db->get_full_data( $this->t_name );
	}


	public function readCounter( $code )
	{
		$stmt = $this->conn->prepare("UPDATE skulPRO_library SET readCount=readCount+1 WHERE code='$code'");
		$stmt->execute();
	}

	function getAssets()
	{
		$query = $this->conn->query("SELECT* FROM skulPRO_library");
		return $query;
	}




	function creator_f(){
		get_header("Upload Assets to library");

				if( in_array(  self::CAN_CREATE, get_user_capability() ) ):
					if( isset( $_POST['btn-new'] ) ):
						$query = upload(rand( 643217890,3214543256 ).".pdf", LIBRARY_PATH );
						$form_data = array(
						"name" => $query['file_name'],
						"author" => $this->author ,
						"title" => addslashes( $_POST['title']) ,
						"description" => addslashes( $_POST['description']) ,
						"academic_class_id" => addslashes( $_POST['academic_class']) ,
						"academic_subject_id" => addslashes( $_POST['academic_subject']) ,
						"type" => $query['file_type']
					 );
					 $query = $this->db->insert_data( $this->t_name, $form_data, true , true );
					 // var_export($query);
					endif;

					$panel_title = get_setting( 'site_name' )." Academic Settings Creator";
					$setting_name = "";
					$setting_value = "";

					$button_name = "btn-new";
					$button_icon = "plus";
					$button_value = "Save";
					require TEMPLATES_PATH."widgets".DS.__CLASS__.DS."form.phtml";
					get_footer();
			else:
				parent::access_error_message();
			endif;
	}

	public function history(){
		get_header("library Books");
		// if( in_array( self::CAN_CREATE_SETTING, get_user_capability() ) ):
			get_sidebar("Upload Assets to library");
			if( isset( $_POST['btn-new'] ) ):
				$query = upload(rand( 643217890,3214543256 ).".pdf", LIBRARY_PATH );
				$form_data = array(
				"name" => $query['file_name'],
				"author" => $_SESSION['user_session']->id,
				"type" => $query['file_type']
			 );
			 $query = $this->db->insert_data( $this->t_name, $form_data, true );
			endif;

			$panel_title = get_setting( 'site_name' )." Academic Settings Creator";
			require __DIR__ . DS . "table.phtml";
			get_footer();
		// else:
		// 	parent::access_error_message();
		// endif;
	}

	function delete_data( array $parameters = null){
		$where =  get_parameters( $parameters );
		if(file_exists( LIBRARY_PATH . $parameters['name'] )){
			unlink( LIBRARY_PATH . $parameters['name'] );
		}
		// var_export($parameters);

		$this->db->delete_data( $this->t_name, $where , true , true );


	}



	// public function countLibrary()
	// {
	// 	$query = $this->conn->query("SELECT* FROM skulpro_library");
	// 	$tCount = mysqli_num_rows($query);
	// 	return $tCount;
	// }

	// public function libraryWidget($bgcolor='default')
	// {
	// 	$counter = self::countLibrary();
	// 	return '<div class="col-md-4 col-sm-12 ">
	// 			<div class="small-box '.$bgcolor.'">
	// 				<div class="inner">
	// 					<h3>'.$counter.'<i class="fa fa-book"></i></h3>
	// 					<p><h5>TOTAL&nbsp;LIBRATY </h5> </p>
	// 				</div>
	// 				<div class="icon"><i class="fa fa-book"></i>
	// 				</div>
	// 				<a href="#" class="small-box-footer"><i class="fa fa-download"></i></a>
	// 			</div>
	// 			</div>';
	// }


	public static function menu(){
		// if(in_array(self::CAN_SEND_SMS,$_SESSION['user_session_role_capabilities'])):
			$menu = '<li class="xn-openable">';
			$menu .=	'<a href="#"><span class="fa fa-book"></span> <span class="xn-text"> Library</span></a>';
			$menu .=	'<ul>';
				$menu .=	'<li>';
				$menu .=	'<a href='.APP_PATH.'library><span class="fa fa-book"></span> <span class="xn-text"> Books</span></a>';
				$menu .=	'</li>';
				if( access_check( self::CAN_CREATE )):
				$menu .=	'<li>';
				$menu .=	'<a href='.APP_PATH.'library/new><span class="fa fa-plus"></span> <span class="xn-text"> New </span></a>';
				$menu .=	'</li>';
				endif;
			$menu	.= '</ul>';
			$menu	.= '</li>';
		// endif;
		echo $menu;
	}


}
?>
