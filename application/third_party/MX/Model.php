<?php
if (!defined('BASEPATH')) exit('No direct script access allowed...');

class MX_Model
{
	var $insert_id = '';
	var $records = "";
	var $records_array = "";
	var $num_rows = 0;
	var $fields = array();
	var $pagination_start = 0;
	var $pagination_per_page = 0;
	var $current_lang = '';

	var $where   = '';
    var $limit = '';
	var $order = '';

	var $current_db;
	
	function __construct(){
		$this->CI = &get_instance();
		//echo $this->CI->session->userdata("user_db");
		// 	$this->current_db = $this->CI->session->userdata("user_db");
		// if ($this->current_db){
		// 	$this->getDB($this->current_db);
		// }

		//print_r($this->CI->db);
	}

	// function getDB($db){
	// 	$this->CI->db->db_select($db);
    // }

	function set_insert_id($val=NULL){
		$this->insert_id = $val;
	}
	function get_insert_id(){
		return $this->insert_id;
	}
	
	function set_pagination_start($val=""){
		$this->pagination_start = $val;
	}
	
	function get_pagination_start()
	{
		return $this->pagination_start;
	}
	
	function set_pagination_per_page($val="")
	{
		$this->pagination_per_page = $val;
	}
	function get_pagination_per_page()
	{
		return $this->pagination_per_page;
	}
	
	function set_current_lang($val="")
	{
		$this->current_lang = $val;
	}
	function get_current_lang()
	{
		return $this->current_lang;
	}
	
	function get_records()
	{
		return $this->records;
	}

	function get_records_array()
	{
		return $this->records_array;
	}
	
	function get_num_rows()
	{
		return $this->num_rows;
	}

	function get_fields()
	{
		return $this->fields;
	}
	
	function truncate_table($table='')
	{
		if(!empty($table))
			$this->CI->db->truncate($table);
	}
	function pg_last_insert_id($table, $fieldname) {
		//gets the last inserted ID
		$result =$this->CI->db->query("SELECT last_value FROM ${table}_${fieldname}_seq");
		$records = $result->row();
		return $records->last_value;
	}


	/********FOR DATATABLE GET******/
	function getWhere() {
    	return $this->where;
    }
    function getOrder() {
    	return $this->order;	
    }
    function getLimit() {
    	return $this->limit;
    }
	
	function save($table='',$data='',$conditions='')
	{
		if(!empty($data) || !empty($table))
		{
			if(!empty($conditions))
			{
				foreach($conditions as $field_name => $field_value)
				{
					if(!empty($field_value))
					$this->CI->db->where($field_name,$field_value);
				}
				
				$q = $this->CI->db->update($table,$data);
			}
			else
			{
				if($this->CI->db->insert($table,$data))
				$q = $this->insert_id = $this->CI->db->insert_id();
			}
			return $q;
		}
	}
	
	function delete($table='',$conditions='')
	{
		if(!empty($table))
		{
			if(!empty($conditions))
			{
				foreach($conditions as $field_name => $field_value)
				{
					$this->CI->db->where($field_name,$field_value);
				}
				$q = $this->CI->db->delete($table);
	
				return $q;
			}
		}
	}

	function find_all($table='',$order_by='',$group_by='')
	{
		if(!empty($table))
		{
			if(!empty($order_by))
				$this->CI->db->order_by($order_by);
			if(!empty($group_by))
				$this->CI->db->group_by($group_by);
			$q = $this->CI->db->get($table);
			$this->records = $q->result();
			$this->num_rows = $q->num_rows();
		}
	}
	
