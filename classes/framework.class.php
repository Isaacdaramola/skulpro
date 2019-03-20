<?php
	/**
	*
	*/
	class framework extends Database
	{

		// function __construct()
		// {
		// 	$database = new Database;
		// 	$db = $database->dbConnection();
		// 	$this->conn = $db;
		// 	return $this->conn;
		// }

		public static function run(){

			$access_obj = new access;

			$path = isset( path_info()[1] ) ? path_info()[1] : "";
			if( $path == "" || $path == "/" || in_array( $path , array('home' , 'login' , 'signin' ) )  ):
				require BACKEND_PATH . "access" . DS .  "login.phtml";
			endif;
			include TEMPLATES_PATH . 'indexModel.php';
			// var_export($path);
			$model_obj = new indexModel( $path );

		}

		private static function _load_header(){
			require SCRIPTS_PATH."header.template.php";
		}

		private static function _load_footer(){
			require SCRIPTS_PATH."footer.template.php";
		}

		public static function frontendConfig(){
            if(!defined("TEMPLATES_PATH"))
 				define("TEMPLATES_PATH",ABSPATH."assets/themes".DS);
            if( file_exists( FUNCTIONS_PATH . 'frontNavigator.php' ))
                require FUNCTIONS_PATH.'frontNavigator.php';

        }
	}


?>
