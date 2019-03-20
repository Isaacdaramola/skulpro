<?php
class publication extends skulpro
{
	public $module_name = "publication";
	protected $type = array("post","page","event","media");
	protected $table_name = "post";
	protected $post_title;
	protected $post_description;
	protected $post_type;
	protected $post_keyword;
	protected $post_author;
	protected $post_date;
	protected $post_content;
	protected $media;
	protected $media_type;
	protected $token;
	protected $audio_type = array("audio/x-ms-wma","audio/mp3");

	public function __construct(){
		$database = new Database;
		$db = $database->dbConnection();
		$this->conn = $db;
		return $this->conn;
	}

	public function new_post(){
		var_dump($_POST);
	}
	protected function set_token($id=null){
			$code = md5($id);
			$query = $this->conn->query("UPDATE $this->table_name SET token='$code' WHERE id='$id' ");
	}

	public function get_author(){
		$this->author = $_SESSION['user_session']->id;
		return $this->author;
	}

	public function new_media(){
		$storeFolder = ASSETS_PATH."storage/users/";   //2
        $tempFile = $_FILES['media']['tmp_name'];  
        $new_file= strReplace(rand(654321,09876). $_FILES['media']['name']);        //3             
        $targetFile =  $storeFolder.$new_file;  //5
        move_uploaded_file($tempFile,$targetFile); //6
        extract($_POST);
        $this->post_title = $post_title;
        $this->post_description = $post_description;
        $this->post_keyword = $post_keyword;
        $this->media = $new_file;
        $this->media_type =  $_FILES['media']['type'];
        $this->post_author = $_SESSION['user_session']->id;

        $query = $this->conn->query("INSERT INTO $this->table_name(title,description,keyword,mime_type,media,author,type,path) VALUES('$this->post_title','$this->post_description','$this->post_keyword','$this->media_type','$this->media','$this->post_author','media','$targetFile') ");
        self::set_token($this->conn->insert_id);
        if($query == true):
        	parent::success_message("Media Uploaded and added successfully");
        else:
        	parent::error_message("Sorry! We could not upload your media");
        endif;

	}

	public function get_media(){
		$query = $this->conn->query("SELECT* FROM $this->table_name WHERE type='media' ");
		return $query;
	}

	public function get_my_images(){
		$this->author = self::get_author();

		$query = $this->conn->query("SELECT* FROM $this->table_name WHERE mime_type='image/png' OR mime_type='image/jpeg' OR mime_type='image/gif' AND author='$this->author' ");
		return $query;

	}


	
}
	
?>