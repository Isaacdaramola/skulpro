<?php
  /**
   *
   */


  class modules extends skulpro
  {
    protected $name;
    protected $description;
    protected $t_name = 'modules';
    protected $token;
    public $db;

    function __construct()
    {
      parent::__construct();
      $this->name =  isset( $_REQUEST['name'] ) ? strReplace( $_REQUEST['name'] ) : "";
      $this->description =  isset( $_REQUEST['description'] ) ? addslashes( $_REQUEST['description'] )  : "";
      $this->token =  isset( $_REQUEST['token'] ) ? addslashes( $_REQUEST['token'] )  : "";
    }

    public function is_exists( array $parameters = null ){
      $where = get_parameters( $parameters );
      $result = $this->db->get_data( $this->t_name , $where );
      if ( count( $result ) > 0 ) {
        return true;
      }
      else {
        return false;
      }
    }

    public function insert_data(){
      if( self::is_exists( array('name' => $this->name) ) ) {
        echo self::error_message( $this->name ." is already in the database.");
        return false;
      }
      $form_data = array( "name" => $this->name , "description" => $this->description );
      // var_export( $form_data );
      return $this->db->insert_data( $this->t_name , $form_data , true , true );
    }

    public function get_modules_name(){
      // var_export($this->db->database->server_version);
      $result = $this->db->get_full_data( $this->t_name );
      foreach ($result as $key => $v) {
        $name[] = $v['name'];
      }
      return $name;
    }

    public function get_modules(){
      return $this->db->get_full_data( $this->t_name );

    }
  }



?>
