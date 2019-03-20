<?php
  /**
   *
   */
  class curl_class
  {
    public static function result($url)
    {
      $ch = curl_init();
      curl_setopt ($ch, CURLOPT_URL, $url);
  		curl_setopt ($ch, CURLOPT_HEADER, 0);
  		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, $url);
  		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $result = curl_exec($ch);
      return $result;
      curl_close($ch);
    }
  }
