<?php
	/**
	*
	*/
	class faqs extends skulpro
	{
        private $category;
        private $question;
        private $answer;
        private $id;

        const CAN_CREATE_FAQ = "can_create_faq";
				const CAN_EDIT_FAQ  = "can_edit_faq";
				const CAN_DELETE_FAQ  = "can_delete_faq";
				private $academic_subject_name;
				private $academic_subject_teacher;
        private $academic_subject_description;
        private $academic_subject_id;
        const MODULE_NAME = "faq";

				//guest_log
				private $title;
				private $full_name;
				private $email;
				private $phone_number;
				private $address;
				private $subject;
				private $note;



		final static function adminAccess(){
			if(in_array(self::CAN_CREATE_FAQ,$_SESSION['user_session_role_capabilities']) &&
				in_array(self::CAN_EDIT_FAQ,$_SESSION['user_session_role_capabilities']) &&
				in_array(self::CAN_DELETE_FAQ,$_SESSION['user_session_role_capabilities']) ):;
				return true;
			else:
				return false;
			endif;

		}

		final static function creatorAccess(){
			if(in_array(self::CAN_CREATE_FAQ,$_SESSION['user_session_role_capabilities']) || self::adminAccess() == true ):
				return true;
			else:
				return false;
			endif;
		}

		final  static function editorAccess(){
			if(in_array(self::CAN_EDIT_FAQ,$_SESSION['user_session_role_capabilities']) || self::adminAccess() == true ):
				return true;
			else:
				return false;
			endif;
		}

		static function deleteAccess( $code=null ){
			if(in_array(self::CAN_DELETE_FAQ,$_SESSION['user_session_role_capabilities']) || self::adminAccess() == true)
				return true;
        }

        static function access_error_message(){
            parent::error_message( "Sorry, you do not have access right to do this." );
        }

		function __construct(){
			$databse = new Database;
            $db = $databse->dbConnection();
            $this->conn = $db;
            return $this->conn;
        }

        public function add_faq(){
            if(self::creatorAccess()):
                extract($_POST);
                $this->category = addslashes($faq_category);
                $this->question = addslashes($faq_question);
                $this->answer = addslashes($faq_answer);
                $query = $this->conn->query("INSERT INTO faqs(question,answer,category) VALUES('$this->question','$this->answer','$this->category') ");
                if($query == true):
                    parent::success_message("  New Question & Answer has been added to successfully");
                else:
                    parent::error_message(" Sorry! operation failed. if the issue continues, contact ICT department");
                endif;
            else:
               self::access_error_message();
            endif;

        }

        public function update_faq(){
            if(self::editorAccess()):
                extract($_POST);
                $this->category = addslashes($faq_category);
                $this->question = addslashes($faq_question);
                $this->answer = addslashes($faq_answer);
                $this->id = (int) base64_decode($faq_code);
                $query = $this->conn->query("UPDATE
                faqs
                SET
                question= '$this->question',
                answer = '$this->answer',
                category = '$this->category'
                WHERE
                id = '$this->id' ");
                if($query == true):
                    parent::success_message(" Question & Answer has been updated to successfully");
                else:
                    parent::error_message("Sorry! operation failed. if the issue continues, contact ICT department");
                endif;
            else:
                self::access_error_message();
             endif;
        }

        public function delete_faq($code){
            if(self::deleteAccess()):
                $this->id = (int) base64_decode($faq_code);
                $query = $this->conn->query("DELETE FROM faqs WHERE id = '$this->id' ");
            else:
                self::access_error_message();
            endif;
        }

				//Geust log starts here

				public function guest_log(){
					if(isset($_POST['btn-new-quest-log'])):
						extract($_POST);
						var_dump($_POST);
						$this->title = addslashes($title);
						$this->full_name = addslashes($full_name);
						$this->phone_number = addslashes($phone_number);
						$this->email = addslashes($email);
						$this->address = addslashes($address);
						$this->subject = addslashes($subject);
						$this->note = addslashes($note);
						$query = $this->conn->query("INSERT INTO guest_log(title,full_name,email,address,phone_number,subject,note)
						VALUES('$this->title','$this->full_name','$this->phone_number','$this->email','$this->address','$this->subject','$this->note')");
						parent::set_token($this->conn->insert_id,'guest_log');
						if($query == true):
							parent::success_message("Guest Log entered successfully");
						else:
							parent::error_message("Error loging guest's info".$this->conn->error);
						endif;
					endif;
				}

				public function get_guest_log(){
					$query = $this->conn->query("SELECT* FROM guest_log ");
					while (	$result = $query->fetch_assoc() ) {
						$data[] = $result;
					}
					return $data;
				}

			public function get_this_quest_log($id = null){
				$query = $this->conn->query("SELECT* FROM guest_log WHERE id='$id' OR token='$id' ");
				$result = $query->fetch_assoc();
				return $result;

			}

				//guest_log ends here



        public function get_faqs(){
            $query = $this->conn->query("SELECT* FROM faqs");
            //$result = $query->fetch_object();
            return $query;
        }

        public function get_faq($code){
            $this->id = (int) base64_decode($code);
            $query = $this->conn->query("SELECT* FROM faqs WHERE id='$this->id' ");
            $result = $query->fetch_object();
            return $result;
        }

	}


?>
