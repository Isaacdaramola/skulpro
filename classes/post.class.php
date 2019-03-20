<?php
class post extends operation
{
	var $postTitle;
	var $postDesc;
	var $postCode;
	var $postCon;
	var $postImage;
	var $postStatus;
	var $postType;
	var $postLink;
	var $postSlug;
	var $postCategory;
	var $can_create_post = "can_create_post";
	var $can_edit_post  = "can_edit_post";
	var $can_delete_post  = "can_delete_post";
	var $can_edit_others_post  = "can_edit_others_post" ;
	var $can_delete_others_post = "can_delete_others_post";



	public static function adminAccess(){
		if(in_array("can_create_post",$_SESSION['userSessionrolecapability']) && 
			in_array("can_edit_post",$_SESSION['userSessionrolecapability']) && 
			in_array("can_delete_post",$_SESSION['userSessionrolecapability']) && 
			in_array("can_edit_others_post",$_SESSION['userSessionrolecapability']) && 
			in_array("can_delete_others_post",$_SESSION['userSessionrolecapability']) ):;
			return true;
		else:
			return false;
		endif;

	}
	

	public  static function creatorAccess(){
		if(in_array('can_create_post',$_SESSION['userSessionrolecapability'])  || self::adminAccess() == true):
			return true;
		else:
			return false;
		endif;	
	}

	public  static function editorAccess($code){
		if($_SESSION['userSessioncode'] == self::get_post_author($code) && in_array('can_edit_post',$_SESSION['userSessionrolecapability']) || self::adminAccess() ):
			return true;
		else:
			return false;
		endif;	
	}

