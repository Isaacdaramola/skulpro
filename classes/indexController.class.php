<?php
/* This controlles the index of the site
* @index controller
*/
$user = new user;
class IndexController extends skulpro
{
    private $model;

    function __construct( $title = null )
    {
        /*if(isset($_SERVER['PATH_INFO'])){
            //check if a path is set
            $path = $_SERVER['PATH_INFO'];
            //give the server path info to $path variable
            $pathInfo = explode('/',ltrim($path));
        }else{
            $pathInfo = '/';
        }
        if($pathInfo !== ''){
        }*/


        $this->model = $title;
        $current_theme = get_setting('active_theme');
        $default_theme = get_setting('default_theme');

        if(file_exists(THEMES_PATH.$current_theme)){
            if(file_exists(THEMES_PATH.get_setting('active_theme').'/functions.php')){
                require THEMES_PATH.get_setting('active_theme').'/functions.php';
            }
            if(file_exists( THEMES_PATH.get_setting('active_theme').'/index.php')){
                require THEMES_PATH.get_setting('active_theme').'/index.php';
            }
        }
        else{
            if(file_exists( CONTENT_PATH . 'themes/' . $default_theme ) ){
                require THEMES_PATH.get_setting('default_theme').'/functions.php';
                require THEMES_PATH.get_setting('default_theme').'/index.php';
            }
            else{
                accessHeader();
            echo "<div class='container col-md-12 jumbotron'><h2 class='text-danger'>Check your themes directory to check if the active theme is available</h2>
            <a href='login' class='btn bg-aqua-gradient pull-left btn-md'><i class='fa fa-sign-in'></i> Use Login</a> <a href='techIssue' class='btn bg-red-gradient pull-right btn-md'><i class='fa fa-bug'></i> Log Issue</a>
            </div>";
            accessFooter();
            }

        }
    }

}





?>
