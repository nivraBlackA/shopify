<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed...');

	function list_group()
	{
		$group_arr = array(
			"2" => "user",
			"3" => "manager"
		);

		return $group_arr;
	}

	function group_opt($val = "")
	{
		$opt = "";
		foreach (list_group() as $gid => $gVal) {
			$selected = ($gid == $val) ? "selected" : "";
			$opt .= "<option value='$gid' $selected>$gVal</option>";
		}

		return $opt;
	}

	function saveDisplay($save,$cond,$redirectlink,$custom_msg = NULL)
    {
    	$CI =& get_instance();
        if ($save)
        {
            $test   = "saved";
            if (!empty($cond))
                $test   = "updated";
            $msg        = (!empty($custom_msg)) ? $custom_msg : "Item successfully $test!";
            $boxColor   = "success";
        }
        else
        {
            $msg        = (!empty($custom_msg)) ? $custom_msg : "Something went wrong, please try again.";
            $boxColor   = "warning";
        }

        $CI->session->set_flashdata('msg', $msg);
        $CI->session->set_flashdata('boxColor', $boxColor);
        redirect(base_url().$redirectlink);
    }

    function checkRemoteFile($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		// don't download content
		curl_setopt($ch, CURLOPT_NOBODY, 1);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($ch);
		curl_close($ch);
		if($result !== FALSE)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function util_upload_image($fileimage,$filedata = ""){
        $uploader   = new ImageUploader();
        $thumb_square_size      = 100; //Thumbnails will be cropped to 200x200 pixels
        $max_image_size         = 500; //Maximum image size (height and width)
        $thumb_prefix           = "thumb_"; //Normal thumb Prefix
        $path = "assets/uploads/notification/";
        if (!file_exists ($path))
            mkdir($path);

        // $destination_thumb_folder       = 'uploads/receiving/thumb/'; //upload directory ends with / (slash)
        $destination_folder             = $path; //upload directory ends with / (slash)
        $jpeg_quality           = 90; //jpeg quality

        // check $_FILES['file'] not empty
        if(!isset($fileimage) || !is_uploaded_file($fileimage['tmp_name'])){
            die('Image file is Missing!'); // output error when above checks fail.
        }

        //uploaded file info we need to proceed
        $image_name = $fileimage['name']; //file name
        $image_size = $fileimage['size']; //file size
        $image_temp = $fileimage['tmp_name']; //file temp

        $image_size_info    = getimagesize($image_temp); //get image size

        if($image_size_info){
            $image_width        = $image_size_info[0]; //image width
            $image_height       = $image_size_info[1]; //image height
            $image_type         = $image_size_info['mime']; //image type
        }else{
            die("Make sure image file is valid!");
        }

        //switch statement below checks allowed image type
        //as well as creates new image from given file
        switch($image_type){
            case 'image/png':
                $image_res =  imagecreatefrompng($image_temp); break;
            case 'image/gif':
                $image_res =  imagecreatefromgif($image_temp); break;
            case 'image/jpeg': case 'image/pjpeg':
                $image_res = imagecreatefromjpeg($image_temp); break;
            default:
                $image_res = false;
        }

        if($image_res){
            //Get file extension and name to construct new file name
            $image_info = pathinfo($image_name);
            $image_extension = strtolower($image_info["extension"]); //image extension
            $image_name_only = strtolower($image_info["filename"]);//file name only, no extension
			$image_name_only = str_replace(" ","_",$image_name_only);// replace spaces;
            //create a random name for new image (Eg: fileName_293749.jpg) ;
            //$pre = ($prepend) ? $prepend : $image_name_only;
            $new_file_name = $image_name_only. '_' .  rand(0, 9999999999) . '.' . $image_extension;

            //folder path to save resized images and thumbnails
            //$thumb_save_folder  = $destination_thumb_folder . $thumb_prefix . $new_file_name;
            $image_save_folder  = $destination_folder . $new_file_name;

            //call normal_resize_image() function to proportionally resize image
            if($uploader->normal_resize_image($image_res, $image_save_folder, $image_type, $max_image_size, $image_width, $image_height, $jpeg_quality))
            {
                //call crop_image_square() function to create square thumbnails
                // if(!$uploader->crop_image_square($image_res, $thumb_save_folder, $image_type, $thumb_square_size, $image_width, $image_height, $jpeg_quality))
                // {
                //     die('Error Creating thumbnail');
                // }

            }

            imagedestroy($image_res); //freeup memory
        }

        echo $image_save_folder;
        die();
	}

	function util_send_notif($title,$message,$type= "all", $type_value = "",$notif_type = "notification",$image = "",$item_id=""){
		
		$data = array(
			"app_id" => ONESIGNAL_APP_ID,
			"headings" => array ("en" => $title),
			"contents" => array ("en" => $message),
			"template_id" => ONESIGNAL_TEMPLATE_ID,
            "data" => ['notif_type' => $notif_type,
                        'item_id' => $item_id]
		);

		if ($image){
			$data['big_picture'] = $image;
			$data['ios_attachments'] = '{"id1" : '.$image.'}';
		}

		if ($type == 'user'){
			if ($type_value){
				$tagData = array(
					array(
						"key" => "user_id",
						"relation" => "=",
						"value" => $type_value,
						)
					);
				$data['tags'] = $tagData;
			}
			else
				return array("result" => "error", "msg" => "No user id");
			
			// $data['include_player_ids'] = $player_id;
		}

		else if ($type == 'platform'){
			
			$tagData = array(
				array(
					"key" => 'platform',
					"relation" => '=',
					"value" => $type_value,
					)
				);
			$data['tags'] = $tagData;
		}
		
		else if ($type == 'all'){
			$data['included_segments'] = array("All");
		}

		// print_r($data);
		
		$ch = curl_init();
		$rawData = json_encode($data);
		curl_setopt($ch,CURLOPT_URL, ONESIGNAL_URL);
		curl_setopt($ch,CURLOPT_POST, TRUE);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $rawData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . ONESIGNAL_REST_API, 'Content-Type: application/json; charset=utf-8'));

		//execute post
		$result = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$res = json_decode($result);
		//close connection
		curl_close($ch);
		if ($http_code == 200)
			return $res;
		else
			return false;
	
	}

	function util_st_get_token(){
		$url = "oauth/token";
        $method = "POST";
        $data = array(
            "username" => ST_CLIENT_CODE,
            "password" => ST_API_PASSWORD,
            "grant_type" => "password",
        );
        return util_st_call_api($url,$data,$method);
	}

	function util_renew_token_get(){
        $rtoken = $this->input->get("rtoken");
        $url = "oauth/token";
        $method = "POST";
        $data = array(
            "refresh_token" => $rtoken,
            "scope" => "read",
            "grant_type" => "refresh_token",
        );
        return util_st_call_api($url,$data,$method);
    }

    function util_product_get($rtoken){
        $url = "goods/v2";
        $data = array(
            "campaignId" => "",
            "dispGoodsStatus" => "sales"
        );
		
        $auth = array('Type: Basic Auth','Authorization: Bearer ' . $rtoken,'Content-Type: application/json');
        return util_st_call_api($url,$data,"GET",$auth);

    }

    function util_product_location_get($rtoken,$sku_code,$campaign_id){
        $url = "goods/v2/$sku_code/locations";
        $data = array(
            "campaignId" => $campaign_id,
            // "dispGoodsStatus" => "sales"
        );
		
        $auth = array('Type: Basic Auth','Authorization: Bearer ' . $rtoken,'Content-Type: application/json');
        return util_st_call_api($url,$data,"GET",$auth);

    }

	function util_st_call_api($url,$dataparams,$method = "GET",$auth = FALSE){
        $ch = curl_init();

        $url_params = "";
        $fields_string = util_stringyfy_array($dataparams);

        // print_r($fields_string);
        if ($method == 'POST'){
            curl_setopt($ch,CURLOPT_POST, TRUE);
            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        }
        else{
            $url_params = "?" . $fields_string;
        }
        
        curl_setopt($ch, CURLOPT_URL, ST_URL. $url . $url_params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_INTERFACE ,'199.250.199.28');

        if (!$auth){
            $auth = array('Type: Basic Auth','Authorization: Basic ' . ST_AUTH_KEY,'Content-Type: application/x-www-form-urlencoded');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $auth);
        }else{
            curl_setopt($ch, CURLOPT_HTTPHEADER, $auth);
        }
        
        // print_r($auth);

        //execute post
        $result = curl_exec($ch);
        $status = curl_getinfo($ch,CURLINFO_HTTP_CODE); 
        $info   = curl_getinfo($ch); 

		// echo "Params : <br/>";
		// echo "<pre>";
		// print_r($dataparams);
		// echo "</pre>";
		// echo "<br/>";
        // echo "Results : <br/><pre>";
        // print_r(json_decode($result));
        // echo "</pre>
        // ---------------------------<br/>
        // Info : <br/><pre>";
        // print_r($info);
		// echo "</pre>";
        // echo $res = json_decode($result);
        //close connection
        curl_close($ch);
		return json_decode($result);
		die();
        // $this->response($result, 200);
	}
	
	function util_stringyfy_array($data){
        $fields_string = '';
        foreach($data as $key=> $value) 
        { 
            $fields_string .= $key.'='.$value.'&'; 
        }
        
        rtrim($fields_string, '&');

        return $fields_string;
    }

    function ultil_status_opt($val="")
    {
        $val_key = "";
        if($val == 'success')
            $val_key = "S";
        elseif($val == 'pending')
            $val_key = "O";
        elseif($val == 'rejected')
            $val_key = "R";

        $status_data = array(
            "O" => "On Process",
            "R" => "Request Rejected",
            "S" => "Successfully Refunded",
        );
		$opt = "";
		foreach ($status_data as $gid => $gVal) {
			$selected = ($gid == $val_key) ? "selected" : "";
			$opt .= "<option value='$gid' $selected>$gVal</option>";
		}

		return $opt;
    }

    function ultil_refund_opt($val="")
    {
        $refund_data = array(
            "paypal" => "Paypal",
            "dragonpay" => "Dragonpay"
        );
		$opt = "";
		foreach ($refund_data as $gid => $gVal) {
			$selected = ($gid == $val) ? "selected" : "";
			$opt .= "<option value='$gid' $selected>$gVal</option>";
		}

		return $opt;
    }

    function ultil_opt_select($optArry = "",$val="")
    {
		$opt = "";
		foreach ($optArry as $gid => $gVal) {
			$selected = ($gid == $val) ? "selected" : "";
			$opt .= "<option value='$gid' $selected>$gVal</option>";
		}

		return $opt;
    }

    function long_text($text="")
    { 
        $desc = substr($text, 0, 50);
        $txt = "";
        if(strlen($text) > 50){
            $txt = $desc."...";
        }
        else
            $txt = $text;
        return $txt;
    }
?>
