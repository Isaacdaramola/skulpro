<?php
	spl_autoload_register(function ( $class )
	{
		//require  CLASSES_PATH."Database.class.php";
		### change class name to lower case ###
		//$class = strtolower($class);

		### create $class absolute path ###
		$class_file = CLASSES_PATH . $class . ".class.php";
		$class_file2 = CLASSES_PATH . $class . ".php";
		$class_file3 = CLASSES_PATH . $class . "/" . $class . ".class.php";
		// echo $class_file3;
		$backend_class_path =  TEMPLATES_PATH . "backend" . DS . $class . "/" . $class . ".class.php";
		$frontend_class_path =  TEMPLATES_PATH ."frontend" . DS . $class . "/" . $class . ".class.php";
		$widget_class_path =  TEMPLATES_PATH . "widgets" . DS . $class . "/" . $class . ".class.php";

		### check if files is in the class directory###
		if ( file_exists( $class_file ) ) :
			require $class_file;
			$class_file_boj = new $class;
			if( method_exists( $class ,  "init" ) ):
				$class_file_boj->init();
			endif;





			### check if files is in the class directory###
		elseif (file_exists( $class_file2 ) ) :
				require $class_file2;
				$class_file_boj = new $class;
				if( method_exists( $class ,  "init" ) ):
					$class_file_boj->init();
				endif;

		### check if files is in the class directory###
		elseif ( file_exists( $class_file3 ) ) :
			require $class_file3;
			// $class_file_boj = new $class3;
			// if( method_exists( $class3 ,  "init" ) ):
			// 	$class_file_boj->init();
			// endif;

		### check if files is in the widgets directory###
		elseif (file_exists( $widget_class_path ) ) :
			require $widget_class_path;

			$class_file_boj = new $class;
			if( method_exists( $class ,  "init" ) ):
				$class_file_boj->init();
			endif;

		### check if files is in the backend directory###
		elseif (file_exists( $backend_class_path ) ) :
			require $backend_class_path;

			$class_file_boj = new $class;
			if( method_exists( $class ,  "init" ) ):
				$class_file_boj->init();
			endif;

		### check if files is in the frontend directory###
		elseif (file_exists( $frontend_class_path ) ) :
			require $frontend_class_path;
		else :
			Echo "<h1>Sorry! <strong><i>This class " . __FILE__ . "$class"  . " does not exists.</i></strong></h1>";
			exit;
		endif;

	});


	function delete_directory_plus_content( $eachDir )
	{
		if ( ! is_dir( $eachDir ) ) :
		//	throw new Ayoola_Doc_Exception("$eachDir is not a directory");
		//	echo $eachDir . ' not found...
			return false;
		endif;
		if ( substr( $eachDir, strlen( $eachDir ) - 1, 1) != '/') :
			$eachDir .= '/';
		endif;
		$dotfiles = glob($eachDir . '.*', GLOB_MARK);
		$insideFiles = glob($eachDir . '*', GLOB_MARK);
		$insideFiles = array_merge($insideFiles, $dotfiles);
		foreach ( $insideFiles as $insideFile ) :
		//	set_time_limit( 30 );
			if( basename( $insideFile ) == '.' || basename( $insideFile ) == '..' ) :
				continue;
			elseif( is_dir( $insideFile ) ) :
				delete_directory_plus_content( $insideFile );
			else :
				unlink($insideFile);
			endif;
		endforeach;
		@rmdir($eachDir);
		return ! is_dir( $eachDir );
	}

	function widget( array $widget_info = null )
	{
		$col      = isset($widget_info['col']) ? $widget_info['col'] : 4;
		$color    = isset($widget_info['color']) ? $widget_info['color'] : "danger";
		$icon     = isset($widget_info['icon']) ? $widget_info['icon'] : "desktop";
		$value    = isset($widget_info['value']) ? $widget_info['value'] : 0;
		$title    = isset($widget_info['title']) ? $widget_info['title'] : "Title" ;
		$subtitle = isset($widget_info['subtitle']) ? $widget_info['subtitle'] : "Subtitle" ;
		$id       = isset($widget_info['id']) ? $widget_info['id'] : "" ;
		$tem = "";
		$tem .= "<div class='col-md-$col'>";
		$tem .=		"<div class='widget widget-$color widget-item-icon'>";
		$tem .=			"<div class='widget-item-left'>";
		$tem .= 			"<span class='fa fa-$icon'></span>";
		$tem .=	 		"</div>";

		$tem .=	 		"<div class='widget-data'>";
		$tem .=  			"<div class='widget-int num-count' id=''> $value </div>";
		$tem .=	 				"<div class='widget-title'> $title </div>";
		$tem .=	 				"<div class='widget-subtitle'> $subtitle </div>";
		$tem .=	 			"</div>";

		$tem .=	   	"<div class='widget-controls'>";
		$tem .=	   		"<a href='#' class='widget-control-right'><span class='fa fa-times'></span></a>";
		$tem .=   	"</div>";
		$tem .=		"</div>";
		$tem .=	"</div>";
		return $tem;
	}

	function user_role( array $parameters = null)
	{
		return get_user( $parameters )['user_role'];
	}


	function to_extract( $file, $destination )
	{
		$phar = 'PharData';
		$backup = new $phar( $file );
		$backup->extractTo( $destination, null, true );
	}

	function educate( $title = "Educate Title" , $content = "Educate contain" , $icon = "question-circle" , $icon_size = "1x" , $color = "danger" )
	{
		return "<a href='javascript://' data-toggle='popover' data-title= '{$title}' data-content='{$content}' > <i class='fa fa-{$icon} fa-{$icon_size} text-{$color}'></i> </a>";
	}

	/**
	 * Copy a file, or recursively copy a folder and its contents
	 * @author      Aidan Lister <aidan@php.net>
	 * @version     1.0.1
	 * @link        http://aidanlister.com/2004/04/recursively-copying-directories-in-php/
	 * @param       string   $source    Source path
	 * @param       string   $dest      Destination path
	 * @param       string   $permissions New folder creation permissions
	 * @return      bool     Returns true on success, false on failure
	 */
	function xcopy( $source, $dest, $permissions = 0755 )
	{
		// Check for symlinks
		if( is_link( $source ) ) :
			return symlink( readlink( $source), $dest );
		endif;

		// Simple copy for a file
		if (is_file($source)) :
			return copy( $source, $dest );
		endif;

		// Make destination directory
		if ( !is_dir( $dest ) ) :
			mkdir( $dest, $permissions, true );
		endif;

		// Loop through the folder
		$dirX = dir($source);
		while (false !== $entry = $dirX->read()) :
			// Skip pointers
			if ($entry == '.' || $entry == '..') :
				continue;
			endif;

			// Deep copy directories
			xcopy("$source/$entry", "$dest/$entry", $permissions);
		endwhile;
	}

	function mail_notify( $subject = null ,$message = null ,$recipient = null ,$response = null )
	{
		$mail_obj = new email;
		return $mail_obj->notification( $subject, $message, $recipient );
	}

	function get_setting($name=null)
	{
		$settings = new settings;
		$setting = $settings->show_setting($name);
		return $setting;
	}



	function get_academic_setting( $name = null )
	{
		$settings = new academic_settings;
		return $settings->show_setting( $name );
	}


	function is_front_page(){
		return get_setting("home_page");
	}

	function send_message( $subject=null, $message=null, $recipient=null, $response=null )
	{
		extract( $_POST );
		$messenger_obj = new messenger;

		if( is_array( $recipient ) ):

			foreach( $recipient as $contact ) :
			 $messenger_obj->send_message( $subject, $message, $contact, $response );
			endforeach;

		else:
			return $messenger_obj->send_message( $subject, $message, $recipient, $response );
		endif;
	}

	function get_theme_data($theme_name,$name){
		$obj = new themedata;
		$fetch = $obj->get_this_data($theme_name,$name);
		$data = $fetch->fetch_object();
		return $data->data;
	}

	function update_theme_data($theme_name,$name){
		$obj = new themedata;
		$query = $obj->update_this_data($theme_name,$name,$data);
		return $query;
	}


	if(!function_exists('get_this_file_content')):
		function get_this_file_content($file){
			$content = file_get_contents($file);
			return $content;
		}
	endif;

	 if(!function_exists('put_this_file_content')):
		function put_this_file_content($file,$content){
			$put = file_put_contents($file,$content);
			return $put;
		}
	endif;

	if(!function_exists('get_file')):
		function get_file($file){
			if(file_exists($file)):
				require $file;
			else:
				echo $file;
			endif;
		}
	endif;

	function send_mail($to,$subject,$message){
		$mail = new mail();

		//Set subject and sender of the mail.
		$mail->send("kayzenk@gmail.com",$subject,$message);
	}

	function getMenu($parent_id=null,$class=null){
		$obj = new menu;
		$menu = $obj->get_menu_tree($parent_id);
		echo $menu;
	}

	function get_gallery($limit=4){
		$gallery_obj = new gallery;
		$gallery_query = $gallery_obj->get_gallery($limit,'system');
		return $gallery_query;
	}

	function get_annex( $id = null ){
		$annex_obj = new annex;
		$result = $annex_obj-> get_data(array('id' => $id));
		return $result;
	}

	function	get_aclass($id=null){
		$class_obj = new academic_classes;
		$result = $class_obj->get_academic_class($id);
		return $result;
	}

	function	get_academic_subject( $id = null ){
		$sub_obj = new academic_subjects;
		return  $sub_obj->get_academic_subject($id);
	}

	function	get_academic_level( $id = null ){
		$lvl_obj = new academic_levels;
		return  $lvl_obj->get_academic_level( addslashes( $id ) );
	}


	function http_themes_path(){
		return get_setting("site_url")."assets/themes/";
	}

	function add_setting( $name = null , $value = null ){
		$settings = new settings;
		$setting = $settings->add_setting( $name , $value );
		return $setting;
	}

	function path_info(){
		@$path_info = $_SERVER['PATH_INFO'];
	    $path = explode("/",$path_info);
	    $new_path = array_filter($path);
	    return $new_path;
	}

	function get_user( array $parameters = null ){
		if( $parameters == null ){
			$parameters = array( "id" => $_SESSION['user_session']->id );
		}

		$user_obj = new user;
		return $user_obj->get_user( $parameters );
		// var_export($id);
		// return $query;
	}

	function get_users( array $parameters = null ){


		$user_obj = new user;
		return $user_obj->get_system_users( $parameters );
		// var_export($id);
		// return $query;
	}

	function get_user_capability( $id = null ){
		$access_obj = new access;
		return $access_obj::current_user_capability( $id );
	}

	function index_loader( $path = null , $index = null ){
		$file = isset( path_info()[2] ) ? path_info()[2] : "" ;
		if( file_exists( $path . DS . $file . ".phtml" ) ):
		  include $path . DS . $file . ".phtml";
		elseif( file_exists( $path . DS . $index  . ".phtml" ) ):
		  include $path . DS . $index . ".phtml";

		elseif( file_exists( $path . DS .  "list.phtml" ) ):
		  include $path . DS . "list.phtml";
		endif;
	}

	function load_js( $resource = null ){
		echo "<script type='text/javascript'>";
			echo $resource;
		echo "</script>";
	}

	function include_js( $resource = null ){
		echo "<script type='text/javascript' src='$resource'></script>";
	}


	function appSetting(){

    if( file_exists( APP_ROOT . "skulpro.json" )){
     return $appConfig = json_decode( file_get_contents( APP_ROOT . "skulpro.json") , true);
   }

	}


	function upload( $new_name = null , $destination, $encoded=false )
	{
		$type = $_FILES['file_name']['type'];
		$file_name = strReplace( $_FILES['file_name']['name'] );
		$tmp_name = $_FILES['file_name']['tmp_name'];
		$size = $_FILES['file_name']['size'];

		if( $new_name  ):
			$file_name = $new_name;
		elseif( $new_name == null ):
			$new_name = rand( 98768,12345 ) . $file_name ;
		else:
			$new_name = rand( 98768,12345 ) . $file_name ;
		endif;

		if( $encoded == false ):
			if( file_exists( $destination.$new_name ) ):
				unlink( $destination.$new_name );
			endif;
			move_uploaded_file( $tmp_name,$destination.$new_name );
			$upload_info = array(
				"file_type" => $type ,
				"file_name" => $new_name ,
				"file_size" => $size/1024,
				"destination" => $destination.$file_name
				);
			return $upload_info;
		else:

			$upload_info = array(
				"file_type" => $type ,
				"file_name" => $new_name ,
				"file_size" => $size/1024,
				"destination" => base64_encode( file_get_contents( $tmp_name) )
				);
				return $upload_info;
		endif;
	}

	function get_user_profile_image( array $parameters = null ){
		$user_obj = new user;
		if( ! $parameters ):
			$parameters = array( 'id' => $_SESSION['user_session']->id);
		endif;
		$user_info = $user_obj->get_user_info( $parameters );
		// var_export($user_info);
		if( file_exists( USERS_PICTURES_PATH . $user_info['token'] . ".png" ) ):
			return APP_PATH . "assets/user/storage/users/" . $user_info['token'] . ".png" ;
		else:
			return APP_PATH . "assets/user/storage/users/no-image.jpg";
		endif;

	}

	function get_user_full_name( array $parameters = null){
		$user = get_user( $parameters );
		return  $user['user_first_name'] . " " .  $user['user_last_name'];
	}

	function execution_time( $time=120 ){
		ini_set( 'max_execution_time', $time );
	}

	function set_setting( $name=null,$value=null )
	{
		$settings = new settings;
		$setting = $settings->set_setting( $name,$value );
		return $setting;
	}

	function get_header( $page_title = null  ){
		require TEMPLATES_PATH."backend/elements/header.phtml";
		if( isset($_GET['no-distraction'] ) ):
		else:
			include TEMPLATES_PATH."backend/elements/sidebar.phtml";
		endif;
	}

	function get_header_unlogged($page_title = null){
		require TEMPLATES_PATH."backend/elements/header-unlogged.phtml";

	}

	function get_sidebar($page_title=null,$breadcrumb=null){

	}

	function get_footer(){
		if( isset($_GET['no-distraction'] ) ):
		else:
			require TEMPLATES_PATH . "backend/elements/footer.phtml";
		endif;
	}

	function data_exporter( $id = null ){
		if( isset($_GET['no-export'] ) ):
		else:
			require TEMPLATES_PATH . "backend/elements/exporter.phtml";
		endif;
	}

	function strReplace($file)
	{
		$file= str_replace(' ','_',strtolower($file));
		return $file;
	}

	function access_check( $string = null ){
		$to_check = array();
		if( in_array( $string , get_user_capability() )):
			return true;
		else:
			return false;
		endif;
	}


	function is_post($slug){
		$obj = new post;
		$query = $obj->get_this_post($slug);
		$postRow = $query->fetch_object();
		echo "$postRow->postCon";
		if($postRow->postType == "Post")
		return true;

	}

	function is_page($slug){
		$obj = new post;
		$query = $obj->get_this_post($slug);
		$postRow = $query->fetch_object();
		//if($postRow->postType == "page"):
		return true;
		//endif;

	}

	function is_post_exist( $slug ){
		$obj = new post;
		$query = $obj->_post_exits( $slug );
		return $query;
	}

	function what_to_do(){
	if ( isset( $_REQUEST['what_to_do'] ) ):
		extract( $_REQUEST );
		return addslashes( $what_to_do );
	else:
		return false;
	endif;
}


	function system_error_check(){
		if (isset( $_REQUEST['sp_show_error'] ) ) {
		    error_reporting(1);
		    error_reporting(E_ALL);
		} else {
			error_reporting(0);

		}

		// switch ( get_setting("debug_mode") ):
	  //   case 'true':
	  //     error_reporting(1);
	  //     error_reporting(E_ALL);
	  //     break;
	  //   case 'false':
		// 		if( isset( $_REQUEST['sp_show_error'])):
	  //     	error_reporting(1);
		//       error_reporting(E_ALL);
		// 		else:
		// 			error_reporting(0);
		// 		endif;
	  //     break;
		//
	  //   default:
	  //     error_reporting(0);
	  //     break;
	  // endswitch;
	}

	function get_modules_name(){
		$module_obj = new modules;
		return get_modules_name();
	}

	function get_academic_subject_data($code=null){
		$academic_subject = new academic_subjects;
		$query = $academic_subject->get_academic_subject_data($code);
		$result = $query->fetch_object();
		return $result;
	}

	function get_parameters( array $parameters = null , $nature = true ){
		$obj = new sql_datacrud;
		return $obj->build_parameters($parameters , $nature);
	}


	function active_nav_element( $class = null ){
		if ( in_array( $class , path_info() ) ) {
			echo 'active';
		}
	}

	function human_title( $gender = null ){
		switch (strtolower($gender)) {
			case 'male':
				return "mr";
				break;

			case 'female':
				return "ms";
				break;

			default:
				// code...
				break;
		}
	}

	function get_question_source( $id = null ){
		$src =  new question_bank;
		return $src->get_question_source( $id );
	}

	function get_question( array $aparameters ){
		$que =  new question_bank;
		return $que->get_question( $aparameters );
	}

	function do_logout(){
		$access_obj = new access;
		$access_obj->do_logout();
	}

	function table_style(){
		return "class='table table-striped table-bordered table-hover' id='datatable' cellspacing='0' width='100%' ";
	}
