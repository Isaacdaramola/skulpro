<?php
class menu extends skulpro
{
  protected $table_name = "menu";
		public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }

   public function addmenuItems($name,$parent_id,$link,$status,$position)
   {
   	$stmt = $this->conn->prepare("INSERT INTO $this->table_nameItems(name,parent_id,link,status,position)
   	VALUES('$name','$parent_id','$link','$status','$position')");
   	$stmt->execute();
  		if($stmt==TRUE)
  		{
  			echo '<div class="alert alert-success alert-dismissable">
     <button type="button" class="close" data-dismiss="alert"
     aria-hidden="true">&times;</button>
     <i class="fa fa-check"></i>
      '.$title.' item added successfully</div>';
  		}
  		elseif($stmt==FALSE)
  		{
  			echo '<div class="alert alert-danger alert-dismissable">
     <button type="button" class="close" data-dismiss="alert"
     aria-hidden="true">&times;</button>
     <i class="fa fa-check"></i>
      '.$title.' failed </div>';
  		}
   }

   public function get_menu_items(){
		$query = $this->conn->query("SELECT* FROM $this->table_name ");
		return $query;

	}

	public function delete_menu_item($code){
		$query = $this->conn->query("DELETE FROM $this->table_name WHERE id='$code' ");
		if($query == true):
			echo "<div class='alert alert-success'><i class='fa fa-trash'></i> Deleted successfully</div>";
		else:
			echo "<div class='alert alert-danger'><i class='fa fa-times'></i> Sorry! Could not delete successfully</div>";

		endif;
	}

	public function get_this_menu_item($code){
		$query = $this->conn->query("SELECT* FROM $this->table_name WHERE id='$id' ");
		return $query;
	}

	public function update_this_menu_item($code,$link,$name,$parent_id,$position){
		$query = $this->conn->query("UPDATE $this->table_name SET link='$link',name='$name',parent_id='$parent_id', position='$position'  WHERE id='$code'");
		if($query == true):
			echo "<div class='alert alert-success'><i class='fa fa-check'></i> Successfully updated</div>";
		else:
			echo "<div class='alert alert-danger'><i class='fa fa-times'></i> Sorry! Could not update the item</div>";

		endif;

	}

   public function deleteMenu($code)
   {
   	$stmt = $this->conn->prepare("DELETE FROM $this->table_name WHERE code='$code'");
   	$stmt->execute();
  		if($stmt==TRUE)
  		{
  			echo '<div class="alert alert-success alert-dismissable">
     <button type="button" class="close" data-dismiss="alert"
     aria-hidden="true">&times;</button>
     <i class="fa fa-check"></i>
      Menu deleted success fully</div>';
  		}
  		elseif($stmt==FALSE)
  		{
  			echo '<div class="alert alert-danger alert-dismissable">
     <button type="button" class="close" data-dismiss="alert"
     aria-hidden="true">&times;</button>
     <i class="fa fa-check"></i>
      Failed to delete menu </div>';
  		}
   }


   public function deletemenuItem($code)
   {
   	$stmt = $this->conn->prepare("DELETE FROM $this->table_nameItems WHERE code='$code'");
   	$stmt->execute();
  		if($stmt==TRUE)
  		{
  			echo '<div class="alert alert-success alert-dismissable">
     <button type="button" class="close" data-dismiss="alert"
     aria-hidden="true">&times;</button>
     <i class="fa fa-check"></i>
      Menu deleted success fully</div>';
  		}
  		elseif($stmt==FALSE)
  		{
  			echo '<div class="alert alert-danger alert-dismissable">
     <button type="button" class="close" data-dismiss="alert"
     aria-hidden="true">&times;</button>
     <i class="fa fa-check"></i>
      Failed to delete menu </div>';
  		}
   }


   public function updateMenu($title,$description,$link,$code)
   {
   	$stmt = $this->conn->prepare("UPDATE $this->table_name SET title='$title', description='$description'
   	WHERE code='$code'
   	");
   	$stmt->execute();
  		if($stmt==TRUE)
  		{
  			echo '<div class="alert alert-success alert-dismissable">
     <button type="button" class="close" data-dismiss="alert"
     aria-hidden="true">&times;</button>
     <i class="fa fa-check"></i>
      '.$title.' updated successfully!</div>';
  		}
  		elseif($stmt==FALSE)
  		{
  			echo '<div class="alert alert-danger alert-dismissable">
     <button type="button" class="close" data-dismiss="alert"
     aria-hidden="true">&times;</button>
     <i class="fa fa-check"></i>
      '.$title.' failed to update</div>';
  		}
   }

   function disable_item($id){
	   $query = $this->conn->query("UPDATE $this->table_name SET status='0' WHERE id='$id' ");
	   if($query == true):
		echo "<div class='alert alert-success'><i class='fa fa-check'></i>Item disabled successfully</div>";
	else:
		echo "<div class='alert alert-danger'><i class='fa fa-times'></i> Sorry! Could not be disabled item</div>";

	endif;
   }

   function enable_item($id){
		$query = $this->conn->query("UPDATE $this->table_name SET status='1' WHERE id='$id' ");
		if($query == true):
		echo "<div class='alert alert-success'><i class='fa fa-check'></i>Item enabled successfully</div>";
		else:
			echo "<div class='alert alert-danger'><i class='fa fa-times'></i> Sorry! Could not be enabled item</div>";
		endif;
	}

	public function create_menu_item($link,$name,$parent_id,$position){
		$query = $this->conn->query("INSERT INTO $this->table_name(link,name,parent_id,position)  VALUES('$link','$name','$parent_id','$position') ");
		if($query == true):
			echo "<div class='alert alert-success'><i class='fa fa-check'></i>Item created successfully</div>";
		else:
			echo "<div class='alert alert-danger'><i class='fa fa-times'></i> Sorry! Could not be created</div>";
		endif;
	}


  //  function get_menu_tree($parent_id,$class="list-inline-item")
	// {
	// 	$menu = "";
	// 	$query = $this->conn->query("SELECT* FROM $this->table_name WHERE status='1' AND parent_id='$parent_id' ");
	// 	while($row= $query->fetch_array())
	// 	{
	// 		$menu .="<li class='".$class."'><a href='".$row['link']."'>".$row['name']."</a>";
	// 		if($parent_id > 0):
  //
	// 		$menu .= "<ul>".self::get_menu_tree($row['id'])."</ul>"; //call  recursively
	// 		endif;
  //
	// 		$menu .= "</li>";
  //
	// 	}
  //
	// 	return $menu;
	// }

  function get_menu_tree($parent_id=null,$class="each-nav-item")
	{
    if(isset($parent_id)) :
      $where = 'WHERE status="1" AND parent_id='.$parent_id;
    else:
      $where =  "WHERE status='1' ";
    endif;

		$menu = "";
		$query = $this->conn->query("SELECT* FROM $this->table_name $where ORDER BY position DESC ");
		while($row= $query->fetch_assoc())
		{
			$menu .="<li class='".$class."'><a href='".$row['link']."'>".$row['name']."</a>";
			//if($parent_id > 0):
      if($row['parent_id'] > 0):
			 $menu .= "<ul>".self::get_menu_tree($row['id'])."</ul>"; //call  recursively
			endif;

			$menu .= "</li>";

		}

		return $menu;
	}
}
