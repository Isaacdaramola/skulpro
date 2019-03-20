<?php
    class todo extends skulpro
    {
        public function __construct()
        {
            $database = new Database();
            $db = $database->dbConnection();
            $this->conn = $db;
        }

        public function addthistodo(){
            if( parent::is_logged_in() == true ):
                $time = addslashes($_POST['time']);
                $date = addslashes($_POST['date']);
                $otherUsers = serialize($_POST['otherUsers']);
                $title = addslashes($_POST['title']);
                $description = addslashes($_POST['description']);
                $user = $_SESSION['userSessioncode'];
                $code = rand(7485949,58595384);
                $query = $this->conn->query("INSERT INTO skulpro_todo(time,date,title,description,user,code,otherUsers) VALUES('$time','$date','$title','$description','$user','$code','$otherUsers') ");

                if($query == true):
                echo '<div class="alert alert-success">
                <button class="close" data-dismiss="alert">&times;
                </button><i class="fa fa-calendar"></i> TO DO added successfully</div>';
                elseif($query == false):
                    echo '<div class="alert alert-danger">
                    <button class="close" data-dismiss="alert">&times;
                    </button><i class="fa fa-times"></i>Sorry! Could not add this '.$title.' to your todo list. I problem persist, contact you system administrator</div>';	
                endif;

            endif;
        }


        public function getTodo()
        {
            $userCode = $_SESSION['userSessioncode'];
            $date = date("Y-m-d");
            $query = $this->conn->query("SELECT* FROM skulpro_todo  WHERE user='$userCode'  LIMIT 5 ");
            return $query;

        }

        public function deletethistodo($code=null){
            $query = $this->conn->query("DELETE FROM skulpro_todo WHERE code='$code' ");
            if($query == true):
                echo 'TODO item deleted successfully';
                elseif($query == false):
                    echo 'TODO item failed to be deleted ';	
                endif;
        }


        public function mark_done($code=null){
            $query = $this->conn->query("UPDATE  skulpro_todo SET status='done' WHERE code='$code' ");
            if($query == true):
                echo 'TODO item marked done.';
                //elseif($query == false):
                    //echo 'TODO item could not be marked done ';	
                endif;
        }
    }

?>
