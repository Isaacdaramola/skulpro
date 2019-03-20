<?php

class event extends operation
{
	var $title;
	var $description;
	var $code;
	var $date;
	var $primaryAccess = array('ADMIN','SUPERAMIN','ACCOUNTANT','TEACHER','EVENTMANAGER','AUTHOR');
	var $secondaryAccess = array('PARENT','STAFF','STUDENT');
//private $conn;
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }
	
	public function addthisEvent($title,$description,$location,$state,$startTime,$endTime,$date,$image)
	{

		if(in_array($_SESSION['userSessionrole'],$this->primaryAccess)):
			$code = rand(36566466,987654778);	
			$stmt = $this->conn->prepare("INSERT INTO skulPRO_events(title,description,location,
			state,startTime,endTime,date,code,image) 
			VALUES('$title','$description','$location','$state',
			'$startTime','$endTime','$date','$code','$image')");
			$stmt->execute();
			if($stmt == true):
			echo '<div class="alert alert-success">
			<button class="close" data-dismiss="alert">&times;
			</button><i class="fa fa-calendar"></i> '.$this->title ." 
			event has been saved successfully! .</div>";
			endif;
		else:
			echo '<div class="alert alert-danger">
			<button class="close" data-dismiss="alert">&times;
			</button><i class="fa fa-times"></i> You do not have access or the right to create any event.</div>';	
		endif;	

	}	

function getprimaryAccess()
{
	$managerAccess = $this->primaryAccess;
	return $managerAccess;
}

function getsecondaryAccess()
{
	$managerAccess = $this->secondaryAccess;
	return $managerAccess;
}

function deletethisEvent($code)
{
	if(in_array($_SESSION['userSessionrole'],$this->primaryAccess)):
		$this->code = $code;
		$stmt = $this->conn->prepare("DELETE FROM skulPRO_events 
		WHERE code='$this->code'");
		$stmt->execute();
		if($stmt == true):
		echo '<div class="alert alert-danger">
		<button class="close" data-dismiss="alert">&times;
		</button><i class="fa fa-trash"></i> '.$this->title .' 
		event has been deleted successfully! .</div>';
		endif;
	else:
		echo '<div class="alert alert-danger">
		<button class="close" data-dismiss="alert">&times;
		</button><i class="fa fa-times"></i> You do not have access or the right to deleted this event.</div>';	
	endif;	
}


public function getthisEvent()
{
	if (isset($_GET['token'])) {
		$code = mysqli_escape_string($this->conn,$_GET['token']);
		$query = $this->conn->query("SELECT* FROM skulPRO_events WHERE code='$code' ");
		if ($query == true) {
			return $query;
		} else {
			echo'<div class="alert alert-error">Sorry! There is no event with such record</div>';
		}
			
							
	} else {
		echo'<div class="alert alert-error">Sorry! There is no event with such record</div>';
	}
	
	
}

///EXPERIMENTAL
function getEvents()
{
	
	
	$userRole = $_SESSION['userSessionrole'];
		$userCode = $_SESSION['userSessioncode'];
		$userName = $_SESSION['userSessionname'];
		switch ($userRole) {
			case 'SUPERADMIN':
			case 'ADMIN':
				$query = $this->conn->query("SELECT* FROM skulPRO_events");
				return $query;
				break;
			
			default:
				$query = $this->conn->query("SELECT* FROM skulPRO_events WHERE author='$userCode' OR author='$userName' ");
				return $query;
				break;
		}
	

}	


public function eventCount()
{
	$query = $this->conn->query("SELECT* FROM skulPRO_events");
	return mysqli_num_rows($query)	;
}

public function counterWidget($color)
{
	$count= self::eventCount();
	$htmlClass = new html;
	$smallBox = $htmlClass->makesmallBox("bg-olive-active",$count,'TOTAL EVENT(S)','calendar',$footer="Events ",$footerLink="?events&allevents=true");
	echo $smallBox;
}


function updatethisEvent($title,$description,$location,$state,$startTime,$endTime,$date,$image,$token)
{

	$this->code =$token;
	if(in_array($_SESSION['userSessionrole'],$this->primaryAccess)):

		$query = $this->conn->query("UPDATE skulPRO_events SET title='$title',description='$description',location='$location',state='$state',startTime='$startTime',
		endTime='$endTime',date='$date',image='$image' 
		WHERE code='$token' ");
		if($query == true):
		echo '<div class="alert alert-success">
		<button class="close" data-dismiss="alert">&times;
		</button><i class="fa fa-calendar-plus-o"></i> '.$title .' 
		event has been updated successfully! .</div>';
		endif;
	else:
		echo '<div class="alert alert-success">
		<button class="close" data-dismiss="alert">&times;
		</button><i class="fa fa-times"></i> Sorry! You do not have the right to update this event</div>';	
	endif;												 										 		
}

public function sanityCheck($str,$col,$code)
{
	if(empty($str)):
	
		return $newStr = self::getEvent($str,$code);
	
	elseif(!empty($str)):
	
		return $str;
	endif;
}

public function eventPicture($image)
{
	$settingClass = new settings;
	$query = new media;

	$htmlClass = new html;
	$siteUrl = getSetting('siteurl');//getting site url	
	if(!empty($image) && file_exists(ABSPATH.$image)):
		return $htmlClass->imageWrapper($siteUrl.$image,$class='img-circle',$alt='skulPRO',$style="",$height="230px",$width="230px");	  	    	  	  
	elseif(!is_readable($image)):			
		return $htmlClass->imageWrapper($siteUrl.'sp-admin/sp-assets/sp-data/system/evnt1.png',"img-circle",$alt='skulPRO',$style="",$height="230px",$width="230px");	  	  
	elseif(empty($image)):			
		return $htmlClass->imageWrapper($siteUrl.'sp-admin/sp-assets/sp-data/system/evnt1.png',"img-circle",$alt='skulPRO',$style="",$height="",$width=""); 
	endif;
	
}

public function eventcountWidget($bgcolor='default')
{
	$totalCount = self::eventCount();
	
	return '
	<div class="col-md-4 col-sm-12 ">
	<div class="small-box '.$bgcolor.'">
	<div class="inner">
		<h3>'.$totalCount.'<i class="fa fa-calendar"></i></h3>
		<p><h5>TOTAL&nbsp;EVENT(S)</h5> </p>
	</div>
	<div class="icon"><i class="fa fa-calendar"></i>
	</div>
	<a href="#" class="small-box-footer"><i class="fa fa-download"> Download Event</i>
	</a>
	</div>
	</div>';
}

 private function eventMailer()
 {
 	//This method is meant to notify users once the even is created.
 }

}
$eventClass = new event;
?>