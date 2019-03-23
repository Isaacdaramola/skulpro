<?php
  /**
   *
   */
  class Test extends Skulpro
  {
    protected $test_name;
    protected $academic_class;
    protected $academic_level;
    protected $academic_subject;
    protected $year;
    protected $duration;
    protected $total_question;
    protected $token;
    protected $test;
    protected $question;
  	protected $option1;
  	protected $option2;
  	protected $option3;
  	protected $option4;
  	protected $answer;
    protected $my_answer;
    public $db;
    public $module_name = "test";
    protected $t_name = "skulpro_test";

    // module access and security
  	const CAN_CREATE = "can_create_test";
  	const CAN_EDIT = "can_edit_test";
  	const CAN_DELETE = "can_delete_test";
  	const CAN_DO = "can_do_test";
    const CAN_VIEW_RESULT = "can_view_test_result";
    const CAN_VIEW_ALL_RESULTS = "can_view_all_test_results";
    const CAN_ADD_QUESTION = "can_add_test_question";


    function __construct( $token =  null )
    {
      parent::__construct();
      $this->test_name = isset( $_REQUEST['test_name'] ) ? addslashes( $_REQUEST['test_name']): "";
      $this->academic_class = isset( $_REQUEST['academic_class'] ) ? addslashes( $_REQUEST['academic_class']): "";
      $this->academic_level = isset( $_REQUEST['academic_level'] ) ? addslashes( $_REQUEST['academic_level']): "";
      $this->academic_subject = isset( $_REQUEST['academic_subject'] ) ? addslashes( $_REQUEST['academic_subject']): "";
      $this->duration = isset( $_REQUEST['duration'] ) ? addslashes( $_REQUEST['duration']): "";
      $this->test = isset( $_REQUEST['test'] ) ? addslashes( $_REQUEST['test'] ) : "";


      $this->question = isset( $_REQUEST['question'] ) ? addslashes( $_REQUEST['question'] ) : "";
  		$this->option1 = isset( $_REQUEST['option1'] ) ? addslashes( $_REQUEST['option1'] ) : "";
  		$this->option2 = isset( $_REQUEST['option2'] ) ? addslashes( $_REQUEST['option2'] ) : "";
  		$this->option3 = isset( $_REQUEST['option3'] ) ? addslashes( $_REQUEST['option3'] ) : "";
  		$this->option4 = isset( $_REQUEST['option4'] ) ? addslashes( $_REQUEST['option4'] ) : "";
  		$this->answer = isset( $_REQUEST['answer'] ) ? addslashes( $_REQUEST['answer'] ) : "";
      $this->my_answer = isset( $_REQUEST['my_answer'] ) ? addslashes( $_REQUEST['my_answer']): "";

      $this->token = isset( $_REQUEST['token']) ? addslashes($_REQUEST['token']) : "";

    }

    public function add_test(){
      extract($_REQUEST);
      $this->test_name = isset($test_name) ? addslashes($test_name) : "";
      $this->academic_level = isset($academic_level) ? addslashes($academic_level) : "";
      $this->academic_subject = isset($academic_subject) ? addslashes($academic_subject) : "";
      $this->year = isset($year) ? addslashes($year) : "";
      $this->duration = isset($duration) ? addslashes($duration) : "";

      // return error if test_name & duration is empty;
      if(empty($this->test_name) || strlen( $this->test_name ) < 3 ){
        return parent::error_message("Test name should be greater than 3 letters" );
      }

      if(empty($this->duration) ){
        return parent::error_message("Test duration can not be empty " );
      }

      $form_data = array(
        "academic_level" =>  $this->academic_level,
        "academic_subject" => $this->academic_subject,
        "test_name" => strReplace(rand( 08765 , 12345 ) . "_" . $this->test_name),
        // "total_question" => ,
        "year" =>   $this->year,
        // "type" => ,
        "duration" => $this->duration,
      );

      if( access_check(self::CAN_CREATE) ):
        $query = $this->db->insert_data( $this->t_name , $form_data  );
        if( $query['status'] ):
          echo self::success_message("Test created successfully.");
        endif;
      else:
        parent::access_error_message();
      endif;
    }

    static function module_path(){
      return basename( __DIR__ );
    }

    public function delete_data(){
      $where = get_parameters( array( 'token' => $this->token ) );
      $query = $this->db->delete_data( $this->t_name , $where , true );
      header("Location: " . APP_PATH . self::module_path() . DS . 'list' );
    }

    public function question_check( array $parameters ){
      $where = get_parameters( $parameters );
      $query = $this->db->get_data( "skulpro_test_questions" , $where  );
      if( $query ):
        return true;
      else:
        return false;
      endif;
    }

    public function add_question_to_test( $test = null , array $questions = null ){
      foreach ( $questions as $key => $t) {
        // $ques = get_question( $t );
        // var_dump($ques);
        $form_data = array(
          "question" => get_question( array( 'token' => $t ) )['question'],
          "option1" => get_question( array( 'token' => $t ) )['option1'],
          "option2" => get_question( array( 'token' => $t ) )['option2'],
          "option3" => get_question( array( 'token' => $t ) )['option3'],
          "option4" => get_question( array( 'token' => $t ) )['option4'],
          "answer" => get_question( array( 'token' => $t ) )['answer'],
          "question_id" => get_question(  array( 'token' => $t ) )['id'],
          "test_name" => self::get_test( array( 'token' => $test ) )['test_name'],
        );
        // echo $test_name;
        if( self::question_check( array( 'question' => get_question(  array( 'token' => $t ) )['question'] , 'test_name' => $this->test_name ) ) ):
          parent::error_message("(<i><strong>"  . get_question( array( 'token' => $t ) )['question']  . "</i></strong>)" . " is already in the database" );
          continue;
        endif;
        $this->db->insert_data( "skulpro_test_questions" , $form_data , true , true );
      }

    }


    function count_test_question($test_name){
      $where = "WHERE test_name='{$test_name}' ";
      return count( $this->db->get_full_data("skulpro_test_questions" , $where ));
    }

    function get_test_questions( array $parameters ){
      $where = get_parameters( $parameters );
      // echo $where;
      return $this->db->get_full_data("skulpro_test_questions" , $where );
    }

    function get_tests_question_id( $test_name ){
      $where = "WHERE test_name='{$test_name}' ";
      // echo $where;
      $query = $this->db->query(" SELECT* FROM skulpro_test_questions $where" );
      if($query->num_rows > 0):
        while( $result = $query->fetch_assoc() ):
          $data[]  = $result['question_id'];
        endwhile;
      else:
        $data = array();
      endif;
      return $data;
    }


    public function get_test( array $parameters ){
      $where = get_parameters( $parameters );
      return $this->db->get_data( $this->t_name , $where );
    }

    public function log_test( array $parameters ){
      // check if the this test has been header-unlogged
      $where = get_parameters(
        array(
          'user' => $parameters['user'],
          'session' => $parameters['session'],
          'test_name' => $parameters['test_name'],
        )
      );
      // echo $where;
      $check = $this->db->get_data( 'skulpro_test_history' , $where );

      if( count( $check ) > 0 ){
        return false ;
      }

      // log this test user test
      $form_data = array(
        'user' => $_SESSION['user_session']->token,
        'session' => session_id(),
        'test_name' => $parameters['test_name'],
        'creation_time' => CREATION_TIME,
        'duration' => $parameters['duration'] * 60,

      );
      $this->db->insert_data( 'skulpro_test_history' , $form_data );
    }

    public function copy_test( array $parameters  ){
      $test_info = self::get_test_questions( array('test_name' => $parameters['test_name'] ) );
      // var_export( $test_info );



      //  check first the db that the user doesn't have the copy of these question_source
      if(  count ( self::get_user_test_questions( $parameters ) ) < 1 ){
        if( $test_info && is_array( $test_info)){
          foreach ($test_info as $key => $v ) {
            $form_data = array(
              'user' => $_SESSION['user_session']->token,
              'session' => session_id(),
              'test_name' => $v['test_name'],
              'question' => $v['question'],
              'option1' => $v['option1'],
              'option2' => $v['option2'],
              'option3' => $v['option3'],
              'option4' => $v['option4'],
              'answer' => $v['answer'],
              'instruction' => $v['instruction'],
              // 'my_answer' => $this->my_answer,
            );
            //  insert data into the db
            $this->db->insert_data( "skulpro_test_answers" , $form_data );
            // code...
          }
        }
      }
    }

    public function get_my_test_history( array $parameters ){
      $where = get_parameters( $parameters );
      $data = $this->db->get_full_data( "skulpro_test_history" , $where );
      $panel_title = "My Test History";
      $panel_body = __DIR__ . DS . "tables/my_results_table.phtml";

      require BACKEND_PATH . "html/panel.phtml";

    }

    public function get_this_test_result( array $parameters ){
      $where = get_parameters( $parameters );
      $data =  $this->db->get_full_data( "skulpro_test_answers" , $where );
      $test_info = self::get_test( array('test_name' => $parameters['test_name']) );
      $panel_title = " ";
      $panel_body = __DIR__ . DS . "tables/my_result_table.phtml";

      require BACKEND_PATH . "html/panel.phtml";

    }

    public function take_test( array $parameters ){
      // var_export($parameters);

      $form_data = array(
        'my_answer' => $parameters['my_answer'],
        'time_left' => $parameters['time_left'],
      );
      $where =  get_parameters( array( 'test_name' => $parameters['test_name'] , 'session' => session_id(), 'user' => $_SESSION['user_session']->token ) );
      $this->db->update_data( "skulpro_test_history" , $form_data['time_left'] , $where );
      $where = get_parameters( array( 'test_name' => $parameters['test_name'] , 'id' => $parameters['question'] , 'session' => session_id(), 'user' => $_SESSION['user_session']->token ));
      $this->db->update_data( "skulpro_test_answers" , $form_data , $where );
      // code...
    }

    public function get_my_test( array $parameters ){
      $where = get_parameters( $parameters);
      return $this->db->get_data( "skulpro_test_history"  , $where );
    }

    public function get_my_tests( array $parameters ){
      $where = get_parameters( $parameters);
      return $this->db->get_full_data( "skulpro_test_history"  , $where );
    }

    public function update_my_test( array $parameters ){
      // log this test user test
      $form_data = array(
        'time_left' =>  $parameters['time_left'],
      );
      unset( $parameters['time_left']);
      unset( $parameters['what_to_do']);
      unset( $parameters['user']);
      // var_export($form_data);

      $where = get_parameters( $parameters );
      echo $where;
      $this->db->update_data( 'skulpro_test_history' , $form_data , $where );
    }


    public function get_user_test_questions( array $parameters ){
      $where = get_parameters( $parameters );
      return $this->db->get_full_data( "skulpro_test_answers" , $where );
    }

    public function get_tests( array $parameters ){
      $where = get_parameters( $parameters );
      return $this->db->get_full_data( $this->t_name , $where );

    }

    public static function menu(){
      // if(in_array(self::CAN_SEND_SMS,$_SESSION['user_session_role_capabilities'])):
        $menu = '<li class="xn-openable">';
        $menu .=	'<a href="#"><span class="fa fa-cogs"></span> <span class="xn-text"> Test/Exam</span></a>';
        $menu .=	'<ul>';
          if( access_check( self::CAN_CREATE ) ):
            $menu .=	'<li>';
              $menu .=	'<a href='.APP_PATH. self::module_path() . DS .'new><span class="fa fa-plus"></span> <span class="xn-text">New  Test</span></a>';
            $menu .=	'</li>';
          endif;
          $menu .=	'<li>';
            $menu .=	'<a href='.APP_PATH.'test><span class="fa fa-list"></span> <span class="xn-text"> List </span></a>';
          $menu .=	'</li>';

          $menu .=	'<li>';
            $menu .=	'<a href='.APP_PATH.'test/my-results><span class="fa fa-list"></span> <span class="xn-text"> Results</span></a>';
          $menu .=	'</li>';
        $menu	.= '</ul>';
        $menu	.= '</li>';
      // endif;
      echo $menu;
    }
  }









?>
