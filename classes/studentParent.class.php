<?php
class studentParent extends skulpro{
var $userRole = "PARENT";


	public function addthisParent($userTitle,$userName,$userStatus,$userGender,$userState,$userFirstname,$userAddress,$userLastname,
	$userNumber,$userNumber,$userEmail,$userChild,$userDob,$userYob,$userMob,$userDayob,$serMob,$userPass)
	{
		$this->userName = $userName;
		$this->userEmail = $userEmail;
		$this->userNumber = $userNumber;
		$code = rand(23456375467899,23973738312345673);
		$this->userStatus = $userStatus;
		$nameCheck = $this->conn->query("SELECT* FROM skulPRO_users WHERE userName='$userName' LIMIT 1");
		if($row = mysqli_num_rows($nameCheck) > 0)
		{
			echo '<div class="alert alert-error"><button class="close" data-dismiss="alert">&times;
			</button> '.$this->userName .' had been registered! Try another details.</div>';	
			exit();
		}
		$emailCheck = $this->conn->query("SELECT* FROM skulPRO_users WHERE  userEmail='$userEmail' LIMIT 1");
		if($row = mysqli_num_rows($emailCheck) > 0):		
			echo '<div class="alert alert-error"><button class="close" data-dismiss="alert">&times;
			</button> '.$this->userEmail.' had been registered! Try another details.</div>';	
			exit();								 										 								
		else:
			mkdir(ASSETS_PATH."sp-data/users/$userName",0644);
			$userPath = "sp-admin/sp-assets/sp-data/users/$userName";
			fopen(ASSETS_PATH."sp-data/users/$userName/index.php", 'w');
			$stmt = $this->conn->prepare("INSERT INTO skulPRO_users
			(userTitle,userName,userStatus,userGender,userOrigin,userFirstname,userAddress,userLastname,
			userNumber,userEmail,userChild,userDob,userYob,userMob,userDayob,userPass,userCode,userRole)
			VALUE
			('$userTitle','$userName','$userStatus','$userGender',
			'$userState','$userFirstname','$userAddress','$userLastname',
			'$userNumber','$userEmail','$userChild','$userDob',
			'$userYob','$userMob','$userDayob','$userPass','$code','$this->userRole')");
			$query = $this->conn->query("INSERT INTO skulPRO_birthdays(userCode,userYob,
			userMob,userDayob)
			VALUES('$code',
			'$userYob','$userMob','$userDayob')");
			$stmt->execute();
			if($stmt == true):
			echo '<div class="alert alert-success">
				<button class="close" data-dismiss="alert">&times;</button>
				'.$this->userName.' has been successfully registered as a '.$this->userRole.'</div>';
			elseif($stmt == false):
				echo '<div class="alert alert-danger">
					<button class="close" data-dismiss="alert">&times;</button>
					'.$this->userName.' couldn\'t be registered as a '.$this->userRole.'</div>';
			endif;	
		endif;
	}



		public function updatethisParent($userTitle,$userGender,$userEmail,$userFirstname,
		$userLastname,$userNumber,$userAddress,$userDob,$userYob,$userMob,$userDayob
		,$userState,$userChild,$userPass,$code)
		{

			$query = self::getthisParent($code);
			$user = $query->fetch_object();
			$query = $this->conn->query("SELECT  userEmail FROM skulPRO_users WHERE userName !='$user->userName' AND userEmail ='$userEmail'");
			if($row = mysqli_num_rows($query) > 0):		
			echo '<div class="alert alert-error"><button class="close" data-dismiss="alert">&times;
			</button> '.$this->userEmail .' had been registered by another user! Try another details.</div>';
			elseif($row = mysqli_num_rows($query) == 0 || $row = mysqli_num_rows($query) == 1):
			$stmt = $this->conn->prepare("UPDATE skulPRO_users 
			SET userTitle='$userTitle',userGender='$userGender',userEmail='$userEmail',
			userFirstname='$userFirstname',userLastname='$userLastname',userNumber='$userNumber',
			userAddress='$userAddress',userDob='$userDob',userYob='$userYob',userMob='$userMob',
			userDayob='$userDayob',userOrigin='$userState',userChild='$userChild',userPass='$userPass' WHERE userCode='$code' ");
			$stmt->execute();
				if($stmt == true):								
				echo '<div class="alert alert-success">
				<button class="close" data-dismiss="alert">&times;</button>
				'.$user->userName.' has been successfully updated</div>';
				elseif($stmt == false):
					echo '<div class="alert alert-danger">
				<button class="close" data-dismiss="alert">&times;</button>
				'.$user->userName.' couldn\'t be updated</div>';
				endif;
			endif;	

		}



		
        public function getParents(){
            $query = $this->conn->query("SELECT* FROM skulpro_users WHERE userRole='TEACHER' ");
            return $query;
		}
		


		public function getthisParent($code){

            $query = $this->conn->query("SELECT* FROM skulpro_users WHERE userCode='$code'");
            return $query;
		}
		


		public function countParents()
		{
			$query = $this->conn->query("SELECT* FROM skulpro_users WHERE userRole='PARENT' ");
			$tCount = mysqli_num_rows($query);
			return $tCount;
		}

		public function parentWidget($bgcolor='default'){
			$counter = self::countParents();
			return '
			<div class="col-md-4 col-sm-12 ">
			<div class="small-box '.$bgcolor.'">
			<div class="inner">
				<h3>'.$counter.'<i class="fa fa-users"></i></h3>
				<p><h5>TOTAL&nbsp;PARENT(S)</h5> </p>
			</div>
			<div class="icon"><i class="fa fa-users"></i>
			</div>
			<a href="#" class="small-box-footer"><i class="fa fa-download"></i>
			</a>
		</div>
	</div>';
		}




}


?>