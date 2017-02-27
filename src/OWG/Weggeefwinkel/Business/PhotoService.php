<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace OWG\Weggeefwinkel\Business;

/**
 * Description of PhotoService
 *
 * @author steven.jespers
 */
class PhotoService {

    private static $target_dir = "src/OWG/Weggeefwinkel/Presentation/Img/";

    function make_thumb($src, $dest, $desired_width) {
        $imageFileType = pathinfo($src, PATHINFO_EXTENSION);
        /* read the source image */
         if ($imageFileType == "jpg" || $imageFileType == "jpeg") {
        $source_image = imagecreatefromjpeg($src);
         } elseif ($imageFileType == "png") {

            $source_image = imagecreatefrompng($src);
            imageAlphaBlending($source_image, true);
            imageSaveAlpha($source_image, true);
        }
        
        $width = imagesx($source_image);
        $height = imagesy($source_image);
        print $src;
        /* find the "desired height" of this thumbnail, relative to the desired width  */
       $desired_height = floor($height * ($desired_width / $width));

        /* create a new, "virtual" image */
        $virtual_image = imagecreatetruecolor($desired_width, $desired_height);

        /* copy source image at a resized size */
        imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

        /* create the physical thumbnail image to its destination */
        imagejpeg($virtual_image, $dest);
    }
    
     function make_thumb2($src, $dest, $desired_height) {
        
        /* read the source image */
        $source_image = imagecreatefromjpeg($src);
        $width = imagesx($source_image);
        $height = imagesy($source_image);
        print $src;
        /* find the "desired height" of this thumbnail, relative to the desired width  */
        //$desired_height = floor($height * ($desired_width / $width));
        $desired_width = floor($width * ($desired_height/$height));
        /* create a new, "virtual" image */
        $virtual_image = imagecreatetruecolor($desired_width, $desired_height);

        /* copy source image at a resized size */
        imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

        /* create the physical thumbnail image to its destination */
        imagejpeg($virtual_image, $dest);
    }

    public function handlePhoto($photo) {
        $randomString = time() . "-" . rand(0, 100000);
        $fileName = $randomString . "_" . basename($photo["name"]);
        // $usernameDir = $_SESSION["username"] . "/";
        // Check if file already exists
        $uploadOk = 1;
        if (file_exists(self::$target_dir . $fileName)) {
            /* $random = mt_rand(0, 100000);
              $fileName =  $random . $fileName; */
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
        $target_file = self::$target_dir . $fileName;
        //print $target_file . " ";

        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        //print $imageFileType . " ";
        $check = getimagesize($photo["tmp_name"]);
        //print_r($check);
        if ($check) {
            //return basename($photo["name"]);
        } else {
            //
        }

        // Check file size
        if ($_FILES["img"]["size"] > 2000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["img"]["tmp_name"], $target_file)) {
                $thumb_target_file = self::$target_dir . 'thumb_' . $fileName;
                $this->make_thumb($target_file, $thumb_target_file, 200);
                echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
                return $fileName;
            } else {
                echo "Sorry, there was an error uploading your file.";
                //return $target_file;
                return "ja";
            }
        }
    }

}
