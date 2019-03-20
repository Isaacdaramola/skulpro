<?php

/**
 *
 */
class leave extends skulpro
{
  protected $annex;
  protected $date;
  protected $year;
  protected $term;
  protected $staff;
  protected $num_of_days_desired;
  protected $desired_days = array();
  protected $purpose = array();
  protected $days_taken;

  // Year Tutor's Recommendation
  protected $year_tutor_recommendation;

  // HOD's Recommendation
  protected $hod_recommendation;
  protected $approved_by;
  protected $approved_on;
  protected $t_name = "leaves";
  public $database;


  public function __construct()
  {
    $database = parent::__construct();
    return $this->database = $database;
  }

  public static function add()
  {
    extract($_POST);
    $form_data = array(
      "annex" => $annex,
      // "date" => $date,
      "year" => $year,
      "term" => $term,
      "staff" => $_SESSION['user_session']->id,
      "number_of_days_desired" => $num_of_days_desired,
      "desired_days" => serialize($desired_days),
      "purpose" => serialize($purpose),
      "status" => "pending"
    );
    // var_export($form_data);
    $database = new sql_datacrud;
    $query = $database->insert_data("leaves",$form_data,true);
  }

  public static function full_data($format=null)
  {
    $database = new sql_datacrud;
    $user = $_SESSION['user_session']->id;
    $where ="WHERE staff='$user' ";
    return $database->get_full_data("leaves",$where,$format);

  }

  public function get_data()
  {
    extract($_GET);
    if(isset($token)):
      $where = "WHERE token='$token' ";
    else:
      $where = "";
    endif;
    $database = new sql_datacrud;
    return $database->get_data("leaves",$where);

  }


  public static function creator(){
    get_header("Request For Leave");
    get_sidebar("New Leave");
    if(isset($_POST['btn-new'])):
      self::add();
    endif;
    $panel_title = "New Leave Form";
    $panel_body = TEMPLATES_PATH."widgets/leave/form.phtml";
    require TEMPLATES_PATH."html/panel.phtml";
    get_footer();

  }

  public static function view(){
    get_header("Leave Info");
    get_sidebar("Leave Info");

    $panel_title = "Leave Info";
    $panel_body = TEMPLATES_PATH."widgets/leave/view.phtml";
    require TEMPLATES_PATH."html/panel.phtml";
    get_footer();

  }


  public static function history(){
    get_header("Leave History");
    get_sidebar("Leave History");
    self::widget();
    $panel_title = "Leave History";
    $panel_body =  TEMPLATES_PATH."widgets/leave/table.phtml";
    require TEMPLATES_PATH."html/panel.phtml";
    get_footer();
  }

  public static function total_taken(){

  }

  public static function total_appl(){
    $database = new sql_datacrud;
    $user = $_SESSION['user_session']->id;
    $where ="WHERE staff='$user' ";
    $result = $database->get_full_data("leaves",$where);
    return $result['row_count'];
  }


    public static function widget(){
      $database = new sql_datacrud;
      $user = $_SESSION['user_session']->id;
      $where ="WHERE staff='$user' ";
      $int_value = $database->row_count("leaves",$where);
      $color = "info";
      $title = "Total Request";
      $subtitle = "Total Leave Request";
      $item_icon = "desktop";
      require TEMPLATES_PATH."html/widget.phtml";
    }





  public static function menu(){
  $menu = '<li>';
  $menu .=	'<a href="#"><span class="fa fa-desktop"></span> <span class="xn-text"> Leave</span></a>';
    $menu .=	'<ul>';
      $menu .=	'<li>';
      $menu .=	'<a href='.APP_PATH.'leave/new><span class="fa fa-plus"></span> <span class="xn-text">Take New</span></a>';
      $menu .=	'</li>';
      $menu .=	'<li>';
      $menu	.= '<a href='.APP_PATH.'leave/history><span class="fa fa-list"></span> <span class="xn-text">Leave History</span></a>';
      $menu	.= '</li>';
    $menu	.= '</ul>';
  $menu	.= '</li>';
  echo $menu;
}






}


?>
