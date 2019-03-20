<?php
    class themedata extends skulpro
    {
        protected $table_name = "theme_data";

        
        function __construct(){
            $obj = new Database();
            $db = $obj->dbConnection();
            $this->conn = $db;
        }

        public function add_data($theme_name,$name,$data){
            $check = self::data_check($theme_name,$name );
            if($check <= 0 ){
                if(is_array($data)):
                    $new_data = serialize($data);
                    $query = $this->conn->query("INSERT INTO $this->table_name(theme_name,name,data) VALUES('$theme_name','$name','$new_data')");
                    
                else:   
                    $query = $this->conn->query("INSERT INTO $this->table_name(theme_name,name,data) VALUES('$theme_name','$name','$data')");

                endif;     
                
            }
        }

        private function data_check($theme_name,$name ){
            $query = $this->conn->query("SELECT* FROM $this->table_name WHERE theme_name='$theme_name' AND name='$name' ");
            $dataCheck = mysqli_num_rows($query);
            return $dataCheck; 


        }


        public function update_this_data($theme_name,$name,$data){
            if(is_array($data)):
                $new_data = serialize($data);
            $query = $this->conn->query("UPDATE $this->table_name SET data='$data' WHERE name='$new_name' AND theme_name='$theme_name' ");
            else:
                $query = $this->conn->query("UPDATE $this->table_name SET data='$data' WHERE name='$name' AND theme_name='$theme_name' ");
            endif; 
            if($query == true):
                parent::success_message("Data updated successfully");
            elseif($query == false):
                parent::error_message("Data could not be updated");  
            endif;     
        }

        public function get_this_data($theme_name,$name){
            $query = $this->conn->query("SELECT* FROM $this->table_name WHERE theme_name='$theme_name' AND name='$name' ");
            $result = $query->fetch_object();
            return $result->data;            
        }

        public function delete_this_data($theme_name,$name){
            $query = $this->conn->query("DELETE FROM $this->table_name WHERE theme_name='$theme_name' AND name='$name' ");
        }

        public function delete_this_theme_data($theme_name){
            $query = $this->conn->query("DELETE FROM $this->table_name WHERE theme_name='$theme_name' ");
        }


 
    }
?>