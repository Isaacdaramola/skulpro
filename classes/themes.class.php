<?php
    class themes extends skulpro
    {
        const CAN_MANAGE_FRONT_END = "can_manage_front_end";
        const CAN_UPLOAD_THEME = "can_upload_theme";
        const CAN_DELETE_THEME = "can_delete_theme";
        const CAN_CHANGE_THEME_STATUS = "can_change_theme_status";
        const CAN_USE_THEME_CUSTOMIZER = "can_use_theme_customizer";
        protected $table_name = "themes";

        
        function __construct(){
            $obj = new Database();
            $db = $obj->dbConnection();
            $this->conn = $db;
        }

        public static function adminAccess(){
            if(in_array(self::CAN_MANAGE_FRONT_END,$_SESSION['user_session_role_capabilities']) && 
                in_array(self::CAN_UPLOAD_THEME,$_SESSION['user_session_role_capabilities']) && 
                in_array(self::CAN_CHANGE_THEME_STATUS,$_SESSION['user_session_role_capabilities']) && 
                in_array(self::CAN_USE_THEME_CUSTOMIZER,$_SESSION['user_session_role_capabilities']) && 
                in_array(self::CAN_DELETE_THEME,$_SESSION['user_session_role_capabilities'])):
                return true;
            else:
                return false;
            endif;
        }

        public static function frontend_mamager_Access(){
            if(in_array(self::CAN_MANAGE_FRONT_END,$_SESSION['user_session_role_capabilities']) && 
                in_array(self::CAN_UPLOAD_THEME,$_SESSION['user_session_role_capabilities']) && 
                in_array(self::CAN_CHANGE_THEME_STATUS,$_SESSION['user_session_role_capabilities']) && 
                in_array(self::CAN_USE_THEME_CUSTOMIZER,$_SESSION['user_session_role_capabilities']) && 
                in_array(self::CAN_DELETE_THEME,$_SESSION['user_session_role_capabilities'])):
                return true;
            else:
                return false;
            endif;
        }



        public function add_this_theme(){
            $accepted_file_type = array("zip");
            $type = strtolower($_FILES['theme-file']['type']);
            $name = strtolower($_FILES['theme-file']['name']);
            $fileInfo = pathinfo($name);
            if(self::theme_exist($name) == true)

            if(in_array($fileInfo["extension"],$accepted_file_type)):
                $tmp_dir = $_FILES['theme-file']['tmp_name'];
                $size = $_FILES['theme-file']['size'];
                move_uploaded_file($tmp_dir,THEMES_PATH.$name);
                $file = explode('.',$fileInfo['basename']);
                $real_file_name = $file[0];
                

                if(class_exists('ZipArchive'))
                {
                    $zip = new ZipArchive;

                    if($zip->open(THEMES_PATH.$name) !== TRUE)
                    {   
                        die('Unable to open the zip file');  
                    }
                    $zip->extractTo(THEMES_PATH) or die ('Unable to extract the file');
                    $zip->close();
                }
                if(file_exists(THEMES_PATH.$name)){
                    //unlink(THEMES_PATH.$name);
                }
                $code = rand(654321,09876);
                $query = $this->conn->query("INSERT INTO $this->table_name(name,code) VALUES('$real_file_name','$code')");
                if($query == true):
                    self::success_message("template uploaded successfully!");
                else:
                    self::error_message("Failed to upload template". $this->conn->error);
                endif;
                
            else:
                parent::error_message("Sorry, the only accespted file is a ZIP file. Thank you.");  
            endif;    
        }

        public function theme_exist($name){
            $na = explode('.', $name);
            if(file_exists(THEMES_PATH.$na[0])):
                self::error_message("Template  $na[0]  is already in the database");
                return false;
            endif;
        }

        public function delete_this_theme(){

        }

        public function activate_this_theme($code){
            $obj = self::get_active_theme();
            $fetch = $obj->fetch_object();
            $setting_value = self::get_active_theme_name($code);
           //echo $setting_value;
            //exit;
            $active_code = $fetch->code;
            $inactive_current_active_theme = $this->conn->query("UPDATE $this->table_name SET status='inactive' WHERE code='$active_code'  ");
            $make_active_theme = $this->conn->query("UPDATE skulPRO_themes SET status='active' WHERE code='$code'  ");
            if($make_active_theme == TRUE):
                updateSetting('active_theme',$setting_value);
			
                echo'<div class="alert alert-success alert-dismissable"><i class="fa fa-bell-o"></i>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                Successfusly Theme successfully activated.
                </div>';
            elseif($query == false):
        
                parent::error_message("Sorry! Failed to activate theme.");    
            endif;
        }


        public function get_active_theme(){
            $query = $this->conn->query("SELECT* FROM $this->table_name WHERE status='active' ");
            return $query;
        }

        public function get_active_theme_name($code){
            $query = $this->conn->query("SELECT name FROM $this->table_name WHERE  code='$code' ");
            $fetch = $query->fetch_object();
            return $fetch->name;
        }


        public function get_themes(){
            $this->theme_dir = THEMES_PATH;
           $query = $this->conn->query("SELECT* FROM $this->table_name ");
           return $query;
        }

        public function deactivate_this_theme(){

        }

        public function get_current_activated__theme(){

        }
        
    }
?>