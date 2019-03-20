<?php
  /**
   *
   */
  class skulpro
  {
    const APP_VERSION = 0.5;
    const APP_NAME = "skulPRO";
    public $parameters;
    public $where;
    public $db;
    private $token;
    private $table_name;



    function __construct()
    {
      $this->db = new sql_datacrud;
    }

    public static function error_message( $message ){
      echo'<div class="alert alert-danger message col-md-12" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><i class="fa fa-bell"></i><i> ' . $message . '</i> </div>';
      return false;
    }

    public  static  function warning_message( $message ){
      echo'<div class="alert alert-warning col-md-12" role="alert">
      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
      <i class="fa fa-bell"></i>  ' . $message . ' </i> </div>';
      false;
    }


    public static  function success_message( $message ){
      echo'<div class="alert alert-success col-md-12" role="alert">
      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
      <i class="fa fa-bell"></i> <i>  ' . $message . ' </i> </div>';
    }

    public static function __msg( $query , $report_error = null){
      if( isset( $report_error ) ):
        $error = addslashes( $report_error ) ;
      else:
        $error	=	"";
      endif;
      switch ($query) {
        case 'true':
          self::success_message( "Action carried out successfully" );
          return true;
          break;

        default:
          self::error_message( "Sorry! action failed to be carried out by system. " . '<b>' . $error . '</b>' );
          return false;
          break;
      }

    }

    public static function access_error_message(){
        self::error_message(" Sorry, you do not have access right to do this. If you think if you think this is an error, contact your system administrator. " );
    }

    // public function is_exists( array $parameters = null , $table = null ){
    //   $where = get_parameters( $parameters );
    //   $this->table_name = $table;
    //   $result = $this->db->get_data( $this->table_name , $where );
    //   if ( count($result) > 0 ) {
    //     return true;
    //   }
    //   else {
    //     return false;
    //   }
    // }


  }


?>
