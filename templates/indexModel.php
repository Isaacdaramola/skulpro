<?php
	/**
	*
	*/
	class indexModel
	{
		function __construct( $para = null )
		{

			// var_export( $para );
			if( file_exists( TEMPLATES_PATH . "widgets" . DS . $para ) ):
				if( file_exists(TEMPLATES_PATH . "widgets" . DS . $para . DS . "index.phtml" ) ):
					require TEMPLATES_PATH . "widgets" . DS . $para . DS . "index.phtml";
				endif;

			elseif( file_exists( TEMPLATES_PATH . "backend" . DS . $para ) ):
				if( file_exists( TEMPLATES_PATH . "backend" . DS . $para . DS . "index.phtml" ) ):
					require TEMPLATES_PATH . "backend" . DS . $para . DS . "index.phtml";
				endif;

			// elseif( in_array( $para , array( "/" , "home" , "index" , "login" )  ) ):
			// 	// var_export($para);
			// 	require BACKEND_PATH. "access" . DS . "login.phtml";
			else:
				// require "404.phtml";
			endif;
		}
	}