	function find_by_field($table='',$conditions='',$fields='',$row=FALSE,$order_by='',$limit=0,$group_by='')
	{
		if(!empty($table))
		{
			if(!empty($fields))
			{
				$this->CI->db->select($fields);
				$this->CI->db->from($table);
				$table = '';
			}
			if(!empty($conditions))
			{
				if (is_array($conditions)){
					foreach($conditions as $field_name => $field_value)
					{
						$this->CI->db->where($field_name,$field_value);
					}
				}
				else
					$this->CI->db->where($conditions);
			}
			if(!empty($order_by))
			{
				if(is_array($order_by))
				{
					foreach($order_by as $order_field => $order_behavior)
					{
						$this->CI->db->order_by($order_field,$order_behavior);
					}
				}
				else
					$this->CI->db->order_by($order_by);
			}
			if(!empty($group_by))
				$this->CI->db->group_by($group_by);
			if(!empty($limit))
				$this->CI->db->limit($limit);
			$q = $this->CI->db->get($table);
			
			if($q->num_rows() > 0)
			{
				if($row)
					$this->records = $q->row();
				else{
					$this->records = $q->result();
					$this->records_array = $q->result_array();
				}

				$this->fields = $q->list_fields();
				$this->num_rows = $q->num_rows();
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
	}
	
	function find_with_joins($table='',$joins='',$conditions='',$fields='',$row=FALSE,$order_by='',$group_by='',$limit='',$offset = '')
	{
		if(!empty($table))
		{
			if(!empty($fields))
			{
				$this->CI->db->select($fields);
				$this->CI->db->from($table);
				$table = '';
			}
			if(!empty($joins))
			{
				foreach($joins as $join_table => $join_data)
				{
					foreach($join_data as $join_type => $join_on)
					{
						$this->CI->db->join($join_table,$join_on,$join_type);
					}
				}
			}
			if(!empty($conditions))
			{
				if (is_array($conditions)){
					foreach($conditions as $field_name => $field_value)
					{
						$this->CI->db->where($field_name,$field_value);
					}
				}
				else
					$this->CI->db->where($conditions);
			}
			if(!empty($order_by))
			{
				if(is_array($order_by))
				{
					foreach($order_by as $order_field => $order_behavior)
					{
						$this->CI->db->order_by($order_field,$order_behavior);
					}
				}
				else
				{
					$this->CI->db->order_by($order_by);
				}
			}
			if(!empty($group_by))
				$this->CI->db->group_by($group_by);
			if(!empty($limit))
				$this->CI->db->limit($limit,$offset);

			$q = $this->CI->db->get($table);
			if($q->num_rows() > 0)
			{
				if($row)
					$this->records = $q->row();
				else{
					$this->records = $q->result();
					$this->records_array = $q->result_array();
				}
	
				$this->fields = $q->list_fields();
				$this->num_rows = $q->num_rows();
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
	}

	function get_options($table,$code,$name,$condition="",$value="", $order_by = NULL,$group_by  = "")
	{
		if (!empty($condition))
			$this->CI->db->where($condition);

		if($order_by) 
			$this->CI->db->order_by($order_by);
		
		if($group_by) 
			$this->CI->db->group_by($group_by);

		$dataOpt    = "";
		$query  = $this->find_by_field($table,'',array($code,$name));
		
		if ($this->get_num_rows() > 0 AND $query) {
			$dataOpt .= "<option value=''>-- Select All-- </option>";
			foreach ($this->get_records() as $dataVal) {
				$selected   = ($dataVal->$code == $value) ? "selected" : "";
				$dataOpt    .= "<option value='".$dataVal->$code."' $selected>".$dataVal->$name."</option>";
			}
		}
		return $dataOpt;
	}

	function get_options_join($table,$code,$name,$condition="",$value="", $order_by = NULL)
	{
		if (!empty($condition))
			$this->CI->db->where($condition);

		if($order_by) 
			$this->CI->db->order_by($order_by);

		$dataOpt    = "";
		$query  = $this->find_by_field($table,'',array($code,$name));
		if ($this->get_num_rows() > 0 AND $query) {
			foreach ($this->get_records() as $dataVal) {
				$selected   = ($dataVal->$code == $value) ? "selected" : "";
				$dataOpt    .= "<option value='".$dataVal->$code."' $selected>".$dataVal->$code." - ".$dataVal->$name."</option>";
			}
		}
		return $dataOpt;
	}

	function select_with_joins($table='',$joins='',$conditions='',$fields='',$row=FALSE,$order_by='',$group_by='',$limit='')
	{
		if(!empty($table))
		{
			if(!empty($fields))
			{
				$this->CI->db->select($fields);
				$this->CI->db->from($table);
				$table = '';
			}
			if(!empty($joins))
			{
				foreach($joins as $join_table => $join_details)
				{
					foreach($join_details as $join_type => $join_on)
					{
						$this->CI->db->join($join_table,$join_on,$join_type);
					}
				}
			}
			if(!empty($conditions))
			{
				foreach($conditions as $field_name => $field_value)
				{
					$this->CI->db->where($field_name,$field_value);
				}
			}
			if(!empty($order_by))
			{
				if(is_array($order_by))
				{
					foreach($order_by as $order_field => $order_behavior)
					{
						$this->CI->db->order_by($order_field,$order_behavior);
					}
				}
				else
				{
					$this->CI->db->order_by($order_by);
				}
			}
			if(!empty($group_by))
				$this->CI->db->group_by($group_by);
			if(!empty($limit))
				$this->CI->db->limit($limit);

			$q = $this->CI->db->get($table);
			if($q->num_rows() > 0)
			{
				if($row)
					$this->records = $q->row();
				else{
					$this->records = $q->result();
					$this->records_array = $q->result_array();
				}

				$this->num_rows = $q->num_rows();
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
	}
	
	function find_with_pagination($table='',$joins='',$conditions='',$fields='',$order_by='',$row=FALSE,$group_by='')
	{
		if(!empty($table))
		{
			if(!empty($fields))
			{
				$this->CI->db->select($fields);
				$this->CI->db->from($table);
				$table = '';
			}
			if(!empty($joins))
			{
				foreach($joins as $join_type => $join_tables)
				{
					foreach($join_tables as $join_table => $join_on)
					{
						$this->CI->db->join($join_table,$join_on,$join_type);
					}
				}
			}
			if(!empty($conditions))
			{
				foreach($conditions as $field_name => $field_value)
				{
					$this->CI->db->where($field_name,$field_value);
				}
			}
			if(!empty($order_by))
				$this->CI->db->order_by($order_by);
			if(!empty($group_by))
				$this->CI->db->group_by($group_by);

			$this->CI->db->limit($this->pagination_per_page,$this->pagination_start);
			$q = $this->CI->db->get($table);
			if($q->num_rows() > 0)
			{
				if($row)
					$this->records = $q->row();
				else
					$this->records = $q->result();
	
				$this->num_rows = $q->num_rows();
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
	}
	
	function count_records($table='',$conditions='',$like=FALSE)
	{
		if(!empty($conditions))
		{
			if($like)
			{
				foreach($conditions as $field_name => $field_value)
				{
					$this->CI->db->like($field_name, $field_value, 'after'); 
				}
			}
			else
			{
				foreach($conditions as $field_name => $field_value)
				{
					$this->CI->db->where($field_name,$field_value);
				}
			}
		}
		$this->CI->db->from($table);
		return $this->CI->db->count_all_results();
	}
	function get_max($table='', $field) {
		$this->CI->db->select_max('id');
		$q = $this->CI->db->get($table);
		$this->records = $q->row();
		$this->num_rows = $q->num_rows();
	}
	
	function get_data_query($tbl, $params = "" , $page = "all", $conditions='', $fields='',$group=''){
		if($fields) {
			if($group) {
				$this->CI->db->group_by($group); 
			}
			if (!empty($params)){
					$fld = $params ["search_field"];
					$fldata = $params ["search_str"];
					$foper = $params ["search_operator"];
					
					if  ($params ["search"] == 'true'){
						switch ($foper) {
							case "bw":
								$this->CI->db->like($fld, $fldata, 'before'); 
								break;
							case "eq":
								$this->CI->db->where($fld, $fldata);
								break;
							case "ne":
								$this->CI->db->where('$fld <> ', $fldata);
								break;
							case "lt":
								$this->CI->db->where('$fld < ', $fldata);
								break;
							case "le":
								$this->CI->db->where('$fld <= ', $fldata);
								break;
							case "gt":
								$this->CI->db->where('$fld > ', $fldata);
								break;
							case "ge":
								$this->CI->db->where('$fld >= ', $fldata);
								break;
							case "ew":
								$this->CI->db->like($fld, $fldata, 'after');
								break;
							case "cn":
								$this->CI->db->like($fld, $fldata);
								break;
							default :
								$wh = "";
						}	
					}
				
				if(!empty($conditions))
				{
					foreach($conditions as $field_name => $field_value)
					{
						if(!empty($field_value))
						$this->CI->db->where($field_name,$field_value);
					}
				}
				
				if($params['sort_by']) {
					$this->CI->db->order_by( "{$params['sort_by']}", $params ["sort_direction"] );
				}
				
				if ($page != "all"){
					$this->CI->db->limit ($params ["num_rows"], $params ["num_rows"] *  ($params ["page"] - 1) );
				}
				
				$this->CI->db->select($fields);
				$query = $this->CI->db->get($tbl);
			}
			
			else{
				$this->CI->db->select($fields);
				$this->CI->db->limit (5);
				$query = $this->CI->db->get($tbl);
			}
		}
		return $query;
	}
	
	
	function get_data($tbl, $params = "" , $page = "all", $conditions='',$where_in=''){
		if (!empty($params)){
				$fld = $params ["search_field"];
				$fldata = $params ["search_str"];
				$foper = $params ["search_operator"];
				
				if  ($params ["search"] == 'true'){
					switch ($foper) {
						case "bw":
							$this->CI->db->like($fld, $fldata, 'before'); 
							break;
						case "eq":
							$this->CI->db->where($fld, $fldata);
							break;
						case "ne":
							$this->CI->db->where('$fld <> ', $fldata);
							break;
						case "lt":
							$this->CI->db->where('$fld < ', $fldata);
							break;
						case "le":
							$this->CI->db->where('$fld <= ', $fldata);
							break;
						case "gt":
							$this->CI->db->where('$fld > ', $fldata);
							break;
						case "ge":
							$this->CI->db->where('$fld >= ', $fldata);
							break;
						case "ew":
							$this->CI->db->like($fld, $fldata, 'after');
							break;
						case "cn":
							$this->CI->db->like($fld, $fldata);
							break;
						default :
							$wh = "";
					}	
				}
			
			if(!empty($conditions))
			{
				foreach($conditions as $field_name => $field_value)
				{
					if(!empty($field_value))
					$this->CI->db->where($field_name,$field_value);
				}
			}
			if(!empty($where_in)) {
				foreach($where_in as $field_name => $field_value)
				{
					if(!empty($field_value))
					$this->CI->db->where_in($field_name,$field_value);
				}
			}
			
			
			if($params['sort_by']) {
				$this->CI->db->order_by( "{$params['sort_by']}", $params ["sort_direction"] );
			}
			
			if ($page != "all"){
				$this->CI->db->limit ($params ["num_rows"], $params ["num_rows"] *  ($params ["page"] - 1) );
			}
			
			$query = $this->CI->db->get($tbl);
		}
		
		else{
			$this->CI->db->limit (5);
			$query = $this->CI->db->get($tbl);
		}
		
		return $query;
	}
	
	
	function dateReplace($string="") {
		$str = "";
		$str = str_replace("/","-",$string);
		return $str;
	}
	
	function _list($tbl='') {
		$this->find_all($tbl);
		return $this->get_records();
	}

	function check_user() {
		$num = $this->count_records('users');
		if(MAX_USER >= $num) {
			return TRUE;
		}
		else {
			return FALSE;
		}
 	}
	

	function query($sql='') {
		if($sql) {
			$q = $this->CI->db->query($sql);
			if($q->num_rows() <= 1)
			{
				$this->records = $q->row();
			} else {
				$this->records = $q->result();
			}

			$this->num_rows = $q->num_rows();
			
		}

	}

	function date_split($date) {
		$date_split = array('date' =>'',
				'hour' => '',
				'minute' => '');
		if($date) {
			$petsa = explode(" ",$date);
			$oras = explode(":",$petsa[1]);
			
			$date_split = array(
				'date' => $petsa[0],
				'hour' => $oras[0],
				'minute' => $oras[1]
			);
		}
		
		return $date_split;
		
	}

	function sybase_date($date) {
		$date = str_replace("  "," ",$date);
		
		$month = '';
		$day = '';
		$year = '';
		$time = '';
		$minute = '';
		$hour = '';
		
		$mo = array("Jan"=>'01',"Feb"=>'02',"Mar"=>'03',
				"Apr"=>'04',	"May"=>'05',"Jun"=>'06',
				"Jul"=>'07',"Aug"=>'08',"Sep"=>'09',
				"Oct"=>'10',"Nov"=>'11',"Dec"=>'12');
		
		if($date) {
			$petsa = explode(" ",$date);
			$month = $petsa[0];
			$day =$petsa[1];
			$year =$petsa[2];
			$time =$petsa[3];
			
			$oras = explode(":",$petsa[3]);
			//get AM or PM
			$am = substr($oras[1],2,4);
			$minute = substr($oras[1],0,2);
			$hour = $oras[0];
			
			if($am == "PM") {
				if($hour < 12 ) {
					$hour = $hour + 12;
				}
				else if($hour == 12 && $minute == 0) {
					$hour = $hour + 12;
				} else {
					$hour = $hour;
				}
			}
			
			if($day < 10) {
				$day = "0".$day;
			}
			$month = $mo[$month];
		}
		$conv_date = array('month'=>$month,'day'=>$day,'year'=>$year,'hour'=>$hour,'minute'=>$minute);
		return $conv_date;
	}
	
	function get_access($url, $user_id='') {
		$this->CI->db->select('addx,viewx,deletex,cancelx,postx,unpostx');
		$this->CI->db->join("menu m", "m.id = ua.menuid", "left");
		$this->CI->db->where("userid = '" . $user_id. "' AND m.url != ''");
		$this->CI->db->where("'".$url."' LIKE CONCAT(m.url,'%')");
		
		$user_access = new stdclass();

		$getAccess = $this->CI->db->get('useraccess ua');
		if ($getAccess->num_rows() > 0) {
			foreach ($getAccess->result() as $acVal) {
				$user_access->add     = ($acVal->addx == 1) ?     "" : "disabled";
				$user_access->delete  = ($acVal->deletex == 1) ?  "" : "disabled";
				$user_access->view    = ($acVal->viewx == 1) ?    "" : "disabled";
				$user_access->cancel  = ($acVal->cancelx == 1) ?  "" : "disabled";
				$user_access->post    = ($acVal->postx == 1) ?    "" : "disabled";
				$user_access->unpost  = ($acVal->unpostx == 1) ?  "" : "disabled";
			}
		}
		return $user_access;
	}

	/****************** DATATABLE LOAD *******************************************/

	function _clause($aColumns=array(), $post = array() ) {
        
        /*
        * Paging
        */
        $sLimit = "";
        if ( isset( $post['iDisplayStart'] ) && $post['iDisplayLength'] != '-1' )
        {
            $sLimit = "LIMIT ".intval( $post['iDisplayLength'] )." OFFSET ".
                intval( $post['iDisplayStart'] );
        }
         
        /*
         * Ordering
         */
        $sOrder = '';
        if ( isset( $post['iSortCol_0'] ) )
        {
            $sOrder = "ORDER BY  ";
            for ( $i=0 ; $i<intval( $post['iSortingCols'] ) ; $i++ )
            {
                if ( $post[ 'bSortable_'.intval($post['iSortCol_'.$i]) ] == "true" )
                {
                    $sOrder .= $aColumns[ intval( $post['iSortCol_'.$i] ) ]."
                        ".($post['sSortDir_'.$i]==='asc' ? 'asc' : 'desc').", ";
                }
            }
             
            $sOrder = substr_replace( $sOrder, "", -2 );
            if ( $sOrder == "ORDER BY" )
            {
                $sOrder = "";
            }
        }
         
         
        /*
         * Filtering
         * NOTE This assumes that the field that is being searched on is a string typed field (ie. one
         * on which ILIKE can be used). Boolean fields etc will need a modification here.
         */
        $sWhere = "";
        if(isset($post['sSearch'])) {
            if ( $post['sSearch'] != "" )
            {
                $sWhere = "WHERE (";
                for ( $i=0 ; $i<count($aColumns) ; $i++ )
                {
                    if ( $post['bSearchable_'.$i] == "true" )
                    {
                        
                        if(is_integer($post['sSearch'])) {
                            $sWhere .= $aColumns[$i]."::text LIKE '%".pg_escape_string( $post['sSearch'] )."%' OR ";
                        } else {
                            $sWhere .= $aColumns[$i]."::text ILIKE '%".pg_escape_string( $post['sSearch'] )."%' OR ";
                        }
                    }
                }
                $sWhere = substr_replace( $sWhere, "", -3 );
                $sWhere .= ")";
            }
        }

        
        $this->where = $sWhere;
        $this->limit = $sLimit;
        $this->order = $sOrder;
    }

    function batch_insertion($table ='', $data =array()) {
		if($data && $table) {
			if($this->CI->db->insert_batch($table,$data)) {
				return true;
			} else {
				return false;
			}

		}
		return false;
	}

	public function get_count($table, $condition = "",$field = "COUNT(id)"){
        $this->CI->db->select("$field as total_count");
        if ($condition)
            $this->CI->db->where($condition);
        $this->CI->db->from($table);
        $query = $this->CI->db->get();
        if ($query->num_rows() > 0){
            return $query->row()->total_count;
        }

        return 0;
    }

    public function get($table, $condition = "",$field = "",$group_by = "",$order_by = "",$limit = "",$offset=""){
        $this->CI->db->select($field);
        if ($condition)
            $this->CI->db->where($condition);
        if ($group_by)
            $this->CI->db->group_by($group_by);
        if ($order_by)
            $this->CI->db->order_by($order_by);
        if ($limit)
			$this->CI->db->limit($limit);
		if($offset)
			$this->CI->db->limit($limit,$offset);

        $this->CI->db->from($table);
        $query = $this->CI->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }

        return false;
    }

    public function get_array($table, $condition = "",$field = "",$group_by = "",$order_by = "",$limit = ""){
        $this->CI->db->select($field);
        if ($condition)
            $this->CI->db->where($condition);
        if ($group_by)
            $this->CI->db->group_by($group_by);
        if ($order_by)
            $this->CI->db->order_by($order_by);
        if ($limit)
            $this->CI->db->limit($limit);
        $this->CI->db->from($table);
        $query = $this->CI->db->get();
        if ($query->num_rows() > 0){
            return $query->result_array();
        }

        return false;
	}
	
	public function hash_password($password){
		return password_hash($password, PASSWORD_BCRYPT);
	 }

	
}
?>