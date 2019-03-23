<?php
 class annex extends skulpro
 {
    const CAN_CREATE = "can_create_annex";
  	const CAN_EDIT = "can_edit_annex";
  	const CAN_DELETE = "can_delete_annex";
    const MODULE_NAME = "Annex";
    private $t_name = "annexes";

    private $name;
    private $phone_number;
    private $email_address;
    private $website;
    private $address;
    private $map;
    private $hoa;
    private $ahoa;
    private $bursar;
    private $receptionist;
    private $token;
    public $db;


    public function __construct()
    {
      parent::__construct();

      $this->name = isset( $_REQUEST['name'] ) ? addslashes( $_REQUEST['name'] ) : "";
      $this->phone_number = isset( $_REQUEST['phone_number'] ) ? addslashes( $_REQUEST['phone_number'] ) : "";
      $this->email_address = isset( $_REQUEST['email_address'] ) ? addslashes( $_REQUEST['email_address'] ) : "";
      $this->website = isset( $_REQUEST['website'] ) ? addslashes( $_REQUEST['website'] ) : "";
      $this->address = isset( $_REQUEST['address'] ) ? addslashes( $_REQUEST['address'] ) : "";
      $this->map = isset( $_REQUEST['map'] ) ? addslashes( $_REQUEST['map'] ) : "";
      $this->hoa = isset( $_REQUEST['hoa'] ) ? addslashes( $_REQUEST['hoa'] ) : "";
      $this->ahoa = isset( $_REQUEST['ahoa'] ) ? addslashes( $_REQUEST['ahoa'] ) : "";
      $this->bursar = isset($_REQUEST['bursar']) ? addslashes($_REQUEST['bursar']) : "";
      $this->receptionist = isset( $_REQUEST['receptionist'] ) ? addslashes( $_REQUEST['receptionist'] ) : "";
      $this->token = isset( $_REQUEST['token'] ) ? addslashes( $_REQUEST['token'] ) : "";
    }

    static function module_path(){
      return basename(__DIR__);
    }



    ### SCHOOL ANNEX CRUD STARTS
	public  function insert_data()
  {
    if( access_check( self::CAN_CREATE ) ):
      if( isset($_POST['btn-newannex'])):
        $query =  $this->db->query( "SELECT* FROM annexes WHERE name='$this->name' LIMIT 1" );
        $count = $query->num_rows;

        if($count === 1  || $count > 0):
          parent::errpr_message( "{$this->name} annex is already in the database" );
          return false;
        endif;

        $form_data = array(
          'name' => $this->name,
          'address' => $this->address,
          'phone_number' => $this->phone_number,
          'email_address' => $this->email_address,
          'website' => $this->website,
          'hoa' => $this->hoa,
          'ahoa' => $this->ahoa,
          'bursar' => $this->bursar,
          'receptionist' => $this->receptionist,
          'map' => $this->map
        );
        $query = $db->insert_data( "annexes", $form_data, true );
        if( $query['status']  == true ):
          parent::success_message( "A new annex created successfully" );
        else:
          parent::error_message( "A failed to create annex" );
        endif;
      endif;
      $panel_title = "Annex Creator";
      $name = "";
      $address = "";
      $phone_number = "";
      $email_address = "";
      $map = "";
      $website = "";
      $hoa = "";
      $ahoa = "";
      $bursar = "";
      $receptionist = "";
      $button_name = "btn-newannex";
      $button_icon = "plus";
      $button_value = "Save";
      $panel_body =  __DIR__ . DS . "form.phtml";
      require BACKEND_PATH . "html/panel.phtml";
    else:
        self::access_error_message();
    endif;
  }

    public static function history(){
      get_header("Annexes");
      // self::widget();
      $panel_title = get_setting('site_name')." Annexes";
      $panel_body =  __DIR__ . "/table.phtml";
      require TEMPLATES_PATH."backend/html/panel.phtml";
      get_footer();
    }

    public static function view(){
      get_header("Annexes");
      // self::widget();
      $panel_title = get_setting('site_name')." Annexes";
      $panel_body =  __DIR__ . "/view.phtml";
      require TEMPLATES_PATH."backend/html/panel.phtml";
      get_footer();
    }

    public function delete_annex($code,$response=null){
        if(self::deleteAccess()):
            $query = $this->db->query("DELETE FROM $this->table_name WHERE id='$code' ");
            if($response == true):
                if($query === true):
                    echo"<div class='alert alert-danger'><i class='fa fa-trash'></i> {$name} annex deleted successfully </div>";
                else:
                    echo"<div class='alert alert-danger'><i class='fa fa-times'></i> {$name} annex failed to be deleted </div>";
                endif;
                // header("Location: annex-manager");
            endif;
        else:
            self::access_error_message();
        endif;

    }

    public static function creator()
    {
      get_header("Annex Creator");
      if(isset($_POST['btn-new'])):
        self::add();
      endif;
      $panel_title = "Annex Creator";
      $name = "";
      $address = "";
      $phone_number = "";
      $email_address = "";
      $map = "";
      $website = "";
      $hoa = "";
      $ahoa = "";
      $bursar = "";
      $receptionist = "";
      $button_name = "btn-new";
      $button_icon = "plus";
      $button_value = "Save";
      require TEMPLATES_PATH."widgets/annex/form.phtml";
      get_footer();
    }

    public function full_data()
    {
      $database = new sql_datacrud;
      return $this->db->get_full_data("annexes");
    }

    public function get_data( array $parameters )
    {

      // $code = addslashes( array $parameters );
      $where = get_parameters( $parameters );
      return $this->db->get_data("annexes", $where );
    }

    public function update_annex($name,$address,$phone_number,$email_address,$website,$head_of_annex,$assist_head_of_annex,$map,$id,$response=null)
    {
        if(self::editorAccess()):
            $query = $this->db->query("UPDATE $this->table_name SET name='$name',address='$address',phone_number='$phone_number',email_address='$email_address',website='$website',head_of_annex='$head_of_annex',assist_head_of_annex='$assist_head_of_annex',map='$map' WHERE id='$id' ");
            if($response == true):
                if($query === true):
                    echo"<div class='alert alert-success'><i class='fa fa-check'></i> {$name} annex created successfully </div>";
                else:
                    echo"<div class='alert alert-danger'><i class='fa fa-times'></i> {$name} annex failed to be created </div>";
                endif;
            endif;
        else:
            self::access_error_message();
        endif;
    }

    public static function menu(){
		$menu = '<li class="xn-openable">';
			// if( access_check( self::CAN_CREATE ) && access_check( self::CAN_EDIT ) && access_check( self::CAN_DELETE ) ):
				$menu .=	'<a href="#"><span class="fa fa-home"></span> <span class="xn-text">Annex</span></a>';
				$menu .=	'<ul>';
					$menu .=	'<li> <a href=' . APP_PATH . self::module_path() . DS . 'new'.'><span class="fa fa-plus"></span> <span class="xn-text">New </span></a></li>';
					$menu .=	'<li> <a href=' . APP_PATH . self::module_path() . DS . 'list'.'><span class="fa fa-list"></span> <span class="xn-text">List</span></a></li>';
				$menu	.= '</ul>';
			// endif;

		$menu	.= '</li>';
		echo $menu;
	}


 }
