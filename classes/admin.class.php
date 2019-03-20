<?php
class admins extends skulpro{
var $userRole = "ADMIN";
var $userClass;
var $userEmploymentdate;
var $userQualification;
var $userworkExperience;
var $userChild;

public function addthisAdmin($userTitle,$userName,$userStatus,$userGender,
$userState,$userFirstname,$userAddress,$userLastname,$userNumber,$userNumber,
$userEmail,$userChild,$userDob,$userYob,$userMob,$userDayob,$serMob,$userPass)
				{
					$this->userName = $userName;
					$this->userEmail = $userEmail;
                    $this->userNumber = $userNumber;
                    $code = rand(23456370009,2397373830003);
                    $this->userStatus = $userStatus;
					$query = $this->conn->query("SELECT* 
							FROM skulPRO_users WHERE userName='$userName' LIMIT 1");
							if($row = mysqli_num_rows($query) > 0):		
                            echo '<div class="alert alert-error"><button class="close" data-dismiss="alert">&times;
                            </button> '.$this->userName .' had been registered! Try another details.</div>';								 										 								
							else:
								@mkdir(USERS_PATH.DS."$userName",0644);
								$userPath = "sp-content/sp-data/users/$userName";
								@fopen(USERS_PATH.DS."$userName/index.php", 'w');
								$stmt = $this->conn->prepare("INSERT INTO skulPRO_users
								(userTitle,userName,userStatus,userGender,
                                userOrigin,userFirstname,userAddress,userLastname,
                                userNumber,userEmail,userChild,userDob,
                                userYob,userMob,userDayob,userPass,userCode,userRole)
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
		


		public function updatethisAdmin($userTitle,$userGender,$userEmail,$userFirstname,
		$userLastname,$userNumber,$userAddress,$userDob,$userYob,$userMob,$userDayob
		,$userState,$userChild,$userPass,$code)
		{

           
			$query = self::getthisAdmin($code);
			$user = $query->fetch_object();
			$query = $this->conn->query("SELECT  userEmail 
							FROM skulPRO_users WHERE userEmail ='$userEmail'");
							if($row = mysqli_num_rows($query) > 1):		
                            echo '<div class="alert alert-error"><button class="close" data-dismiss="alert">&times;
                            </button> '.$this->userEmail .' had been registered! Try another details.</div>';
							elseif($row = mysqli_num_rows($query) == 0 || $row = mysqli_num_rows($query) == 1):
							$stmt = $this->conn->prepare("UPDATE skulPRO_users 
							SET userTitle='$userTitle',userGender='$userGender',userEmail='$userEmail',
							userFirstname='$userFirstname',userLastname='$userLastname',userNumber='$userNumber',
							userAddress='$userAddress',userDob='$userDob',userYob='$userYob',userMob='$userMob',
							userDayob='$userDayob',userOrigin='$userState',userChild='$userChild',userPass='$userPass' WHERE userCode='$code' AND userRole='STAFF' ");
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



		public function adminUpdateself($userGender,$userFirstname,
		$userLastname,$userNumber,$userAddress,$userDob,
		$userYMD,$userYob,$userMob,$userDayob,$userAge,$userState,
		$userPass,$code)
		{

			$query = self::getthisAdmin($code);
			$user = $query->fetch_object();

			$stmt = $this->conn->prepare("UPDATE skulPRO_users 
							SET userGender='$userGender',
							userFirstname='$userFirstname',
		userLastname='$userLastname',userNumber='$userNumber',userAddress='$userAddress',userDob='$userDob',
		userYob='$userYob',userMob='$userMob',userDayob='$userDayob',userAge='$userAge',userOrigin='$userState',
		userPass='$userPass' WHERE userCode='$code' AND userRole='ADMIN'");
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

		}
        
        public function getAdmins(){
            $query = $this->conn->query("SELECT* FROM skulpro_users WHERE userRole='STAFF' ");
            return $query;
		}
		


		public function getthisAdmin($code){

            $query = $this->conn->query("SELECT* FROM skulpro_users WHERE userCode='$code' AND userRole='ADMIN' ");
            return $query;
        }
        



        public function deactivatethisAdmin($code){

            $query = $this->conn->query("UPDATE skulpro_users SET userStatus='N' WHERE userCode='$code' AND userRole='ADMIN' ");
            return $query;
        }
        

        public function activatethisAdmin($code){

            $query = $this->conn->query("UPDATE skulpro_users SET userStatus='Y' WHERE userCode='$code' AND userRole='ADMIN' ");
            return $query;
		}
		


		public function countAdmins()
		{
			$query = $this->conn->query("SELECT* FROM skulpro_users WHERE userRole='ADMIN' ");
			$tCount = mysqli_num_rows($query);
			return $tCount;
		}

		public function adminWidget($bgcolor='default'){
			$counter = self::countAdmins();
			return '
			<div class="col-md-4 col-sm-12 ">
			<div class="small-box '.$bgcolor.'">
			<div class="inner">
				<h3>'.$counter.'<i class="fa fa-users"></i></h3>
				<p><h5>TOTAL&nbsp;ADMIN(S)</h5> </p>
			</div>
			<div class="icon"><i class="fa fa-users"></i>
			</div>
			<a href="#" class="small-box-footer"><i class="fa fa-download"></i>
			</a>
		</div>
	</div>';
		}




}

$adminClass = new admins;



?>