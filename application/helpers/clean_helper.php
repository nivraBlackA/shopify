<?php
if (!defined('BASEPATH')) exit('No direct script access allowed...');

	function remove_accent($text='')
	{
		$search = explode(",","ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u,ñ,Ñ,Ç,Æ,Œ,Á,É,Í,Ó,Ú,À,È,Ì,Ò,Ù,Ä,Ë,Ï,Ö,Ü,Â,Ê,Î,Ô,Û,Å,E,I,Ø,U,Œ,Š,š,ÿ,ý,ž,ß,?,Ý,Ÿ,?");
		$replace = explode(",","c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u,n,N,C,A,O,A,E,I,O,U,A,E,I,O,U,A,E,I,O,U,A,E,I,O,U,A,E,I,O,U,O,S,s,y,y,z,z,ss,Y,Y,S");
		$trim = str_replace($search, $replace, $text);
		return $trim;
	}
	
	function clean_pagename($pagename)
	{
		$bad = array("<!--", "-->", "-", "'", ",", ".", "<", ">", '"', ":",
					 '&', '$', '=', ';', '?', '/', "%20", "%22", "!", 
						"%3c",		// <
						"%253c", 	// <
						"%3e", 		// >
						"%0e", 		// >
						"%28", 		// (
						"%29", 		// )
						"%2528", 	// (
						"%26", 		// &
						"%24", 		// $
						"%3f", 		// ?
						"%3b", 		// ;
						"%3d"		// =
					);
					
		$pagename = str_replace($bad, '', $pagename);
		$pagename = str_replace(" ", '-', $pagename);

		return stripslashes($pagename);
	}

	function clean_text($text_data){
		if (is_array($text_data)){
			$cleanArr = array();
			foreach ($text_data as $key => $data) {
				$cleanArr[$key] = preg_replace('/\s+/S', " ", $data);
			}
			return $cleanArr;
		}
		else if (is_object($text_data)){

		}
		else{
			return preg_replace('/\s+/S', " ", $text_data);
		}
	}
	
	function text_encode($text='')
	{
		return htmlentities($text,ENT_QUOTES);
	}
	
	function text_decode($text)
	{
		$trim = html_entity_decode(stripslashes($text));
		return $body = preg_replace('/&(?![#]?[a-z0-9]+;)/i', "&$1", $trim); 
	}
	
	function remove_bad_char($text)
	{
		$bad = array("#039", "#8211", "(",")"
					);
					
		$text = str_replace($bad, '', $text);

		return stripslashes($text);
	}
    
    function remove_element($arr, $val) {
        foreach($arr as $key=>$value) {
            if($arr[$key]==$val) {
                unset($arr[$key]);
            }
        }
        return $arr = array_values($arr);
    }
	
	function decode_text($text="")
	{
		if(preg_match("/<p>(.*)<\/p>/",$text))
			return text_decode($text);
		else
			return $text;
	}
    function create_guid() {
        $microTime = microtime();
        list($a_desc, $a_sec) = explode(" ", $microTime);
        
        $dec_hex = sprintf("%x", $a_desc * 1000000);
        $sec_hex = sprintf("%x", $a_sec);
        
        ensure_length($dec_hex, 5);
        ensure_length($sec_hex, 6);
        
        $guid = "";
        $guid .= $dec_hex;
        $guid .= create_guid_section(3);
        $guid .= '-';
        $guid .= create_guid_section(4);
        $guid .= '-';
        $guid .= create_guid_section(4); 
        $guid .= '-';
        $guid .= create_guid_section(4); 
        $guid .= '-';
        $guid .= $sec_hex;
        $guid .= create_guid_section(6); 
        
        return $guid;
    }
    function create_guid_section($characters)
    {   
        $return = "";

        for($i=0; $i < $characters; $i++)
        {
            $return .= sprintf("%x", mt_rand(0,15));
        }

        return $return;
    }

    function ensure_length(&$string, $length)
    {
        $strlen = strlen($string);
        if($strlen < $length)
        {
            $string = str_pad($string,$length,"0");
        }

        else if($strlen > $length)
        {
            $string = substr($string, 0, $length);
        }

    }
	
	function encrypt($sData, $sKey='cmp'){
		$sResult = '';
		
		$sData	=	$sData.$sKey;
		
		for($i=0;$i<strlen($sData);$i++){
			$sChar    = substr($sData, $i, 1);
			$sKeyChar = substr($sKey, ($i % strlen($sKey)) - 1, 1);
			$sChar    = chr(ord($sChar) + ord($sKeyChar));
			$sResult .= $sChar;
		}
		return encode_base64($sResult);
	}

	function decrypt($sData, $sKey='cmp'){
		$sResult = '';
		
		$sData   = decode_base64($sData);
		
		for($i=0;$i<strlen($sData);$i++){
			$sChar    = substr($sData, $i, 1);
			$sKeyChar = substr($sKey, ($i % strlen($sKey)) - 1, 1);
			$sChar    = chr(ord($sChar) - ord($sKeyChar));
			$sResult .= $sChar;
		}
		return str_replace($sKey,"",$sResult);
	}

	function encode_base64($sData){
		$sBase64 = base64_encode($sData);
		return strtr($sBase64, '+/', '-_');
	}

	function decode_base64($sData){
		$sBase64 = strtr($sData, '-_', '+/');
		return base64_decode($sBase64);
	}
	function array_clean($array) {
		$data = array();
		foreach($array as $field=>$value) {
			if(!is_array($value) && !empty($value)) {
				$data[$field] = $value;
			}
		}
		return $data;
	} 

	function clean_data($array) {
		$data = array();
		foreach($array as $field=>$value) {
			if(!is_array($value)) {
				if($value == '' || $value == null) 
					$data[$field] = NULL;
				else 
					$data[$field] = $value;
			}
		}
		return $data;
	} 

	function array_to_CSV($data){
        $outstream = fopen("php://temp", 'r+');
        fputcsv($outstream, $data, ',', '"');
        rewind($outstream);
        $csv = fgets($outstream);
        fclose($outstream);
        return $csv;
    }

	/**
	* var_dump a data
	*
	* @param  any data to var_dump
	* @author Cris Bacera
	*/
	function fn_print_r() {
		static $count = 0;
		$args = func_get_args();

		if (!empty($args)) {
			echo '<ol id="fn_print_r" style="font-family: Courier; font-size: 12px; border: 1px solid #dedede; background-color: #efefef; float: left; padding-right: 20px;">';
			foreach ($args as $k => $v) {
				$v = htmlspecialchars(print_r($v, true));
				if ($v == '') {
					$v = '    ';
			}
				echo '<li><pre>' . $v . "\n" . '</pre></li>';
			}
			echo '</ol><div style="clear:left;"></div>';
		}
		$count++;
	}

/*This portion of code will select the all the records of the table mentioned here.*/

?>