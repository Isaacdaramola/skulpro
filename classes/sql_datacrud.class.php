<?php

/**
 *
 */
class Sql_datacrud extends Skulpro
{


    private $database;
    private $table;
    private $token;
    private $creation_time;
    private $modified_time;
    private $limit;
    private $order;

    private $extra_instruct;
    public $db;
    function __construct()
    {
      $database = new Database();
      $this->db = $database->dbConnection();
      $this->token = md5( time() );
      $this->creation_time = time();
      $this->modified_time = time();
    }


    function insert_data( $table_name , $form_data , $response = null , $show_error = null ) {
  		// retrieve the keys of the array (column titles)
      $form_data['token'] = $this->token ;
      $form_data['creation_time'] = $this->creation_time ;

  		$fields = array_keys( $form_data );
  		// build the query
  		$sql = "INSERT INTO ".$table_name." (`".implode('`,`', $fields)."`) VALUES('".implode("','", $form_data)."')";
  		$query = $this->db->query( $sql );
      if( $query == true ):
        $last_id = $this->db->insert_id;
        if($response == true):
            parent::__msg( $query );
        endif;
        return array( "last_id" => $last_id , "status" => true , "token" =>   $form_data['token'] );

      else:
        if( $show_error == true  ):
          parent::__msg( $query , $this->db->error);
        endif;
  		  return false;
      endif;
  	}

    public function query( $sql ){
      return $query = $this->db->query( $sql );
    }


    public function build_parameters( array $parameters = null , $nature = true ){
      extract($_REQUEST);
      // var_export( $parameters );
      // $where = "";
      if( count( $parameters ) > 0 ):

        foreach ( $parameters as $key => $v ) {
          if($nature):

            if( is_array($v)):
              foreach ( $v as $key => $value ) {
                $where[] = $key . ' = ' ."'{$value}' "  ;
              }
            else:
              $where[] = $key . ' = ' ."'{$v}' "  ;
            endif;

          else:
            $where[] = $key . ' != ' ."'{$v}' "  ;
          endif;


        };
        $para = str_replace( "," , " AND " , implode("," , $where ) );
        if( $para ):
          $where = "WHERE  {$para} ";
        else:
          $where = "";
        endif;
        $this->parameters = $where;
        return  $this->parameters;
      endif;
    }

    function table_exists( $table = null ){
      $result = $this->db->query( " SHOW TABLES LIKE '{$table}' " );
      return $result->num_rows;
    }


    public function delete_data( $table_name , $where_clause = '' , $response = null ) {
       // check for optional where clause
       $whereSQL = '';
       if(!empty($where_clause)):

         if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE') :
           // not found, add keyword
           $whereSQL = " WHERE ".$where_clause;
         else:
            $whereSQL = " ".trim($where_clause);
          endif;
        endif; // build the query
        $sql = "DELETE FROM ".$table_name.$whereSQL;
        $query = $this->db->query($sql);
        if($query == true):
          if( $response == true):
            parent::__msg($query);
          endif;
          return array("status" => true);
        else:
          parent::__msg($query);
    		  return false;
        endif;
    }

    public function update_data( $table_name , $form_data , $where_clause = '' , $response = null ) {
      // check for optional where clause
      // loop and build the column /

      // $sets = array();
      // foreach($form_data as $column => $value) {
      // $sets[] = "`".$column."` = '".$value."'";
      // }

      $whereSQL = '';
      if(!empty($where_clause)) {
       // check to see if the 'where' keyword exists
         if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE') {
        // not found, add key word
        $whereSQL = " WHERE " . $where_clause;
        }
        else {
        $whereSQL = " ".trim($where_clause);
        }
      }
      // start the actual SQL statement
      $sql = "UPDATE ".$table_name." SET ";
      // loop and build the column /
      $sets = array();
      foreach($form_data as $column => $value) {
      $sets[] = "`".$column."` = '".$value."'";
      }
      $sql .= implode(', ', $sets);
      // append the where statement
      $sql .= $whereSQL;
      // run and return the query result return
      $query = $this->db->query($sql);

