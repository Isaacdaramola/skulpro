<?php
/*
 * PHP Pagination Class
 *
 * @author isaac Daramola - isaac@isaac.com.ng - http://www.isaac.com.ng
 * @version 1.0
 * @date 27 April, 2018
 */
// class paginator{
//
//         /**
// 	 * set the number of items per page.
// 	 *
// 	 * @var numeric
// 	*/
// 	private $_perPage;
//
// 	/**
// 	 * set get parameter for fetching the page number
// 	 *
// 	 * @var string
// 	*/
// 	private $_instance;
//
// 	/**
// 	 * sets the page number.
// 	 *
// 	 * @var numeric
// 	*/
// 	private $_page;
//
// 	/**
// 	 * set the limit for the data source
// 	 *
// 	 * @var string
// 	*/
// 	private $_limit;
//
// 	/**
// 	 * set the total number of records/items.
// 	 *
// 	 * @var numeric
// 	*/
// 	private $_totalRows = 0;
//
//
//
// 	/**
// 	 *  __construct
// 	 *
// 	 *  pass values when class is istantiated
// 	 *
// 	 * @param numeric  $_perPage  sets the number of iteems per page
// 	 * @param numeric  $_instance sets the instance for the GET parameter
// 	 */
// 	public function __construct($perPage,$instance){
// 		$this->_instance = $instance;
// 		$this->_perPage = $perPage;
// 		$this->set_instance();
// 	}
//
// 	/**
// 	 * get_start
// 	 *
// 	 * creates the starting point for limiting the dataset
// 	 * @return numeric
// 	*/
// 	public function get_start(){
// 		return ($this->_page * $this->_perPage) - $this->_perPage;
// 	}
//
// 	/**
// 	 * set_instance
// 	 *
// 	 * sets the instance parameter, if numeric value is 0 then set to 1
// 	 *
// 	 * @var numeric
// 	*/
// 	private function set_instance(){
// 		$this->_page = (int) (!isset($_GET[$this->_instance]) ? 1 : $_GET[$this->_instance]);
// 		$this->_page = ($this->_page == 0 ? 1 : $this->_page);
// 	}
//
// 	/**
// 	 * set_total
// 	 *
// 	 * collect a numberic value and assigns it to the totalRows
// 	 *
// 	 * @var numeric
// 	*/
// 	public function set_total($_totalRows){
// 		$this->_totalRows = $_totalRows;
// 	}
//
// 	/**
// 	 * get_limit
// 	 *
// 	 * returns the limit for the data source, calling the get_start method and passing in the number of items perp page
// 	 *
// 	 * @return string
// 	*/
// 	public function get_limit(){
//         	return "LIMIT ".$this->get_start().",$this->_perPage";
//         }
//
//         /**
//          * page_links
//          *
//          * create the html links for navigating through the dataset
//          *
//          * @var sting $path optionally set the path for the link
//          * @var sting $ext optionally pass in extra parameters to the GET
//          * @return string returns the html menu
//         */
// 	public function page_links($path='?',$ext=null)
// 	{
// 	    $adjacents = "2";
// 	    $prev = $this->_page - 1;
// 	    $next = $this->_page + 1;
// 	    $lastpage = ceil($this->_totalRows/$this->_perPage);
// 	    $lpm1 = $lastpage - 1;
//
// 	    $pagination = "";
// 		if($lastpage > 1)
// 		{
// 		    $pagination .= "<ul class='pagination'>";
// 		if ($this->_page > 1)
// 		    $pagination.= "<li><a href='".$path."$this->_instance=$prev"."$ext'>Previous</a></li>";
// 		else
// 		    $pagination.= "<span class='disabled'>Previous</span>";
//
// 		if ($lastpage < 7 + ($adjacents * 2))
// 		{
// 		for ($counter = 1; $counter <= $lastpage; $counter++)
// 		{
// 		if ($counter == $this->_page)
// 		    $pagination.= "<li><span class='current'>$counter</span></li>";
// 		else
// 		    $pagination.= "<li><a href='".$path."$this->_instance=$counter"."$ext'>$counter</a></li>";
// 		}
// 		}
// 		elseif($lastpage > 5 + ($adjacents * 2))
// 		{
// 		if($this->_page < 1 + ($adjacents * 2))
// 		{
// 		for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
// 		{
// 		if ($counter == $this->_page)
// 		    $pagination.= "<li><span class='current'>$counter</span></li>";
// 		else
// 		    $pagination.= "<li><a href='".$path."$this->_instance=$counter"."$ext'>$counter</a></li>";
// 		}
// 		    $pagination.= "...";
// 		    $pagination.= "<li><a href='".$path."$this->_instance=$lpm1"."$ext'>$lpm1</a></li>";
// 		    $pagination.= "<li><a href='".$path."$this->_instance=$lastpage"."$ext'>$lastpage</a></li>";
// 		}
// 		elseif($lastpage - ($adjacents * 2) > $this->_page && $this->_page > ($adjacents * 2))
// 		{
// 		    $pagination.= "<li><a href='".$path."$this->_instance=1"."$ext'>1</a></li>";
// 		    $pagination.= "<li><a href='".$path."$this->_instance=2"."$ext'>2</a></li>";
// 		    $pagination.= "...";
// 		for ($counter = $this->_page - $adjacents; $counter <= $this->_page + $adjacents; $counter++)
// 		{
// 		if ($counter == $this->_page)
// 		    $pagination.= "<span class='current'>$counter</span>";
// 		else
// 		    $pagination.= "<li><a href='".$path."$this->_instance=$counter"."$ext'>$counter</a></li>";
// 		}
// 		    $pagination.= "..";
// 		    $pagination.= "<li><a href='".$path."$this->_instance=$lpm1"."$ext'>$lpm1</a></li>";
// 		    $pagination.= "<li><a href='".$path."$this->_instance=$lastpage"."$ext'>$lastpage</a></li>";
// 		}
// 		else
// 		{
// 		    $pagination.= "<li><a href='".$path."$this->_instance=1"."$ext'>1</a></li>";
// 		    $pagination.= "<li><a href='".$path."$this->_instance=2"."$ext'>2</a></li>";
// 		    $pagination.= "..";
// 		for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
// 		{
// 		if ($counter == $this->_page)
// 		    $pagination.= "<span class='current'>$counter</span>";
// 		else
// 		    $pagination.= "<li><a href='".$path."$this->_instance=$counter"."$ext'>$counter</a></li>";
// 		}
// 		}
// 		}
//
// 		if ($this->_page < $counter - 1)
// 		    $pagination.= "<li><a href='".$path."$this->_instance=$next"."$ext'>Next</a></li>";
// 		else
// 		    $pagination.= "<li><span class='disabled'>Next</span></li>";
// 		    $pagination.= "</ul>\n";
// 		}
//
//
// 	return $pagination;
// 	}
// }
	/**
	 *
	 */
	class paginator
	{
		private $_conn;
		private $limit;
		private $page;
		private $query;
		private $total;

		function __construct($query)
		{
			$this->_conn = self::_connector();
	    $this->_query = $query;

	    $rs= $this->_conn->query( $this->_query );
	    $this->_total = $rs->num_rows;
		}

		function _connector()
		{
			$database = new Database();
			$db = $database->dbConnection();
			$this->conn = $db;
		}

		public function getData( $limit = 10, $page = 1 )
		{
		    $this->_limit   = $limit;
		    $this->_page    = $page;

		    if ( $this->_limit == 'all' ) {
		        $query      = $this->_query;
		    } else {
		        $query      = $this->_query . " LIMIT " . ( ( $this->_page - 1 ) * $this->_limit ) . ", $this->_limit";
		    }
		    $rs             = $this->_conn->query( $query );

		    while ( $row = $rs->fetch_assoc() ) {
		        $results[]  = $row;
		    }

		    $result         = new stdClass();
		    $result->page   = $this->_page;
		    $result->limit  = $this->_limit;
		    $result->total  = $this->_total;
		    $result->data   = $results;

		    return $result;
		}

		public function createLinks( $links, $list_class ) {
		    if ( $this->_limit == 'all' ) {
		        return '';
		    }

		    $last       = ceil( $this->_total / $this->_limit );

		    $start      = ( ( $this->_page - $links ) > 0 ) ? $this->_page - $links : 1;
		    $end        = ( ( $this->_page + $links ) < $last ) ? $this->_page + $links : $last;

		    $html       = '<ul class="' . $list_class . '">';

		    $class      = ( $this->_page == 1 ) ? "disabled" : "";
		    $html       .= '<li class="' . $class . '"><a href="?limit=' . $this->_limit . '&page=' . ( $this->_page - 1 ) . '">&laquo;</a></li>';

		    if ( $start > 1 ) {
		        $html   .= '<li><a href="?limit=' . $this->_limit . '&page=1">1</a></li>';
		        $html   .= '<li class="disabled"><span>...</span></li>';
		    }

		    for ( $i = $start ; $i <= $end; $i++ ) {
		        $class  = ( $this->_page == $i ) ? "active" : "";
		        $html   .= '<li class="' . $class . '"><a href="?limit=' . $this->_limit . '&page=' . $i . '">' . $i . '</a></li>';
		    }

		    if ( $end < $last ) {
		        $html   .= '<li class="disabled"><span>...</span></li>';
		        $html   .= '<li><a href="?limit=' . $this->_limit . '&page=' . $last . '">' . $last . '</a></li>';
		    }

		    $class      = ( $this->_page == $last ) ? "disabled" : "";
		    $html       .= '<li class="' . $class . '"><a href="?limit=' . $this->_limit . '&page=' . ( $this->_page + 1 ) . '">&raquo;</a></li>';

		    $html       .= '</ul>';

		    return $html;
		}

	}
