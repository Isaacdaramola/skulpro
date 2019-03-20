<?php

  class chat
  {

    public function __construct(){
        $databse = new Database;
        $db = $databse->dbConnection();
        $this->conn = $db;
        return $this->conn;
    }

    public static function getRestChatLines($id)
    {
      $arr = array();
      $jsonData = '{"results":[';
      $this->conn->query( "SET NAMES 'UTF8'" );
      $statement = $this->conn->prepare( "SELECT id, user_name, color, chat_text, chat_time FROM chat WHERE id > ? and chat_time >= DATE_SUB(NOW(), INTERVAL 1 HOUR)");
      $statement->bind_param( 'i', $id);
      $statement->execute();
      $statement->bind_result( $id, $usrname, $color, $chattext, $chattime);
      $line = new stdClass;
      while ($statement->fetch()) {
        $line->id = $id;
        $line->usrname = $usrname;
        $line->color = $color;
        $line->chattext = $chattext;
        $line->chattime = date('H:i:s', strtotime($chattime));
        $arr[] = json_encode($line);
      }
      $statement->close();
      $db_connection->close();
      $jsonData .= implode(",", $arr);
      $jsonData .= ']}';
      return $jsonData;
    }
    
    public static function setChatLines( $chattext, $usrname, $color) {
      $this->conn->query( "SET NAMES 'UTF8'" );
      $statement = $this->conn->prepare( "INSERT INTO chat( usrname, color, chattext) VALUES(?, ?, ?)");
      $statement->bind_param( 'sss', $usrname, $color, $chattext);
      $statement->execute();
      $statement->close();
      $db_connection->close();
    }
  }
?>