      if( $query == true ):
        if( $response == true  ):
          return array("status" => true);
        endif;
      else:
        return false;
      endif;
    }

    public function get_data( $table_name , $where_clause = null, $data_type = null ){
      // check for optional where clause
      // echo $where_clause;
      $whereSQL = '';
      if( !empty( $where_clause )) {
       // check to see if the 'where' keyword exists
         if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE') {
        // not found, add key word
        $whereSQL = " WHERE ".$where_clause;
        }
        else {
        $whereSQL = " ".trim($where_clause);
        }
      }
      $cols = '*';
      $sql = "SELECT* FROM " . $table_name . $whereSQL;
      $query = $this->db->query($sql);
      if( $query == true):
        $data = $query->fetch_assoc();
        $total_rows = array("row_count" => $query->num_rows);
        if( $data_type == 'json' ):
          return json_encode($data);
        elseif( $data_type == 'serialize '):
          return serialize( $data );
        else:
          return $data;
        endif;
      else:
        return false;
      endif;
    }



    public function select( $table_name , array $fields = null , $order = null , $limit = null ,  $where_clause = null, $data_type = null ){
      // Prepare the fiels
      if ( $fields !== null ):
        $fields = implode(',' , $fields );
      else:
        $fields = '*';
      endif;


      // prepare the order of select
      if ( $order !== null ):
        $order = "ORDER BY $order";
      else:
        $order = '';
      endif;

      // set data limit
      if ( $limit !== null ):
        $limit = "LIMIT $limit";
      else:
        $limit = 'LIMIT 1';
      endif;

      // check for optional where clause
      $whereSQL = '';
      if( !empty( $where_clause )) {
       // check to see if the 'where' keyword exists
         if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE') {
        // not found, add key word
        $whereSQL = " WHERE ".$where_clause;
        }
        else {
        $whereSQL = " ".trim($where_clause);
        }
      }
      $cols = '*';
      $sql = "SELECT $fields FROM " . $table_name . $whereSQL . $limit ;
      $query = $this->db->query($sql);
      if( $query == true):
        $data = $query->fetch_assoc();
        $total_rows = array("row_count" => $query->num_rows);
        if( $data_type == 'json' ):
          return json_encode($data);
        elseif( $data_type == 'serialize '):
          return serialize( $data );
        else:
          return $data;
        endif;
      else:
        return false;
      endif;
    }

    // This returns a full data
    public function get_full_data( $table_name  = null , $where = null , $data_type  = null , $extra_instruct = null )
    {
      $this->t_name = addslashes( $table_name );
      $this->extra_instruct = addslashes( $extra_instruct );
      if( self::table_exists( $table_name ) ):
          $query = $this->db->query( " SELECT* FROM $this->t_name  $where  $this->extra_instruct ");
        if( $query->num_rows > 0 ):
          $total_rows = array( "row_count" => $query->num_rows );
          while($result = $query->fetch_assoc()):
            $data[]  = $result;
          endwhile;
        else:
          $data = array();
        endif;
        if( $data_type == 'json' ):
          return json_encode($data);
        elseif($data_type == 'serialize'):
          return serialize(array_merge($data,$total_rows));
        else:
          return $data;
        endif;
      else:
        return array();
      endif;
    }

    public function row_count( $table_name = null , $where = null )
    {
      $this->t_name = addslashes($table_name);
      $this->where = $where;
      $query = $this->db->query("SELECT* FROM $this->t_name $this->where ");
      if($query == true):
        return $query->num_rows;
      else:
        return false;
      endif;
    }



  // This returns a full data by current loggin user
  public function get_user_full_data( $table_name ,$owner = null , $where = null , $order = null , $limit = null ){
    if( isset( $owner )):
      $author = addslashes($owner);
    else:
      $author = isset( $_SESSION['user_session']->id ) ? $_SESSION['user_session']->id : "" ;
    endif;

    // stop running is author is not set
    if( !$author ){ return false; }

    // $set data order
    if( $order !== null):
      $this->order = addslashes( $order );
      $order = "ORDER BY {$order}";
    else:
      $order = "";
    endif;

    // $set data limit
    if( $limit !== null):
      $this->limit = addslashes( $limit );
      $limit = "LIMIT {$this->limit}";
    else:
      $limit = "";
    endif;

    $query = $this->db->query("SELECT* FROM $table_name WHERE author='$author' OR user_id='{$author}' OR user_token='{$author}'  $where  $order $limit ");

    /*
    Check if result is greater that zero. If it is, conver result to array and return the result.
    Else, return empty array.
    */
    if($query->num_rows > 0):
      while( $result = $query->fetch_assoc() ):
        $data[]  = $result;
      endwhile;
    else:
      $data = array();
    endif;
    return $data;
  }


  // set to create table
  public function create_databse_table( $table_name = null )
  {
    // return $this->db->query()
  }


}
 ?>
