<?php

class media extends operation
{
	
	var $name;
	var $type;
	var $link;
	var $category;
	var $author;
	var $size;
	var $path;
	const CAN_CREATE_MEDIA = "can_create_media";
	const CAN_EDIT_MEDIA = "can_edit_media";
	const CAN_DELETE_MEDIA = "can_delete_media";
	const CAN_DELETE_OTHERS_MEDIA = "can_delete_others_media";
	const CAN_EDIT_OTHERS_MEDIA = "can_edit_others_media";

	
	public function __construct()
	{
		$database = new Database;
		$db = $database->dbConnection();
		$this->conn = $db;
	}
	
	protected static function access_error_message(){
		echo'<div class="alert alert-danger"><i class="fa fa-ban"></i> <i>Sorry! Please you do not have access to perform this action</i> </div>';
	}
			
	public function runQuery($sql)
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}

	public static function adminAccess()
	{
		if(in_array(self::CAN_CREATE_MEDIA,$_SESSION['userSessionrolecapability']) &&
			in_array(self::CAN_EDIT_MEDIA,$_SESSION['userSessionrolecapability']) && 
			in_array(self::CAN_DELETE_MEDIA,$_SESSION['userSessionrolecapability']) && 
			in_array(self::CAN_DELETE_OTHERS_MEDIA,$_SESSION['userSessionrolecapability']) && 
			in_array(self::CAN_EDIT_OTHERS_MEDIA,$_SESSION['userSessionrolecapability'])):
			return true;
		else:
			
			return false;
		endif;
	}
			
	public static function creatorAccess()
	{
		if(in_array(self::CAN_CREATE_MEDIA,$_SESSION['userSessionrolecapability']) || self::adminAccess()):
			return true;
		else:
			return false;
		endif;
	}

	public static function editorAccess($code)
	{
		if($code == $_SESSION['userSessioncode'] || in_array(self::CAN_EDIT_OTHERS_MEDIA,$_SESSION['userSessionrolecapability']) || self::adminAccess()):
			return true;
		else:
			return false;
		endif;
	}
	

	public static function deleteAccess($code)
	{
		if($code == $_SESSION['userSessioncode'] || in_array(self::CAN_DELETE_OTHERS_MEDIA,$_SESSION['userSessionrolecapability']) || self::adminAccess() ):
			return true;
		else:
			return false;
		endif;
	}


	public function download_this_media($code=null)
	{ 
		$path = self::getMedia('link',$code);
		$category = 'Media';
		$downloadAgent = $_SERVER['HTTP_USER_AGENT'].'<br>';
		$user = $_SESSION['userSessionname'];
		$setting = new operation;
		$download = new download();
		$stmt = $this->conn->prepare("UPDATE skulPRO_media SET downloaded=downloaded+1 WHERE code='$code'");
		$stmt->execute();
		if($stmt==TRUE)		
		{
			$download->downloadAsset($path,$category,$downloadAgent,$user);	
		}
	}
	
	function addthisMedia()
	{
		if (isset($_POST['addMedia'])):
			if(self::creatorAccess()):
			
				$type = $_FILES['file_Name']['type'];
				$File = $_FILES['file_Name']['name'];
				$tmp_dir = $_FILES['file_Name']['tmp_name'];
				$fileSize = $_FILES['file_Name']['size'];
				$newfile = strReplace($File);
				$uniqID = rand(100,10000);
				$newFile = $uniqID . $newfile;
				$url = getSetting('siteurl');
				$newSize = $fileSize/1024;
				$size = $newSize;
				$userPath = ASSETS_PATH.'sp-data/users/'.$_SESSION['userSessionname'].DS;
				if(file_exists($userPath)):
					move_uploaded_file($tmp_dir,$userPath.$newFile); 
					$link = USERS_DATA_PATH.$_SESSION['userSessionname'].DS. $newFile;
					$code = rand(100,1000000); 
					$path = "sp-admin/sp-assets/sp-data/users/".$_SESSION['userSessionname'].DS. $newFile;
					$this->name = $newFile;	
					$this->type = $type;
					$this->code = $code;
					$this->link = $link;
					$this->size = $size;
					$this->author = $_SESSION['userSessioncode'];
					$this->path = $path;
					@$stmt = $this->conn->prepare("INSERT INTO skulPRO_media(name,type,code,link,size,author,path)
					VALUES('$this->name','$this->type','$code','$this->link','$this->size','$this->author','$this->path')");
					$stmt->execute();
					if($stmt == TRUE): 
						echo"<div class='alert alert-success alert-dismissable'><i class='fa fa-check'></i> {$this->name} was uploaded successfully!</div>";
					elseif($stmt == FALSE):
						echo"<div class='alert alert-error alert-dismissable'><i class='fa fa-times'></i> {$this->name} failed to upload!</div>";
					endif;
					//@refresh2url('?mediaManager');//refreshes after successful action
				elseif(!file_exists($userPath)):
					echo"<div class='alert alert-dander alert-dismissable'><i class='fa fa-times'></i> {$userRow->userName} directorty does not exist on the system. </div>";
				endif;
			else:
				self::access_error_message();
			endif;
		endif;
	}	

	function delete_this_media($code=null)
	{
		if(self::deleteAccess($code)):
			
			$user = $_SESSION['userSessionname'];
			$name = self::getMedia('name',$code);	
			$this->code	 = $code;
			$this->name = $name;
			echo ASSETS_PATH.'sp-data/users/'."$user".DS."$name";
			if(file_exists( ASSETS_PATH.'sp-data/users/'."$user".DS."$name")):
				unlink( ASSETS_PATH.'sp-data/users/'."$user".DS."$name");
				$stmt = $this->conn->prepare("DELETE FROM skulPRO_media WHERE code='$this->code'");
				$stmt->execute();	
				if($stmt == TRUE):
					echo'<div class="alert alert-danger alert-dismissable"> <i class="fa fa-trash"></i> File deleted successfully!</div>';
					header('Location: media');
				endif;
			endif;	
		else:
			self::access_error_message();
		endif;
	}

	function getMedia($col,$code)
	{
		$this->col = $col;
		//	$this->type = $type;
		$query = $this->conn->query("SELECT*  FROM skulPRO_media WHERE code='$code'");
		//$query->fetch_array();
		$row = mysqli_fetch_array($query);
		return $row[$col];
	}

	function getthisMedia($code){
		$userRole = $_SESSION['userSessionrole'];
		$userCode = $_SESSION['userSessioncode'];
		$userName = $_SESSION['userSessionname'];
		switch ($userRole) {
			case 'SUPERADMIN':
			case 'ADMIN':
				$query = $this->conn->query("SELECT*  FROM skulPRO_media WHERE code='$code'");
				return $query;
				break;
			
			default:
				$query = $this->conn->query("SELECT*  FROM skulPRO_media WHERE author='$userCode' OR author='$userName'  AND code='$code' ");
				return $query;
				break;
		}

	}


	function getallMedia()
	{	

		$userRole = $_SESSION['userSessionrole'];
		$userCode = $_SESSION['userSessioncode'];
		$userName = $_SESSION['userSessionname'];
		switch ($userRole) {
			case 'SUPERADMIN':
			case 'ADMIN':
				$query = $this->conn->query("SELECT*  FROM skulPRO_media ");
				return $query;
				break;
			
			default:
				$query = $this->conn->query("SELECT*  FROM skulPRO_media WHERE author='$userCode' OR author='$userName' ");
				return $query;
				break;
		}

	}
		
	function getImages()
	{			
		$userRole = $_SESSION['userSessionrole'];
		$userCode = $_SESSION['userSessioncode'];
		$userName = $_SESSION['userSessionname'];
		switch ($userRole) {
			case 'SUPERADMIN':
			case 'ADMIN':
				$query = $this->conn->query("SELECT*  FROM skulPRO_media WHERE type='image/jpeg' OR type='image/png' OR type='image/gif' ");
				return $query;
				break;
			
			default:
				$query = $this->conn->query("SELECT*  FROM skulPRO_media WHERE author={$_SESSION['userSessioncode']} ");
				return $query;
				break;
		}
		
	}	
	

	public function imageWrapper($source,$class,$size)
	{
		$imageWrap='<img src="'.$source.'" class="'.$class.'" alt="Admin Image" style="'.$size.'">';
		return $imageWrap;
	}
}
?>