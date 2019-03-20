<?php
/**
* 
*/
class notice extends operation
{
	var $board;
	var $title;
	var $content;
	var $can_create = "can_create_notice";
	var $can_edit  = "can_edit_notice";
	var $can_delete  = "can_delete_notice";
	var $can_edit_others  = "can_edit_others_notice" ;
	var $can_delete_others  = "can_delete_others_notice";



	public static function adminAccess(){
		if(in_array("can_create_notice",$_SESSION['userSessionrolecapability']) && 
			in_array("can_edit_notice",$_SESSION['userSessionrolecapability']) && 
			in_array("can_delete_notice",$_SESSION['userSessionrolecapability']) && 
			in_array("can_edit_others_notice",$_SESSION['userSessionrolecapability']) && 
			in_array("can_delete_others_notice",$_SESSION['userSessionrolecapability']) ):;
			return true;
		else:
			return false;
		endif;

	}
	

	public  static function creatorAccess(){
		if(in_array('can_create_notice',$_SESSION['userSessionrolecapability']) || self::adminAccess() == true):
			return true;
		else:
			return false;
		endif;	
	}

	public  static function editorAccess(){
		if(in_array('can_edit_notice',$_SESSION['userSessionrolecapability']) || self::adminAccess() == true ):
			return true;
		else:
			return false;
		endif;	
	}

	public function deleteAccess($code){
		if($_SESSION['userSessioncode'] == self::get_this_author($code) || in_array('can_delete_notice',$_SESSION['userSessionrolecapability']) || self::adminAccess() == true)
			return true;


	}
	
		function addNotice()
		{
			if(self::adminAccess() == true || self::creatorAccess() == true):
			
				$this->board= strip_tags($_POST['board']) ;
				$this->title =  strip_tags($_POST['title']);
				$this->content = addslashes($_POST['content']);	
				$this->author = $_SESSION['userSessioncode'];
				$code = rand(2345678,098098654433);
				$stmt = $this->conn->prepare("INSERT INTO skulPRO_notice (board,title,content,code,author)  VALUES ('$this->board','$this->title', '$this->content','$code','$this->author')");
				$stmt->execute();
				if($stmt == true):
					echo'<div class="alert alert-success alert-dismissable"> <i class="fa fa-check"></i> Notice created successfully!</div>';
				elseif($stmt == false):
					echo'<div class="alert alert-danger alert-dismissable"> <i class="fa fa-times"></i> Failed to create notice.</div>';
				endif;
			else:
				echo'<div class="alert alert-danger alert-dismissable"> <i class="fa fa-ban"></i> Sorry no creator access</div>';	
			endif;
		}
		
		function deleteNotice()
		{
			
			
				if(isset($_GET['delete']) && isset($_GET['token'])):
					
						$code = $_GET['token'];
						$getN = self::getNotice($code);
						$N = $getN->fetch_object();
						$title = $N->title;
						if(self::deleteAccess($code) == true):

						$this->code = $code;
						$stmt = $this->conn->prepare("DELETE FROM skulPRO_notice 
						WHERE code='$code'");
						$stmt->execute();
						if($stmt == true):
							echo'<div class="alert alert-danger alert-dismissable">
								<button type="button" class="close" 
								data-dismiss="alert" aria-hidden="true">&times;</button>
								<i class="fa fa-trash"></i> '.$title.' deleted successfully</div>';
						elseif($stmt == false):
							echo'<div class="alert alert-danger alert-dismissable">
								<button type="button" class="close" 
								data-dismiss="alert" aria-hidden="true">&times;</button>
								<i class="fa fa-trash"></i> '.$title.' failed to be deleted</div>';
						endif;

					else:
						echo'<div class="alert alert-danger alert-dismissable"> <i class="fa fa-ban"></i> Sorry no delete access</div>';	
					endif;
				endif;	

			
		}


		public function get_this_author($code){
			$query = $this->conn->query("SELECT author FROM skulpro_notice WHERE code='$code' ");
			$result = $query->fetch_object();
			return $result->author;
		}

		function getNotice($code)
		{
			$query = $this->conn->query("SELECT* FROM skulPRO_notice WHERE code='$code' ");
			return $query;
		}



		public function getallNotice()
		{
			$userRow = $_SESSION['userSessionrole'];

			switch ($userRow) {
				case 'SUPERADMIN':
					$query = $this->conn->query("SELECT* FROM skulPRO_notice ");
					return $query;
					break;
				case 'ADMIN':
					$query = $this->conn->query("SELECT* FROM skulPRO_notice ");
					return $query;
					break;
					
				case 'TEACHER':
					$query = $this->conn->query("SELECT* FROM skulPRO_notice  WHERE board='$userRow' AND board='PUBLIC' ");
					return $query;
					break;
					
				case 'STUDENT':
					$query = $this->conn->query("SELECT* FROM skulPRO_notice WHERE board='$userRow' AND board='PUBLIC' ");
					return $query;
					break;	

				case 'STAFF':
					$query = $this->conn->query("SELECT* FROM skulPRO_notice WHERE board='$userRow' AND board='PUBLIC' ");
					return $query;
					break;
				
				case 'PARENTS':
					$query = $this->conn->query("SELECT* FROM skulPRO_notice WHERE board='$userRow' AND board='PUBLIC' ");
					return $query;
					break;	

				case 'ACCOUNTANT':
					$query = $this->conn->query("SELECT* FROM skulPRO_notice ");
					return $query;
					break;	
				
				default:
					# code...
					break;
			}
	
		}
}
?>