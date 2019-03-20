<?php
  /**
   *
   */
  class user_info extends skulpro
  {
    protected $user_agent ;
    protected $browser;
    protected $device;
    function __construct()
    {
      $this->user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
      $this->browser = array('firefox','chrome','opera','msie','safari','blackberry','trident');
      $this->device = array('iphone','ipad','android','silk','blackberry','touch','linux');
      $this->browser_length = count( $this->browser );
      $this->device_length = count( $this->device );
    }

    public function find_browser()
    {
      for($uaSniff = 0 ; $uaSniff < $this->browser_length ; $uaSniff ++)
      {
        if( strstr( $this->user_agent, $this->browser[$uaSniff] ) )
        {
          return $this->browser[$uaSniff];
        }
      }
    }

    public function find_device()
    {
      for($uaSniff=0;$uaSniff < $this->device_length;$uaSniff ++)
      {
        if(strstr($this->user_agent,$this->device[$uaSniff]))
        {
          return $this->device[$uaSniff];
        }
      }
    }



    public function find_ip()
    {
        // Get real visitor IP behind CloudFlare network
      if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
                $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
                $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
      }
      $client  = @$_SERVER['HTTP_CLIENT_IP'];
      $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
      $remote  = $_SERVER['REMOTE_ADDR'];

      if(filter_var($client, FILTER_VALIDATE_IP))
      {
          $ip = $client;
      }
      elseif(filter_var($forward, FILTER_VALIDATE_IP))
      {
          $ip = $forward;
      }
      else
      {
          $ip = $remote;
      }

      return $ip;
    }

  }



?>
