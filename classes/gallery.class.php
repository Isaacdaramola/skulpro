
<?php
class gallery extends skulpro
{
	protected $table_name = "gallery";
	public function __construct()
	{
		$database = new Database;
		$db = $database->dbConnection();
		$this->conn = $db;
			}
			
			public function addGallery($user="system")
			{
				if (isset($_POST['btn-addGallery'])):
					$File = $_FILES['file_Name']['name'];
					$tmp_dir = $_FILES['file_Name']['tmp_name'];
					$fileSize = $_FILES['file_Name']['size'];
					$newfile = strtolower($File);
					$uniqID = rand(123456,987654);
					$newFile = $uniqID ."_".$newfile;
					$title = addslashes($_POST['title']);
					$caption = addslashes($_POST['caption']);  
					$url = getSetting('siteurl');
					$newSize = $fileSize/1024;
					$size = $newSize;
					move_uploaded_file($tmp_dir,GALLERY_PATH.$newFile); 
					$image = $newFile;
					$http_path =  getSetting("siteurl").'sp-content'.DS.'storage/gallery'.DS.$image;
					$image_path = GALLERY_PATH.$image;

					$code = rand(7654432,123763);
					$query = $this->conn->query("INSERT  INTO skulPRO_gallery(user,image,http_path,image_path,title,caption,code) VALUES('$user','$image','$http_path','$image_path','$title','$caption','$code')");
					if($query == TRUE):
						echo'<div class="alert alert-success alert-dismissable">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Gallery asset added successfully
							</div>';	
						//echo '<meta http-equiv="refresh" content="2;url=gallery">';
					endif;
				endif;
	
			}
			
			function getGallery()
			{
				$url = getSetting('siteurl');				
    			$userGal = new templating(TEMPLATES_PATH.DS.'user'.DS.'userGallery.tpl');
    			$query = $this->conn->query("SELECT* FROM $this->table_name ");
				while($row = mysqli_fetch_array($query)):
					$data = '<div class="col-md-3"><div class="thumbnail">';
					$data .='<a class="thumbnail" href="'.$row['http_path'].'"
					data-title="'.$row['caption'].'" data-lightbox="example-1">
					<img class="img-responsive" style="" src="'.$row['http_path'].'" style="" alt="image-1" /></a>';
					$data .='</div></div>';
					echo $data;
    			endwhile;
    		}
			
			public function deleteGallery($code)
			{
			$img = self::getuserGallery($code,'image');
			$path = GALLERY_PATH.$img;
			if(file_exists($path)):	
				unlink($path);
			$query = $this->conn->query("DELETE FROM $this->table_name WHERE code='$code'");
			if($query == TRUE)
			{
			echo'<div class="alert alert-danger alert-dismissable">
					<button type="button" class="close" data-dismiss="alert"  aria-hidden="true">&times;</button> <i class="fa fa-trash"></i> File deleted successfully!
				</div>';	
			}
			endif;
			}



			public function get_gallery($limit=4,$user="system"){
				$query = $this->conn->query("SELECT image, user,http_path,caption,title FROM $this->table_name WHERE user='$user' LIMIT $limit ");
				return $query;
			}
			
			
			public function updateGallery()
			{	
				if(isset($_POST['updateGallery'])):
					$title = addslashes($_POST['updatetitle']);	
					$caption = addslashes($_POST['updatecaption']);	
					$code = (int) $_GET['edit']	;
					
					$this->title = $title;
					$stmt = $this->conn->prepare("UPDATE $this->table_name SET title='$title', caption='$caption' WHERE code='$code'");
					$stmt->execute();
					if($stmt == TRUE):
					echo'<div class="alert alert-success alert-dismissable">
							<button type="button" class="close" data-dismiss="alert"  aria-hidden="true">&times;</button> '.$this->title.' was updated successfully!
						</div>';	
					else:
						echo'<div class="alert alert-danger alert-dismissable">
								<button type="button" class="close" data-dismiss="alert"  aria-hidden="true">&times;</button>'.$this->title.' failed to update 
							</div>';
					endif;		
					echo '<meta http-equiv="refresh" content="1;url=gallery">';
				endif;
				
				
			}
			
			function getuserGallery($code,$col)
			{
				$query = $this->conn->query("SELECT* FROM $this->table_name WHERE code='$code' ");
				$row = mysqli_fetch_array($query);				
				return $row[$col];
			}
			
			
}
?>