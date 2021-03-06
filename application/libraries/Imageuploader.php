<?php 
class ImageUploader {

    function normal_resize_image($source, $destination, $image_type, $max_size, $image_width, $image_height, $quality,$force_upload = FALSE){
	
        if($image_width <= 0 || $image_height <= 0){return false;} //return false if nothing to resize

        //do not resize if image is smaller than max size
        if (!$force_upload){
            if($image_width <= $max_size && $image_height <= $max_size){
                if($this->save_image($source, $destination, $image_type, $quality)){
                    return true;
                }
            }
        }

        //Construct a proportional size of new image
        $image_scale	= min($max_size/$image_width, $max_size/$image_height);
        $new_width		= ceil($image_scale * $image_width);
        $new_height		= ceil($image_scale * $image_height);

        $new_canvas		= imagecreatetruecolor( $new_width, $new_height ); //Create a new true color image

        if($image_type == "image/gif" || $image_type == "image/png"){
            imagecolortransparent($new_canvas, imagecolorallocatealpha($new_canvas, 0, 0, 0, 127));
            imagealphablending($new_canvas, false);
            imagesavealpha($new_canvas, true);
        }

        //Copy and resize part of an image with resampling
        if(imagecopyresampled($new_canvas, $source, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height)){
            $this->save_image($new_canvas, $destination, $image_type, $quality); //save resized image
        }

        return true;
    }

    ##### This function corps image to create exact square, no matter what its original size! ######
    function crop_image_square($source, $destination, $image_type, $square_size, $image_width, $image_height, $quality){
        if($image_width <= 0 || $image_height <= 0){return false;} //return false if nothing to resize

        if( $image_width > $image_height )
        {
            $y_offset = 0;
            $x_offset = ($image_width - $image_height) / 2;
            $s_size 	= $image_width - ($x_offset * 2);
        }else{
            $x_offset = 0;
            $y_offset = ($image_height - $image_width) / 2;
            $s_size = $image_height - ($y_offset * 2);
        }
        $new_canvas	= imagecreatetruecolor( $square_size, $square_size); //Create a new true color image
        if($image_type == "image/gif" || $image_type == "image/png"){
            imagecolortransparent($new_canvas, imagecolorallocatealpha($new_canvas, 0, 0, 0, 127));
            imagealphablending($new_canvas, false);
            imagesavealpha($new_canvas, true);
        }
        //Copy and resize part of an image with resampling
        if(imagecopyresampled($new_canvas, $source, 0, 0, $x_offset, $y_offset, $square_size, $square_size, $s_size, $s_size)){
            $this->save_image($new_canvas, $destination, $image_type, $quality);
        }

        return true;
    }

    ##### Saves image resource to file #####
    function save_image($source, $destination, $image_type, $quality){
        switch(strtolower($image_type)){//determine mime type
            case 'image/png':
                imagepng($source, $destination); return true; //save png file
                break;
            case 'image/gif':
                imagegif($source, $destination); return true; //save gif file
                break;
            case 'image/jpeg': case 'image/pjpeg':
                imagejpeg($source, $destination, $quality); return true; //save jpeg file
                break;
            default: return false;
        }
    }

}

?>