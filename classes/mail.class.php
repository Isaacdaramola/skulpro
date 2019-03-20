<?php
// include phpmailer class
  // creates object

  /**
   *
   */
  class mail
  {

    function __construct()
    {
      require_once 'mailer/class.phpmailer.php';
    }

    public function send($to,$subject,$message){
      $mail = new PHPMailer();
      $mail->CharSet =  "utf-8";
      $mail->IsSMTP();
      $mail->SMTPAuth = true;
      $mail->Username = "demo@acecollege.com.ng";
      $mail->Password = "74123@Demo";
      $mail->SMTPSecure = "";
      $mail->Host = "mail.acecollege.com.ng";
      $mail->Port = "587";

      $mail->setFrom("isaac@isaac.com.ng");
      $mail->AddAddress("kayzenk@gmail.com");

      $mail->Subject  =  $subject;
      $mail->IsHTML(true);
      $mail->Body = $message;

      if($mail->Send())
     {
        echo "Message was Successfully Send :)";
     }
     else
     {
        echo "Mail Error - >".$mail->ErrorInfo;
     }

    }

    public static function sendMail( array $mailInfo ){
      var_export( $mailInfo );
//		var_export( empty( $mailInfo['body'] ) );
		if( empty( $mailInfo['body'] ) )
		{
			return false;
		//	throw new Ayoola_Abstract_Exception( 'E-mail cannot be sent without a body' );
		}
		if( empty( $mailInfo['to'] ) )
		{
			return false;
		//	throw new Ayoola_Abstract_Exception( 'E-mail destination was not specified' );
		}
		if( empty( $mailInfo['from'] ) )
		{
		//	$mailInfo['from'] = 'no-reply@' . Ayoola_Page::getDefaultDomain();
			$mailInfo['from'] = '"' . htmlspecialchars( get_setting("site_name") ? : $_SERVER['HTTP_HOST'] . '" <no-reply@' . $_SERVER['HTTP_HOST']  . '>' . "";
		}

//		var_export( htmlentities( $mailInfo['from'] ) );
	//	return false;
		if( empty( $mailInfo['subject'] ) )
		{
			$mailInfo['subject'] = 'E-mail Notification';
		}
		$header = 'From: ' . $mailInfo['from'] . "\r\n";
	 	$header .= "Return-Path: " . @$mailInfo['return-path'] ? : $mailInfo['from'] . "\r\n";

		if( ! empty( $mailInfo['bcc'] ) )
		{
			$header .= "bcc: {$mailInfo['bcc']}\r\n";
		//	var_export( $header );
		}
		if( ! empty( $mailInfo['html'] ) || strip_tags( $mailInfo['body'] ) != $mailInfo['body'] )
		{
			if( stripos( $mailInfo['body'], '<body>' ) === false )
			{
				$mailInfo['body'] = '<body>' . $mailInfo['body'] . '</body>';
			}
			if( stripos( $mailInfo['body'], '<html>' ) === false )
			{
				// $styleFile = Ayoola_Loader::checkFile( 'documents/css/pagecarton.css' );
        $styleFile = "";
				$mailInfo['body'] = '
										<html>
											<head>
												<style>
													' . file_get_contents( $styleFile ) . '
												</style>
											</head>
											' . $mailInfo['body'] . '
										</html>';
			}
			$header .= "MIME-Version: 1.0\r\n";
			$header .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            // $mailInfo['body'] = Ayoola_Page_Editor_Text::addDomainToAbsoluteLinks( $mailInfo['body'] );
	//		var_export( $styleFile );
	//		var_export( htmlentities( $mailInfo['body'] ) );
	//		return false;

		}
		// $sent = mail( $mailInfo['to'], $mailInfo['subject'], $mailInfo['body'], $header );
		exit( var_export( $mailInfo ) );
	//	if( ! $sent ){ throw new Ayoola_Abstract_Exception( 'Error encountered while sending e-mail' ); }
		return true;
    }


  }





   //
   //  if($mail->Send())
   //  {
   //
   //   $msg = "<div class='alert alert-success'>
   //     Hi,<br /> ".$first_name." your seat has been successfully reserved with ".$email."  :) </br> This Page Should Reload In 10 Seconds.....
   //     </div>";
   //
   //  }
   // }
   // catch(phpmailerException $ex)
   // {
   //  $msg = "<div class='alert alert-warning'>".$ex->errorMessage()."</div>";
   // }
   // echo $msg;
   // echo '<meta http-equiv="refresh" content="10; url=index.php" />';


?>