	public function deleteAccess($code){
		if($_SESSION['userSessioncode'] == self::get_post_author($code) && in_array('can_delete_post',$_SESSION['userSessionrolecapability']) || self::adminAccess() == true):
			return true;
		else:
			return false;
		endif;
	}
	
	
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
	}

	

	function getpostAccess($code)
	{
		$stmt = $this->conn->prepare("SELECT postAccess FROM skulPRO_posts WHERE postCode='$code'");
		$result = $stmt->fetch_object;
		$newResult = unserialize($result);
		return $newResult;
	}

	function getPost($code)
	{
		$stmt = $this->conn->prepare("SELECT* FROM skulPRO_posts WHERE postType='post' AND postCode='$code'");
		return $stmt;
	}
	
	public function get_home_page(){

		$query = $this->conn->query("SELECT* FROM skulpro_posts WHERE postType='page' AND root='1'  LIMIT 1");
		return $query;

	}

	public function _post_exits($slug){
	$query = $this->conn->query("SELECT* FROM skulpro_posts WHERE postSlug='$slug' OR postID='$slug' LIMIT 1");
	$row = $query->num_rows;
	if($row > 0 ):
		return true;
	else:
		return false;
	endif;		




	}

	public function set_home_page($code){
		$post = self::get_home_page();
		$action = $post->fetch_object();
		$oldPageCode = $action->postCode;
		$query = $this->conn->query("UPDATE skulpro_posts SET root='0' WHERE postCode='$oldPageCode' AND postType='page' ");
		$query = $this->conn->query("UPDATE skulpro_posts SET root='1' WHERE postCode='$code' AND postType='page' ");
		if($query == TRUE):
			
			echo'<div class="alert alert-success alert-dismissable"><i class="fa fa-bell-o"></i>
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			Successfully set as home page.
			</div>';	
		
		elseif($query == FALSE):
			
				echo'<div class="alert alert-danger alert-dismissable">
				<i class="fa fa-bell-o"></i>
				<button type="button" class="close" data-dismiss="alert" 
				aria-hidden="true">&times;</button>
				Sorry! Failed to set as home page.
				</div>';
		endif;	

	}

	public function addthisPost($postTitle,$postDesc,$postImage,$postContent,$postStatus,$postCode,$postType)
	{
		if(self::creatorAccess()):
			$this->postTitle = $postTitle;
			$postSlug = slug($postTitle);
			//$this->postLink = $postLink;
			$postAuthor = $_SESSION['userSessioncode'];
			$query = $this->conn->query("INSERT 
				INTO 
				skulPRO_posts(postTitle, postDesc, postCon, 
				postStatus, author, postCode, 
				postImage, postType, postSlug) 
				VALUES
				('$postTitle','$postDesc','$postContent','$postStatus','$postAuthor','$postCode','$postImage','$postType','$postSlug')");
			if($query == TRUE):
				
					echo'<div class="alert alert-success alert-dismissable"><i class="fa fa-bell-o"></i>
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					'.$postTitle.' saved successfully!
					</div>';	
				
			elseif($query == FALSE):
				
					echo'<div class="alert alert-danger alert-dismissable">
					<i class="fa fa-bell-o"></i>
					<button type="button" class="close" data-dismiss="alert" 
					aria-hidden="true">&times;</button>
					'.$this->postTitle.' failed to save!
					</div>';
			endif;
		else:
			echo "<div class='alert alert-danger'><b><i class='fa fa-ban'></i> Sorry!</b> No access.</div> ";
		endif;			
			

	}	

  	function getPosts($limit=null,$status=null)
	{
		
		if(!empty($limit)):
			$query = $this->conn->query("SELECT* FROM skulPRO_posts WHERE postType='post' AND postStatus='$status' ORDER BY postID DESC LIMIT $limit ");
			return $query;
		else:
			$query = $this->conn->query("SELECT* FROM skulPRO_posts WHERE postType='post' AND postStatus='$status' ORDER BY postID DESC  ");
			return $query;
		endif;	
	}

	public function update_post_view_counter($token){
		$query = $this->conn->query("UPDATE skulPRO_posts SET post_views=post_views+1 WHERE postSlug='$token'  ");

	}


	


  	function getPages()
	{
		$query = $this->conn->query("SELECT* FROM skulPRO_posts WHERE postType='page'");
		return $query;
	}

	function getthisPost($token)
	{
		$query = $this->conn->query("SELECT* FROM skulPRO_posts WHERE postCode='$token'");
		return $query;
	}
	
	function get_this_Post($token)
	{
		$query = $this->conn->query("SELECT* FROM skulPRO_posts WHERE postSlug='$token' AND postStatus='PUBLISHED' ");
		self::update_post_view_counter($token);
		return $query;
	}

	public function get_page($slug){
		$query = $this->conn->query("SELECT* FROM skulPRO_posts WHERE postSlug='$slug' ");
		return $query;
	}
  
	function deletethisPost($code)
	{
		if(self::editorAccess()):
			$this->code = $code;
			if(in_array($_SESSION['userSessionrole'],$this->primaryAccess)):
				$stmt = $this->conn->prepare("DELETE 
				FROM 
				skulPRO_posts
				WHERE 
				postCode='$this->code'");
				$stmt->execute();
				if($stmt == TRUE):
					echo'<div class="alert alert-danger alert-dismissable"><i class="fa fa-bell-o"></i>
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Deleted permanently successfully!
					</div>';	
				else:
					echo'<div class="alert alert-danger alert-dismissable">
					<i class="fa fa-bell-o"></i>
					<button type="button" class="close" data-dismiss="alert" 
					aria-hidden="true">&times;</button>
					Failed to be deleted!
					</div>';
				endif;
			endif;
		else:
			echo "<div class='alert alert-danger'><b><i class='fa fa-ban'></i> Sorry!</b> No access.</div> ";
		endif;	
		
	}

	function updatethisPost($postTitle,$postDesc,$postImage,$postContent,$postStatus,$postCode)
	{
		
		if(self::editorAccess($postCode)):
			global $dateTime;
			$date = $dateTime;
			$postSlug = slug($postTitle);
			$postContent;
			$query = $this->conn->query("UPDATE skulPRO_posts 
			SET postTitle='$postTitle',postDesc='$postDesc',postImage='$postImage',postCon='$postContent',postMode='$date',postStatus='$postStatus',
			postSlug='$postSlug' 
			WHERE 
			postCode='$postCode' ");
			if($query == TRUE):

				echo'<div class="alert alert-success alert-dismissable"><i class="fa fa-bell-o"></i>
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						'.$postTitle.' updated successfully!
					</div>';	

			else:
				echo'<div class="alert alert-danger alert-dismissable"><i class="fa fa-bell-o"></i>
						<button type="button" class="close" data-dismiss="alert"  aria-hidden="true">&times;</button>
						'.$postTitle.' failed to update!
					</div>';	

			endif;
		else:
			echo "<div class='alert alert-danger'><b><i class='fa fa-ban'></i> Sorry!</b> No access.</div> ";
		endif;	
	}

	public function get_post_author($code){
		//$query = $this->conn->query("SELECT author FROM skulpro_posts WHERE author='$code' ");
		//$result = $query->fetch_object();
		//return $result->author;
	}

	public function getTotalPosts(){
		$userRole = $_SESSION['userSessionrole'];
		$userCode = $_SESSION['userSessioncode'];
		switch ($userRole) {
			case 'SUPERADMIN':
			case 'ADMIN':
				$query = $this->conn->query("SELECT* FROM skulPRO_posts WHERE postType='post'");
				$result = mysqli_num_rows($query);
				return $result;
				break;

			default:
			$query = $this->conn->query("SELECT* FROM skulPRO_posts WHERE postType='Post' AND author='$userCode' ");
			$result = mysqli_num_rows($query);
			return $result;
			break;
			
			
		}

	}


}
?>