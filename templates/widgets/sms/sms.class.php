<?php
/*
	Creator: Isaac Daramola
	Framework: skulPro
	Contact: isaac@isaac.com.ng
	Script Purpose: Single and bulk sms sender
*/
class sms extends skulpro
{
	const CAN_SEND = "can_send_sms";
	const CAN_READ_HISTORY = "can_read_sms_history";

	protected 	$apikey;
	protected 	$message;
	protected 	$recipients;
	protected 	$sender ;
	public 			$module_name = "sms";
	protected 	$sms_route;
	private			$response;
	private			$count;

	public function __construct()
	{
		parent::__construct();
		extract( $_REQUEST );
		$this->apikey 		= isset( $apikey ) ? urlencode( $apikey ) : "" ;
		$this->sender 		= isset( $sender ) ? urlencode( $sender ) : "" ;
		$this->recipients = isset( $recipients ) ? urlencode( implode(',' , array_unique( $recipients ) ) ) : "" ;
		$this->message 		= isset(  $message ) ? urlencode( $message ) : "" ;
		$this->count			= isset( $recipients ) ? count( array_unique( $recipients ) ) : "";
	}

	public function init()
	{
		 // self::composer();
	}


	public function total_sent(){
		$result = self::get_history();
		return $result->num_rows;
	}

	public function get_history( array $parameters = null ){
		$where = get_parameters( $parameters );
		return $this->db->get_full_data( 'sms_history' , $where );

	}

	public function history_data(){
		$query = parent::get_user_full_data('sms_history');
		return $query;
	}

	public function history($title=null,$color=null){
		if(self::creatorAccess()):
			$template = TEMPLATES_PATH.'sms'.DS.'history.phtml';
			if(file_exists($template)):

				$panel_body = $template;
				$panel_title = "SMS History";
				$data;
				$sym_template = TEMPLATES_PATH.'html'.DS.'panel.phtml';
				require $sym_template;

			endif;
		else:
			parent::access_error_message();
		endif;
	}

	public function get_sender(){
		$this->sender = $_SESSION['user_session']->id;
		return $this->sender;
	}

	public function get_apikey(){
		$this->apikey = get_setting("sms_api");
		return $this->apikey;
	}

	final static function get_route(){
		$this->sms_route = get_setting("sms_route");
		return $this->sms_route;
	}


	public function get_balance(){
		$url = 'http://sms.caretel.net/api/v1/http.php?api_key='.self::get_apikey().'&balance=true';
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_HEADER, 0);
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		execution_time(60);
		$response = curl_exec ($ch);
		if($response == false):
			return "0.00";
		elseif($response == 2912 || $response == "-2912"):
			return "0.00";
		else:
			return "$response";
		endif;
		curl_close ($ch);
	}

	public function send_sms()
	{
		if( access_check( self::CAN_SEND ) ):

			if( strlen( $this->sender ) > 11 ):
				parent::error_message("Your Sender Name character lenght should not be greater than 11.");
				return;
			endif;

			if(!empty($this->apikey) || !empty($this->recipients) || !empty($this->message)):
				$url= "https://caretelnet.swiftnetsms.com/api/v1/http.php?api_key=$this->apikey&recipient=$this->recipients&message=$this->message&sender=$this->sender&route=2";
				$ch = curl_init();
				// echo $url ;
				// return;
				curl_setopt ($ch, CURLOPT_URL, $url);
				curl_setopt ($ch, CURLOPT_HEADER, 0);
				curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$this->response = $response = curl_exec ($ch);
				curl_close ($ch);
				$reg_date = date("d-m-Y");

				if(strstr($response,'OK')):
					$sms_info = explode( ' ' , $this->response );
					self::success_message( "SMS sent to (" . urldecode( $this->recipients ) .") successfully . Total sms unit used is ($sms_info[1])");

					$form_data 	= array(
						'message'		=> urldecode( $this->message ) ,
						'sender'		=> urldecode( $this->sender ),
						'recipient' => urldecode( $this->recipients ),
						'response'	=> $sms_info[1] ,
						'status'		=> "successful",
						'user_id'		=> get_user()['id'],
					);
					$this->db->insert_data( 'sms_history' , $form_data );

				else:
					$form_data 	= array(
						'message'		=> urldecode( $this->message ) ,
						'sender'		=> urldecode( $this->sender ),
						'recipient' => urldecode( $this->recipients ),
						'response'	=> $this->response ,
						'status'		=> "failed",
						'user_id'		=> get_user()['id'],
					);

					$this->db->insert_data( 'sms_history' , $form_data );
					self::error_message( $this->response );

				endif;
			else:
				// throw empty variables error message
				parent::error_message('Sorry! Check your <code>SMS Route</code> or <code>Recipientt</code> or <code>Message Body</code>');
			endif;

		else:
			// throw access error message
			parent::error_message("Sorry! You do not have the right to send sms.");
		endif;
	}



	public function widgets (){
		$info['color']	 	= "primary";
		$info['icon'] 		= "mobile-phone";
		$info['value'] 		= self::get_history( array( 'user_id' => get_user()['id'] , 'status' => 'successful' ) )[0]  ;
		$info['title'] 		= "SMS Sent" ;
		$info['subtitle'] = "Total SMS Sent" ;
		var_export( $info['value'] );
		return;
		if ( access_check( self::CAN_SEND )  || user_role() == 'admin' ) {
			echo widget( $info );
		}
	}


	public function sent_widget(){
		self::widget($color="info",$item_position="left",$item_icon="envelope",$title="Sent",$subtitle="Total SMS Sent",$int_class="total_sms_sent");
	}

	public function balance_widget(){
		self::widget($color="danger",$item_position="left",$item_icon="mobile",$title="Balance",$subtitle="SMS Balance",$int_class="sms_balance");

	}

	public function composer()
	{
		get_header("Send SMS");
			if( isset( $_POST['btn_send_sms'] ) ):
				self::send_sms();
			endif;


		if( access_check( self::CAN_SEND ) ):
			$panel_title	= " Sende SMS";
			$form_data['sender_name'] = get_user()['user_first_name'];
			$form_data['api'] = get_user()['user_sms_api'];
			$panel_body 	= __DIR__ . DS . "composer_form.phtml";
			require PANEL_PATH ;
			include_js( JS_PATH . "classes/sms.class.js");
		else:
			parent::access_error_message("You do not have the access to use the sms composer compnent");
		endif;
		get_footer();
	}

	public static function menu()
	{
		$menu = '';
		if( access_check( self::CAN_SEND )):
			$menu 	.= '<li>';
			$menu 	.=	'<a href="#"><span class="fa fa-desktop"></span> <span class="xn-text"> SMS</span></a>';
			$menu 	.=	'<ul>';
			$menu 	.=	'<li>';
			$menu 	.=	'<a href='.APP_PATH.'sms/compose><span class="fa fa-plus"></span> <span class="xn-text">Send SMS</span></a>';
			$menu 	.=	'</li>';
			$menu		.= '</ul>';
			$menu		.= '</li>';
		endif;
		echo $menu;
	}
}
?>
